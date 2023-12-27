import mysql.connector
from mysql.connector import Error
from sklearn.model_selection import train_test_split
from sklearn.ensemble import RandomForestClassifier
from sklearn.preprocessing import LabelEncoder
import pandas as pd

db_config = {
        'user': 'root',
        'password': '',
        'host': 'localhost',
        'database': 'skilvul',
    }

conn = mysql.connector.connect(**db_config)
cursor = conn.cursor()

def predict_product(latest_customer_id):
        # Load data from the 'terra' table
        terra_df = pd.read_sql("SELECT * FROM terra", conn)

        # Preprocess data (Replace with actual preprocessing logic)
        features = terra_df[['page_views', 'time_spent', 'price', 'ratings', 'recency_of_purchase', 'purchase_month']]
        target = terra_df['product_id']

        # Splitting the dataset into training and testing sets
        X_train, X_test, y_train, y_test = train_test_split(features, target, test_size=0.2, random_state=42)

        # Train the Random Forest model
        model = RandomForestClassifier(n_estimators=100, random_state=42)
        model.fit(X_train, y_train)

        # Convert 'purchase_date' to datetime if it's not already
        terra_df['purchase_date'] = pd.to_datetime(terra_df['purchase_date'])

        # Sort the DataFrame by 'purchase_date' in descending order (latest first)
        terra_df_sorted = terra_df.sort_values(by='purchase_date', ascending=False)

        # Fetch latest customer's data for prediction
        latest_customer_data = terra_df_sorted[terra_df_sorted['customer_id'] == latest_customer_id].iloc[0]
        latest_features = latest_customer_data[['page_views', 'time_spent', 'price', 'ratings', 'recency_of_purchase', 'purchase_month']]

        # Predict the product
        predicted_product_id = model.predict([latest_features])[0]
        return predicted_product_id

def product_name(predicted_product_id):
    query = "SELECT category FROM terra WHERE product_id = %s LIMIT 1"
    cursor.execute(query, (int(predicted_product_id),))  # Cast to int and ensure it's a tuple
    result = cursor.fetchone()
    return result[0] if result else None

# Fetch the latest customer_id and timestamp from the 'result' table
df = pd.read_sql("SELECT customer_id, timestamp FROM result ORDER BY timestamp DESC LIMIT 1", conn)

# Ensure you are extracting the values correctly from the DataFrame
latest_customer_id = df.iloc[0]['customer_id']
latest_customer_id = int(latest_customer_id)

latest_timestamp = df.iloc[0]['timestamp']

# Convert the timestamp to the correct string format for MySQL, if it's not already
latest_timestamp_str = latest_timestamp.strftime('%Y-%m-%d %H:%M:%S')

# Make the prediction
predicted_product_id = predict_product(latest_customer_id)
predicted_product_id = int(predicted_product_id)  # Explicitly cast to int

product_category_name = product_name(predicted_product_id)

# Print out debugging information
print(f"Attempting to update customer_id: {latest_customer_id}, timestamp: {latest_timestamp_str}, with product_id: {predicted_product_id}, category: {product_category_name}")

# Update the last row in the 'result' table with the predicted product
update_query = """
    UPDATE result 
    SET product_id = %s, category = %s 
    WHERE customer_id = %s AND timestamp = %s
"""

# Ensure parameters are passed as a tuple and execute the update
try:
    cursor.execute(update_query, (predicted_product_id, product_category_name, latest_customer_id, latest_timestamp_str))
    conn.commit()
    if cursor.rowcount == 0:
        print("No rows were updated.")
    else:
        print(f"{cursor.rowcount} row(s) were updated.")
except mysql.connector.Error as err:
    print(f"Error: {err}")

# Close the cursor and connection
cursor.close()
conn.close()

