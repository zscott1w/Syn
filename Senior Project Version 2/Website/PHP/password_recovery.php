<?php
//password_recovery.php | Zachary Boone | 4/26/2020
//Request email on file to change their account password

//Database connection
include("php/database_conn.php");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$email_error = $password_error = "";
$email = $password = $password2 = "";

//Once form is submitted and email is verified, send them a email that redirects* them to another form
//*password_change.php
if($_SERVER["REQUEST_METHOD"] == "POST"){
    if(empty($_POST["email"])){
        $email_error = "Email is required<br>";
    }else{

        $email = mysqli_real_escape_string($conn,$_POST['email']);
        $result_email = mysqli_query($conn, "Select user_id, activation_code from UserID where user_email = '$email'");

        if(mysqli_num_rows($result_email) > 0){
            while($row = mysqli_fetch_assoc($result_email)){
                $code = $row["activation_code"];
            }

            $to = $email;
            $subject = 'Syn Account Change Password';
            $message = '

            You have requested to change your password, please click this link to change it:

            http://arden.cs.unca.edu/~zboone/change.php?&email='.$email.'&activation_code='.$code.'

            ';

            $from = 'From:noreply@cs.unca.edu' . "\r\n";
            mail($to, $subject, $message, $from);
            //Redirect to home
            header("location:login.php");
        }else{
            $email_error = "Email not found<br>";
        }
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
    <h3 class="larger_text"> Recover Account </h3><br>
    <form action = "<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method = "post">
    <label> Give email address: </label><input type = "text" name = "email"><br><br>
    <input type="submit" value="Enter email" class="accept login">
    </form><br><br>
    <p class="error">
    <?php
    echo $email_error;
    ?>
    </p>
    <p> An email will be sent to the address </p>
    </div>
</center>
</body>
</html>
