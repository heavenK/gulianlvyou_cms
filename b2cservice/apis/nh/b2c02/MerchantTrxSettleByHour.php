<html>
<head>
<title>TrustPay - 下载指定时间段交易对账单</title>
<meta http-equiv='Content-Type' content='text/html; charset=GB2312'>
</head>
<body>
<?php 
	require("api.php");

	$add = "http://www.dlgulian.com:8080/axis02/services/B2CWarpper?wsdl";
	//$add = "http://187.61.1.5:8080/axis/services/B2CWarpper?wsdl";
	
	$tSettleDate = $_POST['SettleDate'];
	$tSettleStartHour = $_POST['SettleStartHour'];
	$tSettleEndHour = $_POST['SettleEndHour'];
	//$merchantTrxSettleByhour = new MerchantTrxSettleByhour($add,$tSettleDate,$tSettleStartHour,$tSettleEndHour);
	$merchantTrxSettleByHourRequest = new MerchantTrxSettleByHourRequest($tSettleDate,$tSettleStartHour,$tSettleEndHour);
	$merchantTrxSettleByhour = new MerchantTrxSettleByhour($add,$merchantTrxSettleByHourRequest);
	$merchantTrxSettleByHourResult = $merchantTrxSettleByhour->invoke();
	//$merchantTrxSettleByhour->showResult();
	
	//显示结果
	if($merchantTrxSettleByHourResult->isSucess==TRUE)
	{
		print("<br>Sucess!!!"."</br>");
		print "<br>SettleDate:".$merchantTrxSettleByHourResult->merchantTrxSettleByHourDetail->SettleDate."</br>";
		print "<br>SettleType:".$merchantTrxSettleByHourResult->merchantTrxSettleByHourDetail->SettleType."</br>";
		print "<br>NumOfPayments:".$merchantTrxSettleByHourResult->merchantTrxSettleByHourDetail->NumOfPayments."</br>";
		print "<br>SumOfPayAmount:".$merchantTrxSettleByHourResult->merchantTrxSettleByHourDetail->SumOfPayAmount."</br>";
		print "<br>NumOfRefunds:".$merchantTrxSettleByHourResult->merchantTrxSettleByHourDetail->NumOfRefunds."</br>";
		print "<br>SumOfRefundAmount:".$merchantTrxSettleByHourResult->merchantTrxSettleByHourDetail->SumOfRefundAmount."</br>";	
		$count = count($merchantTrxSettleByHourResult->merchantTrxSettleByHourDetail->settleItems);
		print "<br>订单明细：</br>";
		for ($i = 0; $i < $count; $i++) 
		{	
			$item = $merchantTrxSettleByHourResult->merchantTrxSettleByHourDetail->settleItems[$i];
			print "<br>$i:".$item."</br>";
		}
	}
	else
	{
		print("<br>Failed!!!"."</br>");
		print("<br>return code:".$merchantTrxSettleByHourResult->returnCode."</br>"); 
		print("<br>Error Message:".$merchantTrxSettleByHourResult->ErrorMessage."</br>");
	}


?>
</body>
</html>