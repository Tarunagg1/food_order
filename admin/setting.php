<?php
include 'top.php';
$cart_min_price = '';
$web_min_msg = '';
$website_close = '';
$website_close_msg = '';
$msg = '';
$web_close_arr = array('No','Yes');
$free_wallet_amt = "";
$referal_amt = "";

if(isset($_POST['updatesetting'])){
    $cart_min_price = get_safe_value($_POST['cart_min_price']);
    $cart_min_msg = get_safe_value($_POST['cart_min_msg']);
    $website_close = get_safe_value($_POST['website_close']);
    $website_close_msg = get_safe_value($_POST['website_close_msg']);
    $free_wallet_amt = get_safe_value($_POST['free_wallet_amt']);
    $referal_amt = get_safe_value($_POST['referal_amt']);
    $q = "UPDATE `web_setting` SET `cart_min_price`='$cart_min_price',`cart_min_msg`='$cart_min_msg',`website_close`='$website_close',`website_close_msg`='$website_close_msg',`free_wallet_amt`='$free_wallet_amt',`referal_amt`='$referal_amt' WHERE id='1'";
    $res = mysqli_query($con,$q);
    $msg = 'Setting updated';
}


$data = mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM web_setting WHERE id='1' LIMIT 1"));
$cart_min_price = $data['cart_min_price'];
$cart_min_msg = $data['cart_min_msg'];
$website_close = $data['website_close'];
$website_close_msg = $data['website_close_msg'];
$free_wallet_amt = $data['free_wallet_amt'];
$referal_amt = $data['referal_amt'];


?>


<div class="row">
		    <h1 class="card-title ml10">Manage website setting</h1>
            <div class="col-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <form method="post" class="forms-sample">
                    <div class="form-group">
                      <label for="exampleInputName1">cart min price</label>
                      <input type="number" class="form-control" value="<?php echo $cart_min_price; ?>" id="cart_min_price" name="cart_min_price" placeholder="cart_min_price" required>
                    </div>
                    <div class="form-group">
                      <label for="number">cart min msg</label>
                      <input type="text" class="form-control" value="<?php echo $cart_min_msg; ?>" name="cart_min_msg" id="cart_min_msg" placeholder="cart_min_msg" required>
                    </div>

                    <div class="form-group">
                      <label for="exampleInputName1">website close</label>
                      <select class="form-control" id="website_close" name="website_close" placeholder="website_close" required>
                            <option selected disabled>-----Select Status------</option>
                            <?php 
                                foreach($web_close_arr as $key => $val){
                                    if($website_close == $key){
                                        echo "<option value='$key' selected>$val</option>";
                                    }else{
                                        echo "<option value=$key>$val</option>";
                                    }
                                }
                            ?>
                      </select>
                    </div>
                    <div class="form-group">
                      <label for="number">website close msg</label>
                      <input type="text" class="form-control" value="<?php echo $website_close_msg; ?>" name="website_close_msg" id="website_close_msg" placeholder="website_close_msg" required>
                    </div>  
                    <div class="form-group">
                      <label for="number">Free wallet amount</label>
                      <input type="number" class="form-control" value="<?php echo $free_wallet_amt; ?>" name="free_wallet_amt" id="free_wallet_amt" placeholder="free_wallet_amt" required>
                    </div>  
                    <div class="form-group">
                      <label for="number">Referal wallet amount</label>
                      <input type="number" class="form-control" value="<?php echo $referal_amt; ?>" name="referal_amt" id="referal_amt" placeholder="referal_amt" required>
                    </div>                    
 
                    <button type="submit" name="updatesetting" class="btn btn-primary mr-2">Update</button>
                    <button class="btn btn-light">Cancel</button>
                  </form>
                </div>
              </div>
            </div>
            <?php
            if($msg != ""){ ?>
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <strong>Damm!! </strong><?php echo $msg; ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    </div>    
           <?php } ?>
		 </div>

<?php
include('footer.php');
?>