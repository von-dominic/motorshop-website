<?php
session_start();
include("php/config.php");

// Ensure the user is logged in
if (!isset($_SESSION['valid_customer'])) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['id'];

// Get action, item ID, and quantity
$action = isset($_GET['action']) ? $_GET['action'] : '';
$item_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$quantity = isset($_GET['quantity']) ? (int)$_GET['quantity'] : 0;

// Handle adding items to the cart
if ($action === 'add' && $item_id > 0 && $quantity > 0) {
    // Check if the item exists and has sufficient stock
    $itemQuery = mysqli_prepare($con, "SELECT stocks FROM items WHERE id = ?");
    $itemQuery->bind_param("i", $item_id);
    $itemQuery->execute();
    $result = $itemQuery->get_result();

    if ($result->num_rows > 0) {
        $item = $result->fetch_assoc();
        if ($quantity > $item['stocks']) {
            echo "<script>alert('Error: Insufficient stock.'); window.location='cart.php';</script>";
            exit();
        }

        // Check if the item is already in the cart
        $cartQuery = mysqli_prepare($con, "SELECT quantity FROM cart WHERE user_id = ? AND item_id = ?");
        $cartQuery->bind_param("ii", $user_id, $item_id);
        $cartQuery->execute();
        $cartResult = $cartQuery->get_result();

        if ($cartResult->num_rows > 0) {
            // Update the existing quantity
            $cartItem = $cartResult->fetch_assoc();
            $newQuantity = $cartItem['quantity'] + $quantity;

            if ($newQuantity > $item['stocks']) {
                echo "<script>alert('Error: Exceeding available stock.'); window.location='cart.php';</script>";
                exit();
            }

            $updateCart = mysqli_prepare($con, "UPDATE cart SET quantity = ? WHERE user_id = ? AND item_id = ?");
            $updateCart->bind_param("iii", $newQuantity, $user_id, $item_id);
            $updateCart->execute();
        } else {
            // Add the item to the cart
            $insertCart = mysqli_prepare($con, "INSERT INTO cart (user_id, item_id, quantity) VALUES (?, ?, ?)");
            $insertCart->bind_param("iii", $user_id, $item_id, $quantity);
            $insertCart->execute();
        }

        echo "<script>alert('Item added to the cart!'); window.location='cart.php';</script>";
        exit();
    } else {
        echo "<script>alert('Error: Item not found.'); window.location='cart.php';</script>";
        exit();
    }
}

// Handle item removal from the cart
if ($action === 'remove' && $item_id > 0) {
    $removeQuery = mysqli_prepare($con, "DELETE FROM cart WHERE id = ? AND user_id = ?");
    $removeQuery->bind_param("ii", $item_id, $user_id);
    $removeQuery->execute();

    echo "<script>alert('Item removed from the cart.'); window.location='cart.php';</script>";
    exit();
}

// Handle item quantity update
if ($action === 'update' && isset($_GET['cart_id']) && isset($_GET['quantity'])) {
    $cart_id = (int)$_GET['cart_id'];
    $new_quantity = (int)$_GET['quantity'];

    // Validate the quantity and update the cart
    $itemQuery = mysqli_prepare($con, "SELECT stocks FROM items WHERE id = (SELECT item_id FROM cart WHERE id = ? AND user_id = ?)");
    $itemQuery->bind_param("ii", $cart_id, $user_id);
    $itemQuery->execute();
    $result = $itemQuery->get_result();

    if ($result->num_rows > 0) {
        $item = $result->fetch_assoc();
        $stocks = $item['stocks'];

        // Ensure the new quantity is valid
        if ($new_quantity <= $stocks && $new_quantity > 0) {
            $updateCart = mysqli_prepare($con, "UPDATE cart SET quantity = ? WHERE id = ? AND user_id = ?");
            $updateCart->bind_param("iii", $new_quantity, $cart_id, $user_id);
            $updateCart->execute();

            echo "<script>alert('Item quantity updated successfully!'); window.location='cart.php';</script>";
        } else {
            echo "<script>alert('Error: Invalid quantity.'); window.location='cart.php';</script>";
        }
    } else {
        echo "<script>alert('Error: Item not found.'); window.location='cart.php';</script>";
    }
}

// Fetch the cart items for the user
$cartItemsQuery = mysqli_prepare($con, 
    "SELECT c.id as cart_id, i.name, i.price, c.quantity 
     FROM cart c 
     JOIN items i ON c.item_id = i.id 
     WHERE c.user_id = ?"
);
$cartItemsQuery->bind_param("i", $user_id);
$cartItemsQuery->execute();
$cartItemsResult = $cartItemsQuery->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Your Cart</title>
    <style>
    /* General Body Styling */
    body {
        font-family: 'Trebuchet MS', sans-serif;
        margin: 20px;
        background: #121212 url('https://w.wallhaven.cc/full/48/wallhaven-483k3k.jpg') no-repeat center center fixed;
        background-size: cover;
        color: #e6e6e6;
    }

    /* Table Styling */
    table {
        width: 100%;
        border-collapse: collapse;
        background: rgba(0, 0, 0, 0.7); /* Semi-transparent black background for the table */
        border: 2px solid #444; /* Metallic-style dark border */
        border-radius: 8px;
        overflow: hidden; /* Rounds the table edges */
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.5);
        margin-bottom: 20px;
    }

    th, td {
        padding: 15px;
        text-align: left;
        font-size: 1rem;
        border-bottom: 1px solid #444; /* Adds a sleek divider */
        color: #e6e6e6;
    }

    th {
        background-color: #b71c1c; /* Deep red header background */
        color: #fff;
        text-transform: uppercase;
        font-weight: bold;
        letter-spacing: 1px;
        border: none;
    }

    td {
        background: rgba(255, 255, 255, 0.05); /* Subtle cell background for contrast */
    }

    tr:nth-child(even) td {
        background: rgba(255, 255, 255, 0.1); /* Alternate row styling */
    }

    tr:hover td {
        background: #b71c1c; /* Tactical red row highlight */
        color: #fff;
        transition: background-color 0.3s ease, color 0.3s ease;
    }

    /* Links Styling */
    a {
        text-decoration: none;
        color: #b71c1c; /* Tactical red links */
        font-weight: bold;
    }

    a:hover {
        text-decoration: underline;
        color: #f44336; /* Brighter red on hover */
    }

    /* Updated Button Styling for <a> Tags */
a.btn {
    display: inline-block;
    padding: 12px 20px; /* Button padding for shape */
    font-size: 1rem; /* Adjust font size */
    font-weight: bold; /* Make text bold */
    text-decoration: none; /* Remove underline from anchor tags */
    color: white; /* White text for contrast */
    background-color: #b71c1c; /* Tactical red button background */
    border-radius: 8px; /* Rounded corners */
    cursor: pointer; /* Pointer cursor on hover */
    text-align: center; /* Center text */
    box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.4); /* Box shadow for depth */
    transition: all 0.3s ease; /* Smooth animation for hover effects */
}

a.btn:hover {
    background-color: #f44336; /* Brighter red on hover */
    transform: translateY(-3px); /* Slight lift effect */
    box-shadow: 0px 6px 12px rgba(0, 0, 0, 0.6); /* Enhanced shadow on hover */
}

a.btn:active {
    background-color: #990000; /* Dark red for active state */
    transform: translateY(2px); /* Push down effect on click */
    box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.4); /* Subtle shadow on active */
}


    /* Header Styling */
    h1 {
        text-align: center;
        color: #fff;
        font-size: 2.5rem;
        letter-spacing: 2px;
        text-shadow: 0px 4px 8px rgba(0, 0, 0, 0.8);
        margin-bottom: 30px;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        h1 {
            font-size: 1.8rem;
        }

        table {
            font-size: 0.9rem;
        }

        .btn {
            font-size: 0.9rem;
            padding: 10px 15px;
        }
    }
</style>

</head>
<body>
    <h1>Your Cart</h1>
    <table>
        <tr>
            <th>Item</th>
            <th>Price</th>
            <th>Quantity</th>
            <th>Total</th>
            <th>Actions</th>
        </tr>
        <?php
        $total = 0;
        while ($cartItem = $cartItemsResult->fetch_assoc()) {
            $itemTotal = $cartItem['price'] * $cartItem['quantity'];
            $total += $itemTotal;
            echo "<tr>";
            echo "<td>" . htmlspecialchars($cartItem['name']) . "</td>";
            echo "<td>$" . number_format($cartItem['price'], 2) . "</td>";
            echo "<td>" . htmlspecialchars($cartItem['quantity']) . "</td>";
            echo "<td>$" . number_format($itemTotal, 2) . "</td>";
            echo "<td>
                    <a href='cart.php?action=remove&id=" . $cartItem['cart_id'] . "'>Remove</a> | 
                    <a href='cart.php?action=edit&id=" . $cartItem['cart_id'] . "&quantity=" . $cartItem['quantity'] . "'>Edit</a>
                  </td>";
            echo "</tr>";
        }
        ?>
        <tr>
            <td colspan="3"><strong>Total:</strong></td>
            <td><strong>$<?php echo number_format($total, 2); ?></strong></td>
            <td></td>
        </tr>
    </table>
    <a href="buycart.php" class="btn">Check out</a>
<a href="home.php" class="btn">Continue Shopping</a>

</body>
</html>

<?php
// Handle the "Edit" action: Display the form for changing quantity
if ($action === 'edit' && isset($_GET['id']) && isset($_GET['quantity'])) {
    $cart_id = (int)$_GET['id'];
    $current_quantity = (int)$_GET['quantity'];

    // Fetch item details for validation if necessary
    $itemQuery = mysqli_prepare($con, "SELECT i.id, i.stocks FROM cart c JOIN items i ON c.item_id = i.id WHERE c.id = ? AND c.user_id = ?");
    $itemQuery->bind_param("ii", $cart_id, $user_id);
    $itemQuery->execute();
    $itemResult = $itemQuery->get_result();

    if ($itemResult->num_rows > 0) {
        $item = $itemResult->fetch_assoc();
        $item_id = $item['id'];
        $stocks = $item['stocks'];

        // Show the edit form
        echo "
            <h2>Edit Item Quantity</h2>
            <form action='cart.php' method='GET'>
                <input type='hidden' name='action' value='update'>
                <input type='hidden' name='cart_id' value='$cart_id'>
                <input type='hidden' name='item_id' value='$item_id'>
                <label for='quantity'>Quantity:</label>
                <input type='number' name='quantity' value='$current_quantity' min='1' max='$stocks' required>
                <button type='submit' class='btn'>Update</button>
            </form>
            <a href='cart.php'>Cancel</a>
        ";
    } else {
        echo "<p>Item not found in your cart.</p>";
    }
}
?>
