<html>
<head>
<title>TrustPay - 直接支付请求</title>
<meta http-equiv='Content-Type' content='text/html; charset=GB2312'>
</head>
<body>
<?php 
	require("api_b2b.php");

	$add = "http://www.gulianlvyou.com:8080/axis_b2b/services/B2BWrapper?wsdl";
	//$add = "http://187.61.1.5:8080/axis/services/B2BWrapper?wsdl";

	$tMerchantTrnxNo = $_POST['MerchantTrnxNo'];
	$tTrnxAmount = $_POST['TrnxAmount'];
	$tTrnxDate = $_POST['TrnxDate'];
	$tTrnxTime = $_POST['TrnxTime'];	
	$tAccountDBNo = $_POST['AccountDBNo'];
	$tAccountDBName = iconv("GBK","UTF-8",$_POST['AccountDBName']);
	$tAccountDBBank = $_POST['AccountDBBank'];
	$tResultNotifyURL = $_POST['ResultNotifyURL'];
	$tMerchantRemarks = iconv("GBK","UTF-8",$_POST['MerchantRemarks']);
	$tTrnxOpr = iconv("GBK","UTF-8",$_POST['TrnxOpr']);
	
	$tTotalCount = $_POST['TotalCount'];
	$tMemoCount = $_POST['MemoCount'];
	
	$tOrderItems=array();
	$tMemoItems=array();
	for($i=0;$i<$tTotalCount;$i++)
	{
		$tOrderItems[]=array(iconv("GBK","UTF-8",$_POST['productid'][$i]), iconv("GBK","UTF-8",$_POST['productname'][$i]), $_POST['uniteprice'][$i], $_POST['qty'][$i]);
		//print("<br>".$_POST['productid'][$i].$_POST['productname'][$i].$_POST['uniteprice'][$i].$_POST['qty'][$i]."</br>");
	}
	for($i=0;$i<$tMemoCount;$i++)
	{
		$tMemoItems[]=array(iconv("GBK","UTF-8",$_POST['memoname'][$i]), iconv("GBK","UTF-8",$_POST['memocontent'][$i]));
		//print("<br>".$_POST['memoname'][$i].$_POST['memocontent'][$i]."</br>");
	}

	$merchantFundTransferRequest = new MerchantFundTransferRequest($tMerchantTrnxNo,$tTrnxAmount,$tTrnxDate,$tTrnxTime,$tAccountDBNo,$tAccountDBName,$tAccountDBBank,$tResultNotifyURL,$tMerchantRemarks,$tTrnxOpr,$tOrderItems,$tMemoItems);
	$merchantFundTransfer = new MerchantFundTransfer($add,$merchantFundTransferRequest);
	$merchantFundTransferResult = $merchantFundTransfer->invoke();
	//$merchantFundTransfer->showResult();
	//显示结果
	if($merchantFundTransferResult->isSucess==TRUE)
	{
		$PaymentURL = $merchantFundTransferResult->paymentURL;
	}
	else
	{
		print("<br>Failed!!!"."</br>");
		print("<br>return code:".$merchantFundTransferResult->returnCode."</br>"); 
		print("<br>Error Message:".$merchantFundTransferResult->ErrorMessage."</br>");
	}


?>
	<script language=javascript>
	//	支付请求页面跳转
		var redirectURL="<?=$PaymentURL?>";
		if(redirectURL!=null&&redirectURL!="")
		{
			location.href='<?=$PaymentURL?>';
		}
	</script>
</body>
</html>