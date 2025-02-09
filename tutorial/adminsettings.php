<?php
session_start();
include("php/config.php");

// Ensure the admin is logged in
if (!isset($_SESSION['admin_valid'])) {
    header("Location: adminlogin.php");
    exit();
}

$id = $_SESSION['admin_id']; // Get the admin ID from the session

// Query the database to get admin details
$query = mysqli_query($con, "SELECT Username, Email, Id FROM admins WHERE Id = $id");

if ($query) {
    $result = mysqli_fetch_assoc($query);
    $res_Uname = $result['Username'];  // Admin's username
    $res_Email = $result['Email'];    // Admin's email
    $res_id = $result['Id'];          // Admin's ID
} else {
    die("Error fetching admin data.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/style.css">
    <title>Admin Settings</title>

    <style>
        /* Base Styling */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Trebuchet MS', sans-serif;
            background: url('https://w.wallhaven.cc/full/48/wallhaven-483k3k.jpg') no-repeat center center fixed;
            background-size: cover;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: #e6e6e6;
            text-align: center;
        }

        .profile-image {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid  #f7c22e;
            margin-bottom: 15px;
        }

        .profile-info h2 {
            font-size: 1.4em;
            color:  #f7c22e;
            margin-bottom: 10px;
        }

        .profile-info p {
            font-size: 1em;
            margin-bottom: 15px;
        }

        .btn {
            display: inline-block;
            font-size: 0.9rem;
            font-weight: bold;
            text-decoration: none;
            color: white;
            background-color:  #f7c22e;
            padding: 8px 15px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
            margin: 5px 0;
        }

        .btn:hover {
            background-color: #f7c22e;
        }

        .btn:active {
            background-color:  #f7c22e;
        }
    </style>
</head>
<body>

    <!-- Circular Profile Image -->
    <img src="https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_1280.png" alt="Profile Image" class="profile-image"> <!-- Replace with actual image path -->

    <!-- Profile Information -->
    <div class="profile-info">
        <h2><?php echo htmlspecialchars($res_Uname); ?></h2>
        <p>Email: <?php echo htmlspecialchars($res_Email); ?></p>
    </div>

    <!-- Buttons -->
    <a href="adminprofile.php?Id=<?php echo $res_id; ?>" class="btn">Change Profile</a>
    <a href="admin.php" class="btn">Go Back</a>

</body>
</html>
