<html>
<head>
<title>TrustPay - 农行网上支付平台-商户接口范例-交易记录批量查询请求</title>
<meta http-equiv='Content-Type' content='text/html; charset=GB2312'>
</head>
<body>
<?php 
	require("api_b2b.php");

	//$add = "http://127.0.0.1:8080/axis/services/B2BWrapper?wsdl";
	$add = "http://www.gulianlvyou.com:8080/axis_b2b/services/B2BWrapper?wsdl";

	$tTrnxStatus = $_POST['TrnxStatus'];
	$tStDate = iconv("GBK","UTF-8",$_POST['StDate']);
	$tEndDate = iconv("GBK","UTF-8",$_POST['EndDate']);

	$merchantQueryTrnxBatchRequest = new MerchantQueryTrnxBatchRequest($tTrnxStatus,$tStDate,$tEndDate);
	$merchantQueryTrnxBatch = new MerchantQueryTrnxBatch($add,$merchantQueryTrnxBatchRequest);
	$merchantQueryTrnxBatchResult = $merchantQueryTrnxBatch->invoke();
	//$merchantFundTransfer->showResult();
	//显示结果
	if($merchantQueryTrnxBatchResult->isSucess==TRUE)
	{
		print("<br>交易记录批量查询请求"."</br>");
		
		$count = count($merchantQueryTrnxBatchResult->merchantQueryTrnxBatchDetail->merchantQueryTrnxBatchItems);
		print("<br>Total:".$count."</br>");
		for ($i = 0; $i < $count; $i++) 
		{	
			print("<br>Items".$i.":</br>");
			print "<br>订单号(MerchantTrnxNo):".$merchantQueryTrnxBatchResult->merchantQueryTrnxBatchDetail->merchantQueryTrnxBatchItems[$i]->MerchantTrnxNo."</br>";
			print "<br>交易时间(TrnxTime):".$merchantQueryTrnxBatchResult->merchantQueryTrnxBatchDetail->merchantQueryTrnxBatchItems[$i]->TrnxTime."</br>";
			print "<br>付款方账户名(PayAccountName):".$merchantQueryTrnxBatchResult->merchantQueryTrnxBatchDetail->merchantQueryTrnxBatchItems[$i]->PayAccountName."</br>";
			print "<br>付款方帐号(PayAccount):".$merchantQueryTrnxBatchResult->merchantQueryTrnxBatchDetail->merchantQueryTrnxBatchItems[$i]->PayAccount."</br>";
			print "<br>交易金额(TrnxAmount):".$merchantQueryTrnxBatchResult->merchantQueryTrnxBatchDetail->merchantQueryTrnxBatchItems[$i]->TrnxAmount."</br>";
			print "<br>交易状态(TrnxStatus):".$merchantQueryTrnxBatchResult->merchantQueryTrnxBatchDetail->merchantQueryTrnxBatchItems[$i]->TrnxStatus."</br>";			
		}
	}
	else
	{
		print("<br>Failed!!!"."</br>");
		print("<br>return code:".$merchantQueryTrnxBatchResult->returnCode."</br>"); 
		print("<br>Error Message:".$merchantQueryTrnxBatchResult->ErrorMessage."</br>");
	}


?>
</body>
</html>