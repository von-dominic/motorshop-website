<?php
session_start();
include("php/config.php");

// Check if user is logged in
if (!isset($_SESSION['valid_customer'])) {
    header("Location: index.php");
    exit();
}

// Get user ID from session
$userId = $_SESSION['id']; // Customer's user ID

// Fetch customer notifications from the database
$query = "SELECT * FROM customer_notifications WHERE user_id = $userId ORDER BY created_at DESC";
$result = mysqli_query($con, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/style.css">
    <title>Customer Notifications</title>
</head>

<style>
        /* General Body Styling */
        body {
            background: #222 url('https://w.wallhaven.cc/full/48/wallhaven-483k3k.jpg') no-repeat center center fixed;
            background-size: cover;
            color: #ccc;
            font-family: 'Trebuchet MS', sans-serif; /* Font from order.php */
            margin: 0;
            padding: 0;
        }

        .notification-container {
            width: 80%;
            margin: 50px auto;
            padding: 25px;
            background: rgba(34, 34, 34, 0.8);
            border-radius: 15px;
            border: 2px solid #555;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.5);
        }

        .notification-container h1 {
    text-align: center;
    font-size: 2.5rem;
    font-weight: bold;
    color:rgb(255, 255, 255); /* Dark red to match buttons */
    text-transform: uppercase;
    letter-spacing: 2px;
    margin-bottom: 20px;
}


        ul {
            list-style: none;
            padding: 0;
        }

        .notification-message {
            background: #333;
            margin: 15px 0;
            padding: 15px;
            border-radius: 8px;
            border: 1px solid #444;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: #ccc;
            font-size: 1em;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.4);
        }

        .notification-message:hover {
            background: #444;
            cursor: pointer;
        }

        .notification-date {
            font-size: 0.9em;
            color: #bbb;
            font-style: italic;
        }

        /* Button Styling (order.php style) */
        .btn {
            display: inline-block;
            background: #b71c1c; /* Deep red for button */
            color: #fff;
            padding: 12px 25px;
            font-size: 1.1rem;
            font-weight: bold;
            text-transform: uppercase;
            text-decoration: none;
            border: none;
            border-radius: 5px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.4);
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .btn:hover {
            background: #f44336; /* Lighter red on hover */
            transform: translateY(-3px);
            box-shadow: 0px 6px 12px rgba(0, 0, 0, 0.6);
        }

        .btn:active {
            background: #aa0000; /* Darker red when clicked */
            transform: translateY(2px);
            box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.4);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .notification-container {
                width: 95%;
            }

            .notification-container h1 {
                font-size: 2rem;
            }

            .btn {
                font-size: 1rem;
                padding: 10px 20px;
            }
        }
    </style>
</head>
<body>
    <div class="notification-container">
        <h1>Your Notifications</h1>

        <?php if (mysqli_num_rows($result) > 0): ?>
            <ul>
                <?php while ($notification = mysqli_fetch_assoc($result)): ?>
                    <li>
                        <div class="notification-message">
                            <p><?php echo htmlspecialchars($notification['message']); ?></p>
                            <span class="notification-date"><?php echo $notification['created_at']; ?></span>
                        </div>
                    </li>
                <?php endwhile; ?>
            </ul>
        <?php else: ?>
            <p style="text-align: center; font-size: 1.2em; font-style: italic; color: #bbb;">
                You have no notifications.
            </p>
        <?php endif; ?>
    </div>
    <div style="text-align: center; margin-top: 20px;">
        <a href="home.php"><button class="btn">Back to Home</button></a>
    </div>
</body>
</html>
