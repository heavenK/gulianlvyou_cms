<html>
<head>
<title>TrustPay - 农行网上支付平台-商户接口范例-交易查询请求</title>
<meta http-equiv='Content-Type' content='text/html; charset=GB2312'>
</head>
<body>
<?php 
	require("api_b2b.php");

	//$add = "http://127.0.0.1:8080/axis/services/B2BWrapper?wsdl";
	$add = "http://www.dlgulian.com:8080/axis_b2b/services/B2BWrapper?wsdl";

	$tMerchantTrnxNo = $_POST['MerchantTrnxNo'];
	$tMerchantRemarks = iconv("GBK","UTF-8",$_POST['MerchantRemarks']);

	$merchantQueryTrnxRequest = new MerchantQueryTrnxRequest($tMerchantTrnxNo,$tMerchantRemarks);
	$merchantQueryTrnx = new MerchantQueryTrnx($add,$merchantQueryTrnxRequest);
	$merchantQueryTrnxResult = $merchantQueryTrnx->invoke();
	//$merchantFundTransfer->showResult();
	//显示结果
	if($merchantQueryTrnxResult->isSucess==TRUE)
	{
		print("<br>交易查询请求"."</br>");
		print "<br>商户代码(MerchantID):".$merchantQueryTrnxResult->merchantQueryTrnxDetail->MerchantID."</br>";
		print "<br>买方企业客户代码(CorporationCustomerNo):".$merchantQueryTrnxResult->merchantQueryTrnxDetail->CorporationCustomerNo."</br>";
		print "<br>商户交易编号(MerchantTrnxNo):".$merchantQueryTrnxResult->merchantQueryTrnxDetail->MerchantTrnxNo."</br>";
		print "<br>交易流水号(TrnxSN):".$merchantQueryTrnxResult->merchantQueryTrnxDetail->TrnxSN."</br>";
		print "<br>交易类型(TrnxType):".$merchantQueryTrnxResult->merchantQueryTrnxDetail->TrnxType."</br>";
		print "<br>交易金额(TrnxAMT):".$merchantQueryTrnxResult->merchantQueryTrnxDetail->TrnxAMT."</br>";
		print "<br>付款方账号(AccountNo):".$merchantQueryTrnxResult->merchantQueryTrnxDetail->AccountNo."</br>";
		print "<br>付款方名称(AccountName):".$merchantQueryTrnxResult->merchantQueryTrnxDetail->AccountName."</br>";
		print "<br>付款方账户行联行号(AccountBank):".$merchantQueryTrnxResult->merchantQueryTrnxDetail->AccountBank."</br>";
		print "<br>收款方账号(AccountDBNo):".$merchantQueryTrnxResult->merchantQueryTrnxDetail->AccountDBNo."</br>";
		print "<br>收款方名称(AccountDBName):".$merchantQueryTrnxResult->merchantQueryTrnxDetail->AccountDBName."</br>";
		print "<br>收款方账户行联行号(AccountDBBank):".$merchantQueryTrnxResult->merchantQueryTrnxDetail->AccountDBBank."</br>";
		print "<br>交易时间(TrnxTime):".$merchantQueryTrnxResult->merchantQueryTrnxDetail->TrnxTime."</br>";		
		print "<br>交易状态(TrnxStatus):".$merchantQueryTrnxResult->merchantQueryTrnxDetail->TrnxStatus."</br>";
		
		print "<br>查询返回码(ReturnCode):".$merchantQueryTrnxResult->merchantQueryTrnxDetail->ReturnCode."</br>";
	}
	else
	{
		print("<br>Failed!!!"."</br>");
		print("<br>return code:".$merchantQueryTrnxResult->returnCode."</br>"); 
		print("<br>Error Message:".$merchantQueryTrnxResult->ErrorMessage."</br>");
	}


?>
</body>
</html>