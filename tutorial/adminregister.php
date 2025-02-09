<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="">
  <title>Admin Register</title>
</head>
<style>
  body {
    background: #f0f0f0 url('https://w.wallhaven.cc/full/48/wallhaven-483k3k.jpg') no-repeat center center fixed;
    background-size: cover;
}
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

/* Container for the Registration Form */
.container {
    max-width: 500px;
    margin: 50px auto;
    padding: 20px;
    background: rgba(34, 34, 34, 0.9); /* Darkened background for better contrast */
    border-radius: 15px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.6); /* Subtle shadow for depth */
}

/* Form Box Styling */
.form-box {
    background: rgba(44, 44, 44, 0.9);
    padding: 25px;
    border-radius: 10px;
    border: 2px solid #555; /* Subtle border for modern look */
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
    background: #444; /* Slightly lighter background on focus */
}

/* Submit Button Styling */
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
}

.btn:hover {
    background: #e7b31c; /* Slightly darker gold on hover */
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
</style>

</style>
<body>
  <div class="container">
    <div class="box form-box">
      <?php
      include("php/config.php");
      if (isset($_POST['submit'])) {
        $username = $_POST['username'];
        $email = $_POST['email'];
        $age = $_POST['age'];
        $password = $_POST['password'];

        // Validate username length
        if (strlen($username) <= 2) {
          echo "<div class='message'>
          <p>Username must be more than 2 characters long.</p>
          </div><br>";
          echo "<a href='javascript:self.history.back()'><button class='btn'>Go Back</button>";
        }
        // Validate password complexity
        elseif (strlen($password) < 4 || !preg_match('/\d/', $password)) {
          echo "<div class='message'>
          <p>Password must be at least 4 characters long and include at least one number.</p>
          </div><br>";
          echo "<a href='javascript:self.history.back()'><button class='btn'>Go Back</button>";
        }
        // Validate age
        elseif ($age < 21) {
          echo "<div class='message'>
          <p>You must be at least 21 years old to register.</p>
          </div><br>";
          echo "<a href='javascript:self.history.back()'><button class='btn'>Go Back</button>";
        }
        // Check if email is already in use
        else {
          $email_query = mysqli_query($con, "SELECT Email FROM admins WHERE Email = '$email'");
          if (mysqli_num_rows($email_query) != 0) {
            echo "<div class='message'>
            <p>This email is already in use. Try another one!</p>
            </div><br>";
            echo "<a href='javascript:self.history.back()'><button class='btn'>Go Back</button>";
          } else {
            // Hash the password for security
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);

            // Insert data into the database
            mysqli_query($con, "INSERT INTO admins (Username, Email, Age, Password) VALUES ('$username', '$email', '$age', '$hashed_password')") or die("Error Occurred");
            echo "<div class='message'>
            <p>Registration successful!</p>
            </div><br>";
            echo "<a href='adminlogin.php'><button class='btn'>Login now</button>";
          }
        }
      } else {
      ?>
      <header>Admin Sign Up</header>
      <form action="" method="post">
        <div class="field input">
          <label for="username">Username</label>
          <input type="text" name="username" id="username" autocomplete="off" required>
        </div>
        <div class="field input">
          <label for="email">Email</label>
          <input type="text" name="email" id="email" autocomplete="off" required>
        </div>
        <div class="field input">
          <label for="age">Age</label>
          <input type="number" name="age" id="age" autocomplete="off" required>
        </div>
        <div class="field input">
          <label for="password">Password</label>
          <input type="password" name="password" id="password" autocomplete="off" required>
        </div>
        <div class="field">
          <input type="submit" class="btn" name="submit" value="Register" required>
        </div>
        <div class="links">
          Already a member? <a href="adminlogin.php">Sign in</a>
        </div>
      </form>
      <?php } ?>
    </div>
  </div>
</body>
</html>