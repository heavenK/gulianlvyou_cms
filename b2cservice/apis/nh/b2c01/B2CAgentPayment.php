<html>
<head>
<title>TrustPay - 支付请求</title>
<meta http-equiv='Content-Type' content='text/html; charset=GB2312'>
</head>
<body>
<?php 

	require("api.php");

	$add = "http://www.dlgulian.com:8080/axis/services/B2CWarpper?wsdl";
	$tOrderNo = $_POST['OrderNo'];
	$tExpiredDate = $_POST['ExpiredDate'];
	$tRequestDate = $_POST['RequestDate'];
	$tRequestTime = $_POST['RequestTime'];
	$tCurrency = $_POST['Currency'];
	$tAmount = $_POST['Amount'];
	$tProductId = $_POST['ProductId'];
	$tProductName = iconv("GBK","UTF-8",$_POST['ProductName']);
	$tQuantity = $_POST['Quantity'];
	$tCertificateNo = $_POST['CertificateNo'];
	$tAgentSignNo = $_POST['AgentSignNo'];
	
	$b2cagentPaymentRequest = new B2CAgentPaymentRequest($tOrderNo,$tExpiredDate,$tRequestDate,$tRequestTime,$tCurrency,$tAmount,$tProductId,$tProductName,$tQuantity,$tCertificateNo,$tAgentSignNo);
	$b2cagentPayment = new B2CAgentPayment($add,$b2cagentPaymentRequest);

	$b2cagentPaymentResult = $b2cagentPayment->invoke();

	//$b2cagentPayment->showResult();
	//显示结果
	if($b2cagentPaymentResult->isSucess==TRUE)
	{
		print("<br>Success!!!"."</br>");
	}
	else
	{
		print("<br>Failed!!!"."</br>");
		print("<br>return code:".$b2cagentPaymentResult->returnCode."</br>"); 
		print("<br>Error Message:".$b2cagentPaymentResult->ErrorMessage."</br>");
	}


?>
 
</body>
</html>