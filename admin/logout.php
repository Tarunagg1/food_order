<?php 
session_start();
unset($_SESSION['IS_LOGIN']);
unset($_SESSION['ADMIN_USER']);
header("Location:login.php");
?>