//user_account.php
//Zach Boone
//user account holder page

//Database connection from another folder
<?php
include("php/database_conn.php");
if(!isset($_COOKIE["user"])){
    header("location:index.php");
}
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
    <?php
    $c = $_COOKIE["user"];
    $result = mysqli_query($conn, "Select * from UserID where activation_code = '$c'");
    if(mysqli_num_rows($result) > 0){
        while($row = mysqli_fetch_assoc($result)){
            $id = $row["user_id"];
            $email = $row["user_email"];
            $type = $row["user_type"];
        }
    }
    mysqli_close($conn);
    echo "<h1 class = 'larger_text'>Hello $id!</h1>";
    echo "<h2 class = 'larger_text'>Your Email is: $email</h2>";
    echo "<h2 class = 'larger_text'>You are a $type</h2>";
    ?>
    <button onclick="window.location.href = 'http://arden.cs.unca.edu/~zboone/delete_account.php';" class="accept login">Delete Account</button>
    <button onclick="window.location.href = 'http://arden.cs.unca.edu/~zboone/email_change.php';" class="accept login">Change Email</button>
    <button onclick="window.location.href = 'http://arden.cs.unca.edu/~zboone/password_recovery.php';" class="accept login">Change Password</button>
    </div>
</center>
</body>
</html>
