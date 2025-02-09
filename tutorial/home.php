<?php
session_start();
include("php/config.php");

if (!isset($_SESSION['valid_customer'])) {
    header("Location: index.php");
    exit();
}

$id = $_SESSION['id'];
$query = mysqli_query($con, "SELECT * FROM users WHERE Id = $id");

while ($result = mysqli_fetch_assoc($query)) {
    $res_Uname = $result['Username'];
    $res_Email = $result['Email'];
    $res_id = $result['Id'];
}

// Check if action is 'add_to_cart'
if (isset($_GET['action']) && $_GET['action'] == 'add_to_cart') {
    // Retrieve and sanitize GET parameters
    $userId = mysqli_real_escape_string($con, $_GET['user_id']);
    $itemId = mysqli_real_escape_string($con, $_GET['item_id']);
    $quantity = mysqli_real_escape_string($con, $_GET['quantity']);

    // Insert the item into the cart table
    $query = "INSERT INTO cart (user_id, item_id, quantity) VALUES ('$userId', '$itemId', '$quantity')";
    
    if (mysqli_query($con, $query)) {
        // Success message for adding to cart
        echo "<script>alert('Item has been added to your cart.');</script>";
    } else {
        // Error message if query fails
        echo "<script>alert('Error adding item to cart: " . mysqli_error($con) . "');</script>";
    }
}

$searchQuery = isset($_GET['query']) ? $_GET['query'] : '';
// Modify the SQL query to filter results based on the search query
if ($searchQuery) {
    // Use prepared statements to avoid SQL injection
    $stmt = $con->prepare("SELECT * FROM items WHERE name LIKE ? OR type LIKE ?");
    $searchTerm = "%" . $searchQuery . "%";
    $stmt->bind_param("ss", $searchTerm, $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    // If no search query, get all items
    $result = mysqli_query($con, "SELECT * FROM items");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="">
    <title>Home</title>
    <!-- Importing Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>
/* General Reset and Styling */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Trebuchet MS', sans-serif; /* Match font */
    background: #0d1117 url('https://w.wallhaven.cc/full/48/wallhaven-483k3k.jpg') no-repeat center center fixed;
    background-size: cover;
    color: #e6e6e6; /* Light grey text */
}

/* Navigation Bar */
.nav {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background-color: rgba(0, 0, 0, 0.9);
    padding: 10px 20px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.5);
}

.logo p a {
    text-decoration: none;
    font-size: 24px;
    font-weight: bold;
    color: #fff;
}

.right-links a {
    text-decoration: none;
    color: #e6e6e6;
    margin-left: 15px;
    font-weight: 500;
    transition: color 0.3s ease;
}

.right-links a:hover {
    color: #f44336; /* Match hover red */
}

/* Buttons */
.btn {
    background-color: #b71c1c; /* Tactical red */
    color: #fff;
    border: none;
    padding: 8px 15px;
    border-radius: 5px;
    cursor: pointer;
    font-weight: bold;
    transition: background-color 0.3s ease, transform 0.2s ease;
}

.btn:hover {
    background-color: #f44336;
    transform: translateY(-3px);
}

.btn:active {
    background-color: #aa0000;
    transform: translateY(2px);
}

/* Welcome Section */
.main-box .top {
    text-align: center;
    margin-top: 20px;
    font-size: 1.2em;
    color: #fff;
    text-shadow: 2px 2px 6px rgba(0, 0, 0, 0.6);
}

/* Search Bar */
.search-bar {
    display: flex;
    justify-content: center;
    margin: 20px 0;
}

.search-bar input[type="text"] {
    padding: 10px;
    width: 300px;
    border: 1px solid #333;
    border-radius: 5px 0 0 5px;
    outline: none;
    font-size: 16px;
    background-color: rgba(255, 255, 255, 0.1);
    color: #e6e6e6;
}

.search-bar button {
    padding: 10px 15px;
    background-color: #b71c1c;
    border: none;
    color: #fff;
    font-size: 16px;
    cursor: pointer;
    border-radius: 0 5px 5px 0;
    transition: background-color 0.3s ease;
}

.search-bar button:hover {
    background-color: #f44336;
}

/* Product Grid */
.food-box {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 20px;
    margin: 20px;
}

.food-item {
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid #333;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.5);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    width: calc(25% - 20px);
    text-align: center;
}

.food-item img {
    width: 100%;
    height: 200px;
    object-fit: cover;
}

.food-info {
    padding: 15px;
}

.food-info h3 {
    font-size: 1.1em;
    color: #e6e6e6;
}

.food-info h2 {
    font-size: 1.4em;
    margin: 10px 0;
    font-weight: 600;
    color: #fff;
}

.food-info p {
    color: #ccc;
    font-size: 0.9em;
}

.food-info .price {
    font-size: 1.3em;
    font-weight: bold;
    color: #b71c1c; /* Price color */
}

.food-item:hover {
    transform: translateY(-8px);
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.6);
}

/* Out of Stock */
.out-of-stock {
    color: #f44336;
    font-weight: bold;
}

/* Popup Modal */
.popup-modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.6);
    justify-content: center;
    align-items: center;
    z-index: 1000;
    animation: fadeIn 0.3s ease-in-out;
}

.popup-content {
    background: #0d1117;
    padding: 20px;
    border-radius: 8px;
    text-align: center;
    width: 300px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.5);
    color: #e6e6e6;
}

.popup-content p {
    font-size: 1.1em;
    margin-bottom: 20px;
}

.popup-content .btn {
    margin: 5px;
}

/* Animations */
@keyframes fadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

/* Responsive Design */
@media (max-width: 1024px) {
    .food-item {
        width: calc(50% - 20px);
    }
}

@media (max-width: 768px) {
    .food-item {
        width: calc(100% - 20px);
    }
}
</style>


   <script>
   // Show the popup for confirmation (buy or add-to-cart)
   function showPopup(action, weaponId, stocks) {
    console.log("Showing popup", action, weaponId, stocks);

    // Ensure the popup is visible and correctly positioned
    const popupModal = document.getElementById('popup-modal');
    popupModal.style.display = 'flex'; // Display the popup as flex
    popupModal.setAttribute('data-weapon-id', weaponId);
    popupModal.setAttribute('data-stocks', stocks);
    popupModal.setAttribute('data-action', action);

    const questionElement = document.getElementById('popup-question');
    if (action === 'buy') {
        questionElement.innerText = 'Are you sure you want to buy this item?';
    } else if (action === 'add-to-cart') {
        questionElement.innerText = 'Do you want to add this item to your cart?';
    }
}

// Handle the purchase confirmation (show quantity popup for buy only)
function confirmPurchase() {
    console.log("Confirming purchase");

    const weaponId = document.getElementById('popup-modal').getAttribute('data-weapon-id');
    const stocks = document.getElementById('popup-modal').getAttribute('data-stocks');
    const action = document.getElementById('popup-modal').getAttribute('data-action');
    
    closePopup(); // Close the confirmation popup
    
    if (weaponId && stocks) {
        if (action === 'buy') {
            showQuantityPopup(weaponId, stocks); // Show quantity input popup for buy action
        } else if (action === 'add-to-cart') {
            addToCart(); // Add to cart directly for add-to-cart action
        }
    }
}


// Close both the confirmation and quantity popups
function closePopup() {
    console.log("Closing popup");

    // Hide both modals (confirmation and quantity)
    const popupModal = document.getElementById('popup-modal');
    const quantityPopup = document.getElementById('quantity-popup-modal');
    popupModal.style.display = 'none';
    quantityPopup.style.display = 'none';
}

// Show the quantity input popup (only for 'buy' action)
function showQuantityPopup(weaponId, stocks) {
    const quantityPopup = document.getElementById('quantity-popup-modal');
    quantityPopup.style.display = 'flex'; // Show the quantity popup
    quantityPopup.setAttribute('data-weapon-id', weaponId);
    quantityPopup.setAttribute('data-stocks', stocks);
}

// Handle the quantity input and submission (for 'buy' only)
function submitQuantity() {
    const quantityPopup = document.getElementById('quantity-popup-modal');
    const weaponId = quantityPopup.getAttribute('data-weapon-id');
    const stocks = parseInt(quantityPopup.getAttribute('data-stocks'), 10);
    const quantity = parseInt(document.getElementById('quantity-input').value, 10);

    // Ensure quantity is valid
    if (quantity > stocks) {
        alert("You cannot buy more than the available stock!");
        return;
    }

    if (weaponId && quantity > 0) {
        // Redirect to buy.php with selected weapon and quantity
        window.location.href = `buy.php?id=${weaponId}&quantity=${quantity}`;
    } else {
        alert("Please enter a valid quantity.");
    }
    closePopup(); // Close the popup after submission
}

// Handle adding item to cart without asking for quantity (default quantity = 1)
function addToCart() {
    const weaponId = document.getElementById('popup-modal').getAttribute('data-weapon-id');
    const quantity = 1; // Default quantity for add-to-cart action
    
    // Redirect to the same page with the necessary GET parameters (user_id, item_id, quantity)
    const userId = <?php echo json_encode($_SESSION['id']); ?>;
    window.location.href = `?action=add_to_cart&user_id=${userId}&item_id=${weaponId}&quantity=${quantity}`;
}


</script>

</head>
<body>
    <!-- Your content structure -->
    <div class="nav">
        <div class="logo"><p><a href="home.php">Weapon Shop</a></p></div>
        <div class="right-links">
            <a href="orders.php"><button class="btn">Orders</button></a>
            <a href="cart.php"><button class="btn">Cart</button></a>
            <a href="cunotifications.php"><button class="btn">Notification</button></a>
            <a href="php/logout.php"><button class="btn">Log out</button></a>
            <a href="settings.php"><button class="btn">Settings</button></a>
        </div>
    </div>

    <!-- Main content for items -->
    <main>
        <div class="main-box">
            <div class="top">
                <div class="box">
                    <p>Hello, <b><?php echo $res_Uname; ?></b>! Welcome to <b>Weapon Shop</b>.</p>
                </div>
            </div>

            <div class="bottom">
                <form action="home.php" method="GET" class="search-bar">
                    <input type="text" name="query" placeholder="Search firearms..." value="<?php echo htmlspecialchars($searchQuery); ?>">
                    <button type="submit" class="btn">Search</button>
                </form>
            </div>

            <!-- Products Section -->
            <div class="food-section">
                <h2>Our Firearms Products</h2>
                <div class="food-box">
                    <?php
                    if (mysqli_num_rows($result) > 0) {
                        while ($item = mysqli_fetch_assoc($result)) {
                            echo '<div class="food-item">';
                            echo '<img src="' . $item['image'] . '" alt="' . htmlspecialchars($item['name']) . '">';
                            echo '<div class="food-info">';
                            echo '<h3>' . htmlspecialchars($item['type']) . '</h3>';
                            echo '<h2>' . htmlspecialchars($item['name']) . '</h2>';
                            echo '<p>' . htmlspecialchars($item['description']) . '</p>';
                            echo '<p class="price">$' . number_format($item['price'], 2) . '</p>';
                            echo '<p class="stocks">Available Stocks: ' . htmlspecialchars($item['stocks']) . '</p>';
                            
                            if ($item['stocks'] > 0) {
                                echo '<button class="btn" onclick="showPopup(\'buy\', ' . $item['id'] . ', ' . $item['stocks'] . ')">Buy</button>';
                                echo '<button class="btn" onclick="showPopup(\'add-to-cart\', ' . $item['id'] . ', ' . $item['stocks'] . ')">Add to Cart</button>';
                            } else {
                                echo '<p class="out-of-stock" style="color: red;">Out of Stock</p>';
                            }

                            echo '</div>';
                            echo '</div>';
                        }
                    } else {
                        echo '<p>No items found matching your search.</p>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </main>

    <!-- Popup modals for quantity -->
    <div id="quantity-popup-modal" class="popup-modal">
        <div class="popup-content">
            

            <p>Enter the quantity:</p>
            <input type="number" id="quantity-input" min="1" placeholder="Quantity">
            <button class="btn" onclick="submitQuantity()">OK</button>
            <button class="btn" onclick="closePopup()">Cancel</button>
        </div>
    </div>

    <!-- Popup Modal for buy or add to cart confirmation -->
    <div id="popup-modal" class="popup-modal">
        <div class="popup-content">
            <p id="popup-question">Are you sure?</p>
            <button class="btn" id="yes-btn" onclick="confirmPurchase()">Yes</button>
            <button class="btn" id="no-btn" onclick="closePopup()">No</button>
        </div>
    </div>
</body>
</html>
