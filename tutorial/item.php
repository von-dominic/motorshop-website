<?php
session_start();
include("php/config.php");

// Ensure admin is logged in
if (!isset($_SESSION['admin_valid'])) {
    header("Location: index.php");  // Redirect to customer login if not logged in as admin
    exit();
}

// Add item functionality
if (isset($_POST['add_item'])) {
    $type = mysqli_real_escape_string($con, $_POST['type']);
    $name = mysqli_real_escape_string($con, $_POST['name']);
    $description = mysqli_real_escape_string($con, $_POST['description']);
    $stocks = (int)$_POST['stocks'];
    $price = (float)$_POST['price'];

    // Handle file upload
    $target_dir = "uploads/";
    $image_name = basename($_FILES["image"]["name"]);
    $target_file = $target_dir . $image_name;

    // Ensure the uploads directory exists
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    // Check if file is uploaded
    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
        // Insert data into the database
        $sql = "INSERT INTO items (image, type, name, description, stocks, price) 
                VALUES ('$target_file', '$type', '$name', '$description', $stocks, $price)";

        if (mysqli_query($con, $sql)) {
            echo "<script>alert('Item added successfully!');</script>";
        } else {
            echo "<script>alert('Error adding item: " . mysqli_error($con) . "');</script>";
        }
    } else {
        echo "<script>alert('Error uploading image.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/style.css">
    <title>Admin - Add Item</title>
</head>
<style>
body {
    background: #f0f0f0 url('https://w.wallhaven.cc/full/48/wallhaven-483k3k.jpg') no-repeat center center fixed;
    background-size: cover;
}

.nav {
    background: transparent; /* Remove white background */
    padding: 10px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.nav .logo {
    display: none; /* Hide the logo */
}

.right-links {
    display: flex;
    justify-content: flex-end;
}

.form-container {
    width: 60%;
    margin: 100px auto;
    padding: 30px;
    background: rgba(34, 34, 34, 0.85); /* Dark transparent background */
    border-radius: 10px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.6);
    color: white;
}

.form-container h2 {
    text-align: center;
    font-size: 2em;
    color: #f7c22e;
}

.form-container label {
    color: #f7c22e;
    font-weight: bold;
    margin-top: 10px;
}

.form-container input,
.form-container textarea {
    width: 100%;
    padding: 12px;
    margin-bottom: 10px;
    background: #444;
    color: #f7c22e;
    border: 2px solid #f7c22e;
    border-radius: 5px;
    font-size: 1.1em;
    transition: border-color 0.3s ease;
}

.form-container input:focus,
.form-container textarea:focus {
    border-color: #f7c22e;
    outline: none;
}

.form-container button {
    background: #f7c22e;
    color: #222;
    font-size: 1.2em;
    padding: 12px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    transition: background 0.3s ease;
    width: 100%;
}

.form-container button:hover {
    background: #e7b31c;
}

.go-back-button {
    background-color: #f7c22e;
    color: #222;
    font-size: 1.2em;
    padding: 12px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    transition: background 0.3s ease;
    width: 100%;
    margin-top: 20px;
}

.go-back-button:hover {
    background: #e7b31c;
}
</style>
<body>
    <div class="nav">
        <div class="right-links">
            <!-- Go back button moved below the Add Item button -->
        </div>
    </div>

    <main>
        <div class="form-container">
            <h2>Fill up this form</h2><br><br>
            <form action="item.php" method="POST" enctype="multipart/form-data">
                <label for="type">Item Type:</label>
                <input type="text" name="type" id="type" required><br><br>

                <label for="name">Item Name:</label>
                <input type="text" name="name" id="name" required><br><br>

                <label for="description">Description:</label>
                <textarea name="description" id="description" rows="4" required></textarea><br><br>

                <label for="stocks">Stocks:</label>
                <input type="number" name="stocks" id="stocks" min="1" required><br><br>

                <label for="price">Price:</label>
                <input type="number" name="price" id="price" step="0.01" required><br><br>

                <label for="image">Item Image:</label>
                <input type="file" name="image" id="image" accept="image/*" required><br><br>

                <button type="submit" name="add_item" class="btn">Add Item</button>
            </form>
            
            <!-- Go Back button moved here -->
            <a href="admin.php"><button class="go-back-button">Go Back</button></a>
        </div>
    </main>
</body>
</html>
