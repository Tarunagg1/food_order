<?php
header("Pragma: no-cache");
header("Cache-Control: no-cache");
header("Expires: 0");

// following files need to be included
require_once("paytm/PaytmKit/lib/config_paytm.php");
require_once("paytm/PaytmKit/lib/encdec_paytm.php");

include('../database.inc.php');
include('../mail_function.php');
include('../function.inc.php');

$paytmChecksum = "";
$paramList = array();
$isValidChecksum = "FALSE";

$paramList = $_POST;
$paytmChecksum = isset($_POST["CHECKSUMHASH"]) ? $_POST["CHECKSUMHASH"] : ""; //Sent by Paytm pg

//Verify all parameters received from Paytm pg to your application. Like MID received from paytm pg is same as your application�s MID, TXN_AMOUNT and ORDER_ID are same as what was sent by you to Paytm PG for initiating transaction etc.
$isValidChecksum = verifychecksum_e($paramList, PAYTM_MERCHANT_KEY, $paytmChecksum); //will return TRUE or FALSE string.


if($isValidChecksum == "TRUE") {
	echo "<b>Checksum matched and following are the transaction details:</b>" . "<br/>";
	if ($_POST["STATUS"] == "TXN_SUCCESS") {
		echo "<b>Transaction status is success</b>" . "<br/>";
		//Process your transaction here as success transaction.
		//Verify amount & order id received from Payment gateway with your application's order id and amount.
		$oid = $_POST['ORDERID'];
		$t = explode("_",$oid);
		$oid = $t[0];
		$userdata = mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM order_master WHERE id='$oid'"));
		$uemail = $userdata['email'];
		$TXNID  = $_POST['TXNID'];
		mysqli_query($con,"UPDATE order_master SET payment_status='Success', payment_id='$TXNID' WHERE id='$oid'");
		$msg = orderemail($oid);
		sendmailuser($uemail,"Your Order Placed Successfully",$msg);
		redirect('shop');
	}
	else {
		echo "<b>Transaction status is failure</b>" . "<br/>";
		$oid = $_POST['ORDERID'];
		$t = $oid.explode('_',$oid);
		$ooid = $t[0];
		$userdata = mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM order_master WHERE id='$ooid'"));
		$uemail = $userdata['email'];
		$TXNID  = $_POST['TXNID'];
		mysqli_query($con,"UPDATE order_master SET payment_status='failure',`order_status`='0',payment_id='$TXNID' WHERE id='$ooid'");
		$msg = orderemail($oid);
		sendmailuser($uemail,"Your Order Placed failure",$msg);
		redirect('shop');
	}

}
else {
	echo "<b>Checksum mismatched.</b>";
	//Process transaction as suspicious.
}

?>