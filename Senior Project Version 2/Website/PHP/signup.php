//signup.php
//Zach Boone
//Syn signup Page

//Database connection from another folder
<?php
include("php/database_conn.php");
//if already logged in rederect to homepage
if(isset($_COOKIE["type"])){
    header("location:index.php");
}
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
//variables used for signup
$username_error = $password_error = $email_error = "<br>";
$username = $password = $email = $password2 = "";
$u = $p = $e = False;
//if script already has been entered
if($_SERVER["REQUEST_METHOD"] == "POST"){
    //if all required fields are empty then error
    if(empty($_POST["username"]) and empty($_POST["password"]) and empty($_POST["email"])){
        $username_error = "All fields are required<br>";
    }else{
        //if username empty then error
        if(empty($_POST["username"])){
            $username_error = "Username field is empty<br>";
        }else{
            //username not empty, test to see if username has been used
            $username = mysqli_real_escape_string($conn,$_POST['username']);
            $result = mysqli_query($conn, "Select * from UserID where user_id = '$username'");
            if(mysqli_num_rows($result) > 0){
                $username_error = "Username has alread been used<br>";
            }else{
                //username passes all tests !!!
                $u = True;
            }
        }

        //if the password fields are empty then error
        if(empty($_POST["password"]) or empty($_POST["password2"])){
            $password_error = "Password field is empty<br>";
        }else{
            //passwords not empty, test to see if equal
            $password = mysqli_real_escape_string($conn,$_POST['password']);
            $password2 = mysqli_real_escape_string($conn,$_POST['password2']);
            if($password != $password2){
                $password_error = "Passwords do not match<br>";
            }else{
                //if equal then hash the password and it passes
                $hash_password = sha1($str.$password);
                $p = True;
            }
        }

        //if email is empty
        if(empty($_POST["email"])){
            $email_error = "Email field is empty";
        }else{
            //if not empty the test to see if used
            $email = mysqli_real_escape_string($conn,$_POST['email']);
            $result_email = mysqli_query($conn, "Select * from UserID where user_email = '$email'");
            if(mysqli_num_rows($result_email) > 0){
                $email_error = "Email has already been used";
            }else{
                //validate email
                if(filter_var($email, FILTER_VALIDATE_EMAIL)){
                    $e = True;
                }else{
                    $email_error = "Email invalid";
                }
            }
        }
    }
    //what type of user they are
    $type = $_POST["type"];
    //activation code for the email
    $hash = md5(rand(0,1000));

    //if passed all tests with user given info
    if($u and $p and $e){
        //timestamp for account creation datetime
        $timestamp = time();
        $date_time = date("Y-m-d H:i:s", $timestamp);
        //create new user
        mysqli_query($conn, "INSERT into UserID(user_id, user_password, user_email, user_type, creation_date, last_login, activation_code, active) VALUES
            ('$username','$hash_password','$email','$type', '$date_time', '$date_time', '$hash', '0')");
        if(mysqli_commit($conn)){
          //if everything works then set cookie and redirect
          setcookie("type", $type, time()+3600);
          header("location:index.php");
        }else{
            $email_error = "Commit error";
        }
    }

    //email verification system for given email
    //works sending user activation code and matching them
    $to = $email;
    $subject = 'Syn Signup Verification';
    $message = '

    Thanks for signing up to Syn!
    Your account has been created, so please just click this link to activate your account:

    http://arden.cs.unca.edu/~zboone/verify.php?&email='.$email.'&activation_code='.$hash.'

    ';

    $from = 'From:noreply@cs.unca.edu' . "\r\n";
    mail($to, $subject, $message, $from);

};
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset = "UTF-8">
    <title>Signup Page</title>
    <link rel="icon" href="pictures/Syn_Icon_White.png">
    <link rel="stylesheet" href="css/signup.css">
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
    <button class = "btn login" onclick="window.location.href = 'http://arden.cs.unca.edu/~zboone/about.php';">About</button>
    <?php
    //If cookies are inactive, show login and signup buttons
    //Else show user account and logout
    if(!isset($_COOKIE["type"])){
        echo "<button class=\"btn login align_right\" onclick=\"window.location.href = 'http://arden.cs.unca.edu/~zboone/login.php';\">Log In</button>";
        echo "<p class=\"align_right\">&nbsp</p>";
        echo "<button class=\"btn login align_right\" onclick=\"window.location.href = 'http://arden.cs.unca.edu/~zboone/signup.php';\">Sign Up</button>";
    }else{
        echo "<button class=\"btn login\" onclick=\"window.location.href = 'http://arden.cs.unca.edu/~zboone/user_account.php';\">Account</button>";
        echo "<a href=\"logout.php\" class=\"btn login align_right\" role=\"button\">Log Out</a>";
    }
    ?>
</header>
<center>
    <br><br>
    <div class="box">
    <h2>Create Account</h2>
    <form action = "<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method = "post"><br>
    <label> Email: </label><input type = "text" name = "email" value = "<?php echo $email?>"><span class="error"> *</span><br>
    <label> Username: </label><input type = "text" name = "username" value = "<?php echo $username?>"><span class="error"> *</span><br>
    <label> Password: </label><input type = password name = "password"><span class="error"> *</span><br>
    <label> Re-Enter Password: </label><input type = password name = "password2"><span class="error"> *</span><br>
    <label> Type of User: </label>
    <select name="type">
        <option value="Fan">Fan</option>
        <option value="Band">Band</option>
        <option value="Venue">Venue</option>
    </select><br><br>
    <p> <input class="accept signup" type="submit" value="Signup">
    </form><br>
    <p> If you already have an account...
    <button class="accept signup" onclick="window.location.href='http://arden.cs.unca.edu/~zboone/login.php'">Log In</button>
    <p class="dark">
    <?php
    //display any error codes
    echo $username_error;
    echo $password_error;
    echo $email_error;
    mysqli_close($conn);
    ?>
    </p>
    <span class="error">* Required</span><br><br>
    </div>
</center>
</body>
</html>
