<html>
<head>
<title>TrustPay - 查询订单</title>
<meta http-equiv='Content-Type' content='text/html; charset=GB2312'>
</head>
<body>
<?php 
	require("api.php");

	$add = "http://www.dlgulian.com:8080/axis02/services/B2CWarpper?wsdl";
	//$add = "http://187.61.1.5:8080/axis/services/B2CWarpper?wsdl";
	
	$tOrderNo = $_POST['OrderNo'];
	$tQueryType = $_POST['QueryType'];
	//$merchantQueryOrder = new MerchantQueryOrder($add,$tOrderNo,$tQueryType);
	$merchantQueryOrderRequest = new MerchantQueryOrderRequest($tOrderNo,$tQueryType);
	$merchantQueryOrder = new MerchantQueryOrder($add,$merchantQueryOrderRequest);
	$merchantQueryOrderResult = $merchantQueryOrder->invoke();
	//$merchantQueryOrder->showResult();
	//显示结果
	if($merchantQueryOrderResult->isSucess==TRUE)
	{
		print("<br>Sucess!!!"."</br>");
		print "<br>OrderAmount:".$merchantQueryOrderResult->order->OrderAmount."</br>";
		print "<br>OrderDate:".$merchantQueryOrderResult->order->OrderDate."</br>";
		print "<br>OrderDesc:".$merchantQueryOrderResult->order->OrderDesc."</br>";
		print "<br>OrderNo:".$merchantQueryOrderResult->order->OrderNo."</br>";
		print "<br>OrderStatus:".$merchantQueryOrderResult->order->OrderStatus."</br>";
		print "<br>OrderTime:".$merchantQueryOrderResult->order->OrderTime."</br>";
		print "<br>OrderURL:".$merchantQueryOrderResult->order->OrderURL."</br>";		
		print "<br>PayAmount:".$merchantQueryOrderResult->order->PayAmount."</br>";		
		print "<br>RefundAmount:".$merchantQueryOrderResult->order->RefundAmount."</br>";		
		$count = count($merchantQueryOrderResult->order->OrderItems);
		print "<br>订单明细：</br>";
		for ($i = 0; $i < $count; $i++) 
		{	
			$item = $merchantQueryOrderResult->order->OrderItems[$i];
			print "<br>ProductID:".$item->ProductID."</br>";
			print "<br>ProductName:".$item->ProductName."</br>";
			print "<br>Qty:".$item->Qty."</br>";
			print "<br>UnitPrice单位价格:".$item->UnitPrice."</br>";
		}
	}
	else
	{
		print("<br>Failed!!!"."</br>");
		print("<br>return code:".$merchantQueryOrderResult->returnCode."</br>"); 
		print("<br>Error Message:".$merchantQueryOrderResult->ErrorMessage."</br>");
	}


?>
</body>
</html>