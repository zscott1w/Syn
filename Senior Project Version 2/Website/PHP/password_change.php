<?php
//password_change.php | Zachary Boone | 4/26/2020
//Utility to allow users to change their password if forgotten or desired

//Connect to database
include("php/database_conn.php");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$password_error = "";
$password = $password2 = "";

//Test values against email link. If they match then allow user to change password
if(isset($_GET['email']) && !empty($_GET['email']) AND isset($_GET['activation_code']) && !empty($_GET['activation_code'])){

    $email = mysqli_real_escape_string($conn,$_GET['email']);
    $code = mysqli_real_escape_string($conn,$_GET['activation_code']);
    $e = $_GET['email'];
    $c = $_GET['activation_code'];
    $action = "change.php?&email=$e&activation_code=$c";
    $query = "SELECT user_id FROM UserID WHERE user_email='$email' AND activation_code='$code'";
    $result = mysqli_query($conn, $query);
    if(mysqli_num_rows($result) > 0){
        if($_SERVER["REQUEST_METHOD"] == "POST"){

            //test if passwords match and change user password
            if(empty($_POST["password"]) or empty($_POST["password2"])){
                    $password_error = "Passwords are required";
            }else{
                $password = mysqli_real_escape_string($conn,$_POST["password"]);
                $password2 = mysqli_real_escape_string($conn,$_POST["password2"]);
                if($password != $password2){
                    $password_error = "Passwords do not match";
                }else{
                    $hash_password = sha1($str.$password);
                    mysqli_query($conn, "UPDATE UserID SET user_password = '$hash_password' WHERE user_email='$email' AND activation_code='$code'");
                    mysqli_commit($conn);
                    setcookie("user", "", time()-3600);
                    $pass = '<h2 class="align_center">Password successfully changed</h2>';
                }
            }
        }
    }else{
        $pass = '<h2 class="align_center">Password not changed</h2>';
    }
}else{
    $pass = '<h2 class="align_center">Something went wrong. Please contact us.</h2>';
}
mysqli_close($conn);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset = "UTF-8">
    <title>Syn User Account</title>
    <link rel="stylesheet" type="text/css" href="css/account.css">
    <link rel="icon" href="pictures/Syn_Icon_White.png">
</head>
<body>
<header>
    <div class="textbox">
        <a href="http://arden.cs.unca.edu/~zboone/">
        <img class = "align_left border" src="pictures/Syn_Logo_Black.png" alt="Logo" width="140" height="140">
        </a>
        <h1 class="fancy_text align_right">Music Marketing</h1>
        <div style="clear:both;"></div>
    </div>
    <button class="btn login" onclick="window.location.href = 'http://arden.cs.unca.edu/~zboone/';">Home</button>
    <button class="btn login" onclick="window.location.href = 'http://arden.cs.unca.edu/~zboone/calendar.php';">Calendar</button>
    <button class="btn login" onclick="window.location.href = 'http://arden.cs.unca.edu/~zboone/graphs.php';">Graphs</button>
    <button class = "btn signup" onclick="window.location.href = 'http://arden.cs.unca.edu/~zboone/about.php';">About</button>
    <?php
    if(!isset($_COOKIE["user"])){
        echo "<button class=\"btn login align_right\" onclick=\"window.location.href = 'http://arden.cs.unca.edu/~zboone/login.php';\">Log In</button>";
        echo "<p class=\"align_right\">&nbsp</p>";
        echo "<button class=\"btn signup align_right\" onclick=\"window.location.href = 'http://arden.cs.unca.edu/~zboone/signup.php';\">Sign Up</button>";
    }else{
        echo "<button class=\"btn login\" onclick=\"window.location.href = 'http://arden.cs.unca.edu/~zboone/user_account.php';\">Account</button>";
        echo "<button class=\"btn login align_right\" onclick=\"window.location.href = 'http://arden.cs.unca.edu/~zboone/logout.php';\">Sign Out</button>";
    }
    ?>
</header>
<center><br><br>
    <div class="box">
    <h3 class="larger_text"> Change Password </h3><br>
    <form action = "<?php echo $action;?>" method = "post">
    <label> Enter new password: </label><input type = "password" name = "password"><br><br>
    <label> Re-Enter new password: </label><input type = "password" name = "password2"><br><br><br>
    <input type="submit" value="Change Password" class="accept login">
    </form><br><br>
    <p class="error">
    <?php
    echo $password_error;
    echo $pass;
    ?>
    </p>
    <p> If logged in, you will be logged out </p>
    </div>
</center>
</body>
</html>
