<html>
<head>
<title>TrustPay - ί�пۿ�ǩԼ�����ѯ</title>
<meta http-equiv='Content-Type' content='text/html; charset=GB2312'>
</head>
<body>
<?php 
require("api.php");

	$add = "http://www.gulianlvyou.com:8080/axis/services/B2CWarpper?wsdl";
    //1��ȡ��ί�пۿ�ǩԼ�����ѯ����Ҫ����Ϣ

  	$tAgentSignNo = $_POST['AgentSignNo'];
 
//2��ί�пۿ�ǩԼ�����ѯ�������
$b2cagentSignContractQueryRequest = new B2CAgentSignContractQueryRequest($tAgentSignNo);
$b2cagentSignContractQuery = new B2CAgentSignContractQuery($add,$b2cagentSignContractQueryRequest);
$b2cagentSignContractQueryResult = $b2cagentSignContractQuery->invoke();
	
	//��ʾ���
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