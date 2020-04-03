//about.php
//Zach Boone
//About our website and what the goals of Syn are

//Database connection from another folder
<?php
include("php/database_conn.php");
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset = "UTF-8">
    <title>About Syn</title>
    <link rel="stylesheet" type="text/css" href="css/about.css">
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
<center>
<br><br>
<div class="box">
<h2 class="larger_text align_center">About</h2>
<p class="medium_text align_center">Syn is committed to giving venues and talent bookers the tools they need to throw successful and profitable shows. By providing in depth analytics, venues and bookers can make more educated decisions about the shows they put on. In addition to analytics that will impact venue and booker planning, Syn is also home to a live music calendar, giving community members a one stop place to find all of the city’s live music events. Syn’s platform and database is continuing to grow, increasing its relevance and accuracy for the Asheville music scene.</p>
<br><br><br>
</div>
</center>
</body>
</html>
