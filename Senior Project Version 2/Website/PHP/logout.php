<?php
//logout.php
//Logout the user
setcookie("user", "", time()-3600);
header("location:index.php");
?>
