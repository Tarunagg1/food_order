<?php
session_start();
include('../function.inc.php');
include('../database.inc.php');
include('../constant.php');
include('../mail_function.php');


$type = get_safe_value($_POST['type']);

if($type == 'profileupdate'){
    $name = get_safe_value($_POST['name']);
    $mobile = get_safe_value($_POST['mobile']);
    $uid = get_safe_value($_SESSION['USER_ID']);
    $res = mysqli_query($con,"UPDATE user SET `name`='$name', `mobile`='$mobile' WHERE id='$uid'");
    if($res){
        $arr = array('status'=>'success','msg'=>'profile updated');
        echo json_encode($arr);  
    }else {
        $arr = array('status'=>'error','msg'=>'Something went wrong');
        echo json_encode($arr); 
    }
}
if($type == 'changepass'){
    $old_password = get_safe_value($_POST['old_password']);
    $new_password = get_safe_value($_POST['new_password']);
    $uid = get_safe_value($_SESSION['USER_ID']);
    $check = mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM user WHERE `id`='$uid'"));
    // $row = mysqli_num_rows($check);
    $dbpasss = $check['password'];
    if(password_verify($old_password,$dbpasss)){
        $new_password = password_hash($new_password,PASSWORD_BCRYPT);
        $res = mysqli_query($con,"UPDATE user SET `password`='$new_password' WHERE id='$uid'");
        if($res){
            $arr = array('status'=>'success','msg'=>'Password updated');
            echo json_encode($arr);  
        }else {
            $arr = array('status'=>'error','msg'=>'Something went wrong');
            echo json_encode($arr); 
        }
    }else{
        $arr = array('status'=>'error','msg'=>'Old password is wrong');
        echo json_encode($arr); 
    }
}
?>