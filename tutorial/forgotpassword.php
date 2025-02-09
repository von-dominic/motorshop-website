<?php
session_start();
include("php/config.php"); // Include database connection file
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="style/style.css">
<title>Forgot Password</title>
<style>
/* Styling similar to the reference */
.adminbox {
    background-color: #fff;
    border: 2px solid black;
    border-radius: 10px;
    overflow: hidden;
    margin-bottom: 20px;
    padding: 15px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease-in-out;
    display: flex;
    flex-direction: column;
    min-height: 300px;
    background-color: #f9f9f9;
}

body {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    body {
    background: #f0f0f0 url('https://w.wallhaven.cc/full/48/wallhaven-483k3k.jpg') no-repeat center center fixed;
    background-size: cover;
}
}
form {
    display: flex;
    flex-direction: column;
}
form .input {
    margin-bottom: 15px;
}
form .btn {
    padding: 10px;
    background-color: #007bff;
    border: none;
    color: white;
    cursor: pointer;
}
form .btn:hover {
    background-color: #0056b3;
}
</style>
</head>
<body>

<div class="adminbox">
    <header>Forgot Password</header>

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email = mysqli_real_escape_string($con, $_POST['email']);
        $new_password = mysqli_real_escape_string($con, $_POST['password']);
    
        $result = mysqli_query($con, "SELECT * FROM users WHERE Email = '$email'");
        if (mysqli_num_rows($result) > 0) {
            // Hash the new password
            $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);
    
            // Update the password
            $stmt = $con->prepare("UPDATE users SET Password = ? WHERE Email = ?");
            $stmt->bind_param("ss", $hashed_password, $email);
            if ($stmt->execute()) {
                echo "<p>Password updated successfully!</p>";
                echo "<a href='index.php'><button class='btn'>Back to Login</button></a>";
            } else {
                echo "<p>Error updating password. Please try again.</p>";
            }
        } else {
            echo "<p>Email not found. Please check your input.</p>";
        }
    }
    
    ?>

    <form method="POST">
        <div class="input">
            <label for="email">Enter your email:</label>
            <input type="email" name="email" id="email" required>
        </div>
        <div class="input">
            <label for="password">Enter new password:</label>
            <input type="password" name="password" id="password" required>
        </div>
        <button type="submit" class="btn">Update Password</button>
    </form>

    <a href="index.php"><button class="btn">Back to Login</button></a>
</div>

</body>
</html>
