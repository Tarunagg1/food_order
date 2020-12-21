<?php
include ("header.php");
if(!isset($_SESSION['USER_ID'])){
	redirect('shop');
}
$uid = $_SESSION['USER_ID'];

if(isset($_GET['cancel_id'])){
    $oid = get_safe_value($_GET['cancel_id']);
    $date = date('Y-m-d h:i:s');
    $sql = "UPDATE order_master SET `order_status`='5',`cancel_by`='user',`cancel_at`='$date' WHERE `id`='$oid' AND `order_status`='1' AND `user_id`='$uid'";
    mysqli_query($con,$sql);
    redirect('order-history');    
}

$sql="select order_master.*,order_status.order_status as order_status_str from order_master,order_status where order_master.order_status=order_status.id and order_master.user_id='$uid' order by order_master.id desc";
$res=mysqli_query($con,$sql);
?>

<div class="cart-main-area pt-95 pb-100">
            <div class="container">
                <h3 class="page-title">Order History</h3>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                        <form method="post">
							<div class="table-content table-responsive">
								
                                <table>
                                    <thead>
                                        <tr>
                                            <th title="order no">Order No</th>
                                            <th>Price</th>
                                            <th>Coupen</th>
                                            <th>Address</th>
                                            <th>Zip code</th>
                                            <th>Order time</th>
                                            <th>payment type</th>
                                            <th>Order Status</th>
                                            <th>Payment Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
										<?php if(mysqli_num_rows($res)>0){
										$i=1;
										while($row=mysqli_fetch_assoc($res)){
										?>
										<tr>
                                            <td><a href="orderdetails?id=<?php echo $row['id']; ?>"><div class="uid"><?php echo $row['id']?></div></a>
											<br/>
											<a href="download_invoice?invoiceid=<?php echo $row['id'] ?>"><img width="30px" title="download invoice" src="assets/img/icon-img/pdf.png" /></a>
											</td>
                                            <td><?php echo $row['total_price']?></td>
                                            <td></td>
                                            <td><?php echo $row['address']?></td>
											<td><?php echo $row['zip']?></td>
                                            <td><?php echo $row['added_on']?></td>
											<td><?php echo $row['payment_type']?></td>
											<td>
                                                <?php echo $row['order_status_str']; ?>
                                                <?php if($row['order_status'] == 1){ $oid=$row['id'];  echo "<div><a href='?cancel_id=$oid'>Cancel order</a></div>"; }  ?>
                                            </td>
											<td>
												<div class="paymentstatus orderstatus<?php echo $row['payment_status']?>"><?php echo ucfirst($row['payment_status'])?></div>
											</td>
                                        </tr>
										<?php }} ?>
                                    </tbody>
                                </table>
								
                            </div>
                            
                        </form>
                    </div>
                </div>
            </div>
        </div>

<?php
include("footer.php");
?>