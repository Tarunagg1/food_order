<?php
session_start();
include('../function.inc.php');
include('../database.inc.php');
include('../constant.php');
include('pdfvendor/autoload.php');

if(!isset($_SESSION['USER_ID'])){
	redirect('shop');
}
if(isset($_GET['invoiceid'])  && $_GET['invoiceid']>0){
	$id=get_safe_value($_GET['invoiceid']);
    $orderEmail=orderemail($id);
	$mpdf = new \Mpdf\Mpdf();
	$mpdf->WriteHTML($orderEmail);
    $file=time().'.pdf';
    $mpdf->Output($file,'D');
}else{
	redirect('shop');
}

?>