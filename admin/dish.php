<?php
include 'top.php';
$sql = "SELECT dish.*,category.category FROM dish,category WHERE dish.category_id =category.id ORDER BY dish.id";
$res = mysqli_query($con,$sql);
if(isset($_GET['type']) && $_GET['type'] !== '' && isset($_GET['id']) && $_GET['id'] > 0 && $_GET['id'] !== ''){
    $type = $_GET['type'];
    $id = $_GET['id'];
    if($type == 'active' || $type == 'deactive'){
        $status = 1;
        if($type == 'deactive'){
          $status = 0;
        }
        mysqli_query($con,"UPDATE dish SET `status`='$status' WHERE id='$id'");
        redirect('dish.php');
    }

}
?>
  <div class="card">
            <div class="card-body">
              <h4 class="mb-5">Category Master</h4>
              <a href="managedish.php" class="btn btn-lights0"> Add Dish</a>
              <div class="row">
                <div class="col-12">
                  <div class="table-responsive">
                    <table id="order-listing" class="table">
                      <thead>
                        <tr>
                            <th width="10%">S No</th>
                            <th width="15%">Category</th>
                            <th width="10%">Dish</th>
                            <th width="20%">Image</th>  
                            <th width="10%">Type</th>  
                            <th width="20%">Added on</th>
                            <th width="20%">Actions</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php
                            if(mysqli_num_rows($res) > 0){ $i=0; 
                                while ($row = mysqli_fetch_assoc($res)) {
                                    $i++;
                                ?>
                            <tr>
                            <td><?php echo $i; ?></td>
                            <td><?php echo $row['category']; ?></td>
                            <td><?php echo $row['dish']; ?></td>  
                            <td><img src="<?php echo SITE_DISP_IMAGE.'/'.$row['image'];  ?>" alt="not-found" /></td> 
                            <td><?php echo $row['type']; ?></td>   
                            <td><?php $datestr = strtotime($row['added_on']); echo date('d-m-y',$datestr) ; ?></td>                         
                            <td>
                            <a href="managedish.php?id=<?php echo $row['id']?>&type=edit"><label class="badge badge-success">Edit</label></a> &nbsp;
                               <?php
                                  if($row['status'] == 1){
                                    ?>
                                      <a href="?id=<?php echo $row['id']?>&type=deactive"><label class="badge badge-secondary">Active</label></a> &nbsp;
                                    <?php
                                  }else{
                                    ?>
                                        <a href="?id=<?php echo $row['id']?>&type=active"><label class="badge badge-primary">Deactive</label></a> &nbsp;
                                    <?php
                                  }
                              ?>
                            </td>
                        </tr>
                           <?php }}else {
                                echo "<tr><td colspan='5'>No data Found</td></tr>";
                            } 

                        ?>
                      </tbody>
                    </table>
                  </div>
				</div>
<?php
include 'footer.php';
?>