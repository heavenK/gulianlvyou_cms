<html>
<head>
<title>TrustPay - ũ������֧��ƽ̨-�̻��ӿڷ���-����ƾ֤��ӡ</title>
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
	//��ʾ���
	if($merchantPrintTrnxVoucherResult->isSucess==TRUE)
	{
		print("<br>����ƾ֤��ӡ"."</br>");
		print "<br>���ӽ���ƾ֤����(VoucherNo):".$merchantPrintTrnxVoucherResult->merchantPrintTrnxVoucherDetail->VoucherNo."</br>";
		print "<br>�����˻���(AccountName):".$merchantPrintTrnxVoucherResult->merchantPrintTrnxVoucherDetail->AccountName."</br>";
		print "<br>�������˺�(AccountNo):".$merchantPrintTrnxVoucherResult->merchantPrintTrnxVoucherDetail->AccountNo."</br>";
		print "<br>�����˿�������(AccountBank):".$merchantPrintTrnxVoucherResult->merchantPrintTrnxVoucherDetail->AccountBank."</br>";
		print "<br>�տ��˻���(AccountDBName):".$merchantPrintTrnxVoucherResult->merchantPrintTrnxVoucherDetail->AccountDBName."</br>";
		print "<br>�տ����˺�(AccountDBNo):".$merchantPrintTrnxVoucherResult->merchantPrintTrnxVoucherDetail->AccountDBNo."</br>";
		print "<br>�տ��˿�������(AccountDBBank):".$merchantPrintTrnxVoucherResult->merchantPrintTrnxVoucherDetail->AccountDBBank."</br>";
		print "<br>���(Сд)(TrnxAMT):".$merchantPrintTrnxVoucherResult->merchantPrintTrnxVoucherDetail->TrnxAMT."</br>";
		print "<br>������ˮ��(TrnxSN):".$merchantPrintTrnxVoucherResult->merchantPrintTrnxVoucherDetail->TrnxSN."</br>";
		print "<br>������(MerchantTrnxNo):".$merchantPrintTrnxVoucherResult->merchantPrintTrnxVoucherDetail->MerchantTrnxNo."</br>";
		print "<br>��������(TrnxDate):".$merchantPrintTrnxVoucherResult->merchantPrintTrnxVoucherDetail->TrnxDate."</br>";
		print "<br>ʱ���(TrnxTime):".$merchantPrintTrnxVoucherResult->merchantPrintTrnxVoucherDetail->TrnxTime."</br>";
		print "<br>��ӡ����(PrtTime):".$merchantPrintTrnxVoucherResult->merchantPrintTrnxVoucherDetail->PrtTime."</br>";
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