<?php
include 'top.php';
$sql = "SELECT * FROM user ORDER BY id DESC";
$res = mysqli_query($con,$sql);
if(isset($_GET['type']) && $_GET['type'] !== '' && isset($_GET['id']) && $_GET['id'] > 0 && $_GET['id'] !== ''){
    $type = $_GET['type'];
    $id = $_GET['id'];
    if($type == 'active' || $type == 'deactive'){
        $status = 1;
        if($type == 'deactive'){
          $status = 0;
        }
        mysqli_query($con,"UPDATE user SET `status`='$status' WHERE id='$id'");
        redirect('displayuser.php');
    }

}
?>
  <div class="card">
            <div class="card-body">
              <h4 class="mb-5">Category Master</h4>
              <a href="managecaterogy.php" class="btn btn-lights0"> Add category</a>
              <div class="row">
                <div class="col-12">
                  <div class="table-responsive">
                    <table id="order-listing" class="table">
                      <thead>
                        <tr>
                            <th width="10%">S No</th>
                            <th width="20%">Name</th>
                            <th width="20%">Email</th>
                            <th width="20%">Mobile</th>
                            <th width="15%">Added on</th>
                            <th width="15%">Actions</th>
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
                            <td><?php echo $row['name']; ?></td>
                            <td><?php echo $row['email']; ?></td>
                            <td><?php echo $row['mobile']; ?></td>  
                            <td><?php $datestr = strtotime($row['added_on']); echo date('d-m-y',$datestr) ; ?></td>                         
                            <td>
                               <a href="?id=<?php echo $row['id']?>&type=view"><label class="badge badge-info">View</label></a> &nbsp;
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