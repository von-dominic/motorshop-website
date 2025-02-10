<?php 
  session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login</title>
<!-- Importing Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<style>
/* General Body Styling */
body {
    background:rgb(8, 8, 8) ;
    background-size: cover;
    margin: 0;
    padding: 0;
    height: 100%;
    font-family: 'Poppins', sans-serif; /* Set font to Poppins */
    color: #333;
}

/* Slogan Styling */
.slogan {
    position: absolute;
    top: 9%; /* Adjust the slogan's position */
    left: 50%;
    transform: translateX(-50%);
    text-align: center;
    color: #fff;
    z-index: 1;
    text-shadow: 2px 2px 6px rgba(0, 0, 0, 0.8); /* Shadow for contrast */
}
.slogan h1 {
    font-size: 4rem;
    margin: 0;
    font-weight: bold;
}
.slogan p {
    font-size: 1.5rem;
    margin-top: 10px;
}

/* Login Box Styling */
.container {
    position: absolute;
    top: 60%; /* Adjust position of the login box */
    left: 50%;
    transform: translate(-50%, -50%);
    width: 90%;
    max-width: 400px;
    background-color: rgba(255, 255, 255, 0.9);
    padding: 25px;
    border-radius: 20px;
    box-shadow: 0 0 128px 0 rgba(0,0,0,0.1), 0 32px 64px -48px rgba(0,0,0,0.5); /* Box shadow from second style */
    z-index: 2;
}

header {
    font-size: 32px;
    margin-bottom: 20px;
    text-align: center;
    font-weight: 600; /* Bold text */
    padding-bottom: 10px;
    border-bottom: 1px solid #e6e6e6; /* Border below header */
    margin-bottom: 10px;
}

/* Input Fields Styling */
input[type="text"], input[type="password"] {
    width: 100%;
    padding: 10px;
    margin: 10px 0;
    border-radius: 5px;
    border: 1px solid #ccc;
    font-size: 16px;
}

input[type="submit"] {
    width: 100%;
    padding: 10px;
    background-color: #007bff;
    color: white;
    border: none;
    border-radius: 5px;
    font-size: 16px;
    cursor: pointer;
    margin-top: 10px;
}
input[type="submit"]:hover {
    background-color: #0056b3;
}

/* Links Section Styling */
.links {
    text-align: center;
    margin-top: 10px;
}

.links a {
    color: #007bff;
    text-decoration: none;
    font-size: 14px;
}

.links a:hover {
    text-decoration: underline;
}

/* Mobile responsiveness */
@media (max-width: 768px) {
    .slogan h1 {
        font-size: 3rem;
    }
    .slogan p {
        font-size: 1rem;
    }
    .container {
        padding: 20px;
    }
}
</style>
</head>
<body>
    

<!-- Background Video -->
<video id="videoPlayer" autoplay muted loop playsinline>
    <source src="images/indexvid/46875-449573531.mp4" type="video/mp4">
    Your browser does not support the video tag.
</video>

<script>
// Array of video files
const videos = [
    'images/indexvid/46875-449573531.mp4',
    'images/indexvid/131088-749689497.mp4',
    'images/indexvid/blackgunspinning.mp4'
];
let currentIndex = 0;
const videoPlayer = document.getElementById('videoPlayer');

// Function to switch videos
function playNextVideo() {
    currentIndex = (currentIndex + 1) % videos.length;
    videoPlayer.src = videos[currentIndex];
    videoPlayer.load();
    videoPlayer.play();
}
videoPlayer.addEventListener('ended', playNextVideo);
</script>

<!-- Slogan Section -->
<div class="slogan">
    <h1>Lock & Load</h1>
    <p>Precision, Power, Protection</p>
</div>

<!-- Login Form Section -->
<div class="container">
    <div class="box form-box">
        <?php
        include("php/config.php");
        if (isset($_POST['submit'])) {
            $email = mysqli_real_escape_string($con, $_POST['email']);
            $password = mysqli_real_escape_string($con, $_POST['password']);

            $result = mysqli_query($con, "SELECT * FROM users WHERE Email = '$email'") or die("Select Error");
            $row = mysqli_fetch_assoc($result);

            if (is_array($row) && !empty($row)) {
                if (password_verify($password, $row['Password'])) {
                    $_SESSION['valid_customer'] = $row['Email'];
                    $_SESSION['username'] = $row['Username'];
                    $_SESSION['age'] = $row['Age'];
                    $_SESSION['id'] = $row['Id'];
                    header("Location: home.php");
                } else {
                    echo "<div class='message'><p>Incorrect Username or Password</p><a href='forgotpassword.php'> Forgot password?</a></div><br>";
                    echo "<a href='index.php'><button class='btn'>Go Back</button>";
                }
            } else {
                echo "<div class='message'><p>Incorrect Username or Password</p><a href='forgotpassword.php'> Forgot password?</a></div><br>";
                echo "<a href='index.php'><button class='btn'>Go Back</button>";
            }
        } else {
        ?>
        <header>Login</header>
        <form action="" method="post">
            <label for="email">Email</label>
            <input type="text" name="email" id="email" required>
            <label for="password">Password</label>
            <input type="password" name="password" id="password" required>
            <input type="submit" name="submit" value="Login">
            <div class="links">
                Don't have an account? <a href="register.php">Sign up</a><br>
                <a href="adminlogin.php">Login as Admin</a>
            </div>
        </form>
        <?php } ?>
    </div>
</div>
<style>
.card-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 20px;
    padding: 20px;
    margin: 50px auto;
    max-width: 1200px;
}

/* Individual card styling */
.card {
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    text-align: center;
}

.card:hover {
    transform: translateY(-10px);
    box-shadow: 0 8px 12px rgba(0, 0, 0, 0.2);
}

.card img {
    width: 100%;
    height: auto;
    display: block;
}

.card-content {
    padding: 20px;
}

.card-title {
    font-size: 24px;
    font-weight: bold;
    margin-bottom: 10px;
}

.card-description {
    font-size: 16px;
    color: #666;
    margin-bottom: 20px;
}

.card-button {
    display: inline-block;
    padding: 10px 20px;
    background-color: #007bff;
    color: #fff;
    text-decoration: none;
    border-radius: 4px;
    transition: background-color 0.3s ease;
}

.card-button:hover {
    background-color: #0056b3;
}

</style>
<!-- Card Section -->
<div class="card-container">
    <!-- Card 1 -->
    <div class="card">
        <img src="https://via.placeholder.com/300x200" alt="Character Design">
        <div class="card-content">
            <h3 class="card-title">Brand Designs</h3>
            <p class="card-description">Customer's builds.</p>
            <a href="#" class="card-button">Watch Now</a>
        </div>
    </div>

    <!-- Card 2 -->
    <div class="card">
        <img src="https://via.placeholder.com/300x200" alt="Fashion and Beauty">
        <div class="card-content">
            <h3 class="card-title">Tutorials</h3>
            <p class="card-description">Ensures that the item used correctly.</p>
            <a href="#" class="card-button">Watch Now</a>
        </div>
    </div>

    <!-- Card 3 -->
    <div class="card">
        <img src="https://via.placeholder.com/300x200" alt="Game Assets">
        <div class="card-content">
            <h3 class="card-title">Quality of Accesories</h3>
            <p class="card-description">Factories of companys accesories .</p>
            <a href="#" class="card-button">Watch Now</a>
        </div>
    </div>

    </div>
</div>
<style>
    footer {
    background-color: #f8f9fa;
    padding: 40px 20px;
    color: #333;
    font-family: 'Poppins', sans-serif;
}

.footer-container {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    max-width: 1200px;
    margin: 0 auto;
}

.footer-column {
    flex: 1;
    min-width: 200px;
    margin: 10px 20px;
}

.footer-column h4 {
    font-size: 18px;
    margin-bottom: 10px;
    font-weight: bold;
}

.footer-column ul {
    list-style: none;
    padding: 0;
}

.footer-column ul li {
    margin: 5px 0;
}

.footer-column ul li a {
    color: #007bff;
    text-decoration: none;
    transition: color 0.3s;
}

.footer-column ul li a:hover {
    color: #0056b3;
}

.footer-bottom {
    text-align: center;
    margin-top: 20px;
    font-size: 14px;
    color: #666;
}

.footer-bottom a {
    color: #007bff;
    text-decoration: none;
    transition: color 0.3s;
}

.footer-bottom a:hover {
    color: #0056b3;
}

    </style>
<footer>
    <div class="footer-container">
        <div class="footer-column">
            <h4>Discover</h4>
            <ul>
                <li><a href="#">Editor's Choice</a></li>
                <li><a href="#">Trophy Collections</a></li>
                <li><a href="#">Popular Products</a></li>
                <li><a href="#">Popular Accessories</a></li>
                <li><a href="#">Popular Motorshop</a></li>
                <li><a href="#">Popular Searches</a></li>
            </ul>
        </div>
        <div class="footer-column">
            <h4>Community</h4>
            <ul>
                <li><a href="#">Blog</a></li>
                <li><a href="#">Forum</a></li>
                <li><a href="#">Creators</a></li>
                <li><a href="#">Cameras</a></li>
            </ul>
        </div>
        <div class="footer-column">
            <h4>About</h4>
            <ul>
                <li><a href="#">About Us</a></li>
                <li><a href="#">FAQ</a></li>
                <li><a href="#">Terms of Service</a></li>
                <li><a href="#">Privacy Policy</a></li>
                <li><a href="#">Report Content</a></li>
                <li><a href="#">API</a></li>
            </ul>
        </div>
    </div>
    <div class="footer-bottom">
        <p>© 2025 YourWebsite. All rights reserved. This site is protected by reCAPTCHA and the Google <a href="#">Privacy Policy</a> and <a href="#">Terms of Service</a>.</p>
    </div>
</footer>

    

</body>
</html>
