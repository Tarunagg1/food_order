<?php
include 'top.php';
$sql = "SELECT * FROM banner ORDER BY banner_order";
$res = mysqli_query($con,$sql);
if(isset($_GET['type']) && $_GET['type'] !== '' && isset($_GET['id']) && $_GET['id'] > 0 && $_GET['id'] !== ''){
    $type = $_GET['type'];
    $id = $_GET['id'];
    if($type == 'del'){
        mysqli_query($con,"DELETE FROM banner WHERE id='$id'");
        redirect('banner');
    }
    if($type == 'active' || $type == 'deactive'){
        $status = 1;
        if($type == 'deactive'){
          $status = 0;
        }
        mysqli_query($con,"UPDATE banner SET `status`='$status' WHERE id='$id'");
        redirect('banner');
    }

}
?>
  <div class="card">
            <div class="card-body">
              <h4 class="mb-5">Category Master</h4>
              <a href="managebanner.php" class="btn btn-lights0"> Add category</a>
              <div class="row">
                <div class="col-12">
                  <div class="table-responsive">
                    <table id="order-listing" class="table">
                      <thead>
                        <tr>
                            <th width="10%">S No</th>
                            <th width="30%">Image</th>
                            <th width="15%">heading</th>
                            <th width="15%">Sub heading</th>
                            <th width="10%">Order</th>
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
                            <td><?php echo $row['image']; ?></td>
                            <td><?php echo $row['heading']; ?></td>
                            <td><?php echo $row['sub_heading']; ?></td>
                            <td><?php echo $row['banner_order']; ?></td>                           
                            <td>
                               <a href="managebanner.php?id=<?php echo $row['id']?>&type=edit"><label class="badge badge-success">Edit</label></a> &nbsp;
                               <a href="?id=<?php echo $row['id']?>&type=del"><label class="badge badge-danger">Delete</label></a> &nbsp;
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