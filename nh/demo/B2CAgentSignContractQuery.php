<html>
<head>
<title>TrustPay - 委托扣款签约结果查询</title>
<meta http-equiv='Content-Type' content='text/html; charset=GB2312'>
</head>
<body>
<?php 
require("api.php");

	$add = "http://www.gulianlvyou.com:8080/axis/services/B2CWarpper?wsdl";
    //1、取得委托扣款签约结果查询所需要的信息

  	$tAgentSignNo = $_POST['AgentSignNo'];
 
//2、委托扣款签约结果查询请求对象
$b2cagentSignContractQueryRequest = new B2CAgentSignContractQueryRequest($tAgentSignNo);
$b2cagentSignContractQuery = new B2CAgentSignContractQuery($add,$b2cagentSignContractQueryRequest);
$b2cagentSignContractQueryResult = $b2cagentSignContractQuery->invoke();
	
	//显示结果
	if($b2cagentSignContractQueryResult->isSucess==TRUE)
	{ 
  
 		print("<br>yes!!!"."</br>");
		print "<br>AgentSignNo:".$b2cagentSignContractQueryResult->b2cagentSignContractQueryDetail->AgentSignNo."</br>";
		print "<br>MerchantNo:".$b2cagentSignContractQueryResult->b2cagentSignContractQueryDetail->MerchantNo."</br>";
		print "<br>CardNo:".$b2cagentSignContractQueryResult->b2cagentSignContractQueryDetail->CardNo."</br>";
		print "<br>AccountType:".$b2cagentSignContractQueryResult->b2cagentSignContractQueryDetail->AccountType."</br>";
     	print "<br>CertificateNo:".$b2cagentSignContractQueryResult->b2cagentSignContractQueryDetail->CertificateNo."</br>";
		print "<br>CertificateType:".$b2cagentSignContractQueryResult->b2cagentSignContractQueryDetail->CertificateType."</br>";
		print "<br>SignStatus:".$b2cagentSignContractQueryResult->b2cagentSignContractQueryDetail->SignStatus."</br>";	
		print "<br>SignDate:".$b2cagentSignContractQueryResult->b2cagentSignContractQueryDetail->SignDate."</br>";
		print "<br>UnSignDate:".$b2cagentSignContractQueryResult->b2cagentSignContractQueryDetail->UnSignDate."</br>";	
	 
		
 	}
	else
	{
		print("<br>Failed!!!"."</br>");
		print("<br>return code:".$b2cagentSignContractQueryResult->returnCode."</br>"); 
		print("<br>Error Message:".$b2cagentSignContractQueryResult->ErrorMessage."</br>");
	}
?>
 
</body>
</html>