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


function getdishcartstatus()
{
  global $con;
  $cartarr = array();
  $dishdetailsid = array();
  if(isset($_SESSION['USER_ID']) && isset($_SESSION['USER_NAME'])){
    $getuserdata = getusercart();
    $cartarr = array();
    foreach($getuserdata as $list){
      // $list['dish_details_id']
      $dishdetailsid[] = $list['dish_details_id'];
    }    
  }else {
    if(isset($_SESSION['cart']) && count($_SESSION['cart']) > 0){
        foreach($_SESSION['cart'] as $key => $list){
              // $list['dish_details_id']
              $dishdetailsid[] = $list['dish_details_id'];
        }
    }
}

  foreach ($dishdetailsid as $id) {
    $res = mysqli_query($con,"SELECT dish_details.status,dish.status as dish_status,dish.id FROM dish_details,dish WHERE dish_details.id ='$id' AND dish_details.dish_id=dish.id");
    $row = mysqli_fetch_assoc($res);
    if($row['dish_status'] == 0){
      $id = $row['id'];
      $re = mysqli_query($con,"SELECT id FROM dish_details WHERE dish_id='$id'");
      while ($row1 = mysqli_fetch_assoc($re)) {  
        removedishfromcartbyid($row1['id']); 
      }
    }
    if($row['status'] == 0){
      removedishfromcartbyid($id); 
    }

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
function getorderbyid($oid){
    global $con;
    $data = array();
    $sql = "SELECT * FROM order_master WHERE id='$oid'";
    $res = mysqli_query($con,$sql);
    while ($row = mysqli_fetch_assoc($res)) {
        $data[] = $row;
    }
    return $data;
}


function orderemail($oid)
{
    $uid = $_SESSION['USER_ID'];
    $udetails = getuserbyid($uid);
    $pdata = getorderbyid($oid);
    $email = $pdata[0]['email'];
    $name = $pdata[0]['name'];
    $total_price = $pdata[0]['total_price'];
    $mobile = $pdata[0]['mobile'];
    $name = $pdata[0]['name'];
    $getOrderDetails = getorderdetailsbyid($oid);

    $html = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    <html>
      <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta name="x-apple-disable-message-reformatting" />
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <link rel="stylesheet" href="assets/css/invoice.css">     
      </head>
      <body>
        <table class="email-wrapper" width="100%" cellpadding="0" cellspacing="0" role="presentation">
          <tr>
            <td align="center">
              <table class="email-content" width="100%" cellpadding="0" cellspacing="0" role="presentation">
                <tr>
                  <td class="email-masthead">
                  <img src="https://i.ibb.co/6myys4W/logo-1.png"/>
                  </a>
                  </td>
                </tr>
                <!-- Email Body -->
                <tr>
                  <td class="email-body" width="100%" cellpadding="0" cellspacing="0">
                    <table class="email-body_inner" align="center" width="570" cellpadding="0" cellspacing="0" role="presentation">
                      <!-- Body content -->
                      <tr>
                        <td class="content-cell">
                          <div class="f-fallback">
                            <h1>Hi '.ucfirst($name).',</h1>
                            <h3>'.$email.',</h3>
                            <h4>'.$mobile.',</h4>
                            <p>This is an invoice for your recent purchase.</p>
                            <table class="attributes" width="100%" cellpadding="0" cellspacing="0" role="presentation">
                              <tr>
                                <td class="attributes_content">
                                  <table width="100%" cellpadding="0" cellspacing="0" role="presentation">
                                    <tr>
                                      <td class="attributes_item">
                                        <span class="f-fallback">
                                        <strong>Amount Due:</strong>'.$total_price.'
                                        </span>
                                      </td>
                                    </tr>
                                    <tr>
                                      <td class="attributes_item">
                                        <span class="f-fallback">
                                    <strong>Order ID:</strong> '.$oid.'
                                    </span>
                                      </td>
                                    </tr>
                                  </table>
                                </td>
                              </tr>
                            </table>
                            <!-- Action -->
                            
                            <table class="purchase" width="100%" cellpadding="0" cellspacing="0">
                            <tr>
                            <td colspan="2">
                              <table class="purchase_content" width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                  <th class="purchase_heading" align="left">
                                    <p class="f-fallback">Description</p>
                                  </th>
								   <th class="purchase_heading" align="left">
                                    <p class="f-fallback">Qty</p>
                                  </th>
                                  <th class="purchase_heading" align="right">
                                    <p class="f-fallback">Amount</p>
                                  </th>
                                </tr>';
                                    
                                $total_price=0;
                                foreach($getOrderDetails as $list){
									$item_price=$list['qty']*$list['price'];
									$total_price=$total_price+$item_price;
									$html.='<tr>
									  <td width="40%" class="purchase_item"><span class="f-fallback">'.$list['dish'].'('.$list['attribute'].')</span></td>
									  <td width="40%" class="purchase_item"><span class="f-fallback">'.$list['qty'].'</span></td>
									  <td class="align-right" width="20%" class="purchase_item"><span class="f-fallback">'.$item_price.'</span></td>
									</tr>';
                                }
                                $html.='<tr>
                                  <td width="80%" class="purchase_footer" valign="middle" colspan="2">
                                    <p class="f-fallback purchase_total purchase_total--label">Total</p>
                                  </td>
                                  <td width="20%" class="purchase_footer" valign="middle">
                                    <p class="f-fallback purchase_total">'.$total_price.'</p>
                                  </td>
                                </tr>
                              </table>
                            <p>If you have any questions about this invoice, simply reply to this email or reach out to our <a href="{{support_url}}">support team</a> for help.</p>
                            <p>Cheers,
                              <br>The Aggarwal Team</p>                        
                          </div>
                        </td>
                      </tr>
                    </table>
                  </td>
                </tr>
                
              </table>
            </td>
          </tr>
        </table>
      </body>
    </html>';
    return $html;
}


function dateformat($date){
    $str = strtotime($date);
    return date('d-m-y',$str);
}

function getDeliveryBoyNameById($id){
    global $con;
    $data = mysqli_fetch_assoc(mysqli_query($con,"SELECT `name` FROM delivery_boy WHERE id='$id'"));
    return $data['name'];
}

function getwebsetting(){
  global $con;
  $res = mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM web_setting WHERE id='1'"));
  return $res;
}


?>
