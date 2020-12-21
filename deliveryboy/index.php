<?php
include 'top.php';

if(!isset($_SESSION['IS_DELIVERY_USER'])){
  redirect('login');
}

$q = "SELECT order_master.*,order_status.order_status as order_status_str FROM order_master,order_status WHERE order_master.order_status = order_status.id AND order_master.delivery_boy_id='$dbid' AND order_master.order_status NOT IN  ('0','4')  ORDER BY order_master.id DESC";
$odata = mysqli_query($con,$q);

if(isset($_GET['type']) && $_GET['type'] !== '' && isset($_GET['id']) && $_GET['id'] > 0 && $_GET['id'] !== ''){
    $type = get_safe_value($_GET['type']);
    $id = get_safe_value($_GET['id']);
    if($type == 'updatedstatus'){
      $date = date('Y-m-d h:i:s');
        mysqli_query($con,"UPDATE order_master SET order_status='4',deleverd_on='$date' WHERE id='$id' AND delivery_boy_id='$dbid'");
        redirect('index');
    }
}

?>
  <div class="card">
            <div class="card-body">
              <h4 class="mb-5">Order Status</h4>
              <div class="row">
                <div class="col-12">
                  <div class="table-responsive">
                  <table id="order-listing" class="table">
                      <thead>
                        <tr>
                           <th width="5%">Sno</th>
                            <th width="10%">Order id</th>
                            <th width="15%">Name/Email/Mobile</th>
                            <th width="15%">Address / Zipcode</th>
                            <th width="10%">Price</th>
                            <th width="10%">Payment status</th>
                            <th width="10%">Order Status</th>
                            <th width="15%">Added on</th>
                            <th width="15%">Asign on</th>
                            <th width="5%">Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php
                            $sno = 0;
                            if(mysqli_num_rows($odata) > 0){
                                while ($row = mysqli_fetch_assoc($odata)) {
                                  $sno++;
                                ?>
                            <tr>
                            <td><?php echo $sno; ?></td>
                            <td>
                                  <div class="odetail"><?php echo $row['id']; ?> </div>
                            </td>
                            <td>
                               <p> <?php echo $row['name'] .'/'; ?></p>
                                <p><?php echo $row['email'] .'/'; ?></p>
                                <p><?php echo $row['mobile']; ?></p>
                            </td>
                            <td>
                                <p><?php echo $row['address'].'/'; ?></p>
                                <p><?php echo $row['zip']; ?></p>
                            </td>
                            <td>
                                <p><?php echo $row['total_price'].'/'; ?></p>
                                <p><?php echo $row['payment_type']; ?></p>
                            </td>

                            <td><p class="paymentstatus <?php echo 'orderstatus'.$row['payment_status']; ?>"><?php echo ucfirst($row['payment_status']); ?></p></td>
                            <td><?php echo $row['order_status_str']; ?></td>
                            <td><?php echo $row['added_on']; ?></td>
                            <td><?php echo $row['Asign_on_boy']; ?></td>
                            <td>
                               <a href="index.php?id=<?php echo $row['id']?>&type=updatedstatus"><label class="badge badge-success">Deleverd</label></a> &nbsp;
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