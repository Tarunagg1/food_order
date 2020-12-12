<?php
include 'top.php';
$msg = ""; $category_id = ""; $dish = ""; $dish_detail = ""; $image = "";
$imagestatus = "required";
$id = '';
$imageerr = "";
$typedish = "";

if(isset($_GET['id']) && $_GET['id'] !== "" && $_GET['id'] > 0 && isset($_GET['type']) && $_GET['type'] !== ''){
  $id = $_GET['id'];
  $data = mysqli_query($con,"SELECT * FROM dish WHERE id='$id' LIMIT 1");
  if(mysqli_num_rows($data) > 0){
      $row = mysqli_fetch_assoc($data);
      $imagestatus = '';
      $category_id = $row['category_id'];
      $dish = $row['dish'];
      $typedish = $row['type'];
      $dish_detail = $row['dish_detail'];
      $image = $row['image'];
  }else{
    $msg = "Data not Exist";
  }
}

if(isset($_GET['dish_details_id']) && $_GET['dish_details_id'] > 0){
    $iid = $_GET['dish_details_id'];
    mysqli_query($con,"DELETE FROM `dish_details` WHERE id='$iid'");
    redirect("managedish.php?id=".$id."&type=edit");
}

if(isset($_POST['adddish'])){
    $category_id = get_safe_value($_POST['category_id']);
    $dish = get_safe_value($_POST['dish']);
    $typedish = get_safe_value($_POST['type']);
    $dish_detail = get_safe_value($_POST['dish_detail']);
    if($id == ''){
      $qu = "SELECT * FROM dish WHERE dish='$dish'";
    }else{
      $qu = "SELECT * FROM dish WHERE dish='$dish' AND id != '$id'";
    }
    if(mysqli_num_rows(mysqli_query($con,$qu)) > 0){
        $msg = "Dish allready exists";    
    }else {
      $type = $_FILES['image']['type'];
      if($id == ''){
        if($type != 'image/jpeg' && $type != 'image/png'){
          $msg = "invalid image format";
        }else{
          $image = rand(111111111,999999999).'_'.$_FILES['image']['name'];
          move_uploaded_file($_FILES['image']['tmp_name'],PATH_DISH_IMAGE.$image);
          $q = "INSERT INTO `dish`(`category_id`, `dish`, `dish_detail`,`type`, `image`) VALUES ('$category_id','$dish','$dish_detail','$typedish','$image')";
          
          mysqli_query($con,$q);
          $did = mysqli_insert_id($con);

          $attributearr = $_POST['attribute'];
          $pricearr = $_POST['price'];
          foreach ($attributearr as $key => $value) {
            $attr = $value;
            $price = $pricearr[$key];
            mysqli_query($con,"INSERT INTO `dish_details`(`dish_id`, `attribute`, `price`) VALUES ('$did','$attr','$price')");
          }
          // redirect('dish.php');
        }
        }else{
          $image_condition = '';
          if($_FILES['image']['name'] != ''){
            if($type != 'image/jpeg' && $type != 'image/png'){
              $msg = "invalid image format";
              $imageerr = "invalid format";
            }else{
              $image = rand(111111111,999999999).'_'.$_FILES['image']['name'];
              move_uploaded_file($_FILES['image']['tmp_name'],PATH_DISH_IMAGE.$image);
              $image_condition=",image='$image'";
              $oldimgrow = mysqli_fetch_assoc(mysqli_query($con,"SELECT `image` FROM dish WHERE `id`='$id'"));
              $oldimg = $oldimgrow['image'];
              $abspath = "C:/wamp64/www/foodorder/media/dish/";
              $abspath.=$oldimg;
              unlink($abspath);
            }
          }
          if($imageerr == ''){
            $q = "UPDATE `dish` SET `category_id`='$category_id',`dish`='$dish',`type`='$typedish',`dish_detail`='$dish_detail' $image_condition WHERE `id`='$id'";
            mysqli_query($con,$q);

            $attributearr = $_POST['attribute'];
            $pricearr = $_POST['price'];
            $dist_details_id = $_POST['dish_details_id'];
            
            foreach ($attributearr as $key => $value) {
              $attr = $value;
              $price = $pricearr[$key];
              
              if(isset($dist_details_id[$key])){
                $did = $dist_details_id[$key];
                mysqli_query($con,"UPDATE `dish_details` SET `attribute`='$attr',`price`='$price',`type`='$typedish' WHERE id='$did'");
              }else{
                mysqli_query($con,"INSERT INTO `dish_details`(`dish_id`, `attribute`, `price`) VALUES ('$id','$attr','$price')");
              }
            }
            redirect('dish.php');
          }
        }
    }
}

$rescat = mysqli_query($con,"SELECT * FROM category WHERE `status`='1' ORDER BY `category` asc");
$arraytype = array("veg","non-veg")
?>


<div class="row">
		    <h1 class="card-title ml10"><?php echo ($id == "") ? "Add category" : "Update Category" ?> </h1>
            <div class="col-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <form method="post" class="forms-sample" enctype="multipart/form-data">
                    <div class="form-group">
                      <label for="exampleInputName1">Select category</label>
                        <select class="form-control" name="category_id" required>
                            <option disable selected value="">-------Select category-------</option>
                            <?php
                                while ($row = mysqli_fetch_assoc($rescat)) {
                                    if($row['id'] == $category_id){
                                        echo "<option selected value='".$row['id']."'>".$row['category']."</option>";
                                    }else{
                                        echo "<option value='".$row['id']."'>".$row['category']."</option>";
                                    }
                                }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                      <label for="number">Enter dish name</label>
                      <input type="text" class="form-control" value="<?php echo $dish; ?>" name="dish" id="dish" placeholder="dish" required>
                    </div>
                    <div class="form-group">
                      <label for="exampleInputName1">Select Dish Type</label>
                        <select class="form-control" name="type" required>
                            <option disable selected value="">-------Select Dish Type-------</option>
                            <?php
                                foreach($arraytype as $type){
                                  if($typedish == $type){
                                    echo "<option selected value='$type'>$type</option>"; 
                                  }else{
                                    echo "<option value='$type'>$type</option>"; 
                                  }
                                }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                      <label for="exampleInputPassword4">Enter dish detail</label>
                      <textarea class="form-control" rows="10" name="dish_detail" id="dish_detail" placeholder="Enter detaiils"><?php echo $dish_detail; ?> </textarea>
                    </div>
                    <div class="form-group">
                      <label for="number">Upload Image</label>
                      <input type="file" class="form-control"  name="image" id="image" <?php echo $imagestatus; ?> placeholder="image">
                    </div>
                    
                      <div class="form-group"id="dish_box1">
                      <label for="number">Dish Details</label>
                    <?php  if($id == 0) { ?>
                       <div class="row">
                          <div class="col-6">
                             <input type="text" class="form-control"  name="attribute[]" id="attribute1" placeholder="attribute 1" required>
                          </div>
                          <div class="col-6">
                             <input type="text" class="form-control"  name="price[]" id="price1" placeholder="Price 1" required>
                          </div>
                        </div>
                    <?php } else{
                          $dis_attr_row = mysqli_query($con,"SELECT * FROM `dish_details` WHERE dish_id='$id'");
                          $i=1;
                          while($distdetails = mysqli_fetch_assoc($dis_attr_row)){ 
                      ?>
                        <div class="row mt-5">
                          <div class="col-5">
                          <input type="hidden" value="<?php echo $distdetails['id'] ?>" name="dish_details_id[]">

                             <input type="text" class="form-control" value="<?php echo $distdetails['attribute'] ?>" name="attribute[]" id="attribute1" placeholder="attribute 1" required>
                          </div>
                          <div class="col-5">
                             <input type="text" class="form-control" value="<?php echo $distdetails['price'] ?>" name="price[]" id="price1" placeholder="Price 1" required>
                          </div>
                          <?php
                              if($i != 1){ ?>
                                  <div class="col-2">
                                  <button type="button" class="btn btn-danger" onclick="remove_more_new(<?php echo $distdetails['id'] ?>)">Remove</button>
                                  </div>

                             <?php } ?>
                        </div>

                    <?php $i++; } } ?>
                    </div>
                    <button type="submit" name="adddish" class="btn btn-primary mr-2">Submit</button>
                    <button type="button" class="btn btn-secondary" onclick="addmore()">Add Attribute</button>
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
<script>
  let inputcounter = 1;
  function addmore() {
    inputcounter++;
    var dishbox1 = `<div class="row mt-5" id="dish_box${inputcounter}">
                          <div class="col-5">
                             <input type="text" class="form-control"  name="attribute[]" id="attribute${inputcounter}" placeholder="attribute ${inputcounter}" required>
                          </div>
                          <div class="col-5">
                             <input type="text" class="form-control"  name="price[]" id="price${inputcounter}" placeholder="Price ${inputcounter}" required>
                          </div>
                          <div class="col-2">
                          <button type="button" class="btn btn-danger" onclick="remove(${inputcounter})">Remove</button>
                          </div>
                        </div>`;
    $("#dish_box1").append(dishbox1);
  };

  function remove(id){
    $('#dish_box'+id).remove();
  };

  function remove_more_new(id){
      const res = confirm("Are u Sure");
      if(res){
          var cur_path = window.location.href;
          window.location.href = cur_path+"&dish_details_id="+id;
      }
  }
</script>
<?php
include('footer.php');
?>