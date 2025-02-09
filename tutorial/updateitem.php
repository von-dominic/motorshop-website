<?php
session_start();
include("php/config.php");

// Check if the session variable for admin login is set
if (!isset($_SESSION['admin_valid'])) {
    header("Location: adminlogin.php");  // Redirect to admin login page if not logged in as admin
    exit();
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
    <title>Items List</title>
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

        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
            background: rgba(34, 34, 34, 0.85);
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.6);
        }

        th, td {
            padding: 12px;
            text-align: center;
            border-bottom: 1px solid #f7c22e;
        }

        th {
            background-color: #333;
            color: #f7c22e;
            font-size: 1.1rem;
        }

        td {
            background-color: #444;
            font-size: 1rem;
        }

        td img {
            width: 50px;
            height: auto;
            border-radius: 5px;
        }

        .btn {
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

        .btn:hover {
            background-color: rgb(20, 35, 200);
        }

        .btn:focus {
            outline: none;
        }

        /* Hide the navigation bar */
        .nav {
            display: none;
        }

        /* Styling for the go back button container */
        .go-back-btn {
            margin-top: 30px;
            text-align: center;
        }
    </style>
</head>
<body>

    <main>
        <h2>Items in Stock</h2>
        <table>
            <tr>
                <th>Image</th>
                <th>Name</th>
                <th>Type</th>
                <th>Description</th>
                <th>Price</th>
                <th>Stocks</th>
                <th>Edit</th>
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
                echo "<td><a href='edititem.php?id=" . $item['id'] . "'><button class='btn'>Edit</button></a></td>";
                echo "</tr>";
            }
            ?>
        </table>

        <!-- Go Back Button under the table -->
        <div class="go-back-btn">
            <a href="admin.php"><button class="btn">Go Back</button></a>
        </div>
    </main>
</body>
</html>
