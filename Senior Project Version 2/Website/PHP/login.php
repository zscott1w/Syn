//login.php
//Zach Boone
//Syn Login Page

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
//variables used in login request
$username_error = $password_error = "<br>";
$username = $password = "";
//once submitted once, then test
if($_SERVER["REQUEST_METHOD"] == "POST"){
    //if they are both null then error
    if(empty($_POST["username"]) and empty($_POST["password"])){
        $username_error = "Both fields are required<br>";
    }
    //if only username is null then error
    elseif(empty($_POST["username"])){
        $username_error = "Username is required<br>";
    }else{
        //username not null (also protected from sql injection)
        $username = mysqli_real_escape_string($conn,$_POST['username']);
        //test database to see if username exists
        $result = mysqli_query($conn, "Select * from UserID where user_id = '$username'");
        //if exists get information
        if(mysqli_num_rows($result) > 0){
            while($row = mysqli_fetch_assoc($result)){
                //get password and type of user from row
                $hash_password = $row["user_password"];
                $user_type = $row["user_type"];
            }
            //if password is empty error
            if(empty($_POST["password"])){
            $password_error = "Password is required";
            }else{
                //username not null and hashed using sha1
                $password = mysqli_real_escape_string($conn,$_POST['password']);
                $hp = sha1($str.$password);
                //if hashed passwords are equal, log in and update last login time
                if($hp == $hash_password){
                    $timestamp = time();
                    $date_time = date("Y-m-d H:i:s", $timestamp);
                    mysqli_query($conn, "UPDATE UserID SET last_login = '$date_time' WHERE user_id = '$username'");
                    mysqli_commit($conn);
                    //cookie is set with the type of user logged in
                    setcookie("type", $user_type, time()+3600);
                    header("location:index.php");
                }else{
                    $password_error = "Password is incorrect";
                }
            }
        }else{
            $username_error = "Username not found<br>";
            if(empty($_POST["password"])){
                $password_error = "Password is required";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset = "UTF-8">
    <title>Login Page</title>
    <link rel="icon" href="pictures/Syn_Icon_White.png">
    <link rel="stylesheet" href="css/login.css">
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
    <h2>User Log In</h2>
    <form action = "<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method = "post">
    <label> Username: </label><input type = "text" name = "username" value="<?php echo $username; ?>"><span class="error"> *</span><br>
    <label> Password: </label><input type = password name = "password" value=""><span class="error"> *</span><br><br>
    <input type="submit" value="Log In" class="accept login">
    </form><br>
    <p> If you do not have an account...
    <button class="accept login" onclick="window.location.href='http://arden.cs.unca.edu/~zboone/signup.php'">Create Account</button><br>
    <p class="error">
    <?php
    //Display errors (if any)
    echo $username_error;
    echo $password_error;
    mysqli_close($conn);
    ?>
    </p>
    <span class="error">* Required</span><br><br>
    </div>
</center>
</body>
</html>
