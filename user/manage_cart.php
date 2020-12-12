<?php
session_start();
include('../function.inc.php');
include('../database.inc.php');
include('../constant.php');

$attr = get_safe_value($_POST['attr']);
$type = get_safe_value($_POST['type']);

if($type == 'add'){
    $qty = get_safe_value($_POST['qty']);
    if(isset($_SESSION['USER_ID']) && isset($_SESSION['USER_NAME'])){
        $id = $_SESSION['USER_ID'];
        manage_user_cart($id,$qty,$attr);

    }else {
        $_SESSION['cart'][$attr]['qty'] = $qty;
    }
    $fullcart = getuserfullcart();
    $totalprice = 0;
    foreach ($fullcart as $key => $list) {
        $totalprice = $totalprice+$list['qty'] * $list['price'];   
    }
    $getdishdetails = getdishdetailsbyid($attr);
    $img = $getdishdetails['image'];
    $price = $getdishdetails['price'];
    $dishname = $getdishdetails['dish'];
    $arr = array('totalcartdish'=>count($fullcart),'totalcartprice'=>$totalprice,'dishname'=>$dishname,'dishimg'=>$img,'dishprice'=>$price);
    echo json_Encode($arr);
}

if($type == 'delete'){
    removedishfromcartbyid($attr);
    $fullcart = getuserfullcart();
    $totalprice = 0;
    foreach ($fullcart as $key => $list) {
        $totalprice = $totalprice+$list['qty'] * $list['price'];   
    }
    $arr = array('totalcartdish'=>count($fullcart),'totalcartprice'=>$totalprice);
    echo json_Encode($arr);
}   
?>

