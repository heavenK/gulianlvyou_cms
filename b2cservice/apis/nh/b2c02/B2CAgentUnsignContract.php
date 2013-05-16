<html>
<head>
<title>TrustPay - 委托扣款解约</title>
<meta http-equiv='Content-Type' content='text/html; charset=GB2312'>
</head>
<body>
<?php 
require("api.php");

	$add = "http://www.dlgulian.com:8080/axis02/services/B2CWarpper?wsdl";
    //1、取得委托扣款解约请求所需要的信息
  	$tOrderNo = $_POST['OrderNo'];
	$tRequestDate = $_POST['RequestDate'];
	$tRequestTime = $_POST['RequestTime'];
	$tCertificateType = iconv("GBK","UTF-8",$_POST['CertificateType']);
	$tCertificateNo = $_POST['CertificateNo'];
	$tAgentSignNo =$_POST['AgentSignNo'];
	$tResultNotifyURL = $_POST['ResultNotifyURL'];
	$tNotifyType = $_POST['NotifyType'];

//2、生成委委托扣款解约请求对象
$b2cagentUnSignContractRequest = new B2CAgentUnSignContractRequest($tOrderNo,$tRequestDate,$tRequestTime,$tCertificateType,$tCertificateNo,$tAgentSignNo,$tResultNotifyURL,$tNotifyType);
$b2cagentUnSignContract = new B2CAgentUnSignContract($add,$b2cagentUnSignContractRequest);
$b2cagentUnSignContractResult = $b2cagentUnSignContract->invoke();
	
	//显示结果
	if($b2cagentUnSignContractResult->isSucess==TRUE)
	{

		$B2CAgentSignContractURL = $b2cagentUnSignContractResult->B2CAgentSignContractURL;
		
		print("<br>Failed!!!".$B2CAgentSignContractURL."</br>");
		
		if($B2CAgentSignContractURL!=null&&$B2CAgentSignContractURL!="")
		{
			echo "<SCRIPT LANGUAGE=JavaScript>";
			echo "location.href='$B2CAgentSignContractURL'";
			echo "</SCRIPT>";
		}
	}
	else
	{
		print("<br>Failed!!!"."</br>");
		print("<br>return code:".$b2cagentUnSignContractResult->returnCode."</br>"); 
		print("<br>Error Message:".$b2cagentUnSignContractResult->ErrorMessage."</br>");
	}
?>
 
</body>
</html>