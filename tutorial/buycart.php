<?php
session_start();
include("php/config.php");

if (!isset($_SESSION['valid_customer'])) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['id'];

// Fetch cart items for the user
$cartItemsQuery = mysqli_prepare($con, 
    "SELECT c.id as cart_id, i.name, i.price, i.image, i.type, c.quantity
     FROM cart c
     JOIN items i ON c.item_id = i.id
     WHERE c.user_id = ?"
);
$cartItemsQuery->bind_param("i", $user_id);
$cartItemsQuery->execute();
$cartItemsResult = $cartItemsQuery->get_result();

// Initialize total price and overall price
$totalPrice = 0;
$shippingFee = 1; // Fixed shipping fee for simplicity
$overallPrice = 0;

if ($cartItemsResult->num_rows == 0) {
    die("Your cart is empty.");
}

// Handle order confirmation when form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $address = mysqli_real_escape_string($con, $_POST['address']);
    $paymentMethod = mysqli_real_escape_string($con, $_POST['payment_method']);
    $onlinePaymentMethod = isset($_POST['online_payment_method']) ? mysqli_real_escape_string($con, $_POST['online_payment_method']) : null;

    // Loop through cart items and insert order records
    while ($cartItem = $cartItemsResult->fetch_assoc()) {
        $itemId = $cartItem['item_id']; // Correct this line
        $quantity = $cartItem['quantity'];
        $itemPrice = $cartItem['price'];
        $itemTotalPrice = $itemPrice * $quantity;

        // Update the overall price
        $totalPrice += $itemTotalPrice;

        // Insert order into the database
        $insertOrderQuery = "
            INSERT INTO orders (user_id, item_id, quantity, total_price, order_date) 
            VALUES ($user_id, {$cartItem['item_id']}, $quantity, $itemTotalPrice, NOW())
        ";

        if (mysqli_query($con, $insertOrderQuery)) {
            // Update item stock
            $itemQuery = mysqli_query($con, "SELECT stocks FROM items WHERE id = {$cartItem['item_id']}");
            $item = mysqli_fetch_assoc($itemQuery);
            $newStock = $item['stocks'] - $quantity;
            $updateStockQuery = "UPDATE items SET stocks = $newStock WHERE id = {$cartItem['item_id']}";
            mysqli_query($con, $updateStockQuery);
        }
    }

    // Calculate overall price
    $overallPrice = $totalPrice + $shippingFee;

    // Insert Admin Notification
    $adminNotificationMessage = "$user_id purchased all items in the cart.";
    $insertAdminNotificationQuery = "INSERT INTO admin_notifications (message) VALUES ('$adminNotificationMessage')";
    mysqli_query($con, $insertAdminNotificationQuery);

    // Insert Customer Notification
    $customerNotificationMessage = "Your order has been placed successfully!";
    $insertCustomerNotificationQuery = "
        INSERT INTO customer_notifications (user_id, message)
        VALUES ($user_id, '$customerNotificationMessage')
    ";
    mysqli_query($con, $insertCustomerNotificationQuery);

    // Redirect to success page
    header("Location: success.php?order=success");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/style.css">
    <title>Order All Items in Cart</title>
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
            max-width: 600px;
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
            return confirm("Are you sure you want to place your order?");
        }
    </script>
</head>
<body>
    <div class="buy-container">
        <h1>Order Details</h1>
        <?php
        while ($cartItem = $cartItemsResult->fetch_assoc()) {
            $itemTotalPrice = $cartItem['price'] * $cartItem['quantity'];
            $totalPrice += $itemTotalPrice;
            ?>
            <div class="item-details">
                <img src="<?php echo $cartItem['image']; ?>" alt="<?php echo htmlspecialchars($cartItem['name']); ?>">
                <h2><?php echo htmlspecialchars($cartItem['name']); ?></h2>
                <p>Type: <?php echo htmlspecialchars($cartItem['type']); ?></p>
                <p>Price: $<?php echo number_format($cartItem['price'], 2); ?></p>
                <p>Quantity: <?php echo $cartItem['quantity']; ?></p>
                <p>Total: $<?php echo number_format($itemTotalPrice, 2); ?></p>
            </div>
            <?php
        }
        // Adding shipping fee and overall price
        $overallPrice = $totalPrice + $shippingFee;
        ?>
        <h3>Total Price: $<?php echo number_format($totalPrice, 2); ?></h3>
        <p>Shipping Fee: $<?php echo number_format($shippingFee, 2); ?></p>
        <h3>Overall Price: $<?php echo number_format($overallPrice, 2); ?></h3>

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
                <input type="radio" id="gcash" name="online_payment_method" value="Gcash">
                <label for="gcash">Gcash</label><br>
                <input type="radio" id="paymaya" name="online_payment_method" value="PayMaya">
                <label for="paymaya">PayMaya</label><br><br>
            </div>
            <button type="submit" class="btn">Confirm Order</button>
        </form>
        <a href="home.php" class="btn">Back to Shop</a>
    </div>
</body>
</html>
