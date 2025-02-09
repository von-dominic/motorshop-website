<?php
session_start();

include("php/config.php");
if (!isset($_SESSION['valid_customer'])) {
    header("Location: index.php");
    exit();
}

// Validate item ID and quantity
$itemId = isset($_GET['id']) ? intval($_GET['id']) : 0;
$quantity = isset($_GET['quantity']) ? intval($_GET['quantity']) : 0;

if ($itemId <= 0 || $quantity <= 0) {
    die("Invalid item or quantity.");
}

// Fetch item details from the database
$itemQuery = mysqli_query($con, "SELECT * FROM items WHERE id = $itemId");
if (mysqli_num_rows($itemQuery) == 0) {
    die("Item not found.");
}
$item = mysqli_fetch_assoc($itemQuery);

// Ensure stock field is available
if (!isset($item['stocks'])) {
    die("Stock field is not available for this item.");
}

// Check if there's enough stock
if ($item['stocks'] < $quantity) {
    die("Not enough stock available.");
}

// Calculate totals
$itemPrice = $item['price'];
$totalPrice = $itemPrice * $quantity;
$shippingFee = 1; // Fixed shipping fee
$overallPrice = $totalPrice + $shippingFee;

// Fetch user details
$id = $_SESSION['id'];
$query = mysqli_query($con, "SELECT * FROM users WHERE Id = $id");

while ($result = mysqli_fetch_assoc($query)) {
    $res_Uname = $result['Username'];
    $res_Email = $result['Email'];
    $res_Age = $result['Age'];
    $res_id = $result['Id'];
}

// Handle order confirmation
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $address = mysqli_real_escape_string($con, $_POST['address']);
    $paymentMethod = mysqli_real_escape_string($con, $_POST['payment_method']);
    $onlinePaymentMethod = isset($_POST['online_payment_method']) ? mysqli_real_escape_string($con, $_POST['online_payment_method']) : null;

    // Insert order into the database
    $insertOrderQuery = "
        INSERT INTO orders (user_id, item_id, quantity, total_price, order_date) 
        VALUES ($res_id, $itemId, $quantity, $overallPrice, NOW())
    ";

    if (mysqli_query($con, $insertOrderQuery)) {
        // Update item stock
        $newStock = $item['stocks'] - $quantity;
        $updateStockQuery = "UPDATE items SET stocks = $newStock WHERE id = $itemId";
        
        if (mysqli_query($con, $updateStockQuery)) {
            // Insert Admin Notification
            $adminNotificationMessage = "$res_Uname purchased {$item['name']} x$quantity";
            $insertAdminNotificationQuery = "
                INSERT INTO admin_notifications (message)
                VALUES ('$adminNotificationMessage')
            ";
            mysqli_query($con, $insertAdminNotificationQuery); // Insert the admin notification

            // Insert Customer Notification
            $customerNotificationMessage = "Your item {$item['name']} is shipped!";
            $insertCustomerNotificationQuery = "
                INSERT INTO customer_notifications (user_id, message)
                VALUES ($res_id, '$customerNotificationMessage')
            ";
            mysqli_query($con, $insertCustomerNotificationQuery); // Insert the customer notification

            header("Location: success.php?order=success");
            exit();
        } else {
            die("Error updating stock: " . mysqli_error($con));
        }
    } else {
        die("Error saving order: " . mysqli_error($con));
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
    <title>Buy Item</title>
    <style>
        body {
    background: #f0f0f0 url('https://w.wallhaven.cc/full/48/wallhaven-483k3k.jpg') no-repeat center center fixed;
    background-size: cover;
}

        .buy-container {
            background: rgba(255, 255, 255, 0.8);
            margin: 50px auto;
            padding: 20px;
            border-radius: 10px;
            max-width: 500px;
            text-align: center;
        }

        .item-details img {
            max-width: 100%;
            height: auto;
            border-radius: 10px;
        }
    </style>
    <script>
        function toggleOnlineOptions(show) {
            const onlineOptions = document.getElementById('online-options');
            if (show) {
                onlineOptions.style.display = 'block';
            } else {
                onlineOptions.style.display = 'none';
            }
        }
        function confirmOrder() {
            return confirm("Are you sure of your order/s?");
        }
    </script>
</head>
<body>
    <div class="buy-container">
        <h1>Order Details</h1>
        <div class="item-details">
            <img src="<?php echo $item['image']; ?>" alt="<?php echo htmlspecialchars($item['name']); ?>">
            <h2><?php echo htmlspecialchars($item['name']); ?></h2>
            <p>Type: <?php echo htmlspecialchars($item['type']); ?></p>
            <p>Price: $<?php echo number_format($itemPrice, 2); ?></p>
            <p>Quantity: <?php echo $quantity; ?></p>
            <p>Total: $<?php echo number_format($totalPrice, 2); ?></p>
            <p>Shipping Fee: $<?php echo number_format($shippingFee, 2); ?></p>
            <h3>Overall Price: $<?php echo number_format($overallPrice, 2); ?></h3>
        </div>
        <form method="POST" onsubmit="return confirmOrder()">
            <label for="address">Enter Shipping Address:</label><br>
            <textarea id="address" name="address" rows="3" cols="50" required></textarea><br><br>
            <label for="payment-method">Select Payment Method:</label><br>
            <input type="radio" id="cod" name="payment_method" value="COD" required onchange="toggleOnlineOptions(false)">
            <label for="cod">Cash on Delivery</label><br>
            <input type="radio" id="online" name="payment_method" value="Online Payment" required onchange="toggleOnlineOptions(true)">
            <label for="online">Online Payment</label><br><br>
            <div id="online-options" style="display: none;">
                <label for="online-payment-method">Select Online Payment Method:</label><br>
                <input type="radio" id="gcash" name="online_payment_method" value="Gcash" required>
                <label for="gcash">Gcash</label><br>
                <input type="radio" id="paymaya" name="online_payment_method" value="PayMaya" required>
                <label for="paymaya">PayMaya</label><br><br>
            </div>
            <button type="submit" class="btn">Confirm Order</button>
        </form>
        <a href="home.php" class="btn">Back to Shop</a>
    </div>
</body>
</html>
