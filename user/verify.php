<?php
include('header.php');
$msg = "";
if(isset($_GET['verifyid']) && $_GET['verifyid'] != ''){
    $id = get_safe_value($_GET['verifyid']);
    mysqli_query($con,"UPDATE user SET email_verify='1' WHERE randstr='$id'");
    $msg = "Email id Veryfiy successfully";
    // $res = mysqli_query($con,"SELECT * FROM user WHERE randstr='$id'");
    // if(mysqli_num_rows($res) > 0){
    //     $rr = mysqli_fetch_assoc($res);
    //     $referalcode = $rr['from_referal_code'];
    //     if($referalcode != "NULL" && $referalcode != ''){
    //         $refferuser = mysqli_fetch_assoc(mysqli_query($con,"SELECT id FROM user WHERE refreal_code='$referalcode'"));
    //         $id = $refferuser['id'];
    //         managewallet($id,"50","in","Refferal bounes",'1','shop',"shop_.$id");
    //     }
    // }
    redirect('index');
}else{
    redirect('index');
}
?>
        
		<div class="breadcrumb-area gray-bg">
            <div class="container">
                <div class="breadcrumb-content">
                    <ul>
                        <li><a href="index.html">Home</a></li>
                        <li class="active">Vefify Account </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="contact-area pt-100 pb-100">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="contact-message-wrapper">
                            <h4 class="contact-title">Verify Acccount</h4>
                            <div class="contact-message">
                                <?php
                                         if($msg != ""){ ?>
                                            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                                <strong>Congratulation !</strong> <?php echo $msg; ?>
                                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>    
                                       <?php  }   ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        
        
<?php
include('footer.php');
?>
