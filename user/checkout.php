<?php
    include('header.php');
    $totalPrice = 0;
    $cartarr = getuserfullcart();
    foreach ($cartarr as $key => $list) {
         $totalPrice = $totalPrice + $list['qty'] * $list['price'];   
    }
    if(!count($cartarr) > 0){
        redirect('shop');
    }
    $islogin = false;
    $userdata = '';
    if(isset($_SESSION['USER_ID']) && isset($_SESSION['USER_NAME'])){
        $islogin = true;
        $userdata = getuserbyid();
    }
    if(isset($_POST['placeorder'])){
        $uid = $_SESSION['USER_ID'];
        $name = get_safe_value($_POST['fname']);
        $email =get_safe_value($_POST['email']);
        $address = get_safe_value($_POST['address']);
        $city = get_safe_value($_POST['city']);
        $number = get_safe_value($_POST['number']);
        $zip = get_safe_value($_POST['zip']);
        $paymenttype = get_safe_value($_POST['paymenttype']);
        $sql = "INSERT INTO `order_master`(`user_id`, `name`, `email`, `mobile`, `address`, `total_price`, `delivery_boy_id`, `payment_status`, `order_status`,`zip`) VALUES ('$uid','$name','$email','$number ','$address','$totalPrice','not-asign','pending','1','$zip')";
        mysqli_query($con,$sql);
        $insertid = mysqli_insert_id($con);
        foreach($cartarr as $key=>$val){
            mysqli_query($con,"INSERT INTO `order_detail`(`order_id`, `dish_details_id`, `price`, `qty`) VALUES ('$insertid','$key','".$val['price']."','".$val['qty']."')");
        }
        emptycart();
        redirect('shop');
    }

?>

<div class="breadcrumb-area gray-bg">
    <div class="container">
        <div class="breadcrumb-content">
            <ul>
                <li><a href="index.html">Home</a></li>
                <li class="active"> Checkout </li>
            </ul>
        </div>
    </div>
</div>
<!-- checkout-area start -->
<div class="checkout-area pb-80 pt-100">
    <div class="container">
        <div class="row">
            <div class="col-lg-9">
                <div class="checkout-wrapper">
                    <div id="faq" class="panel-group">
                        <?php if(!$islogin){ ?>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h5 class="panel-title"><span>1.</span> <a data-toggle="collapse" data-parent="#faq"
                                        href="#payment-1">Checkout method</a></h5>
                            </div>
                            <div id="payment-1" class="panel-collapse collapse show">
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="checkout-login">
                                                <div class="title-wrap">
                                                    <h4 class="cart-bottom-title section-bg-white">LOGIN</h4>
                                                </div>
                                                <p>Already have an account? </p>
                                                <span>Please log in below:</span>
                                                <h5 class="loginerr" id="loginerr"></h5>
                                                <form id="loginform">
                                                    <div class="login-form">
                                                        <label>Email Address * </label>
                                                        <input type="email" name="lemail" placeholder="Email" required>
                                                    </div>
                                                    <div class="login-form">
                                                        <label>Password *</label>
                                                        <input type="password" name="lpassword-password"
                                                            placeholder="Password" required>
                                                    </div>
                                                    <input type="hidden" value="login" name="what">
                                                    <input type="hidden" id="ischeckout" value="yes" name="ischeckout">
                                                    <div class="login-forget">
                                                        <a href="forgotpass">Forgot your password?</a>
                                                        <p>* Required Fields</p>
                                                    </div>
                                                    <div class="checkout-login-btn">
                                                        <button id="loginbtn" class="btn btn-lg btn-secondary"
                                                            type="submit"><span>Login</span></button>
                                                        <a href="index">Register</a>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php } ?>
                        <?php if($islogin){ ?>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h5 class="panel-title"><span>1.</span> <a data-toggle="collapse" data-parent="#faq"
                                        href="#payment-2">billing information</a></h5>
                            </div>
                            <div id="payment-2" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <div class="billing-information-wrapper">
                                        <form id="placeorder" method="post">
                                            <div class="row">
                                                <div class="col-lg-6 col-md-6">
                                                    <div class="billing-info">
                                                        <label>Name</label>
                                                        <input name="fname" value="<?php echo $userdata['name']; ?>"
                                                            type="text">
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6">
                                                    <div class="billing-info">
                                                        <label>Email Address</label>
                                                        <input name="email" value="<?php echo $userdata['email']; ?>"
                                                            type="email">
                                                    </div>
                                                </div>
                                                <div class="col-lg-12 col-md-12">
                                                    <div class="billing-info">
                                                        <label>Address</label>
                                                        <input name="address" type="text">
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6">
                                                    <div class="billing-info">
                                                        <label>city</label>
                                                        <input name="city" type="text">
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6">
                                                    <div class="billing-info">
                                                        <label>Number</label>
                                                        <input name="number" value="<?php echo $userdata['mobile']; ?>"
                                                            type="number">
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6">
                                                    <div class="billing-info">
                                                        <label>Zip/Postal Code</label>
                                                        <input name="zip" type="text">
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="ship-wrapper">
                                                <div class="single-ship">
                                                    <input type="radio" name="paymenttype" value="cod" checked="">
                                                    <label>Cash On delivery(Cod)</label>
                                                </div>
                                            </div>
                                            <div class="billing-back-btn">
                                                <div class="billing-back">
                                                    <a href="#"><i class="ion-arrow-up-c"></i> back</a>
                                                </div>
                                                <div class="billing-btn">
                                                    <button name="placeorder" type="submit">Place Order</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="checkout-progress">
                    <div class="shopping-cart-content-box">
                        <h4 class="checkout_title">Cart Details</h4>
                        <ul>
                            <?php foreach($cartarr as $key=>$list){ ?>
                            <li class="single-shopping-cart">
                                <div class="shopping-cart-img">
                                    <a href="#"><img style="width: 100%;" alt=""
                                            src="<?php echo SITE_DISP_IMAGE.$list['image']?>"></a>
                                </div>
                                <div class="shopping-cart-title">
                                    <h4><a href="#">Phantom Remote </a></h4>
                                    <h6>Qty: <?php echo $list['qty']?></h6>
                                    <span><?php echo 
												$list['qty']*$list['price'];?> Rs</span>
                                </div>

                            </li>
                            <?php } ?>
                        </ul>
                        <div class="shopping-cart-total">
                            <h4>Total : <span class="shop-total"><?php echo $totalPrice; ?> Rs</span></h4>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<?php
    include('footer.php');
?>