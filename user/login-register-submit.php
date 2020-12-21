<?php
session_start();
include('../function.inc.php');
include('../database.inc.php');
include('../constant.php');
include('../mail_function.php');


$type = get_safe_value($_POST['what']);

if($type === "register"){
    $name = get_safe_value($_POST['user-name']);
    $email = get_safe_value($_POST['user-email']);
    $number = get_safe_value($_POST['user-number']);
    $pass = get_safe_value($_POST['user-password']);
    $pass = get_safe_value($_POST['user-password']);
    $encpass = password_hash($pass,PASSWORD_BCRYPT);
    $fromreferalcode = 'NULL';
    $checkemail = mysqli_num_rows(mysqli_query($con,"SELECT * FROM user WHERE email='$email'"));
    if($checkemail > 0){
        $arr = array('status'=>'error','msg'=>'email allredy exit','field'=>'email_err');
        echo json_encode($arr);
    }else{
        $randstr = rand_str();
        $refralcode = rand_str();
        if(isset($_SESSION['FROM_REFERRAL_CODE']) && $_SESSION['FROM_REFERRAL_CODE'] != ''){
            $fromreferalcode = $_SESSION['FROM_REFERRAL_CODE'];
        }
        $sql = "INSERT INTO `user`(`name`, `email`, `mobile`, `password`,`randstr`,`refreal_code`,`from_referal_code`) VALUES ('$name','$email','$number','$encpass','$randstr','$refralcode','$fromreferalcode')";
        mysqli_query($con,$sql);
        $id = mysqli_insert_id($con);
        $getsetting = getwebsetting();
        $free = $getsetting['free_wallet_amt'];
        managewallet($id,$free,"in","Welcome bounes",'1','shop',"shop_.$id");
        $sub = 'Thank you for registration';
        $msg = 'congo please verify your id no http://localhost/foodorder/user/verify.php?verifyid='.$randstr;  
        sendmailuser($email,$sub,$msg);
        unset($_SESSION['FROM_REFERRAL_CODE']);
        $arr = array('status'=>'success','msg'=>'Thanks for Registration Please check your email id for further verification OF account.','field'=>'form_msg');
        echo json_encode($arr);
    }
}


if($type === 'login'){
    $email = get_safe_value($_POST['lemail']);
    $password = get_safe_value($_POST['lpassword-password']);
    $q = mysqli_query($con,"SELECT * FROM user WHERE email='$email'LIMIT 1");
    $checkemail = mysqli_num_rows($q);
    if($checkemail == 0){
        $arr = array('status'=>'error','msg'=>'email is not exit','field'=>'loginerr');
        echo json_encode($arr);
    }else{
        $data = mysqli_fetch_assoc($q);
        if($data['email_verify'] == 1){
            if($data['status'] == 1){
                if(!password_verify($password,$data['password'])){
                    $arr = array('status'=>'error','msg'=>'Password is not valid','field'=>'loginerr');
                    echo json_encode($arr);
                }else{
                    $arr = array('status'=>'success','msg'=>'found','field'=>'go dashboard');
                    $_SESSION['USER_ID'] = $data['id'];
                    $_SESSION['USER_NAME'] = $data['name'];
                    
                    if(isset($_SESSION['cart']) && count($_SESSION['cart']) > 0){
                        foreach ($_SESSION['cart'] as $key => $val) {
                            manage_user_cart($data['id'],$val['qty'],$key);
                        }
                    }
                    echo json_encode($arr);
                }
            }else{
                $arr = array('status'=>'error','msg'=>'Your account has been deactivated','field'=>'loginerr');
                echo json_encode($arr);     
            }
        }else{
            $arr = array('status'=>'error','msg'=>'Email is not verify yet','field'=>'loginerr');
            echo json_encode($arr);
        }
    }
}

if($type =='forgot'){
    $email = get_safe_value($_POST['email']);
    $q = mysqli_query($con,"SELECT * FROM user WHERE email='$email'LIMIT 1");
    $checkemail = mysqli_num_rows($q);
    if($checkemail == 0){
        $arr = array('status'=>'error','msg'=>'email is not exit','field'=>'forgotpasserr');
        echo json_encode($arr);
    }else{
        $data = mysqli_fetch_assoc($q);
        if($data['email_verify'] == 1){
            if($data['status'] == 1){
               $tpass = rand(11111,99999);
               $newpass = password_hash($tpass,PASSWORD_BCRYPT);
               $uid = $data['id'];
               mysqli_query($con,"UPDATE user SET `password`='$newpass' WHERE id='$uid'");
               $sub = 'Forgot password';
               $msg = 'your temp pass is'.$tpass;  
               sendmailuser($email,$sub,$msg);
               $arr = array('status'=>'error','msg'=>'Ypur temp password send on email please login and change password: ','field'=>'forgotpasserr');
               echo json_encode($arr);
            }else{
                $arr = array('status'=>'error','msg'=>'Your account has been deactivated','field'=>'forgotpasserr');
                echo json_encode($arr);     
            }
        }else{
            $arr = array('status'=>'error','msg'=>'Email is not verify yet','field'=>'forgotpasserr');
            echo json_encode($arr);
        }
    }
}

?>