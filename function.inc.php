<?php

function pr($arr){
    echo "<pre>";
    print_r($arr);
}
function prx($arr){
    echo "<pre>";
    print_r($arr);   
    die();
}

function get_safe_value($input){
    global $con;
    $input = mysqli_real_escape_string($con,$input);
    $input = trim($input);
    return $input;
}

function redirect($link)
{
    ?>
        <script>window.location.href='<?php echo $link; ?>'</script>
    <?php
}

function rand_str()
{
    $str = str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz');
    return substr($str,0,20);
}


function getusercart()
{
    global $con;
    $arr = array();
    $id = $_SESSION['USER_ID'];
    $res = mysqli_query($con,"SELECT * FROM cart WHERE `user_id`='$id'");
    while ($row = mysqli_fetch_assoc($res)) {
        $arr[] = $row;
    }
    return $arr;
}

function manage_user_cart($uid,$qty,$attr)
{
    global $con;
    $res = mysqli_query($con,"SELECT * FROM cart WHERE `user_id`='$uid' AND `dish_details_id`='$attr'");
    if(mysqli_num_rows($res) > 0){
        $row = mysqli_fetch_assoc($res);
        $cid = $row['id'];
        mysqli_query($con,"UPDATE cart SET qty='$qty' WHERE id='$cid'");
    }else{
        mysqli_query($con,"INSERT INTO `cart`(`user_id`, `dish_details_id`, `qty`) VALUES ('$uid','$attr','$qty')");
    }
}

function getuserfullcart($attr_id='')
{
    $cartarr = array();
    if(isset($_SESSION['USER_ID']) && isset($_SESSION['USER_NAME'])){
        $getuserdata = getusercart();
        $cartarr = array();
        foreach($getuserdata as $list){
            $cartarr[$list['dish_details_id']]['qty']=$list['qty'];
            $getdishdetails = getdishdetailsbyid($list['dish_details_id']);
            $cartarr[$list['dish_details_id']]['price'] = $getdishdetails['price'];
            $cartarr[$list['dish_details_id']]['dishname'] = $getdishdetails['dish'];
            $cartarr[$list['dish_details_id']]['image'] = $getdishdetails['image'];
        }    
    }else {
        if(isset($_SESSION['cart']) && count($_SESSION['cart']) > 0){
            foreach($_SESSION['cart'] as $key => $list){
                $cartarr[$key]['qty']=$list['qty'];
                $getdishdetails = getdishdetailsbyid($key);
                $cartarr[$key]['price']= $getdishdetails['price'];
                $cartarr[$key]['dishname']= $getdishdetails['dish'];
                $cartarr[$key]['image']= $getdishdetails['image'];       
            }
        }
    }
    if($attr_id != ''){
        return $cartarr[$attr_id]['qty'];
    }else{
        return $cartarr;
    }
}

function getdishdetailsbyid($id)
{
    global $con;
    $q = "SELECT dish.dish,dish.image,dish.dish_detail,dish_details.price FROM dish_details,dish WHERE dish_details.id='$id' AND dish.id=dish_details.dish_id";
    $res = mysqli_query($con,$q);
    $row = mysqli_fetch_assoc($res);
    return $row;
}

function removedishfromcartbyid($id)
{
    global $con;
    if(isset($_SESSION['USER_ID']) && isset($_SESSION['USER_NAME'])){
        $uid = $_SESSION['USER_ID'];
       mysqli_query($con,"DELETE FROM cart WHERE user_id='$uid' AND dish_details_id='$id'");
    }else {
        unset($_SESSION['cart'][$id]);
    }
}

function getuserbyid()
{
    global $con;
    if(isset($_SESSION['USER_ID']) && isset($_SESSION['USER_NAME'])){
        $uid = $_SESSION['USER_ID'];
        $data = mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM user WHERE id='$uid' LIMIT 1"));
        return $data;
    }else{
        return null;
    }
}


function emptycart()
{
    global $con;
    if(isset($_SESSION['USER_ID']) && isset($_SESSION['USER_NAME'])){
        $uid = $_SESSION['USER_ID'];
        mysqli_query($con,"DELETE FROM cart WHERE user_id='$uid'");
    }else{
        unset($_SESSION['cart']);
    }
}

function getorderdetailsbyid($oid)
{
    global $con;
    $data = array();
    $sql = "SELECT order_detail.price,order_detail.qty,dish_details.attribute,dish.dish
            from order_detail,dish_details,dish
            WHERE order_detail.order_id = '$oid' AND
            order_detail.dish_details_id=dish_details.id AND
            dish_details.dish_id=dish.id";
    $res = mysqli_query($con,$sql);
    while ($row = mysqli_fetch_assoc($res)) {
        $data[] = $row;
    }
    return $data;
}

?>