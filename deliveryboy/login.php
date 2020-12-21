<?php
    session_start();
    include('../function.inc.php');
    include('database.inc.php');
    $msg = "";
    if(isset($_SESSION['IS_DELIVERYBOY'])){
      redirect('index');
    }
    if(isset($_POST['login'])){
        $number = $_POST['number'];
        $password = $_POST['password'];
        $encpass = md5($password);
        $sql = "SELECT * FROM `delivery_boy` WHERE `mobile`='$number' And `status`='1' LIMIT 1";
        $res = mysqli_query($con,$sql);
        if(mysqli_num_rows($res) > 0){
            $row = mysqli_fetch_assoc($res);
          if($encpass== $row['password']){
              $_SESSION['IS_DELIVERY_USER'] = $row['id'];
              redirect('index');
            }else{
              $msg = "Invalid password";
            }
        }else {
            $msg = "please enter valid information";
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Delivery Boy Login</title>
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="sidebar-light">
  <div class="container-scroller">
    <div class="container-fluid page-body-wrapper full-page-wrapper">
      <div class="content-wrapper d-flex align-items-center auth">
        <div class="row w-100">
          <div class="col-lg-4 mx-auto">
            <div class="auth-form-light text-left p-5">
              <div class="brand-logo text-center">
                <img src="assets/images/logo.png" alt="logo">
              </div>
              <h6 class="font-weight-light">Sign in to continue.</h6>
              <form class="pt-3" method="post">
                <div class="form-group">
                  <input type="number" class="form-control form-control-lg" name="number" id="number" placeholder="Enter number" required>
                </div>
                <div class="form-group">
                  <input type="password" class="form-control form-control-lg" id="password" name="password" placeholder="Enter Password" required>
                </div>
                <div class="mt-3">
                  <input type="submit" class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn" name="login" value="SIGN IN">
                </div>
              </form>
              <div class="login_msg"><?php echo $msg; ?></div>
            </div>
          </div>
        </div>
      </div>
      <!-- content-wrapper ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>
</body>
</html>