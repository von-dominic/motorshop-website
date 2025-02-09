<?php
session_start();
include("php/config.php");

// Check if the session variable for admin login is set
if (!isset($_SESSION['admin_valid'])) {
    header("Location: adminlogin.php");  // Redirect to admin login page if not logged in as admin
    exit();
}

// Handle item deletion if a delete request is received
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $deleteQuery = "DELETE FROM items WHERE id = $delete_id";

    if (mysqli_query($con, $deleteQuery)) {
        echo "<script>alert('Item deleted successfully!'); window.location.href='deleteitem.php';</script>";
    } else {
        echo "<script>alert('Failed to delete item.');</script>";
    }
}

// Fetch all items from the database
$queryItems = mysqli_query($con, "SELECT * FROM items");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/style.css">
    <title>Delete Items</title>
    <style>
        body {
            background: #f0f0f0 url('https://w.wallhaven.cc/full/48/wallhaven-483k3k.jpg') no-repeat center center fixed;
            background-size: cover;
            font-family: 'Arial', sans-serif;
            color: #333;
            margin: 0;
            padding: 0;
        }

        h2 {
            text-align: center;
            color: #f7c22e;
            font-size: 2rem;
            margin-bottom: 30px;
            text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.3);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            background-color: #f7c22e;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 15px;
            text-align: left;
            vertical-align: middle;
            color: white;
        }

        th {
            background-color: #333;
            font-weight: bold;
        }

        tr:nth-child(even) {
            background-color:rgb(0, 0, 0);
        }

        td img {
            width: 50px;
            height: auto;
            border-radius: 5px;
        }

        .btn {
            background-color:rgb(247, 46, 46);
            color: black;
            padding: 8px 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .btn:hover {
            background-color:rgb(247, 46, 46);
        }

        .go-back {
            display: block;
            text-align: center;
            margin-top: 30px;
        }

        .go-back a {
            text-decoration: none;
            color: #fff;
            background-color:rgb(20, 35, 255);
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 1rem;
            transition: background-color 0.3s ease;
        }

        .go-back a:hover {
            background-color:rgb(57, 69, 237);
        }
    </style>
</head>
<body>

    <main>
        <h2>Delete Items from Stock</h2>
        <table>
            <tr>
                <th>Image</th>
                <th>Name</th>
                <th>Type</th>
                <th>Description</th>
                <th>Price</th>
                <th>Stocks</th>
                <th>Delete</th>
            </tr>

            <?php
            // Display the items from the database
            while ($item = mysqli_fetch_assoc($queryItems)) {
                echo "<tr>";
                echo "<td><img src='" . $item['image'] . "' alt='" . htmlspecialchars($item['name']) . "'></td>";
                echo "<td>" . htmlspecialchars($item['name']) . "</td>";
                echo "<td>" . htmlspecialchars($item['type']) . "</td>";
                echo "<td>" . htmlspecialchars($item['description']) . "</td>";
                echo "<td>$" . number_format($item['price'], 2) . "</td>";
                echo "<td>" . $item['stocks'] . "</td>";
                echo "<td><button class='btn' onclick=\"confirmDeletion('deleteitem.php?delete_id=" . $item['id'] . "')\">Delete</button></td>";
                echo "</tr>";
            }
            ?>
        </table>

        <div class="go-back">
            <a href="admin.php">Go Back</a>
        </div>
    </main>

    <script>
        function confirmDeletion(deleteUrl) {
            if (confirm('Are you sure you want to delete this item?')) {
                window.location.href = deleteUrl;
            }
        }
    </script>
</body>
</html>
