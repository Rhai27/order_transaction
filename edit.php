<!DOCTYPE HTML>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
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
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        label {
            margin-bottom: 8px;
        }

        input {
            padding: 10px;
            margin-bottom: 16px;
            width: 100%;
            box-sizing: border-box;
        }

        .button-container {
            text-align: center;
        }

        .update-button {
            background-color: #2196f3;
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

<div id="container">
    <h2 style="text-align: center;">Edit User</h2>

    <?php
        include 'includes/db_connection.php';

        try {
            $conn = connectDB();

            if ($conn && isset($_POST['edit_id'])) {
                $userId = $_POST['edit_id'];
                $sql = "SELECT id, name, email FROM users WHERE id = :id";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':id', $userId);
                $stmt->execute();

                $userData = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($userData) 
                {
                    // User data found, render the edit form
    ?>
                <form action="includes/update.php" method="post">
                    <input type="hidden" name="id" value="<?php echo $userData['id']; ?>">
                    <label for="name">Name:</label>
                    <input type="text" name="name" id="name" value="<?php echo $userData['name']; ?>">

                    <label for="email">Email:</label>
                    <input type="text" name="email" id="email" value="<?php echo $userData['email']; ?>">

                    <div class="button-container">
                        <button type="submit" class="update-button">Update User</button>
                    </div>
                </form>
    <?php
            } 
            else 
            {
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
