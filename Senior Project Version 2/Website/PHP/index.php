//index.php
//Zach Boone
//Syn Homepage

//Database connection from another folder
<?php
include("php/database_conn.php");
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset = "UTF-8">
    <title>Syn : Music Marketing</title>
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
    <button class="btn login" onclick="window.location.href = 'http://arden.cs.unca.edu/~zboone/';">Home</button>
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
    mysqli_close($conn);
    ?>
</header>
<?php
$c = $_COOKIE["type"];
if(isset($_COOKIE["type"])){
    echo "<h2 class=\"align_center dark\">Welcome $c!!!</h2>";
}
?>
<a href="http://arden.cs.unca.edu/~zboone/">
<div class="timeline">
<h1 class="larger_text">Welcome to Syn!</h1><p>1:13 AM 4/12/2020</p><br>
<img src="pictures/Syn_Icon_White.png" height="156" width="156" align="left" hspace="30">
<p>Hello, welcome to our website! Syn (pronunced like Scene) is a music marketing project based in Asheville NC. Syn is a Research Project created at UNC Asheville by Quinn McKerney and Zach Boone as a part of the Ideas to Action program. We are dedicated to helping the Asheville area music scene connect with the local citizens. Our goal is to make music shows more profitable and successful for all parties involved. Our website consists of a comprehensive show calendar and graphical analysis of previous shows in based the Asheville area venues. As we recieve feedback the trends will continue to grow and change as the music scene changes. Get started by signing up for an account and jump into Syn.</p>
</div>
</a>
</body>
</html>
