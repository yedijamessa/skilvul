<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $customer_id = $_POST["customer_id"];

    // Database connection
    $conn = new mysqli("localhost", "root", "", "skilvul");

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO result (customer_id) VALUES (?) ON DUPLICATE KEY UPDATE customer_id = VALUES(customer_id)");
    $stmt->bind_param("s", $customer_id);

    // Set parameters and execute
    $stmt->execute();
    
    // Assuming predict.py is in the same directory and executable
    $command = escapeshellcmd("python predict.py " . escapeshellarg($customer_id));
    $output = shell_exec($command);

    // Close statement and connection
    $stmt->close();
    $conn->close();

    // Redirect to result.php with the customer_id and output as GET parameters
    header("Location: result.php?customer_id=" . urlencode($customer_id) . "&prediction=" . urlencode($output));
    exit();
}
?>