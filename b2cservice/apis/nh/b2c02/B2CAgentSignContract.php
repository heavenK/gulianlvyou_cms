<html>
<head>
<title>TrustPay - 委托扣款签约请求</title>
<meta http-equiv='Content-Type' content='text/html; charset=GB2312'>
</head>
<body>
<?php 
 	require("api.php");

	$add = "http://www.dlgulian.com:8080/axis02/services/B2CWarpper?wsdl";
    //1、取得委托扣款签约请求所需要的信息
  	$tOrderNo = $_POST['OrderNo'];
	$tRequestDate = $_POST['RequestDate'];
	$tRequestTime = $_POST['RequestTime'];
	$tCertificateType = iconv("GBK","UTF-8",$_POST['CertificateType']);
	$tCertificateNo = $_POST['CertificateNo'];
	$tCardType =iconv("GBK","UTF-8",$_POST['CardType']);
	$tResultNotifyURL = $_POST['ResultNotifyURL'];
	$tNotifyType = $_POST['NotifyType'];


//2、生成委托扣款解约请求对象
$b2cagentSignContractRequest = new B2CAgentSignContractRequest($tOrderNo,$tRequestDate,$tRequestTime,$tCertificateType,$tCertificateNo,$tCardType,$tResultNotifyURL,$tNotifyType);
$b2cagentSignContract = new B2CAgentSignContract($add,$b2cagentSignContractRequest);
$b2cagentSignContractResult = $b2cagentSignContract->invoke();
	//显示结果
	if($b2cagentSignContractResult->isSucess==true)
	{

		$B2CAgentSignContractURL = $b2cagentSignContractResult->B2CAgentSignContractURL;
		if($B2CAgentSignContractURL!=null&&$B2CAgentSignContractURL!="")
		{
			echo "<SCRIPT LANGUAGE=JavaScript>";
			echo "location.href='$B2CAgentSignContractURL'";
			echo "</SCRIPT>";
		}
	}
	else
	{
	
		print("<br>Failessadd!!!"."</br>");
		print("<br>return code:".$b2cagentSignContractResult->returnCode."</br>"); 
		print("<br>Error Message:".$b2cagentSignContractResult->ErrorMessage."</br>");
		
				$B2CAgentSignContractURL = $b2cagentSignContractResult->B2CAgentSignContractURL;
			print("<br>Error Message:".$B2CAgentSignContractURL."</br>");		
		 
	}

?>
 

 
</body>
</html>