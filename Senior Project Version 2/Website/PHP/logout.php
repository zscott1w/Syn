<?php
//logout.php
//Zach Boone
//Logout script for users

setcookie("user", "", time()-3600);
header("location:index.php");
?>
