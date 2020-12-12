<?php
include('function.inc.php');
include('database.inc.php');
include('constant.php');
?>

<!doctype html>
<html class="no-js" lang="zxx">
<head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title><?php echo "Home".usersite; ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="user/assets/css/bootstrap.min.css">
        <link rel="stylesheet" href="user/assets/css/animate.css">
        <link rel="stylesheet" href="user/assets/css/owl.carousel.min.css">
        <link rel="stylesheet" href="user/assets/css/font-awesome.min.css">
        <link rel="stylesheet" href="user/assets/css/style.css">
        <link rel="stylesheet" href="user/assets/css/responsive.css">
        <script src="user/assets/js/vendor/modernizr-2.8.3.min.js"></script>
    </head>
    <body>
        <div class="slider-area">
            <div class="slider-active owl-dot-style owl-carousel">
                <?php
                    $bannerres = mysqli_query($con,"SELECT * FROM banner WHERE `status`='1' ORDER BY banner_order");
                    while ($row = mysqli_fetch_assoc($bannerres)) { ?>
                       <div class="single-slider pt-210 pb-220 bg-img" style="background-image:url(<?php echo SITE_BANNER_DISP_IMAGE.$row['image'] ?>);">
                    <div class="container">
                        <div class="slider-content slider-animated-1">
                            <h1 class="animated"><?php echo $row['heading']  ?></h1>
                            <h3 class="animated"><?php echo $row['sub_heading']; ?></h3>
                            <div class="slider-btn mt-90">
                            <a class="animated" href="user/<?php echo $row['link'] ?>">Order Now</a>
                            </div>
                        </div>
                    </div>
                </div> 
                <?php  } ?>
            </div>
        </div>
        <script src="user/assets/js/vendor/jquery-1.12.0.min.js"></script>
        <script src="user/assets/js/bootstrap.min.js"></script>
        <script src="user/assets/js/imagesloaded.pkgd.min.js"></script>
        <script src="user/assets/js/isotope.pkgd.min.js"></script>
        <script src="user/assets/js/owl.carousel.min.js"></script>
        <script src="user/assets/js/plugins.js"></script>
        <script src="user/assets/js/main.js"></script>
    </body>
</html>
