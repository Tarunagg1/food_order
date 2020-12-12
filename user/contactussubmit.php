<?php
include('../function.inc.php');
include('../database.inc.php');
include('../constant.php');

$name = get_safe_value($_POST['name']);
$email = get_safe_value($_POST['email']);
$subject = get_safe_value($_POST['subject']);
$message = get_safe_value($_POST['message']);

$sql = "INSERT INTO `contactus`(`name`,`email`, `subject`, `message`) VALUES ('$name','$email','$subject','$message')";
mysqli_query($con,$sql);

echo "Data send Success";

?>