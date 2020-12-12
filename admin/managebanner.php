<?php
include 'top.php';
$msg = "";	$heading = ""; $sub_heading = ""; $link = ""; $banner_order=""; $image = "";
$imagestatus = "required";
$id = '';
$imageerr = "";
if(isset($_GET['id']) && $_GET['id'] !== "" && $_GET['id'] > 0 && isset($_GET['type']) && $_GET['type'] !== ''){
  $id = $_GET['id'];
  $data = mysqli_query($con,"SELECT * FROM banner WHERE id='$id' LIMIT 1");
  if(mysqli_num_rows($data) > 0){
    $imagestatus = '';
      $row = mysqli_fetch_assoc($data);
      $image = $row['image'];
      $heading = $row['heading'];
      $sub_heading = $row['sub_heading'];
      $link = $row['link'];
      $banner_order = $row['banner_order'];
  }else{
    $msg = "Data not Exist";
  }
}

if(isset($_POST['addbanner'])){
    $heading = get_safe_value($_POST['heading']);
    $sub_heading = get_safe_value($_POST['sub_heading']);
    $link = get_safe_value($_POST['link']);
    $banner_order = get_safe_value($_POST['banner_order']);
    $image = "kjh";

    if($id == ''){
      $qu = "SELECT * FROM banner WHERE banner_order='$banner_order'";
    }else{
      $qu = "SELECT * FROM banner WHERE banner_order='$banner_order' AND id != '$id'";
    }
    if(mysqli_num_rows(mysqli_query($con,$qu)) > 0){
        $msg = "Banner order allready exists";    
    }else {
      if($id == ''){
        $type = $_FILES['image']['type'];
        if($type != 'image/jpeg' && $type != 'image/png'){
            $msg = "invalid image format";
          }else{
                $image = rand(111111111,999999999).'_'.$_FILES['image']['name'];
                move_uploaded_file($_FILES['image']['tmp_name'],PATH_BANNER_IMAGE.$image);
                $q = "INSERT INTO `banner`(`image`, `heading`, `sub_heading`, `link`, `banner_order`) VALUES ('$image','$heading','$sub_heading','$link','$banner_order')";          
                mysqli_query($con,$q);
                redirect('banner.php');
          }
        }else{
            if($_FILES['image']['type'] == ''){
                $q = "UPDATE `banner` SET `heading`='$heading',`sub_heading`='$sub_heading',`link`='$link',`banner_order`='$banner_order' WHERE `id`='$id'";
              }else{
                $type = $_FILES['image']['type'];
                if($type != 'image/jpeg' && $type != 'image/png'){
                    $msg = "invalid image format";
                  }else{
                    $image = rand(111111111,999999999).'_'.$_FILES['image']['name'];
                    move_uploaded_file($_FILES['image']['tmp_name'],PATH_BANNER_IMAGE.$image);
                    $oldimgrow = mysqli_fetch_assoc(mysqli_query($con,"SELECT `image` FROM banner WHERE `id`='$id'"));
                    $rmimg = $oldimgrow['image'];
                    $abspath = "C:/wamp64/www/foodorder/media/banner/";
                    $abspath.=$rmimg;
                    unlink($abspath);
                    $q = "UPDATE `banner` SET `image`='$image' ,`heading`='$heading',`sub_heading`='$sub_heading',`link`='$link',`banner_order`='$banner_order' WHERE `id`='$id'";
                  }
              }
              mysqli_query($con,$q);
              redirect('banner.php');
        }
    }
}

?>


<div class="row">
        <h1 class="card-title ml10"><?php echo ($id == "") ? "Add banner" : "Update banner" ?> </h1>
            <div class="col-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <form method="post" class="forms-sample" enctype='multipart/form-data'>
                    
                    <div class="form-group">
                      <label for="exampleInputName1">Heading</label>
                      <input type="text" class="form-control" value="<?php echo $heading; ?>" id="heading" name="heading" placeholder="heading" required>
                    </div>
        
                    
                    <div class="form-group">
                      <label for="exampleInputPassword4">Sub Heading</label>
                      <input type="text" class="form-control" id="sub_heading" value="<?php echo $sub_heading; ?>" name="sub_heading" placeholder="sub heading">
                    </div>

                    <div class="form-group">
                      <label for="cart_min_value">link</label>
                      <input type="text" class="form-control" id="link" value="<?php echo $link; ?>" name="link" placeholder="link" required>
                    </div>

                    <div class="form-group">
                      <label for="expired_on">Banner order</label>
                      <input type="number" class="form-control" id="banner_order" value="<?php echo $banner_order; ?>" name="banner_order" placeholder="banner order">
                    </div>
 
                    <div class="form-group">
                      <label for="expired_on">Image</label>
                      <input type="file" class="form-control" id="image" value="<?php echo $image; ?>" name="image" placeholder="image" <?php echo $imagestatus; ?>>
                    </div>
 
                    <button type="submit" name="addbanner" class="btn btn-primary mr-2">Submit</button>
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