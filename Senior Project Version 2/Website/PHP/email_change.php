<?php
//email_change.php | Zachary Boone | 4/26/2020
//Changes the email for the current user and sends a confirmation email

//Redirect if user is not logged in
//else -> Include database and set cookie value to a variable
if(!isset($_COOKIE["user"])){
    header("location:index.php");
}else{
    include("php/database_conn.php");
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    $c = $_COOKIE["user"];
}

$error = $email = "";

//Once the form has been submitted then try to change the user's email
if($_SERVER["REQUEST_METHOD"] == "POST"){

    //If there isn't an email for some reason -> display error
    if(empty($_POST["new_email"])){
        $error = "Email is required<br>";
    }else{

        //Take new email and test to see if has been used already (Emails are unique in the database)
        $email = mysqli_real_escape_string($conn,$_POST['new_email']);
        $result_email = mysqli_query($conn, "Select * from UserID where user_email = '$email'");
        if(mysqli_num_rows($result_email) > 0){
            $error = "Email has already been used";
        }else{

            //If email hasn't been used ensure its a valid email
            //If valid -> Update email and send activation link to new address
            if(filter_var($email, FILTER_VALIDATE_EMAIL)){
                mysqli_query($conn, "UPDATE UserID SET user_email = '$email', active = '0' WHERE activation_code = '$c'");
                mysqli_commit($conn);
                $to = $email;
                $subject = 'Syn New Email Verification';
                $message = '

                You changed your Syn account Email, please click this link to verify it:

                http://arden.cs.unca.edu/~zboone/verify.php?&email='.$email.'&activation_code='.$c.'

                ';
                $from = 'From:noreply@cs.unca.edu' . "\r\n";
                mail($to, $subject, $message, $from);
                //Redirect to home
                header("location:index.php");
            }else{
                $error = "Email invalid<br>";
            }
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
    <button class="btn login" onclick="window.location.href = 'http://arden.cs.unca.edu/~zboone/graphs.py';">Graphs</button>
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
    <h2 class="larger_text">Change Email </h2>
    <form action = "<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method = "post">
    <label> New email address: </label><input type = "text" name = "new_email"><br><br>
    <input type="submit" value="Change Email" class="accept login">
    </form><br><br>
    <p> You will be sent an message to verify your email </p>
    <p class="error">
    <?php
    echo $error;
    ?>
    </p>
    </div>
</center>
</body>
</html>
