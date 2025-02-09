<?php
session_start();
include("php/config.php");

// Ensure admin is logged in
if (!isset($_SESSION['admin_valid'])) {
    header("Location: adminlogin.php");
    exit();
}

// Fetch orders
$query = "SELECT orders.order_id, users.Username, items.name AS item_name, orders.quantity, orders.total_price, orders.order_date
          FROM orders
          JOIN users ON orders.user_id = users.Id
          JOIN items ON orders.item_id = items.id";
$result = mysqli_query($con, $query);

$totalSales = 0;
$totalItems = 0;
$totalOrders = 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Overview</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f0f0f0 url('https://w.wallhaven.cc/full/48/wallhaven-483k3k.jpg') no-repeat center center fixed;
            background-size: cover;
            color: #333;
            margin: 0;
            padding: 0;
        }
        h1 {
            text-align: center;
            color: #f7c22e;
            margin-top: 20px;
            font-size: 2.5rem;
        }
        table {
            width: 90%;
            margin: 20px auto;
            border-collapse: collapse;
            background: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
        }
        th, td {
            font-family: Arial, sans-serif;
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f7c22e;
            color: #333;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tr:hover {
            background-color: #f7e3b1;
        }
        h3 {
            text-align: center;
            color: #f7c22e;
            margin: 10px 0;
            font-size: 1.5rem;
        }
        .back-button {
            display: block;
            width: 200px;
            margin: 20px auto;
            text-align: center;
            padding: 10px;
            background-color: #f7c22e;
            color: #333;
            text-decoration: none;
            font-weight: bold;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
            transition: background-color 0.3s ease;
        }
        .back-button:hover {
            background-color: #e0b020;
            color: #000;
        }
    </style>
</head>
<body>
    <h1>Sales Overview</h1>
    <table>
        <tr>
            <th>Order ID</th>
            <th>Customer</th>
            <th>Item</th>
            <th>Quantity</th>
            <th>Total Price</th>
            <th>Order Date</th>
        </tr>
        <?php
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>
                    <td>{$row['order_id']}</td>
                    <td>{$row['Username']}</td>
                    <td>{$row['item_name']}</td>
                    <td>{$row['quantity']}</td>
                    <td>\${$row['total_price']}</td>
                    <td>{$row['order_date']}</td>
                  </tr>";
            $totalSales += $row['total_price'];
            $totalItems += $row['quantity'];
            $totalOrders++;
        }
        ?>
    </table>
    <h3>Total Sales: $<?php echo number_format($totalSales, 2); ?></h3>
    <h3>Total Items Sold: <?php echo $totalItems; ?></h3>
    <h3>Total Orders: <?php echo $totalOrders; ?></h3>

    <!-- Go Back to Admin Page Button -->
    <a href="admin.php" class="back-button">Go Back to Admin Page</a>
</body>
</html>
