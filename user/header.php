<?php
SESSION_START();
include('../function.inc.php');
include('../database.inc.php');
include('../constant.php');

$curstr = $_SERVER['REQUEST_URI'];
$curarr = explode('/',$curstr);
$curpath = $curarr[count($curarr)-1];
$pagetitle = explode('.',$curpath)[0];

$cartarr = getuserfullcart();
$getUserDetails=getuserbyid();


$totalprice = 0;
foreach ($cartarr as $key => $list) {
    $totalprice = $totalprice+$list['qty'] * $list['price'];   
}

if(isset($_POST['updatecart'])){
    foreach($_POST['qty'] as $key => $val){
    if(isset($_SESSION['USER_ID']) && isset($_SESSION['USER_NAME'])){
        $uid = $_SESSION['USER_ID'];
        if($val[0] == 0){
            mysqli_query($con,"DELETE FROM cart WHERE user_id='$uid' AND dish_details_id='$key'");
        }else{
            mysqli_query($con,"UPDATE cart SET qty='".$val[0]."' WHERE user_id='$uid' AND dish_details_id='$key'");
        }
    }else {
            if($val[0] == 0){
                unset($_SESSION['cart'][$key]['qty']);               
            }else{
                $_SESSION['cart'][$key]['qty'] = $val[0];
            }
        }
    }
}

// prx($cartarr);
?>

<!doctype html>
<html class="no-js" lang="zxx">
<head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title><?php echo $pagetitle.usersite; ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="assets/css/bootstrap.min.css">
        <link rel="stylesheet" href="assets/css/animate.css">
        <link rel="stylesheet" href="assets/css/owl.carousel.min.css">
        <link rel="stylesheet" href="assets/css/slick.css">
        <link rel="stylesheet" href="assets/css/chosen.min.css">
        <link rel="stylesheet" href="assets/css/ionicons.min.css">
        <link rel="stylesheet" href="assets/css/font-awesome.min.css">
        <link rel="stylesheet" href="assets/css/simple-line-icons.css">
        <link rel="stylesheet" href="assets/css/jquery-ui.css">
        <link rel="stylesheet" href="assets/css/meanmenu.min.css">
        <link rel="stylesheet" href="assets/css/style.css">
        <link rel="stylesheet" href="assets/css/responsive.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" integrity="sha512-+4zCK9k+qNFUR5X+cKL9EIR+ZOhtIloNl9GIKS57V1MyNsYpYcUrUeQc9vNfzsWfV28IaLL3i96P9sdNyeRssA==" crossorigin="anonymous" />
        <script src="assets/js/vendor/modernizr-2.8.3.min.js"></script>
    </head>
    <body>
        <!-- header start -->
        <header class="header-area">
            <div class="header-top black-bg">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-12 col-sm-4">
                            <div class="welcome-area">
                                <!-- <p>Default welcome msg! </p> -->
                            </div>
                        </div>
                        <div class="col-lg-8 col-md-8 col-12 col-sm-8">
                            <div class="account-curr-lang-wrap f-right">
                                <?php if(isset($_SESSION['USER_NAME'])){    ?>                                
                                    <ul>
                                    <li class="top-hover"><a href="javascript:void(0)">Welcome <span id="headeruname"><?php echo $getUserDetails['name']; ?></span> <i class="ion-chevron-down"></i></a>
                                        <ul>
                                            <li><a href="profile">Profile</a></li>
                                            <li><a href="orderhistory">My Order</a></li>
                                            <li><a href="logout">Logout</a></li>
                                        </ul>
                                    </li>
                                </ul>

                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="header-middle">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-3 col-md-4 col-12 col-sm-4">
                            <div class="logo">
                                <a href="index.html">
                                    <img alt="" src="assets/img/logo/logo.png">
                                </a>
                            </div>
                        </div>
                        <div class="col-lg-9 col-md-8 col-12 col-sm-8">
                            <div class="header-middle-right f-right">
                                <div class="header-login">
                                <?php if(!isset($_SESSION['USER_NAME'])){  ?>
                                    <a href="index">
                                        <div class="header-icon-style">
                                            <i class="icon-user icons"></i>
                                        </div>
                                        <div class="login-text-content">
                                            <p>Register <br> or <span>Sign in</span></p>
                                        </div>
                                    </a>
                                    <?php } ?>
                                </div>
                                <div class="header-wishlist">
                                   &nbsp;
                                </div>
                                <div class="header-cart">
                                    <a href="javascript:void(0)">
                                        <div class="header-icon-style">
                                            <i class="icon-handbag icons"></i>
                                            <span class="count-style" id="totaldishcount"><?php echo count($cartarr); ?></span>
                                        </div>
                                        <div class="cart-text">
                                            <span class="digit">My Cart</span>
                                            <span class="cart-digit-bold" id="totalcartprice"> <?php echo ($totalprice != 0) ? 'Rs'.$totalprice : ''; ?></span>
                                        </div>
                                    </a>
                                    <?php if($totalprice != 0){ ?>
                                        <div id="cartmenu" class="shopping-cart-content">
                                            <ul id="cart_ul">
                                            <?php foreach($cartarr as $key => $list){ ?>
                                                <li class="single-shopping-cart" id="attr_<?php echo $key; ?>">
                                                    <div class="shopping-cart-img">
                                                        <a href="javascript:void(0)"><img style="width: 100%;" alt="" src="<?php echo SITE_DISP_IMAGE.$list['image'] ?>"></a>
                                                    </div>
                                                    <div class="shopping-cart-title">
                                                        <h4><a href="javascript:void(0)"><?php echo $list['dishname'] ?> </a></h4>
                                                        <h6>Qty: <?php echo $list['qty'] ?></h6>
                                                        <span>Rs <?php echo ($list['price'] * $list['qty']) ?></span>
                                                    </div>
                                                    <div class="shopping-cart-delete">
                                                        <a href="javascript:void(0)" onclick="delete_cart('<?php echo $key; ?>')"><i class="ion ion-close"></i></a>
                                                    </div>
                                                </li>
                                            <?php } ?>
                                            </ul>
                                            <div class="shopping-cart-total">
                                                <h4>Total : <span id="cartinsidetotal" class="shop-total">Rs <?php echo $totalprice; ?></span></h4>
                                            </div>
                                            <div class="shopping-cart-btn">
                                                <a onclick="gotocart()" href="javascript:void(0)">view cart</a>
                                                <a onclick="gotocheckout()"  href="javascript:void(0)">checkout</a>
                                            </div>
                                    </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="header-bottom transparent-bar black-bg">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-12">
                            <div class="main-menu">
                                <nav>
                                    <ul>
                                        <li><a href="shop">Shop</a></li>
                                        <li><a href="about-us">about</a></li>
                                        <li><a href="contact">contact us</a></li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- mobile-menu-area-start -->
			<div class="mobile-menu-area">
				<div class="container">
					<div class="row">
						<div class="col-lg-12">
							<div class="mobile-menu">
								<nav id="mobile-menu-active">
									<ul class="menu-overflow" id="nav">
                                    <li><a href="shop">Shop</a></li>
                                        <li><a href="about-us">about</a></li>
                                        <li><a href="contact">contact us</a></li>
									</ul>
								</nav>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- mobile-menu-area-end -->
        </header>
        