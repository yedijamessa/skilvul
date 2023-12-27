<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Next Best Possible Purchase</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            height: 100vh; /* Full viewport height */
            display: flex;
            flex-direction: column;
            justify-content: center; /* Center vertically */
            align-items: center; /* Center horizontally */
            background-color: #e9f5f5;
            font-family: Arial, sans-serif;
        }

        .logo {
            max-width: 300px;
        }
        .content {
            width: 80%; /* Adjust width as needed */
            text-align: center; /* Center text */
        }
        table {
            width: 100%; /* Full width of the content div */
            margin-top: 20px; /* Space above the table */
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        th {
            background-color: #4CAF50;
            color: white;
        }
        p {
            margin-top: 20px; /* Space after the table */
        }

        .bold-text {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="content">
    <h2>Next Best Possible Purchase</h2>
    <img src="logo.png" alt="Terra Store Logo" class="logo">


    <?php
    // Database connection parameters
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "skilvul";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // SQL query to fetch the latest row
    $sql = "SELECT customer_id, product_id, category FROM `result` ORDER BY timestamp DESC LIMIT 1";
    $customer_id = "";
    $category = "";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Output data of each row
        while($row = $result->fetch_assoc()) {
            $customer_id = $row["customer_id"];
            $category = $row["category"];
            echo "<table><tr>
                    <th>Customer ID</th>
                    <th>Product ID</th>
                    <th>Category</th></tr>";
            echo "<tr>
                    <td>" . $row["customer_id"]. "</td>
                    <td>" . $row["product_id"]. "</td>
                    <td>" . $row["category"]. "</td></tr>";
            echo "</table>";
        }
    } else {
        echo "0 results";
    }
    $conn->close();
    ?>
    <p class="explanation">
            The Next Best Offer (NBO) is a tailored suggestion for each customer based on their shopping history and preferences. For example, our data predicts that customer ID <span class="bold-text"><?php echo $customer_id; ?></span> will likely be interested in products from the <span class="bold-text"><?php echo $category; ?></span> category next. Marketing teams can capitalize on this prediction by providing compelling offers and discounts tailored to this customer's interests, thereby encouraging additional purchases and enhancing customer satisfaction.
    </p>
    </div>
    <br>
    <div class="content">
    <p class="explanation">
    If you wish to see the purchase prediction for a different customer, please submit again.    </p>
    </div>

    <div class="form-container">
        <form action="existing_input.php" method="post">
            <label for="customer_id">Customer ID:</label>
            <select name="customer_id" id="customer_id">
            <!-- PHP code to populate customer IDs from the 'terra' table in 'skilvul' database -->
            <?php
            // Assuming you have a database connection set up
            $conn = new mysqli("localhost", "root", "Kakakpinter-300199", "skilvul");

            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            $result = $conn->query("SELECT DISTINCT customer_id FROM terra ORDER BY customer_id ASC");

            while ($row = $result->fetch_assoc()) {
                echo "<option value='" . $row["customer_id"] . "'>" . $row["customer_id"] . "</option>";
            }
            $conn->close();
            ?>
            </select>
            <input type="submit" value="Check Another Customer">
        </form>
    </div>
</body>
</html>
