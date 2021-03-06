<?php
//signup.php | Zachary Boone | 4/26/2020
//Signup form for the user accounts

if(isset($_COOKIE["user"])){
    header("location:index.php");
}

//Connect to database
include("php/database_conn.php");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$username_error = $password_error = $email_error = "<br>";
$username = $password = $email = $password2 = "";
$u = $p = $e = False;

//Once form is submitted test information
if($_SERVER["REQUEST_METHOD"] == "POST"){
    if(empty($_POST["username"]) and empty($_POST["password"]) and empty($_POST["email"])){
        $username_error = "All fields are required<br>";
    }else{

        //Test username to see if valid
        if(empty($_POST["username"])){
            $username_error = "Username field is empty<br>";
        }else{

            $username = mysqli_real_escape_string($conn,$_POST['username']);
            $result = mysqli_query($conn, "Select user_id from UserID where user_id = '$username'");

            if(mysqli_num_rows($result) > 0){
                $username_error = "Username has alread been used<br>";
            }else{
                $u = True;
            }
        }

        //Test password to see is valid
        if(empty($_POST["password"]) or empty($_POST["password2"])){
            $password_error = "Password field is empty<br>";
        }else{

            $password = mysqli_real_escape_string($conn,$_POST['password']);
            $password2 = mysqli_real_escape_string($conn,$_POST['password2']);

            if($password != $password2){
                $password_error = "Passwords do not match<br>";
            }else{
                $hash_password = sha1($str.$password);
                $p = True;
            }
        }

        //Test email to see if valid
        if(empty($_POST["email"])){
            $email_error = "Email field is empty";
        }else{

            $email = mysqli_real_escape_string($conn,$_POST['email']);
            $result_email = mysqli_query($conn, "Select user_id from UserID where user_email = '$email'");

            if(mysqli_num_rows($result_email) > 0){
                $email_error = "Email has already been used";
            }else{
                if(filter_var($email, FILTER_VALIDATE_EMAIL)){
                    $e = True;
                }else{
                    $email_error = "Email invalid";
                }
            }
        }
    }

    //other values need for user id
    $type = $_POST["type"];
    $hash = md5(rand(0,1000));

    if($u and $p and $e){

        //Timestamp to see when they created their account and when they last logged in
        $timestamp = time();
        $date_time = date("Y-m-d H:i:s", $timestamp);

        mysqli_query($conn, "INSERT into UserID(user_id, user_password, user_email, user_type, creation_date, last_login, activation_code, active) VALUES
            ('$username','$hash_password','$email','$type', '$date_time', '$date_time', '$hash', '0')");

        //if successful set cookie and redirect to home
        //else error
        if(mysqli_commit($conn)){
            setcookie("user", $hash, time()+3600);
            header("location:index.php");
        }else{
            $email_error = "Commit error";
        }

        //Send verification email to given address
        $to = $email;
        $subject = 'Syn Signup Verification';
        $message = '

        Thanks for signing up to Syn!
        Your account has been created, so please just click this link to activate your account:

        http://arden.cs.unca.edu/~zboone/verify.php?&email='.$email.'&activation_code='.$hash.'

        ';

        $from = 'From:noreply@cs.unca.edu' . "\r\n";
        mail($to, $subject, $message, $from);
    }
}
mysqli_close($conn);
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
    <button class="btn login" onclick="window.location.href = 'http://arden.cs.unca.edu/~zboone/';">Home</button>
    <button class="btn login" onclick="window.location.href = 'http://arden.cs.unca.edu/~zboone/calendar.php';">Calendar</button>
    <button class="btn login" onclick="window.location.href = 'http://arden.cs.unca.edu/~zboone/graphs.php';">Graphs</button>
    <button class = "btn login" onclick="window.location.href = 'http://arden.cs.unca.edu/~zboone/about.php';">About</button>
    <button class="btn login align_right" onclick="window.location.href = 'http://arden.cs.unca.edu/~zboone/login.php';">Log In</button>;
    <p class="align_right">&nbsp</p>";
    <button class="btn login align_right" onclick="window.location.href = 'http://arden.cs.unca.edu/~zboone/signup.php';">Sign Up</button>
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
    <input class="accept signup" type="submit" value="Signup">
    </form><br>
    <p> If you already have an account...
    <button class="accept signup" onclick="window.location.href='http://arden.cs.unca.edu/~zboone/login.php'">Log In</button>
    <p class="dark">
    <?php
    echo $username_error;
    echo $password_error;
    echo $email_error;
    ?>
    </p>
    <span class="error">* Required</span><br><br>
    </div>
</center>
</body>
</html>
