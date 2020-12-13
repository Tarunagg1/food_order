<?php
include ("header.php");
if(!isset($_SESSION['USER_ID'])){
	redirect('shop');
}
$uid = $_SESSION['USER_ID'];
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
                                            <th>Address</th>
                                            <th>Dish</th>
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
                                            <td><?php echo $row['id']?>
											<br/>
											<a href="download_invoice?invoiceid=<?php echo $row['id'] ?>"><img width="30px" title="download invoice" src="assets/img/icon-img/pdf.png" /></a>
											</td>
                                            <td><?php echo $row['total_price']?></td>
                                            <td><?php echo $row['address']?><br/>
											<?php echo $row['zip']?></td>
											<td>
												<table style="border:1px solid #e9e8ef;">
												<tr>
													<th>Dish</th>
													<th>Attribute</th>
													<th>Price</th>
													<th>Qty</th>
												</tr>
												<?php
												$getOrderDetails=getorderdetailsbyid($row['id']);
												foreach($getOrderDetails as $list){
													?>
														<tr>
															<td><?php echo $list['dish']?></td>
															<td><?php echo $list['attribute']?></td>
															<td><?php echo $list['price']?></td>
															<td><?php echo $list['qty']?></td>
														</tr>
													<?php
												}
												?>
												</table>
											</td>
											<td><?php echo $row['order_status_str']?></td>
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