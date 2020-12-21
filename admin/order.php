<?php
include 'top.php';
$sql = "SELECT order_master.*,order_status.order_status as order_status_str FROM order_master,order_status WHERE order_master.order_status = order_status.id ORDER BY order_master.id DESC";
// $sql = "SELECT * FROM order_master ORDER BY id DESC";
$res = mysqli_query($con,$sql);

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
                            <th width="10%">Order id</th>
                            <th width="15%">Name/Email/Mobile</th>
                            <th width="15%">Address / Zipcode</th>
                            <th width="10%">Price</th>
                            <th width="10%">Payment status</th>
                            <th width="10%">Order Status</th>
                            <th width="15%">Added on</th>
                            <th width="15%">Details</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php
                            if(mysqli_num_rows($res) > 0){
                                while ($row = mysqli_fetch_assoc($res)) {
                                ?>
                            <tr>
                            <td>
                                  <a href="order_detail?id=<?php echo $row['id'];  ?>"><div class="odetail">
                                      <?php echo $row['id']; ?>
                                  </div> </a>
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
                             <?php
                              echo $row['total_price'] . "/". $row['payment_type']; ?></td>

                            <td><p class="paymentstatus <?php echo 'orderstatus'.$row['payment_status']; ?>"><?php echo ucfirst($row['payment_status']); ?></p></td>
                            <td><?php echo $row['order_status_str']; ?></td>
                            <td><?php echo $row['added_on']; ?></td>

                            <td>
                                <table style="border:1px solid black;">
                                <tr>
                                    <td>Dish</td>
                                    <td>Attribute</td>
                                    <td>Price</td>
                                    <td>Qty</td>
                                </tr>
                                <?php  
                                    $item = getorderdetailsbyid($row['id']);
                                    foreach ($item as $value) { ?>
                                        <tr>
                                            <td><?php echo $value['dish'] ?></td>
                                            <td><?php echo $value['attribute'] ?></td>
                                            <td><?php echo $value['price'] ?></td>
                                            <td><?php echo $value['qty'] ?></td>
                                        </tr>
                                    <?php  }  ?>
                                </table>
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