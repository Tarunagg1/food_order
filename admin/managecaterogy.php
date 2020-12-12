<?php
include 'top.php';
$msg = ""; $ordnum = ""; $catname = "";
$id = '';

if(isset($_GET['id']) && $_GET['id'] !== "" && $_GET['id'] > 0 && isset($_GET['type']) && $_GET['type'] !== ''){
  $id = $_GET['id'];
  $data = mysqli_query($con,"SELECT * FROM category WHERE id='$id' LIMIT 1");
  if(mysqli_num_rows($data) > 0){
      $row = mysqli_fetch_assoc($data);
      $catname = $row['category'];
      $ordnum = $row['order_number'];
  }else{
    $msg = "Data not Exist";
  }
}

if(isset($_POST['addcat'])){
    $catname = get_safe_value($_POST['Category']);
    $ordnum = get_safe_value($_POST['orderno']);
    if($id == ''){
      $qu = "SELECT * FROM category WHERE category='$catname'";
    }else{
      $qu = "SELECT * FROM category WHERE category='$catname' AND id != '$id'";
    }
    if(mysqli_num_rows(mysqli_query($con,$qu)) > 0){
        $msg = "Category allready exists";    
    }else {
      if($id == ''){
          $q = "INSERT INTO `category`(`category`, `order_number`, `status`) VALUES ('$catname','$ordnum','1')";
        }else{
          $q = "UPDATE `category` SET `category`= '$catname',`order_number`='$ordnum' WHERE id='$id'";
        }
        mysqli_query($con,$q);
        redirect('category.php');
    }
}

?>

<div class="row">
		    <h1 class="card-title ml10 ml15">Add Category</h1>
            <div class="col-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-bo)dy">
                  <form method="post" class="forms-sample">
                    <div class="form-group">
                      <label for="Category">Enter Category</label>
                      <input type="text" class="form-control" id="Category"name="Category" value="<?php echo $catname; ?>"  class="Category" placeholder="Enter Category" required>
                    </div>
                    <div class="form-group">
                      <label for="exampleInputName1">Order No</label>
                      <input type="number" class="form-control" id="orderno" name="orderno" value="<?php echo $ordnum; ?>" class="orderno" placeholder="Order number" required>
                    </div>
                   
                    <button type="submit" name="addcat" class="btn btn-primary mr-2">Submit</button>
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
include 'footer.php';
?>