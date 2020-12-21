<?php
include('header.php');
$catdish = "";
$type = "";
$search_str = "";
$cat_dish_arr = array();

if(isset($_GET['cat_dish'])){
    $catdish = get_safe_value($_GET['cat_dish']);
    $cat_dish_arr = array_filter(explode(":",$catdish));
    $cat_dish_str = implode(',',$cat_dish_arr);
}
if(isset($_GET['type'])){
    $type = get_safe_value($_GET['type']);
}
if(isset($_GET['search'])){
    $search_str = get_safe_value($_GET['search']);
}

$arrtype = array("veg","non-veg","both");
?>
<div class="breadcrumb-area gray-bg">
    <div class="container">
        <div class="breadcrumb-content">
            <ul>
                <li><a href="shop.php">Home</a></li>
                <li class="active">Shop Grid Style </li>
            </ul>
        </div>
    </div>
</div>
<div class="shop-page-area pt-100 pb-100">
    <div class="container">
        <div class="row flex-row-reverse">
            <div class="col-lg-9">
                <div class="banner-area pb-30">
                    <div class="typecon">
                        <form class="searchform">
                            <input type="text" name="search" id="search" value="<?php echo $search_str; ?>"
                                class="searchbox" placeholder="Search Items Here">
                            <input class="searchbtn" onclick="setsearch()" type="submit" value="Search">
                        </form>
                        <?php foreach($arrtype as $list){ 
                                       $isselected = ($list == $type)? "checked='checked'" : "";     
                                ?>
                        <span class="dishtypespan"><?php echo $list; ?>:</span> <input class="radiodishtype"
                            type="radio" <?php echo $isselected; ?> value="<?php echo $list; ?>"
                            onclick="setfood('<?php echo $list; ?>')" name="type">
                        <?php } ?>
                    </div>
                </div>
                <div class="banner-area pb-30">
                    <a href="shop.php"><img alt="" src="assets/img/banner/banner-49.jpg"></a>
                </div>

                <div class="grid-list-product-wrapper">
                    <div class="product-grid product-view pb-20">
                        <div class="row">
                            <?php
                                    $catid = 0;
                                    $q = "SELECT * FROM dish WHERE `status`='1'";
                                    if($catdish != ''){
                                        $q .= " and category_id IN ($cat_dish_str)";
                                    }
                                    if($type != ''){
                                        $q .= " and `type`='$type'";
                                    }
                                    if($search_str != ''){
                                        $q .=" AND (dish LIKE '%$search_str%' OR dish_detail LIKE'%$search_str%')";
                                    }
                                    $q .= " ORDER BY id DESC";
                                    $sql = mysqli_query($con,$q);
                                    $count = mysqli_num_rows($sql);
                                    if($count > 0){
                                    while ($dish = mysqli_fetch_assoc($sql)) {
                                ?>
                            <div class="product-width col-xl-4 col-lg-4 col-md-4 col-sm-6 col-12 mb-30">
                                <div class="product-wrapper">
                                    <div class="product-img">
                                        <a href="javascript:void(0)">
                                            <img src="<?php echo SITE_DISP_IMAGE.$dish['image']; ?>" alt="">
                                        </a>
                                    </div>
                                    <div class="product-content" id="dishdetails">
                                        <h4>
                                            <?php
                                                            echo ($dish['type'] == 'veg') ? "<span class='veg'>veg</span>" : "<span class='non-veg'>non-veg</span>"; 
                                                    ?>
                                            <a href="javascript:void(0)"><?php echo $dish['dish']; ?> </a>
                                            <a href="javascript:void(0)"><?php getrattingbydishid($dish['id']); ?> </a>

                                        </h4>
                                        <?php
                                                ?>
                                        <div class="product-price-wrapper">
                                            <?php
                                                    $dist_atr_arr = mysqli_query($con,"SELECT * FROM dish_details WHERE dish_id='".$dish['id']."' ANd `status`='1' ORDER BY price ASC");
                                                    while ($row =  mysqli_fetch_assoc($dist_atr_arr)) {
                                                        echo "<input class='dish_radio' type='radio' value='".$row['id']."' id='radio_".$dish['id']."' name='radio_".$dish['id']."' /> &nbsp;";
                                                        echo $row['attribute'];
                                                        echo "<span class='price'>(".$row['price'].")</span>";
                                                        if(array_key_exists($row['id'],$cartarr)){
                                                            $added_qty = getuserfullcart($row['id']); 
                                                            echo "<span class='allreadyadded' id='allreadyadded_".$dish['id']."' >(Added-$added_qty)</span>";
                                                        }
                                                        echo "&nbsp;&nbsp;&nbsp;<br/>";
                                                    }
                                                ?>
                                            <?php if($webclose == 0){ ?>
                                            <select class="qtydrop" required name="qty"
                                                id="qty<?php echo $dish['id'] ?>">
                                                <option value="0">---Select Qty---</option>
                                                <?php
                                                                 for($i=1; $i<=20; $i++){
                                                                     echo "<option value='$i'>$i</option>";
                                                                 }           
                                                           ?>
                                            </select>
                                            <button onclick="addtocart('<?php echo $dish['id'] ?>','add')"
                                                class="cartbtn"><i class="fas fa-cart-plus"></i></button>
                                            <?php }else{ 
                                                        echo "<strong>$websiteclosemsg</strong>";
                                                    }?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php  }}else{
                                        echo "<h1>No Dish found</h1>";
                                    } ?>
                        </div>
                    </div>

                </div>
            </div>
            <?php
                            $catres = mysqli_query($con,"SELECT * FROM category WHERE `status`='1' ORDER BY order_number");
                    ?>
            <div class="col-lg-3">
                <div class="shop-sidebar-wrapper gray-bg-7 shop-sidebar-mrg">
                    <div class="shop-widget">
                        <h4 class="shop-sidebar-title">Shop By Categories</h4>
                        <div class="shop-catigory">
                            <ul id="faq" class="category_list">
                                <li><a href='shop.php'>View All Product</a></li>
                                <?php
                                            while ($row = mysqli_fetch_assoc($catres)) {
                                                $class="selected";
                                                if($catid == $row['id']){
                                                      $class = "active";  
                                                }
                                                $ischecked = "";
                                                if(in_array($row['id'],$cat_dish_arr)){
                                                       $ischecked = "checked='selected'"; 
                                                }
                                                echo "<li> <input type='checkbox' onclick=setcheckbox('".$row['id']."') $ischecked class='catcheckbox' name='catarr[]' value='".$row['id']."' />".$row['category']."</li>";
                                            }
                                    ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<form method="get" id="frmcatdist">
    <input type="hidden" name="cat_dish" value="<?php echo $catdish; ?>" id="cat_dish" />
    <input type="hidden" name="type" value="<?php echo $type; ?>" id="type" />
    <input type="hidden" name="search_str" value="<?php echo $search_str; ?>" id="search_str" />
</form>
<?php
    include('footer.php');
?>