//verify.php
//Zach Boone
//Page for verifying email address

//Database connection from another folder
<?php
include("php/database_conn.php");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset = "UTF-8">
    <title>Verify Email</title>
    <link rel="stylesheet" type="text/css" href="css/index.css">
    <link rel="icon" href="pictures/Syn_Icon_White.png">
</head>
<header>
    <div class="textbox">
        <a href="http://arden.cs.unca.edu/~zboone/">
        <img class = "align_left border" src="pictures/Syn_Logo_Black.png" alt="Logo" width="140" height="140">
        </a>
        <h1 class="fancy_text align_right">Music Marketing</h1>
        <div style="clear:both;"></div>
    </div>
    <button class="btn login" onclick="window.location.href = 'http://arden.cs.unca.edu/~zboone/calendar.php';">Calendar</button>
    <button class="btn login" onclick="window.location.href = 'http://arden.cs.unca.edu/~zboone/graphs.php';">Graphs</button>
    <button class = "btn signup" onclick="window.location.href = 'http://arden.cs.unca.edu/~zboone/about.php';">About</button>
    <?php
    //If cookies are inactive, show login and signup buttons
    //Else show user account and logout
    if(!isset($_COOKIE["type"])){
        echo "<button class=\"btn login align_right\" onclick=\"window.location.href = 'http://arden.cs.unca.edu/~zboone/login.php';\">Log In</button>";
        echo "<p class=\"align_right\">&nbsp</p>";
        echo "<button class=\"btn signup align_right\" onclick=\"window.location.href = 'http://arden.cs.unca.edu/~zboone/signup.php';\">Sign Up</button>";
    }else{
        echo "<button class=\"btn login\" onclick=\"window.location.href = 'http://arden.cs.unca.edu/~zboone/user_account.php';\">Account</button>";
        echo "<a href=\"logout.php\" class=\"btn signup align_right\" role=\"button\">Log Out</a>";
    }
    ?>
</header>
<?php
//if both fields are passed through the URL then set email and activation code
if(isset($_GET['email']) && !empty($_GET['email']) AND isset($_GET['activation_code']) && !empty($_GET['activation_code'])){
    $email = mysqli_real_escape_string($conn,$_GET['email']);
    $code = mysqli_real_escape_string($conn,$_GET['activation_code']);
    //test datebase for user using email
    $query = "SELECT * FROM UserID WHERE user_email='$email' AND active='0'";
    $result = mysqli_query($conn, $query);
    //if user exists and is not activated, activate Account
    //else account has already been activated
    if(mysqli_num_rows($result) > 0){
        mysqli_query($conn, "UPDATE UserID SET active='1' WHERE user_email='$email' AND activation_code='$code'");
        echo '<h2 class="align_center dark">Your account has been activated</h2>';
    }else{
        echo '<h2 class="align_center dark">Account already activated</h2>';
    }
}else{
    echo "Something went wrong and account not activated";
}
mysqli_close($conn);
?>
</body>
</html>
