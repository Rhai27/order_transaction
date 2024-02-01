<?php
include 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $u_name = $_POST['customer'];
    $p_id = $_POST['selectProduct']; // Assuming you are receiving the product ID from the form
    $o_date = $_POST['date'];

    try {
        // Use the function to get a PDO connection
        $conn = connectDB();

        // Set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Fetch user_id based on user_name
        $userQuery = $conn->prepare("SELECT id FROM users WHERE name = :user_name");
        $userQuery->bindParam(':user_name', $u_name);
        $userQuery->execute();
        $u_id = $userQuery->fetchColumn();

        // Insert into orders table
        $sql = "INSERT INTO orders (id, product_id, order_date) VALUES (:user_id, :product_id, :order_date)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':user_id', $u_id);
        $stmt->bindParam(':product_id', $p_id); // Change ':prod' to ':product_id'
        $stmt->bindParam(':order_date', $o_date);
        $stmt->execute();

        $sql = "UPDATE products SET product_stock = product_stock - 1 WHERE product_id = :product_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':product_id', $p_id);
        $stmt->execute();

        // Redirect back to the orders page after successful insertion
        header("Location: ../index.php");
        exit();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    } finally {
        // Always close the connection
        if ($conn) {
            $conn = null;
        }
    }
}
?>
