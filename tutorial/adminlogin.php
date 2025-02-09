<?php
  session_start();
  include("php/config.php");

  if (isset($_POST['submit'])) {
    // Sanitize input to prevent SQL Injection
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $password = mysqli_real_escape_string($con, $_POST['password']);
    
    // Query to check the admin credentials
    $result = mysqli_query($con, "SELECT * FROM admins WHERE Email = '$email'") or die("Query Error: " . mysqli_error($con));
    $row = mysqli_fetch_assoc($result);

    // Check if admin exists and compare the hashed password
    if ($row && password_verify($password, $row['Password'])) {  // Use password_verify for secure comparison
      $_SESSION['admin_valid'] = $row['Email']; // Store admin session
      $_SESSION['admin_username'] = $row['Username'];
      $_SESSION['admin_id'] = $row['Id'];
      header("Location: admin.php"); // Redirect to admin.php
      exit(); // Ensure the rest of the code doesn't execute
    } else {
      echo "<div class='message'><p>Invalid Email or Password</p></div><br>";
      echo "<a href='adminlogin.php'><button class='btn'>Go Back</button></a>";
    }
  } else {
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="">
  <title>Admin Login</title>
</head>
<style>
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

/* Container for the Login Form */
.container {
    max-width: 500px;
    margin: 50px auto;
    padding: 20px;
    background: rgba(34, 34, 34, 0.9);
    border-radius: 15px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.6);
}

/* Form Box Styling */
.form-box {
    background: rgba(44, 44, 44, 0.8);
    padding: 25px;
    border-radius: 10px;
    border: 2px solid #555;
}

/* Header Styling */
header {
    text-align: center;
    font-size: 2.5em;
    color: #f7c22e; /* Gold Accent */
    margin-bottom: 20px;
    font-weight: bold;
    text-transform: uppercase;
    letter-spacing: 2px;
}

/* Form Field Styling */
.field {
    margin-bottom: 20px;
}

.field input {
    width: 100%;
    padding: 12px;
    background: #333;
    border: 1px solid #444;
    border-radius: 8px;
    color: #ccc;
    font-size: 1em;
    transition: all 0.3s ease;
}

.field input:focus {
    border-color: #f7c22e;
    outline: none;
    background: #444;
}

/* Submit Button Styling */
.btn {
    width: 100%;
    padding: 12px;
    background: #f7c22e;
    color: #222;
    font-size: 1.2em;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    transition: background 0.3s ease;
}

.btn:hover {
    background: #e7b31c;
}

/* Links Styling */
.links {
    text-align: center;
    margin-top: 20px;
    font-size: 0.9em;
}

.links a {
    color: #f7c22e;
    text-decoration: none;
    font-weight: bold;
}

.links a:hover {
    text-decoration: underline;
}

/* Message Box Styling */
.message {
    background: #444;
    color: #f7c22e;
    padding: 10px;
    margin: 15px 0;
    border-radius: 8px;
    text-align: center;
    font-weight: bold;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.6);
}

.message p {
    margin: 0;
}

.message a {
    color: #f7c22e;
    font-weight: bold;
}

.message a:hover {
    text-decoration: underline;
}


  body {
    background: #f0f0f0 url('https://w.wallhaven.cc/full/48/wallhaven-483k3k.jpg') no-repeat center center fixed;
    background-size: cover;
  }
</style>
<body>
  <div class="container">
    <div class="box form-box">
      <header> Admin Login</header>
      <form action="" method="post">
        <div class="field input">
          <label for="email">Admin Email</label>
          <input type="text" name="email" id="email" required>
        </div>
        <div class="field input">
          <label for="password">Password</label>
          <input type="password" name="password" id="password" required>
        </div>
        <div class="field">
          <input type="submit" class="btn" name="submit" value="Login as Admin" required>
        </div>
        <div class="links">
          Don't have an account? <a href="adminregister.php">Sign up</a>
          <br>
          Go back to <a href="index.php">Customer Login</a>
        </div>
      </form>
    </div>
  </div>
</body>
</html>

<?php
  } // Closing the else block
?>
