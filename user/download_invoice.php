<?php
session_start();
include('../function.inc.php');
include('../database.inc.php');
include('../constant.php');
include('pdfvendor/autoload.php');

if(isset($_SESSION['ADMIN_USER'])){
}else{
	if(!isset($_SESSION['USER_ID'])){
		redirect('shop');
	}
}
if(isset($_GET['invoiceid'])  && $_GET['invoiceid']>0){
	$id=get_safe_value($_GET['invoiceid']);

	if(isset($_SESSION['ADMIN_USER'])){
		
	}else{
		$check = mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM order_master WHERE id='$id'"));
		if($check['user_id'] != $_SESSION['USER_ID']){
			redirect('shop');
		}
	}
    $orderEmail=orderemail($id);
	$mpdf = new \Mpdf\Mpdf();
	$mpdf->WriteHTML($orderEmail);
	$file=time().'.pdf';
    $mpdf->Output($file,'D');
}else{
	redirect('shop');
}

?>