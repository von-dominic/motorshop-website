<?php
session_start();

include("php/config.php");

// Check if admin is logged in
if (!isset($_SESSION['admin_valid'])) {
    header("Location: index.php");
    exit();
}

$profile_updated = false; // Variable to track if profile was updated

if (isset($_POST['submit'])) {
    // Collect data from the form
    $username = $_POST['username'];
    $email = $_POST['email'];
    $age = $_POST['age'];

    // Get the logged-in admin's ID
    $id = $_SESSION['admin_id'];

    // Update the admin's profile in the database
    $edit_query = mysqli_query($con, "UPDATE admins SET Username='$username', Email='$email', Age='$age' WHERE Id = $id") or die("Error occurred");

    if ($edit_query) {
        $profile_updated = true;
    } else {
        echo "<div class='message'>
                <p>Error updating profile. Please try again.</p>
              </div><br>";
    }
} else {
    // Fetch current admin's data from the database
    $id = $_SESSION['admin_id'];
    $query = mysqli_query($con, "SELECT * FROM admins WHERE Id = $id");

    while ($result = mysqli_fetch_assoc($query)) {
        $res_Uname = $result['Username'];
        $res_Email = $result['Email'];
        $res_Age = $result['Age'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="">
    <title>Admin Profile</title>
</head>

<style>
/* General Page Styling */
body {
    background: #222 url('https://w.wallhaven.cc/full/48/wallhaven-483k3k.jpg') no-repeat center center fixed;
    background-size: cover;
    font-family: 'Roboto', sans-serif;
    color: #fff;
    margin: 0;
    padding: 0;
}

/* Container for Form */
.container {
    margin-top: 80px; /* Adjust margin for spacing */
    padding: 30px;
    background: rgba(34, 34, 34, 0.85); /* Dark transparent background */
    width: 60%;
    margin: 100px auto;
    border-radius: 10px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.6);
}

/* Form Box Styling */
.form-box {
    background: #333;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.6);
}

.form-box header {
    font-size: 1.8em;
    font-weight: bold;
    color: #f7c22e;
    margin-bottom: 20px;
    text-align: center;
    text-transform: uppercase;
    letter-spacing: 2px;
}

.form-box .field {
    margin-bottom: 20px;
}

.form-box .field input {
    width: 100%;
    padding: 12px;
    background: #444;
    color: #f7c22e;
    border: 2px solid #f7c22e;
    border-radius: 5px;
    font-size: 1.1em;
    transition: border-color 0.3s ease;
}

.form-box .field input:focus {
    border-color: #f7c22e;
    outline: none;
}

.form-box .btn {
    background: #f7c22e;
    color: #222;
    font-size: 1.2em;
    padding: 12px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    transition: background 0.3s ease;
    width: 100%;
}

.form-box .btn:hover {
    background: #e7b31c;
}

/* Message Styling */
.message {
    text-align: center;
    font-size: 1.2em;
    color: #f7c22e;
    margin-bottom: 20px;
}

/* Back to Admin Dashboard Button */
a[href='admin.php'] button {
    background: #444;
    color: #f7c22e;
    font-size: 1.2em;
    padding: 12px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    transition: background 0.3s ease;
    width: 100%;
}

a[href='admin.php'] button:hover {
    background: #e7b31c;
}
</style>

<body>
    <div class="container">
        <?php if ($profile_updated): ?>
            <div class="message">
                <p>Profile Updated!</p>
            </div>
            <a href="admin.php"><button class="btn">Back to Admin Dashboard</button></a>
        <?php else: ?>
            <div class="box form-box">
                <header>Change Profile</header>
                <form action="" method="post">
                    <div class="field input">
                        <label for="username">Username</label>
                        <input type="text" name="username" id="username" value="<?php echo $res_Uname; ?>" autocomplete="off" required>
                    </div>
                    <div class="field input">
                        <label for="email">Email</label>
                        <input type="text" name="email" id="email" value="<?php echo $res_Email; ?>" autocomplete="off" required>
                    </div>
                    <div class="field input">
                        <label for="age">Age</label>
                        <input type="number" name="age" id="age" value="<?php echo $res_Age; ?>" autocomplete="off" required>
                    </div>
                    <div class="field">
                        <input type="submit" class="btn" name="submit" value="Update" required>
                    </div>
                </form>
            </div>
        <?php endif; ?>
        <a href="admin.php"><button class="btn">Go Back</button></a>
    </div>
</body>
</html>
