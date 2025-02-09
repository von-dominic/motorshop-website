<?php
session_start();

include("php/config.php");

// Check if admin is logged in
if (!isset($_SESSION['admin_valid'])) {
    echo "Please log in to view notifications.";
    exit;
}

// Get admin ID from session
$adminId = $_SESSION['admin_id']; // Admin's user ID, assuming it's stored in the session

// Fetch admin notifications from the database
$query = "SELECT * FROM admin_notifications ORDER BY created_at DESC";
$result = mysqli_query($con, $query);

// Handle notification deletion
if (isset($_GET['delete_id'])) {
    $deleteId = intval($_GET['delete_id']);
    // Delete the specific notification
    $deleteQuery = "DELETE FROM admin_notifications WHERE id = $deleteId";
    
    if (mysqli_query($con, $deleteQuery)) {
        header("Location: adnotifications.php"); // Refresh the page after deletion
        exit();
    } else {
        echo "Error deleting notification: " . mysqli_error($con);
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
    <title>Admin Notifications</title>
</head>

<style>
/* General Page Styling */
body {
    background: #222 url('https://w.wallhaven.cc/full/48/wallhaven-483k3k.jpg') no-repeat center center fixed;
    background-size: cover;
    color: #ccc;
    font-family: 'Roboto', sans-serif;
    margin: 0;
    padding: 0;
}

/* Notification Container Styling */
.notification-container {
    width: 70%;
    margin: 50px auto;
    padding: 30px;
    background: rgba(34, 34, 34, 0.85); /* Darkened background with transparency */
    border-radius: 15px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.6); /* Add depth with shadow */
}

/* Title Styling */
.notification-container h1 {
    text-align: center;
    font-size: 2.5em;
    color: #f7c22e; /* Gold Accent for the header */
    margin-bottom: 20px;
    font-weight: bold;
    text-transform: uppercase;
    letter-spacing: 2px;
}

/* Individual Notification Message Styling */
.notification-message {
    background: #333; /* Dark background for each notification */
    margin: 15px 0;
    padding: 20px;
    border-radius: 8px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.6); /* Subtle shadow for depth */
}

/* Notification Date Styling */
.notification-date {
    font-size: 0.9em;
    color: #ccc;
    font-style: italic;
}

/* Delete Button Styling */
.delete-btn {
    color: #f7c22e; /* Gold color for the delete button */
    text-decoration: none;
    font-weight: bold;
    padding: 5px 10px;
    border: 2px solid #f7c22e;
    border-radius: 5px;
    transition: all 0.3s ease;
}

.delete-btn:hover {
    background: #f7c22e;
    color: #222;
    border: 2px solid #f7c22e;
}

/* Button to Return to Admin Dashboard */
.btn {
    width: 100%;
    padding: 12px;
    background: #f7c22e; /* Bold gold color for buttons */
    color: #222;
    font-size: 1.2em;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    transition: background 0.3s ease;
    text-align: center;
    display: inline-block;
    margin-top: 20px;
}

.btn:hover {
    background: #e7b31c; /* Slightly darker gold on hover */
}

/* No notifications message */
.notification-container p {
    color: #ccc;
    text-align: center;
    font-size: 1.2em;
    font-weight: bold;
}
</style>



<body>
    <div class="notification-container">
        <h1>Admin Notifications</h1>

        <?php if (mysqli_num_rows($result) > 0): ?>
            <ul>
                <?php while ($notification = mysqli_fetch_assoc($result)): ?>
                    <li>
                        <div class="notification-message">
                            <p><?php echo htmlspecialchars($notification['message']); ?></p>
                            <span class="notification-date"><?php echo $notification['created_at']; ?></span>
                            <a href="adnotifications.php?delete_id=<?php echo $notification['id']; ?>" class="delete-btn">Delete</a>
                        </div>
                    </li>
                <?php endwhile; ?>
            </ul>
        <?php else: ?>
            <p>No notifications found.</p>
        <?php endif; ?>

    </div>
    <a href="admin.php"><button class="btn">Back to Admin Dashboard</button></a>
</body>
</html>
