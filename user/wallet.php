<?php
include('header.php');
if(!isset($_SESSION['USER_ID'])){
    redirect('shop');
}
$uid = $_SESSION['USER_ID'];
$tdata = getwallet($uid);
$amtmsg = "";
if(isset($_POST['Addmoney'])){
    $amt = get_safe_value($_POST['amt']);
    if($amt < 50){
        $amtmsg = "Min amount 50";
    }else{
        $html = '<form method="post" action="pgRedirect.php" name="frmpaymt" style="display:none;">
                <input id="ORDER_ID" tabindex="1" maxlength="20" size="20"
                    name="ORDER_ID" autocomplete="off"
                    value="WALLET_'.$uid.'_'.time().'">
                <input id="CUST_ID" tabindex="2" maxlength="12" size="12" name="CUST_ID" autocomplete="off" value="'.$uid.'">
                <input id="INDUSTRY_TYPE_ID" tabindex="4" maxlength="12" size="12" name="INDUSTRY_TYPE_ID" autocomplete="off" value="Retail">                       <td><input id="CHANNEL_ID" tabindex="4" maxlength="12"
                    size="12" name="CHANNEL_ID" autocomplete="off" value="WEB">
            
                <input title="TXN_AMOUNT" tabindex="10"
                    type="text" name="TXN_AMOUNT"
                    value="'.$amt.'">
                <input value="CheckOut" type="submit" onclick="">
            </form>
            <script type="text/javascript">
                document.frmpaymt.submit();
            </script>';
        echo $html;
    }
}

?>
		<div class="cart-main-area pt-50 pb-100">
            <div class="container">
            <div class="row">
                    <div class="col-lg-8 col-md-12 col-sm-12 col-12">
                        <div class="walletmain">
                            <div class="walletbalance">
                                <h2><span>&#8377;</span><?php echo $walletamt; ?></h2>
                                <p>Avilable for Shopping</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-12 col-sm-12 col-12">
                    <form id="addmoneyform" method="post">
                        <div class="addmoney">
                            <p>Enter Amount</p>
                            <input  class="input" type="number" name="amt" min="0"  max="100000" autocomplete="off" placeholder="&#8377; 0">
                            <?php if($amtmsg != ''){ echo "<span id='amterr'>$amtmsg</span>"; } ?>
                            <div class="predefinamount">
                                <p class="pamt">+ &#8377;100</p>
                                <p class="pamt">+ &#8377;200</p>
                                <p class="pamt">+ &#8377;500</p>
                            </div>
                            <input type="submit" class="button" name="Addmoney" value="Add money">
                            </form>
                        </div>
                    </div>
                </div>
                <div class="row mt-5">
                         <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                            <div class="table-content table-responsive">
                                <table>
                                    <thead>
                                        <tr>
                                            <th>Sno</th>
                                            <th>Transaction id</th>
                                            <th>Amount</th>
                                            <th>Message</th>
                                            <th>Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $sno = 0; foreach($tdata as $list) { $sno++; ?>
                                            <tr>
                                                <td><?php echo $sno; ?></td>
                                                <td><?php echo $list['id']; ?></td>
                                                <td><?php echo ($list['type'] == 'in') ? "<p class='in'>+".$list['amt']."</p>" :  "<p class='out'>-".$list['amt']."</p>" ?></td>
                                                <td><?php echo $list['msg']; ?></td>
                                                <td><?php echo $list['added_on']; ?></td>                                
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="row">
                                <div class="col-lg-10">
                                    <div class="cart-shiping-update-wrapper">
                                        <div class="cart-shiping-update">
                                            <a href="shop">Continue Shopping</a>
                                        </div>
                                     
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