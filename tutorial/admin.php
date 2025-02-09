<?php
session_start();

include("php/config.php");

// Check if the session variable for admin login is set
if (!isset($_SESSION['admin_valid'])) {
    header("Location: adminlogin.php");  // Redirect to admin login page if not logged in as admin
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="">
<title>Admin Dashboard</title>
</head>

<style>
/* General Reset and Styling */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Trebuchet MS', sans-serif; /* Match font */
    background: #222 url('https://w.wallhaven.cc/full/48/wallhaven-483k3k.jpg') no-repeat center center fixed;
    background-size: cover;
    color: #e6e6e6;
}

/* Navigation Bar Styling */
.nav {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 20px;
    background: rgba(0, 0, 0, 0.9);
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.5);
}

.nav .logo p {
    font-size: 1.8em;
    font-weight: bold;
    color: #fff;
    text-transform: uppercase;
}

.nav .right-links {
    display: flex;
    gap: 15px;
}

.nav .right-links button {
    background-color: #f7c22e; /* Gold */
    color: #222; /* Dark text for contrast */
    border: none;
    padding: 8px 15px;
    border-radius: 5px;
    cursor: pointer;
    font-weight: bold;
    transition: background-color 0.3s ease, transform 0.2s ease;
    font-family: 'Trebuchet MS', sans-serif;
}

.nav .right-links button:hover {
    background-color: #f4b618; /* Slightly darker gold on hover */
    transform: translateY(-3px);
}

.nav .right-links button:active {
    background-color: #c99710; /* Deeper gold when active */
    transform: translateY(2px);
}

.nav .right-links a {
    color: #f7c22e;
    text-decoration: none;
    font-size: 1.1em;
    font-weight: bold;
    transition: color 0.3s ease;
}

.nav .right-links a:hover {
    color: #f4b618;
}

/* Main Content Styling */
main {
    margin-top: 80px;
    padding: 30px;
    background: rgba(34, 34, 34, 0.85);
    width: 80%;
    margin: 100px auto;
    border-radius: 10px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.6);
}

/* Weapon Shop Section */
.main-box .top .box {
    background: rgba(0, 0, 0, 0.9);
    border-radius: 8px;
    padding: 20px;
    text-align: center;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.5);
    color: #e6e6e6;
    text-transform: uppercase;
    font-size: 1.4em;
    font-weight: bold;
    font-family: 'Trebuchet MS', sans-serif;
}

.main-box .top .box p {
    font-size: 1.8em;
    color: #fff;
    margin-bottom: 10px;
}

.main-box .top .box button {
    background-color: #f7c22e; /* Gold button */
    color: #222; /* Dark text for contrast */
    border: none;
    padding: 8px 15px;
    margin: 10px 5px;
    font-weight: bold;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease, transform 0.2s ease;
}

.main-box .top .box button:hover {
    background-color: #f4b618; /* Slightly darker gold on hover */
    transform: translateY(-3px);
}

.main-box .top .box button:active {
    background-color: #c99710; /* Deeper gold when active */
    transform: translateY(2px);
}

/* Responsive Adjustments */
@media (max-width: 768px) {
    .nav {
        flex-direction: column;
    }

    .nav .right-links {
        flex-direction: column;
    }
}
</style>



<body>
    <div class="nav">
        <div class="logo">
            <p>Weapon Shop</p>
        </div>

        <div class="right-links">
            <?php
            $id = $_SESSION['admin_id']; // Using admin_id instead of user ID for admin
            $query = mysqli_query($con, "SELECT * FROM admins WHERE Id = $id");

            while ($result = mysqli_fetch_assoc($query)) {
                $res_Uname = $result['Username'];
                $res_Email = $result['Email'];
                $res_Age = $result['Age'];
                $res_id = $result['Id'];
            }
            
            ?>
            <a href="php/logout.php"><button class="btn">Log out</button></a>
            <a href="adnotifications.php"><button class="btn">Notification</button></a>
            <a href="adminsettings.php"><button class="btn">Settings</button></a>

        </div>
    </div>

    <main>
        <div class="main-box">
            <!-- Admin Information Section -->
            <div class="top">
                <div class="box">
                    <p>Hello Admin! <b><?php echo $res_Uname; ?></b></p>
                </div>
                <div class="bottom">
                    <div class="box">
                        <h2>Administration Management System</h2>

                        <a href="item.php"><button class="btn">Add Item</button></a>
                        <a href="updateitem.php"><button class="btn">Update Item</button></a>
                        <a href="deleteitem.php"><button class="btn">Delete Item</button></a>
                        <a href="sales.php"><button class="btn">Sales</button></a>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
