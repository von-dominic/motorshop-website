<?php
  session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="style/style.css">
<title>Success</title>
</head>
<style>
/* General Styles */
body {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh; /* Full viewport height */
    margin: 0;
    background: #f0f0f0 url('https://w.wallhaven.cc/full/48/wallhaven-483k3k.jpg') no-repeat center center fixed;
    background-size: cover;
    font-family: Arial, sans-serif;
}

/* Success Box */
.adminbox {
    background: rgba(255, 255, 255, 0.9); /* Semi-transparent white */
    border: 2px solid #000; /* Black border */
    border-radius: 10px; /* Rounded corners */
    padding: 25px;
    box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2); /* Deeper shadow for focus */
    text-align: center;
    max-width: 400px; /* Restrict the width */
    width: 90%; /* Adjust for smaller screens */
    transition: transform 0.3s ease-in-out; /* Smooth hover effect */
}

/* Header */
.adminbox header {
    font-size: 24px;
    font-weight: bold;
    color: #b71c1c; /* Tactical red */
    margin-bottom: 15px;
}

/* Success Message */
.adminbox p {
    font-size: 18px;
    color: #333; /* Dark gray for readability */
    margin-bottom: 20px;
}

/* Buttons */
.btn {
    height: 40px;
    background: rgba(190, 12, 12, 0.9); /* Tactical red with bold opacity */
    border: none;
    border-radius: 5px;
    color: #fff;
    font-size: 15px;
    cursor: pointer;
    transition: all 0.3s ease;
    margin: 10px 0; /* Space between buttons */
    padding: 0 15px;
    font-weight: bold;
    text-transform: uppercase;
    width: 100%; /* Full width buttons for better mobile UX */
}

.btn:hover {
    transform: scale(1.05); /* Slightly larger */
    opacity: 0.9; /* Slightly dimmed */
}

.btn:active {
    background: rgba(150, 0, 0, 0.9); /* Darker red on click */
    transform: scale(0.95); /* Slight shrink */
}
</style>

<body>

<div class="adminbox">   
    <header>Thank You for Your Purchase!</header>
    <p>You have successfully ordered your weapon. Prepare for battle!</p>
    <a href="home.php"><button class="btn">Go Home</button></a>
    <a href="index.php"><button class="btn">Log Out</button></a>
</div>

</body>
</html>
