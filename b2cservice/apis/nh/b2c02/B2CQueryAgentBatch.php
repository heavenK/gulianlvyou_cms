<html>
<head>
<title>TrustPay - ί�пۿ�ǩԼ�����ѯ</title>
<meta http-equiv='Content-Type' content='text/html; charset=GB2312'>
</head>
<body>
<?php 
require("api.php");

	$add = "http://www.dlgulian.com:8080/axis02/services/B2CWarpper?wsdl";
    //1��ȡ��ί�пۿ�ǩԼ�����ѯ����Ҫ����Ϣ

  	$tBatchNo = $_POST['BatchNo'];	
  	$tBatchDate = $_POST['BatchDate'];
  
//2��ί�пۿ�ǩԼ�����ѯ�������
$b2cqueryAgentBatchRequest = new B2CQueryAgentBatchRequest($tBatchNo,$tBatchDate);
$b2cqueryAgentBatch = new B2CQueryAgentBatch($add,$b2cqueryAgentBatchRequest);
$b2cqueryAgentBatchResult = $b2cqueryAgentBatch->invoke();
	
	//��ʾ���
	if($b2cqueryAgentBatchResult->isSucess==TRUE)
	{ 
 		
        print("<br>Sucess!!!"."</br>");
		print "<br>BatchNo:".$b2cqueryAgentBatchResult->b2cQueryAgentBatchDetail->BatchNo."</br>";
		print "<br>BatchDate:".$b2cqueryAgentBatchResult->b2cQueryAgentBatchDetail->BatchDate."</br>";
		print "<br>BatchTime:".$b2cqueryAgentBatchResult->b2cQueryAgentBatchDetail->BatchTime."</br>";
		print "<br>AgentAmount:".$b2cqueryAgentBatchResult->b2cQueryAgentBatchDetail->AgentAmount."</br>";
		print "<br>AgentCount:".$b2cqueryAgentBatchResult->b2cQueryAgentBatchDetail->AgentCount."</br>";
		print "<br>BatchStatus:".$b2cqueryAgentBatchResult->b2cQueryAgentBatchDetail->BatchStatus."</br>";
		print "<br>BatchStatusZH:".$b2cqueryAgentBatchResult->b2cQueryAgentBatchDetail->BatchStatusZH."</br>";		
		$count = count($b2cqueryAgentBatchResult->b2cQueryAgentBatchDetail->agentBatchDetails);

		for ($i = 0; $i < $count; $i++) 
		{	
			$item = $b2cqueryAgentBatchResult->b2cQueryAgentBatchDetail->agentBatchDetails[$i];
			if($item)
			{		print "<br>������ϸ��</br>";
			    print "<br>OrderNo:".$item->OrderNo."</br>";
			    print "<br>OrderAmount:".$item->OrderAmount."</br>";
			    print "<br>CertificateNo:".$item->CertificateNo."</br>";
			    print "<br>ContractID:".$item->ContractID."</br>";
			    print "<br>ProductID:".$item->ProductID."</br>";
			    print "<br>ProductName:".$item->ProductName."</br>";
			    print "<br>ProductNum:".$item->ProductNum."</br>";
			    print "<br>OrderStatus:".$item->OrderStatus."</br>";	
	            print "<br>OrderStatusZH:".$item->OrderStatusZH."</br>";	
	        }					
		}
		
 	}
	else
	{
		print("<br>Failed!!!"."</br>");
		print("<br>return code:".$b2cqueryAgentBatchResult->returnCode."</br>"); 
		print("<br>Error Message:".$b2cqueryAgentBatchResult->ErrorMessage."</br>");
	}
?>
 
</body>
</html>