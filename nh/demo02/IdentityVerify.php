<html>
<head>
<title>TrustPay - 身份验证</title>
<meta http-equiv='Content-Type' content='text/html; charset=GB2312'>
</head>
<body>
<?php 
	require("api.php");

	$add = "http://www.gulianlvyou.com:8080/axis02/services/B2CWarpper?wsdl";
	//$add = "http://187.61.1.5:8080/axis/services/B2CWarpper?wsdl";
	//$add = "http://tongyizhu.s156.eatj.com/axis/services/B2CWarpper?wsdl";

	$tResultNotifyURL = $_POST['ResultNotifyURL'];//改成php自己的页面!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
	$tBankCardNo = $_POST['BankCardNo'];
	$tCertificateType = $_POST['CertificateType'];
	$tCertificateNo = $_POST['CertificateNo'];
	$tRequestDate = $_POST['RequestDate'];
	$tRequestTime = $_POST['RequestTime'];

	//$identityVerify = new IdentityVerify($add,$tResultNotifyURL,$tBankCardNo,$tCertificateType,$tCertificateNo,$tRequestDate,$tRequestTime);
	$identityVerifyRequest = new IdentityVerifyRequest($tResultNotifyURL,$tBankCardNo,$tCertificateType,$tCertificateNo,$tRequestDate,$tRequestTime);
	$identityVerify = new IdentityVerify($add,$identityVerifyRequest);
	$identityVerifyResult = $identityVerify->invoke();
	//$identityVerify->showResult();
	//显示结果
	if($identityVerifyResult->isSucess==TRUE)
	{
		$identityVerifyURL = $identityVerifyResult->identityVerifyURL;
	}
	else
	{
		print("<br>Failed!!!"."</br>");
		print("<br>return code:".$identityVerifyResult->returnCode."</br>"); 
		print("<br>Error Message:".$identityVerifyResult->ErrorMessage."</br>");
	}
	if($identityVerifyURL!=null&&$identityVerifyURL!="")
	{//跳转到身份验证页面
		echo "<SCRIPT LANGUAGE=JavaScript>";
		echo "location.href='$identityVerifyURL'";
		echo "</SCRIPT>";
	}


?>
</body>
</html>