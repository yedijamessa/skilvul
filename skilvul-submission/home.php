<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Terra Store</title>
<style>
  body, html {
    height: 100%;
    margin: 0;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    background-color: #e9f5f5;
    font-family: Arial, sans-serif;
  }

  .logo {
    max-width: 300px;
  }

  .button {
    display: inline-block;
    width: 200px;
    padding: 10px;
    margin: 20px 0;
    font-size: 18px;
    color: white;
    background: #4CAF50;
    text-align: center;
    border: none;
    border-radius: 5px;
    text-decoration: none;
    transition: background-color 0.3s, transform 0.3s;
  }

  .button:hover {
    background-color: #45a049;
    transform: translateY(-2px);
  }

  .container {
    text-align: center;
    max-width: 800px; /* customizable width */
    margin: 0 20px; /* customizable space */
  }

  .description {
    border: 1px solid #ddd;
    border-radius: 8px;
    padding: 20px;
    margin: 20px 0;
    font-size: 16px;
    color: #333;
    background-color: #fff;
  }
</style>
</head>
<body>

<div class="container">
  <img src="logo.png" alt="Terra Store Logo" class="logo">
  <div class="description">
    <p>Terra Store is an innovative e-commerce platform offering a personalized shopping experience through AI-powered predictive analytics. This website will assist the marketing team in simulating the next best offer for potential customers.</p>
    <br>
    <p>Please select a customer to find out their next best offer.</p>
  </div>
</div>

<div class="container">
  <form action="existing_input.php" method="post">
    <label for="customer_id">Customer ID:</label>
    <select name="customer_id" id="customer_id">
      <!-- PHP code to populate customer IDs from the 'terra' table in 'skilvul' database -->
      <?php
      // Assuming you have a database connection set up
      $conn = new mysqli("localhost", "root", "", "skilvul");

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
    <input type="submit" value="Submit">
  </form>
</div>
</body>
</html>
