<html>
<head>
<title>TrustPay - 农行网上支付平台-商户接口范例-电子凭证打印</title>
<meta http-equiv='Content-Type' content='text/html; charset=GB2312'>
</head>
<body>
<?php 
	require("api_b2b.php");

	//$add = "http://127.0.0.1:8080/axis/services/B2BWrapper?wsdl";
	$add = "http://www.gulianlvyou.com:8080/axis_b2b/services/B2BWrapper?wsdl";

	$tMerchantTrnxNo = $_POST['MerchantTrnxNo'];

	$merchantPrintTrnxVoucherRequest = new MerchantPrintTrnxVoucherRequest($tMerchantTrnxNo);
	$merchantPrintTrnxVoucher = new MerchantPrintTrnxVoucher($add,$merchantPrintTrnxVoucherRequest);
	$merchantPrintTrnxVoucherResult = $merchantPrintTrnxVoucher->invoke();
	//$merchantFundTransfer->showResult();
	//显示结果
	if($merchantPrintTrnxVoucherResult->isSucess==TRUE)
	{
		print("<br>电子凭证打印"."</br>");
		print "<br>电子交易凭证号码(VoucherNo):".$merchantPrintTrnxVoucherResult->merchantPrintTrnxVoucherDetail->VoucherNo."</br>";
		print "<br>付款人户名(AccountName):".$merchantPrintTrnxVoucherResult->merchantPrintTrnxVoucherDetail->AccountName."</br>";
		print "<br>付款人账号(AccountNo):".$merchantPrintTrnxVoucherResult->merchantPrintTrnxVoucherDetail->AccountNo."</br>";
		print "<br>付款人开户银行(AccountBank):".$merchantPrintTrnxVoucherResult->merchantPrintTrnxVoucherDetail->AccountBank."</br>";
		print "<br>收款人户名(AccountDBName):".$merchantPrintTrnxVoucherResult->merchantPrintTrnxVoucherDetail->AccountDBName."</br>";
		print "<br>收款人账号(AccountDBNo):".$merchantPrintTrnxVoucherResult->merchantPrintTrnxVoucherDetail->AccountDBNo."</br>";
		print "<br>收款人开户银行(AccountDBBank):".$merchantPrintTrnxVoucherResult->merchantPrintTrnxVoucherDetail->AccountDBBank."</br>";
		print "<br>金额(小写)(TrnxAMT):".$merchantPrintTrnxVoucherResult->merchantPrintTrnxVoucherDetail->TrnxAMT."</br>";
		print "<br>交易流水号(TrnxSN):".$merchantPrintTrnxVoucherResult->merchantPrintTrnxVoucherDetail->TrnxSN."</br>";
		print "<br>订单号(MerchantTrnxNo):".$merchantPrintTrnxVoucherResult->merchantPrintTrnxVoucherDetail->MerchantTrnxNo."</br>";
		print "<br>交易日期(TrnxDate):".$merchantPrintTrnxVoucherResult->merchantPrintTrnxVoucherDetail->TrnxDate."</br>";
		print "<br>时间戳(TrnxTime):".$merchantPrintTrnxVoucherResult->merchantPrintTrnxVoucherDetail->TrnxTime."</br>";
		print "<br>打印日期(PrtTime):".$merchantPrintTrnxVoucherResult->merchantPrintTrnxVoucherDetail->PrtTime."</br>";
	}
	else
	{
		print("<br>Failed!!!"."</br>");
		print("<br>return code:".$merchantPrintTrnxVoucherResult->returnCode."</br>"); 
		print("<br>Error Message:".$merchantPrintTrnxVoucherResult->ErrorMessage."</br>");
	}


?>
</body>
</html>