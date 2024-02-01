<!DOCTYPE HTML>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add User</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        #container {
            background-color: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            width: 80%;
            max-width: 600px;
        }

        form {
            margin-top: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
        }

        input, #selectOption, #selectProduct {
            width: 100%;
            padding: 8px;
            margin-bottom: 15px;
            box-sizing: border-box;
        }

        button {
            background-color: #4caf50;
            color: white;
            border: none;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            cursor: pointer;
            border-radius: 4px;
        }
    </style>
</head>
<body>

<!-- Form -->

<div id="container">
    <h2 style="text-align: center;">Orders</h2>

    <?php
    include 'includes/db_connection.php';

    try {
        $conn = connectDB();

        if ($conn && isset($_POST['order_id'])) {
            $userId = $_POST['order_id'];
            $sql = "SELECT id, name, email FROM users WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id', $userId);
            $stmt->execute();

            $userData = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($userData) {
                ?>
                <form method="post" action="includes/insert_order.php">
                    <label for="customer">Customer's Name:</label>
                    <input type="text" id="customer" name="customer" value="<?php echo $userData['name']; ?>" required readonly>

                    <label for="selectProduct">Select Product:</label>
                    <select name="selectProduct" id="selectProduct">
                        <option value="">Select a Product</option> <!-- Added option for better debugging -->
                        <?php
                        try {
                            $stmt = $conn->query("SELECT * FROM products");
                            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

                            foreach ($result as $row) {
                                $productName = $row['product_name'];
                                echo "<option value='{$row['product_id']}'>$productName</option>";
                            }
                        } catch (PDOException $e) {
                            die("Query failed: " . $e->getMessage());
                        }
                        ?>
                    </select>

                    <label for="date">Date:</label>
                    <input type="date" id="date" name="date" required>

                    <button type="submit">Insert</button>
                    <a href="index.php"><button type="button">Users</button></a>
                </form>
                <?php
            } else {
                echo "<p>User not found.</p>";
            }
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    } finally {
        if ($conn) {
            $conn = null;
        }
    }
    ?>
</div>

</body>
</html>
