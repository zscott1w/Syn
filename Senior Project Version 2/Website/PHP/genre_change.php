<?php
//genre_change.php | Zachary Boone | 4/26/2020
//A form that allows bands to change their current genre (incase we got it wrong)

//Redirect to home if user not logged in
//else -> fetch the affiliated band from the info stored in the cookie and change genre according to form information
include("php/database_conn.php");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if(!isset($_COOKIE["user"])){
    mysqli_close($conn);
    header("location:index.php");
}else{
    $temp = $_COOKIE["user"];
    $temp_query = mysqli_query($conn, "SELECT user_type, affiliation from UserID where activation_code = '$temp'");
    $row = mysqli_fetch_assoc($temp_query);
    $type = $row["user_type"];
    $affil = $row["affiliation"];
    if($affil == NULL){
        $success = "Cannot use this because your account isn't affiliated with a band in our database<br>To affiliated contact synmusicmarketing@gmail.com";
    }
    if($type == 'Band'){
        $band_query = "SELECT band_id from Band where band_name = '$affil'";
        $bq = mysqli_query($conn, $band_query);
        $r = mysqli_fetch_assoc($bq);
        $band = $r["band_id"];
        if($_SERVER["REQUEST_METHOD"] == "POST"){
           if(empty($_POST["genre"])){
                $error = "Error with form";
            }else{
                $genre = $_POST["genre"];
                $get_gid = "SELECT genre_id from Genre where genre_name = '$genre'";
                $gid_query = mysqli_query($conn, $get_gid);
                $g = mysqli_fetch_assoc($gid_query);
                $gid = $g["genre_id"];
                $change_query = "UPDATE BandGenres SET genre_id = $gid WHERE band_id = $band";
                if(mysqli_query($conn, $change_query)){
                    mysqli_commit($conn);
                    $success = "Genre successfully changed!";
                }else{
                    $success = "Something went wrong";
                }
            }
        }
    }else{
        header("location:index.php");
    }
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
    <button class="btn login" onclick="window.location.href = 'http://arden.cs.unca.edu/~zboone/graphs.py';">Graphs</button>
    <button class = "btn signup" onclick="window.location.href = 'http://arden.cs.unca.edu/~zboone/about.php';">About</button>
    <button class="btn login" onclick="window.location.href = 'http://arden.cs.unca.edu/~zboone/user_account.php';">Account</button>
    <button class="btn login align_right" onclick="window.location.href = 'http://arden.cs.unca.edu/~zboone/logout.php';">Sign Out</button>
</header>
<center><br><br>
    <div class="box">
    <form action = "genre_change.php" method = "post"><br>
    <label> Choose new Genre: </label>
    <select id="genre" name="genre">
        <option value="Americana">Americana</option>
        <option value="Bluegrass">Bluegrass</option>
        <option value="Blues">Blues</option>
        <option value="Country">Country</option>
        <option value="Electronic">Electronic</option>
        <option value="Funk">Funk</option>
        <option value="Rap">Hip-Hop/Rap</option>
        <option value="Jazz">Jazz</option>
        <option value="Metal">Metal</option>
        <option value="Pop">Pop</option>
        <option value="Punk">Punk</option>
        <option value="Reggae">Reggae/Ska</option>
        <option value="Rock">Rock</option>
        <option value="Singer">Singer/Songwriter</option>
        <option value="Soul">Soul/R&amp;B</option>
    </select><br><br>
    <input type="submit" value="Change Genre" class="accept login">
    </form><br><br>
    <p>Choose the genre that most closely represents your band</p>
    <p>
    <?php
    echo $success;
    ?>
    </p>
    </div>
</center>
</body>
</html>
