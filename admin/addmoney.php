<?php
include 'top.php';

$id = '';
$msg = '';

if(isset($_GET['id']) && $_GET['id'] !== "" && $_GET['id'] > 0){
  $id = get_safe_value($_GET['id']);
}

if(isset($_POST['addmoney'])){
    $money = get_safe_value($_POST['money']);
    $message = get_safe_value($_POST['message']);
    $txid = "shop_wallet_".$id;
    managewallet($id,$money,'in',$message,'1','shop',$txid);
    redirect('displayuser');
}

?>


<div class="row">
		    <h1 class="card-title ml10">Add Money</h1>
            <div class="col-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <form method="post" class="forms-sample">
                    <div class="form-group">
                      <label for="exampleInputName1">Name</label>
                      <input type="text" class="form-control" id="money" name="money" placeholder="Enter Amount" required>
                    </div>
                    <div class="form-group">
                      <label for="exampleInputName1">Enter message</label>
                      <input type="text" class="form-control" id="money" name="message" placeholder="Enter message" required>
                    </div>
                    <button type="submit" name="addmoney" class="btn btn-primary mr-2">Submit</button>
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