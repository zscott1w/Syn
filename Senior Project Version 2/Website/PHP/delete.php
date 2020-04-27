<?php
//delete.php | Zachary Boone | 4/26/2020
//Deletes the user account from the database
if(!isset($_COOKIE["user"])){
    header("location:index.php");
}
include("php/database_conn.php");
$c = $_COOKIE["user"];
mysqli_query($conn, "DELETE from UserID WHERE activation_code = '$c'");
mysqli_commit($conn);
setcookie("user", "", time()-3600);
mysqli_close($conn);
header("location:index.php");
?>
