<html>
<head>
<title>TrustPay - 批量退款查询</title>
<meta http-equiv='Content-Type' content='text/html; charset=GB2312'>
</head>
<body>
<?php 
	require("api.php");

	$add = "http://www.gulianlvyou.com:8080/axis/services/B2CWarpper?wsdl";
	//$add = "http://187.61.1.5:8080/axis/services/B2CWarpper?wsdl";

	$tSerialNumber = $_POST['SerialNumber'];
	//$merchantQueryBatch = new MerchantQueryBatch($add,$tSerialNumber);
	$merchantQueryBatchRequest = new MerchantQueryBatchRequest($tSerialNumber);
	$merchantQueryBatch = new MerchantQueryBatch($add,$merchantQueryBatchRequest);
	$merchantQueryBatchResult = $merchantQueryBatch->invoke();	
	//$merchantQueryBatch->showResult();
	//显示结果
	if($merchantQueryBatchResult->isSucess==TRUE)
	{
		print("<br>Sucess!!!"."</br>");
		print "<br>SerialNumber:".$merchantQueryBatchResult->merchantQueryBatchResDetail->SerialNumber."</br>";
		print "<br>RefundAmount:".$merchantQueryBatchResult->merchantQueryBatchResDetail->RefundAmount."</br>";
		print "<br>RefundCount:".$merchantQueryBatchResult->merchantQueryBatchResDetail->RefundCount."</br>";
		print "<br>BatchStatus:".$merchantQueryBatchResult->merchantQueryBatchResDetail->BatchStatus."</br>";	
		$count = count($merchantQueryBatchResult->merchantQueryBatchResDetail->batchItems);
		print "<br>批量退款查询明细：</br>";
		for ($i = 0; $i < $count; $i++) 
		{	
			$item = $merchantQueryBatchResult->merchantQueryBatchResDetail->batchItems[$i];
			print "<br>OrderNo:".$item->OrderNo."</br>";
			print "<br>RefundAmount:".$item->RefundAmount."</br>";
			print "<br>OrderStatus:".$item->OrderStatus."</br>";
		}
	}
	else
	{
		print("<br>Failed!!!"."</br>");
		print("<br>return code:".$merchantQueryBatchResult->returnCode."</br>"); 
		print("<br>Error Message:".$merchantQueryBatchResult->ErrorMessage."</br>");
	}

?>
</body>
</html>