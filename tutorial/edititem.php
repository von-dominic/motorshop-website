<?php
include("php/config.php");

// Check if the 'id' is set in the URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch item details from the database
    $stmt = $con->prepare("SELECT * FROM items WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $item = $result->fetch_assoc();
    } else {
        echo "Item not found!";
        exit();
    }

    // Handle the form submission (update item)
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Get the updated data from the form
        $name = $_POST['name'];
        $type = $_POST['type'];
        $description = $_POST['description'];
        $price = $_POST['price'];
        $stocks = $_POST['stocks'];

        // Handle image upload (if a new image is selected)
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            // Define image upload directory
            $uploadDir = "uploads/";
            $uploadFile = $uploadDir . basename($_FILES['image']['name']);
            
            // Move the uploaded file to the uploads directory
            if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadFile)) {
                $imagePath = $uploadFile;
            } else {
                echo "Image upload failed.";
                exit();
            }
        } else {
            // If no new image is uploaded, keep the existing image
            $imagePath = $item['image'];
        }

        // Update the item in the database
        $updateQuery = "UPDATE items SET name = ?, type = ?, description = ?, price = ?, stocks = ?, image = ? WHERE id = ?";
        $stmt = $con->prepare($updateQuery);
        $stmt->bind_param("ssssisi", $name, $type, $description, $price, $stocks, $imagePath, $id);

        if ($stmt->execute()) {
            // Redirect back to the item list after successful update
            header("Location: updateitem.php");
            exit();
        } else {
            echo "Error updating item: " . $stmt->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Item</title>
    <link rel="stylesheet" href="style/style.css">
    <style>
        body {
            background: #f0f0f0 url('https://w.wallhaven.cc/full/48/wallhaven-483k3k.jpg') no-repeat center center fixed;
            background-size: cover;
            font-family: Arial, sans-serif;
            color: #fff;
        }

        main {
            padding: 40px 20px;
            text-align: center;
        }

        h2 {
            font-size: 2.5rem;
            color: #f7c22e;
            margin-bottom: 20px;
            text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.3);
        }

        form {
            background-color: rgba(34, 34, 34, 0.85);
            padding: 20px;
            border-radius: 10px;
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.6);
        }

        label {
            display: block;
            margin-bottom: 10px;
            font-size: 1.1rem;
            color: #f7c22e;
        }

        input[type="text"],
        input[type="number"],
        textarea,
        input[type="file"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #333;
            color: #fff;
        }

        button {
            background-color: rgb(20, 35, 255);
            color: white;
            padding: 8px 15px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            text-transform: uppercase;
            font-size: 0.9rem;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: rgb(20, 35, 200);
        }

        .btn:focus {
            outline: none;
        }

        small {
            color: #f7c22e;
        }
    </style>
</head>
<body>

    <main>
        <h2>Edit Item</h2>
        <form action="edititem.php?id=<?php echo $item['id']; ?>" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo $item['id']; ?>">

            <label for="name">Item Name:</label>
            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($item['name']); ?>" required><br>

            <label for="type">Type:</label>
            <input type="text" id="type" name="type" value="<?php echo htmlspecialchars($item['type']); ?>" required><br>

            <label for="description">Description:</label>
            <textarea id="description" name="description" required><?php echo htmlspecialchars($item['description']); ?></textarea><br>

            <label for="price">Price:</label>
            <input type="text" id="price" name="price" value="<?php echo $item['price']; ?>" required><br>

            <label for="stocks">Stocks:</label>
            <input type="number" id="stocks" name="stocks" value="<?php echo $item['stocks']; ?>" required><br>

            <label for="image">Image:</label>
            <input type="file" id="image" name="image"><br>
            <small>Current Image: <img src="<?php echo $item['image']; ?>" alt="Item Image" style="width: 50px;"></small><br>

            <button type="submit" class="btn">Update Item</button>
        </form>
    </main>
</body>
</html>
