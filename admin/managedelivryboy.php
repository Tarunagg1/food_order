<?php
include 'top.php';
$msg = ""; $name = ""; $mobile = ""; $password = "";
$id = '';

if(isset($_GET['id']) && $_GET['id'] !== "" && $_GET['id'] > 0 && isset($_GET['type']) && $_GET['type'] !== ''){
  $id = $_GET['id'];
  $data = mysqli_query($con,"SELECT * FROM delivery_boy WHERE id='$id' LIMIT 1");
  if(mysqli_num_rows($data) > 0){
      $row = mysqli_fetch_assoc($data);
      $name = $row['name'];
      $mobile = $row['mobile'];
      $password = $row['password'];
  }else{
    $msg = "Data not Exist";
  }
}

if(isset($_POST['adddelivary'])){
    $name = get_safe_value($_POST['name']);
    $mobile = get_safe_value($_POST['number']);
    $pass = get_safe_value($_POST['password']);
    $encpass = md5($password);
    if($pass == ""){
        $encpass = $password;
    }

    if($id == ''){
      $qu = "SELECT * FROM delivery_boy WHERE mobile='$mobile'";
    }else{
      $qu = "SELECT * FROM delivery_boy WHERE mobile='$mobile' AND id != '$id'";
    }
    if(mysqli_num_rows(mysqli_query($con,$qu)) > 0){
        $msg = "Mobile Number allready exists";    
    }else {
      if($id == ''){
          $q = "INSERT INTO `delivery_boy`(`name`, `mobile`, `password`) VALUES ('$name','$mobile','$$encpass')";
        }else{
          $q = "UPDATE `delivery_boy` SET `name`='$name',`mobile`='$mobile',`password`='$encpass' WHERE id='$id'";
        }
        mysqli_query($con,$q);
        redirect('delivery_boy.php');
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
                      <label for="exampleInputName1">Name</label>
                      <input type="text" class="form-control" value="<?php echo $name; ?>" id="name" name="name" placeholder="Name" required>
                    </div>
                    <div class="form-group">
                      <label for="number">Number</label>
                      <input type="number" class="form-control" value="<?php echo $mobile; ?>" name="number" id="number" placeholder="Email" required>
                    </div>
                    <div class="form-group">
                      <label for="exampleInputPassword4">Password</label>
                      <input type="password" class="form-control" id="password" name="password" placeholder="Password">
                    </div>
 
                    <button type="submit" name="adddelivary" class="btn btn-primary mr-2">Submit</button>
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