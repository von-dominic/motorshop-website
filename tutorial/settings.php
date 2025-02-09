<?php
session_start();
include("php/config.php");  // Include your database connection

// Ensure the user is logged in
if (!isset($_SESSION['valid_customer'])) {
    header("Location: index.php");
    exit();
}

$id = $_SESSION['id'];  // Get the user's ID from session

// Query the database to get user details
$query = mysqli_query($con, "SELECT Username, Email, Id FROM users WHERE Id = $id");

if ($query) {
    $result = mysqli_fetch_assoc($query);
    $res_Uname = $result['Username'];  // Username from database
    $res_Email = $result['Email'];    // Email from database
    $res_id = $result['Id'];          // User ID
} else {
    // Handle case where the user data is not found
    die("Error fetching user data.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/style.css">
    <title>Settings</title>

    <style>
        /* Base Styling */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Trebuchet MS', sans-serif; /* Match order.php font */
            background: url('https://w.wallhaven.cc/full/48/wallhaven-483k3k.jpg') no-repeat center center fixed;
            background-size: cover;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            text-align: center;
            color: #e6e6e6; /* Light grey text color */
        }

        /* Centering the Content */
        .container {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
        }

        /* Circular Profile Image */
        .profile-image {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            border: 5px solid #b71c1c; /* Tactical red border */
            margin-bottom: 20px;
        }

        /* Profile Information */
        .profile-info {
            margin-bottom: 20px;
        }

        .profile-info h2 {
            font-size: 1.8em;
            color: #b71c1c; /* Tactical red for headings */
        }

        /* Style for the "Change Profile" link */
        .right-links a {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #b71c1c; /* Tactical red background */
            color: #fff;
            text-decoration: none;
            font-weight: bold;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .right-links a:hover {
            background-color: #f44336; /* Lighter red on hover */
        }


        /* Updated Button Styling for <a> Tags */
a.btn {
    display: inline-block;
    font-size: 1rem; /* Adjust font size */
    font-weight: bold; /* Make text bold */
    text-decoration: none; /* Remove underline from anchor tags */
    color: white; /* White text for contrast */
    background-color: #b71c1c; /* Tactical red button background */
    border: none; /* Remove default button borders */
    cursor: pointer; /* Pointer cursor on hover */
    text-align: center; /* Center text */
}

a.btn:hover {
    background-color: #f44336; /* Brighter red on hover */
}

a.btn:active {
    background-color: #990000; /* Dark red for active state */
}


</style>

</head>
<body>

    <div class="container">
        <!-- Circular Profile Image -->
        <img src="https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_1280.png" alt="Profile Image" class="profile-image"> <!-- Replace with dynamic path from your DB -->

        <!-- Profile Info (Username, Email, etc.) -->
        <div class="profile-info">
            <h2><?php echo htmlspecialchars($res_Uname); ?></h2>
            <p>Email: <?php echo htmlspecialchars($res_Email); ?></p>
        </div>

        <!-- Change Profile Link -->
        <div class="right-links">
            <a href="customerprofile.php?Id=<?php echo $res_id; ?>">Change Profile</a>
        </div>
        <a href="home.php" class="btn">go back</a>
    </div>
    


</body>
</html>
