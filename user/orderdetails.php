<?php
include ("header.php");
if(!isset($_SESSION['USER_ID'])){
	redirect('shop');
}

if(isset($_GET['id']) && $_GET['id'] > 0){
	$oid = get_safe_value($_GET['id']);
	$getOrderDetails=getorderbyid($oid);
	if($getOrderDetails[0]['user_id'] != $_SESSION['USER_ID']){
	    redirect('shop');		
	}
}else{
    redirect('shop');
}

?>

<div class="cart-main-area pt-95 pb-100">
            <div class="container">
                <h3 class="page-title">Order Details</h3>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                        <form method="post">
							<div class="table-content table-responsive">
								<table style="border:1px solid #e9e8ef;">
												<tr>
													<th width="30%">Dish</th>
													<th width="20%">Attribute</th>
													<th width="15%">Unit Price</th>
													<th width="5%">Qty</th>
													<th width="15%">total price</th>
													<th width="15%">Ratting</th>
												</tr>
												<?php
												$pp = 0;
												$getOrderDetails=getorderdetailsbyid($oid);
												foreach($getOrderDetails as $list){
													$pp = $pp + $list['qty'] * $list['price'];
													?>
														<tr>
															<td><?php echo $list['dish']?></td>
															<td><?php echo $list['attribute']?></td>
															<td><?php echo $list['price']?></td>
															<td><?php echo $list['qty']?></td>
															<td><?php echo $pp ?></td>
															<td id="ratting<?php echo $list['dish_details_id'] ?>">
																<?php getratting($list['dish_details_id'],$oid); ?>
															</td>
														</tr>
													<?php
												}
												?>
												<tr>
													<td colspan="4"></td>
													<td><strong>Total Price :-</strong></td>
													<td><strong><?php echo $pp; ?></strong></td>
												</tr>
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