<?php
 session_start();

 include("php/config.php");
 if (!isset($_SESSION['valid_customer'])) {
    header("Location: index.php");
    exit();
}


?>


<!DOCTYPE html>
<html lang = "en">
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="">
<title>Change Profile</title>

</head>
<style>
/* General Reset */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Trebuchet MS', sans-serif; /* Match order.php font */
}

/* Body Styling */
body {
    background: url('https://w.wallhaven.cc/full/48/wallhaven-483k3k.jpg') no-repeat center center fixed;
    background-size: cover;
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    color: #e6e6e6; /* Match text color */
}

/* Navigation Bar */
.nav {
    position: absolute;
    top: 20px;
    right: 20px;
}

.nav .btn {
    background-color: #b71c1c; /* Tactical red */
    color: #fff;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-weight: bold;
    transition: background-color 0.3s ease, transform 0.2s ease;
}

.nav .btn:hover {
    background-color: #f44336; /* Lighter hover red */
    transform: translateY(-3px);
}

/* Main Container */
.container {
    background: rgba(0, 0, 0, 0.85); /* Slightly darker transparency */
    padding: 30px 40px;
    border-radius: 10px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.5);
    width: 400px;
    text-align: center;
}

/* Header Styling */
.container header {
    font-size: 1.8em;
    margin-bottom: 20px;
    color: #b71c1c; /* Tactical red for headings */
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
}

/* Form Fields */
.field {
    margin-bottom: 20px;
    text-align: left;
}

.field label {
    display: block;
    margin-bottom: 8px;
    font-weight: bold;
    color: #e6e6e6; /* Lighter grey text */
}

.field input {
    width: 100%;
    padding: 10px;
    border: 1px solid #333;
    border-radius: 5px;
    font-size: 1em;
    background: rgba(255, 255, 255, 0.1);
    color: #e6e6e6;
    outline: none;
    transition: border-color 0.3s ease;
}

.field input:focus {
    border: 2px solid #f44336; /* Focus border red */
}

/* Submit Button */
.field .btn {
    background-color: #b71c1c; /* Tactical red */
    color: #fff;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-weight: bold;
    width: 100%;
    transition: background-color 0.3s ease, transform 0.2s ease;
}

.field .btn:hover {
    background-color: #f44336; /* Lighter red */
    transform: translateY(-3px);
}

/* Success Message */
.message {
    background-color: #b71c1c;
    color: #fff;
    padding: 10px;
    margin-bottom: 15px;
    border-radius: 5px;
    font-weight: bold;
    text-align: center;
}


/* Responsive Design */
@media (max-width: 768px) {
    .container {
        width: 90%;
    }
}
</style>

<body>
    <div class ="nav">
              <a href="home.php"><button class="btn">go back</a>
        </div>
    </div>
    <div class= "container">
        <div class="box form-box">
        <?php 
        if (isset($_POST['submit'])){
            $username = $_POST['username'];
            $email = $_POST['email'];
            $age = $_POST['age'];

            $id = $_SESSION['id'];
            $edit_query = mysqli_query($con,"UPDATE users SET Username='$username', Email='$email', Age='$age' WHERE Id = $id ") or die ("error occurred");

            if($edit_query){
                echo "<div class = 'message'>
                <p>Profile Updated!</p>
                </div> <br>";
                echo  "<a href='home.php'><button class = 'btn'>Go Home</button>";
        
            }
        }else{

            $id = $_SESSION['id'];
            $query = mysqli_query($con, "SELECT*FROM users WHERE Id=$id ");

            while ($result = mysqli_fetch_assoc($query)){
                $res_Uname = $result['Username'];
                $res_Email = $result['Email'];
                $res_Age = $result['Age'];

            }
        


        ?>
        <header>Change Profile</header>
        <form action=""method="post">
            <div class="field input">
                <label for ="username">Username</label>
                <input type ="text"name="username" id= "username" value="<?php echo $res_Uname; ?>" autocomplete="off" required>
            </div>
            <div class="field input">
                <label for ="email">Email</label>
                <input type ="text"name="email" id= "email" value="<?php echo $res_Email; ?>" autocomplete="off" required>
            </div>
            <div class="field input">
                <label for ="age">Age</label>
                <input type ="number"name="age" id= "age" value="<?php echo $res_Age; ?>" autocomplete="off" required>
            </div>
          
            <div class="field">
               
                <input type ="submit" class= "btn" name="submit" value= "Update" required>
            </div>
           
        </form>
        </div>
        <?php }?>
        </div>
</body>
</html>