<?php
include 'top.php';
$sql = "SELECT * FROM coupon_code ORDER BY id";
$res = mysqli_query($con,$sql);
if(isset($_GET['type']) && $_GET['type'] !== '' && isset($_GET['id']) && $_GET['id'] > 0 && $_GET['id'] !== ''){
    $type = $_GET['type'];
    $id = $_GET['id'];
    if($type == 'del'){
        mysqli_query($con,"DELETE FROM coupon_code WHERE id='$id'");
        redirect('coupen');
    }
    if($type == 'active' || $type == 'deactive'){
        $status = 1;
        if($type == 'deactive'){
          $status = 0;
        }
        mysqli_query($con,"UPDATE coupon_code SET `status`='$status' WHERE id='$id'");
        redirect('coupen');
    }

}
?>
  <div class="card">
            <div class="card-body">
              <h4 class="mb-5">Category Master</h4>
              <a href="managecoupencode.php" class="btn btn-lights0"> Add Coupen code</a>
              <div class="row">
                <div class="col-12">
                  <div class="table-responsive">
                    <table id="order-listing" class="table">
                      <thead>
                        <tr>
                            <th width="10%">S No</th>
                            <th width="15%">Coupen code</th>
                            <th width="5%">type</th>
                            <th width="10%">coupon_value</th>
                            <th width="10%">cart_min_value</th>
                            <th width="15%">expired_on</th>
                            <th width="10%">added_on</th>
                            <th width="35%">Actions</th>
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
                            <td><?php echo $row['coupon_code']; ?></td>
                            <td><?php echo $row['coupon_type']; ?></td>
                            <td><?php echo $row['coupon_value']; ?></td>
                            <td><?php echo $row['cart_min_value']; ?></td>
                            <td><?php 
                                if($row['expired_on'] == '000-00-00')
                                    echo "NA"; 
                                else
                                    echo $row['expired_on'];
                            ?></td>
                            <td><?php $datestr = strtotime($row['added_on']); echo date('d-m-y',$datestr) ; ?></td>                         
                            <td>
                               <a href="managecoupencode.php?id=<?php echo $row['id']?>&type=edit"><label class="badge badge-success">Edit</label></a> &nbsp;
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