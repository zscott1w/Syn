<?php
//logout.php
//Zach Boone
//Logout script for users

setcookie("type", "", time()-3600);
header("location:index.php");
?>