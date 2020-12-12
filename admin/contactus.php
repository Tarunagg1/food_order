<?php
include 'top.php';
$sql = "SELECT * FROM contactus ORDER BY id";
$res = mysqli_query($con,$sql);
if(isset($_GET['type']) && $_GET['type'] !== '' && isset($_GET['id']) && $_GET['id'] > 0 && $_GET['id'] !== ''){
    $type = $_GET['type'];
    $id = $_GET['id'];
    if($type == 'del'){
        mysqli_query($con,"DELETE FROM contactus WHERE id='$id'");
        redirect('contactus.php');
    }
}
?>
  <div class="card">
            <div class="card-body">
              <h4 class="mb-5">View All quries</h4>
              <div class="row">
                <div class="col-12">
                  <div class="table-responsive">
                    <table id="order-listing" class="table">
                      <thead>
                        <tr>
                            <th width="10%">S No</th>
                            <th width="15%">Name</th>
                            <th width="15%">Email</th>
                            <th width="20%">Subject</th>
                            <th width="20%">Message</th>
                            <th width="10%">Date</th>
                            <th width="10%">Actions</th>
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
                            <td><?php echo $row['subject']; ?></td>
                            <td><?php echo $row['message']; ?></td>
                            <td><?php echo $row['added_on']; ?></td>
                            <td>
                               <a href="?id=<?php echo $row['id']?>&type=del"><label class="badge badge-danger">Delete</label></a> &nbsp;
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