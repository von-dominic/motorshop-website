<?php
session_start();
include("php/config.php");

// Check if the user is logged in
if (!isset($_SESSION['valid_customer'])) {
    header("Location: index.php");
    exit();
}

// Get the logged-in user's ID
$user_id = $_SESSION['id'];

// Fetch the user's orders
$orderQuery = "
    SELECT 
        orders.order_id,
        items.name AS item_name,
        items.price AS item_price,
        orders.quantity,
        orders.total_price,
        orders.order_date
    FROM orders
    JOIN items ON orders.item_id = items.id
    WHERE orders.user_id = $user_id
    ORDER BY orders.order_date DESC
";

$orderResult = mysqli_query($con, $orderQuery);

// Check for errors
if (!$orderResult) {
    die("Error fetching orders: " . mysqli_error($con));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/style.css">
    <title>Order History</title>
    <style>
    /* General Body Styling */
    body {
        background: #0d1117 url('https://w.wallhaven.cc/full/48/wallhaven-483k3k.jpg') no-repeat center center fixed;
        background-size: cover;
        font-family: 'Trebuchet MS', sans-serif;
        color: #e6e6e6;
        margin: 0;
        padding: 0;
    }

    h1 {
        text-align: center;
        color: #fff;
        text-shadow: 0px 4px 6px rgba(0, 0, 0, 0.6);
        font-size: 3rem;
        margin-top: 20px;
        letter-spacing: 2px;
    }

    /* Table Styling */
    .table-container {
        background: rgba(13, 17, 23, 0.9); /* Semi-transparent black container */
        border-radius: 10px;
        padding: 20px;
        margin: 20px auto;
        max-width: 90%;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.5);
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }

    th, td {
        padding: 12px 15px;
        text-align: center;
        border: 1px solid #333; /* Darker borders for a rugged look */
        font-size: 1rem;
    }

    th {
        background: #b71c1c; /* Deep red for headers */
        color: #fff;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    td {
        background: rgba(255, 255, 255, 0.05); /* Subtle translucent cell background */
    }

    tr:nth-child(even) td {
        background: rgba(255, 255, 255, 0.1); /* Slight variation for alternating rows */
    }

    tr:hover td {
        background: #b71c1c; /* Highlight row on hover */
        color: #fff;
        transition: background-color 0.3s ease, color 0.3s ease;
    }

    /* No Orders Message */
    .no-orders {
        text-align: center;
        color: #999;
        font-size: 1.2rem;
        font-style: italic;
        margin-top: 20px;
    }

    /* Buttons */
    .button-container {
        text-align: center;
        margin-top: 20px;
    }

    .btn {
        display: inline-block;
        background: #b71c1c; /* Tactical red for buttons */
        color: #fff;
        padding: 12px 25px;
        font-size: 1.1rem;
        font-weight: bold;
        text-transform: uppercase;
        text-decoration: none;
        border-radius: 5px;
        box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.4);
        transition: all 0.3s ease;
    }

    .btn:hover {
        background: #f44336; /* Lighter red on hover */
        transform: translateY(-3px);
        box-shadow: 0px 6px 12px rgba(0, 0, 0, 0.6);
    }

    .btn:active {
        background: #aa0000; /* Darker red on click */
        transform: translateY(2px);
        box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.4);
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        h1 {
            font-size: 2rem;
        }

        table th, table td {
            font-size: 0.9rem;
        }

        .btn {
            font-size: 0.9rem;
            padding: 10px 20px;
        }
    }
</style>

</head>
<body>
    <h1>Your Order History</h1>

    <?php if (mysqli_num_rows($orderResult) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Item Name</th>
                    <th>Item Price</th>
                    <th>Quantity</th>
                    <th>Total Price</th>
                    <th>Order Date</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($order = mysqli_fetch_assoc($orderResult)): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($order['order_id']); ?></td>
                        <td><?php echo htmlspecialchars($order['item_name']); ?></td>
                        <td>$<?php echo number_format($order['item_price'], 2); ?></td>
                        <td><?php echo htmlspecialchars($order['quantity']); ?></td>
                        <td>$<?php echo number_format($order['total_price'], 2); ?></td>
                        <td><?php echo htmlspecialchars($order['order_date']); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p class="no-orders">You have no orders yet.</p>
    <?php endif; ?>
    <a href="home.php" class="btn">Back to Shop</a>
</body>
</html>
