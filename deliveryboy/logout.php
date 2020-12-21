<?php 
session_start();
unset($_SESSION['IS_DELIVERY_USER']);
header("Location:login");
?>