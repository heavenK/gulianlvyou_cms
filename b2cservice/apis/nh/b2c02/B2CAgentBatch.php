<html>
<head>
<title>TrustPay - 委托扣款批量</title>
<meta http-equiv='Content-Type' content='text/html; charset=GB2312'>
</head>
<body>
<?php 

	 require("api.php");
	$add = "http://www.dlgulian.com:8080/axis02/services/B2CWarpper?wsdl";
    //1、取得委托扣款批量所需要的信息
   	$batchNo = $_POST['BatchNo'];
	$batchDate = $_POST['BatchDate'];
	$agentCount = $_POST['AgentCount'];
	$agentAmount = $_POST['AgentAmount']; 
	$tOrderItems=array();
	print("<br>".$agentCount."</br>");
	$tOrderItems=array();
	for($i=0;$i<$agentCount;$i++)
	{
 		$tOrderItems[]=array($_POST['orderno'][$i],$_POST['orderamount'][$i],$_POST['expireddate'][$i],$_POST['certificateno'][$i], $_POST['contractid'][$i],$_POST['productid'][$i],$_POST['productname'][$i], $_POST['productnum'][$i]);
	}
	//2、委托扣款批量对象
	$b2cagentBatchRequest = new B2CAgentBatchRequest($batchNo,$batchDate,$agentCount,$agentAmount,$tOrderItems);
	$b2cagentBatch = new B2CAgentBatch($add,$b2cagentBatchRequest);
	$b2cagentBatchResult = $b2cagentBatch->invoke();
	//显示结果
	if($b2cagentBatchResult->isSucess==TRUE)
	{ 
			print("<br>Submit Success!!!"."</br>");
	}
	else
	{
		print("<br>Failed!!!"."</br>");
		print("<br>return code:".$b2cagentBatchResult->returnCode."</br>"); 
		print("<br>Error Message:".$b2cagentBatchResult->ErrorMessage."</br>");
	}


?>
 
</body>
</html>