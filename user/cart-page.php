<?php
include('header.php');

?>
        
		<div class="cart-main-area pt-95 pb-100">
            <div class="container">
                <h3 class="page-title">Your cart items</h3>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                        <form method="post" action="cart-page">
                         <?php if(count($cartarr) > 0){ ?>
                            <div class="table-content table-responsive">
                                <table>
                                    <thead>
                                        <tr>
                                            <th>Image</th>
                                            <th>Product Name</th>
                                            <th>Until Price</th>
                                            <th>Qty</th>
                                            <th>Subtotal</th>
                                            <th>action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                       <?php   $cartarr = getuserfullcart();
                                              foreach($cartarr as $key => $list){ ?>
                                       
                                        <tr>
                                            <td class="product-thumbnail">
                                                <a href="#"><img style="width: 75%;" src="<?php echo SITE_DISP_IMAGE.$list['image'] ?>" alt=""></a>
                                            </td>

                                            <td class="product-name"><a href="#"><?php echo $list['dishname'] ?></a></td>
                                            <td class="product-price-cart"><span class="amount">Rs <?php echo $list['price']  ?></span></td>
                                            <td class="product-quantity">
                                                <div class="cart-plus-minus">
                                                    <input class="cart-plus-minus-box" type="text" name="qty[<?php echo $key; ?>][]" value="<?php echo $list['qty'] ?>">
                                                </div>
                                            </td>
                                            <td class="product-subtotal">Rs <?php echo $list['price']*$list['qty']  ?></td>
                                            <td class="product-remove">
                                                <a href="#"><i class="fa fa-pencil"></i></a>
                                                <a href="#" onclick="delete_cart('<?php echo $key; ?>','load')"><i class="fa fa-times"></i></a>
                                           </td>
                                        </tr>
                                       <?php } ?>
                                    </tbody>
                                </table>
                            <?php }else{
                                echo "<h2>Cart Is Empty</h2>";
                            } ?>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="cart-shiping-update-wrapper">
                                        <div class="cart-shiping-update">
                                            <a href="shop">Continue Shopping</a>
                                        </div>
                                        <div class="cart-clear">
                                         <!-- <input type="submit" name="updatecart" value="Update Shopping Cart" > -->
                                         <button type="submit" name="updatecart">Update Shopping Cart</button>
                                            <a onclick="gotocheckout()"  href="checkout" >Checkout Cart</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        
<?php
    include('footer.php');
?>