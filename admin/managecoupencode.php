<?php
include 'top.php';
$msg = ""; $coupon_code = ""; $coupon_type = ""; $coupon_value = ""; $cart_min_value=""; $expired_on="";
$id = '';

if(isset($_GET['id']) && $_GET['id'] !== "" && $_GET['id'] > 0 && isset($_GET['type']) && $_GET['type'] !== ''){
  $id = $_GET['id'];
  $data = mysqli_query($con,"SELECT * FROM coupon_code WHERE id='$id' LIMIT 1");
  if(mysqli_num_rows($data) > 0){
      $row = mysqli_fetch_assoc($data);
      $coupon_code = $row['coupon_code'];
      $coupon_type = $row['coupon_type'];
      $coupon_value = $row['coupon_value'];
      $cart_min_value = $row['cart_min_value'];
      $expired_on = $row['expired_on'];
  }else{
    $msg = "Data not Exist";
  }
}

if(isset($_POST['addcoupen'])){
    $coupon_code = get_safe_value($_POST['coupon_code']);
    $coupon_type = get_safe_value($_POST['coupon_type']);
    $coupon_value = get_safe_value($_POST['coupon_value']);
    $cart_min_value = get_safe_value($_POST['cart_min_value']);
    $expired_on = get_safe_value($_POST['expired_on']);

    if($id == ''){
      $qu = "SELECT * FROM coupon_code WHERE coupon_code='$coupon_code'";
    }else{
      $qu = "SELECT * FROM coupon_code WHERE coupon_code='$coupon_code' AND id != '$id'";
    }
    if(mysqli_num_rows(mysqli_query($con,$qu)) > 0){
        $msg = "Coupen Code allready exists";    
    }else {
      if($id == ''){
          $q = "INSERT INTO `coupon_code`(`coupon_code`, `coupon_type`, `coupon_value`, `cart_min_value`, `expired_on`) VALUES ('$coupon_code','$coupon_type','$coupon_value','$cart_min_value','$expired_on')";
        }else{
          $q = "UPDATE `coupon_code` SET `coupon_code`='$coupon_code',`coupon_type`='$coupon_type',`coupon_value`='$coupon_value',`cart_min_value`='$coupon_value',`expired_on`='$expired_on' WHERE `id`='$id'";
        }
        mysqli_query($con,$q);
        redirect('coupen.php');
    }
}

?>


<div class="row">
		    <h1 class="card-title ml10">Basic form elements</h1>
            <div class="col-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <form method="post" class="forms-sample">
                    
                    <div class="form-group">
                      <label for="exampleInputName1">Coupen code</label>
                      <input type="text" class="form-control" value="<?php echo $coupon_code; ?>" id="coupon_code" name="coupon_code" placeholder="coupon_code" required>
                    </div>
                    
                    <div class="form-group">
                      <label for="number">coupon type</label>
                      <select class="form-control" name="coupon_type"  required>
                        <option value="" selected disable>-------Select Type-----</option>
                        <?php
                            $arr = Array('P'=>'Percentage','F'=>'Fixed');
                            foreach ($arr as $key => $value) {
                                if($key == $coupon_type){
                                    echo "<option selected value='".$key."'>".$value."</option>";
                                }else{
                                    echo "<option value='".$key."'>".$value."</option>";
                                }
                            }
                        ?>
                      </select>
                    </div>
                    
                    <div class="form-group">
                      <label for="exampleInputPassword4">coupon value</label>
                      <input type="text" class="form-control" id="coupon_value" value="<?php echo $coupon_value; ?>" name="coupon_value" placeholder="coupon_value">
                    </div>

                    <div class="form-group">
                      <label for="cart_min_value">cart min value</label>
                      <input type="text" class="form-control" id="cart_min_value" value="<?php echo $cart_min_value; ?>" name="cart_min_value" placeholder="cart_min_value">
                    </div>

                    <div class="form-group">
                      <label for="expired_on">expired on</label>
                      <input type="date" class="form-control" id="expired on" value="<?php echo $expired_on; ?>" name="expired_on" placeholder="expired_on">
                    </div>
 
                    <button type="submit" name="addcoupen" class="btn btn-primary mr-2">Submit</button>
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