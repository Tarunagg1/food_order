<?php
session_start();
include('../function.inc.php');
include('../database.inc.php');
include('../constant.php');

if(!isset($_SESSION['USER_ID'])){
    redirect('shop');
}

$id = get_safe_value($_POST['id']);
$rate = get_safe_value($_POST['rate']);
$uid = $_SESSION['USER_ID'];
$oid = get_safe_value($_POST['orderid']);
$q = "INSERT INTO `order_ratting`(`user_id`, `order_id`,`dish_details_id`, `ratting`) VALUES ('$uid','$oid  ','$id','$rate')";
mysqli_query($con,$q);
?>