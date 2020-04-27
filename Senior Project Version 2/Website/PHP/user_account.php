<?php
//user_account.php | Zachary Boone | 4/26/2020
//The user account page that allows the user to manage their Syn account

//No user account if not logged in
if(!isset($_COOKIE["user"])){
    header("location:index.php");
}

//Connect database
include("php/database_conn.php");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
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
    //Redirect if user not currently logged in
    if(!isset($_COOKIE["user"])){
        header("location:index.php");
    }else{
        echo "<button class=\"btn login\" onclick=\"window.location.href = 'http://arden.cs.unca.edu/~zboone/user_account.php';\">Account</button>";
        echo "<button class=\"btn login align_right\" onclick=\"window.location.href = 'http://arden.cs.unca.edu/~zboone/logout.php';\">Sign Out</button>";
    }
    ?>
</header>
<center><br><br>
    <div class="box">
    <?php
    //Get information about the user
    $c = $_COOKIE["user"];
    $result = mysqli_query($conn, "SELECT user_id, user_email, user_type, affiliation from UserID where activation_code = '$c'");
    if(mysqli_num_rows($result) > 0){
        while($row = mysqli_fetch_assoc($result)){
            $id = $row["user_id"];
            $email = $row["user_email"];
            $type = $row["user_type"];
            $affil = $row["affiliation"];
        }
    }
    //Display information to user
    //If band find genre and display it
    if($affil == NULL){
        echo "<h1 class = 'larger_text'>Hello $id!</h1>";
    }else{
        echo "<h1 class = 'larger_text'>Hello $affil!</h1>";
    }
    echo "<h2 class = 'larger_text'>Your Email is: $email</h2>";
    echo "<h2 class = 'larger_text'>You are a: $type</h2>";
    if($type == "Band"){
        $genre_query = "SELECT genre_name from Genre where genre_id = (SELECT genre_id from BandGenres where band_id = (SELECT band_id from Band where band_name = '$affil'))";
        $result2 = mysqli_query($conn, $genre_query);
        $r = mysqli_fetch_assoc($result2);
        $genre = $r['genre_name'];
        echo "<h2 class = 'larger_text'>Your Genre: $genre</h2>";
    }
    mysqli_close($conn);
    ?>
    <button onclick="window.location.href = 'http://arden.cs.unca.edu/~zboone/delete_account.php';" class="accept login">Delete Account</button>
    <button onclick="window.location.href = 'http://arden.cs.unca.edu/~zboone/email_change.php';" class="accept login">Change Email</button>
    <button onclick="window.location.href = 'http://arden.cs.unca.edu/~zboone/password_recovery.php';" class="accept login">Change Password</button>
    <?php
    //Extra functionality (changing genre) for bands only
    if($type == "Band"){
        echo "<button onclick=\"window.location.href = 'http://arden.cs.unca.edu/~zboone/genre_change.php';\" class=\"accept login\">Change Genre</button>";
    }
    ?>
    </div>
</center>
</body>
</html>
