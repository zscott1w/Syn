//calendar.php
//Zach Boone
//Syn's show calendar using Tockify's embedded calendar for websites

//Database connection from another folder
<?php
include("php/database_conn.php");
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset = "UTF-8">
    <title>Syn Shows Calendar</title>
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
    mysqli_close($conn);
    ?>
</header>
<div data-tockify-component="calendar" data-tockify-calendar="synshowcalendar"></div>
<script data-cfasync="false" data-tockify-script="embed"
src="https://public.tockify.com/browser/embed.js"></script>
</body>
</html>
