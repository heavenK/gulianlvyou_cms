<html>
<head>
<title>TrustPay - ũ������֧��ƽ̨-�̻��ӿڷ���-���ײ�ѯ����</title>
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
	//��ʾ���
	if($merchantQueryTrnxResult->isSucess==TRUE)
	{
		print("<br>���ײ�ѯ����"."</br>");
		print "<br>�̻�����(MerchantID):".$merchantQueryTrnxResult->merchantQueryTrnxDetail->MerchantID."</br>";
		print "<br>����ҵ�ͻ�����(CorporationCustomerNo):".$merchantQueryTrnxResult->merchantQueryTrnxDetail->CorporationCustomerNo."</br>";
		print "<br>�̻����ױ��(MerchantTrnxNo):".$merchantQueryTrnxResult->merchantQueryTrnxDetail->MerchantTrnxNo."</br>";
		print "<br>������ˮ��(TrnxSN):".$merchantQueryTrnxResult->merchantQueryTrnxDetail->TrnxSN."</br>";
		print "<br>��������(TrnxType):".$merchantQueryTrnxResult->merchantQueryTrnxDetail->TrnxType."</br>";
		print "<br>���׽��(TrnxAMT):".$merchantQueryTrnxResult->merchantQueryTrnxDetail->TrnxAMT."</br>";
		print "<br>����˺�(AccountNo):".$merchantQueryTrnxResult->merchantQueryTrnxDetail->AccountNo."</br>";
		print "<br>�������(AccountName):".$merchantQueryTrnxResult->merchantQueryTrnxDetail->AccountName."</br>";
		print "<br>����˻������к�(AccountBank):".$merchantQueryTrnxResult->merchantQueryTrnxDetail->AccountBank."</br>";
		print "<br>�տ�˺�(AccountDBNo):".$merchantQueryTrnxResult->merchantQueryTrnxDetail->AccountDBNo."</br>";
		print "<br>�տ����(AccountDBName):".$merchantQueryTrnxResult->merchantQueryTrnxDetail->AccountDBName."</br>";
		print "<br>�տ�˻������к�(AccountDBBank):".$merchantQueryTrnxResult->merchantQueryTrnxDetail->AccountDBBank."</br>";
		print "<br>����ʱ��(TrnxTime):".$merchantQueryTrnxResult->merchantQueryTrnxDetail->TrnxTime."</br>";		
		print "<br>����״̬(TrnxStatus):".$merchantQueryTrnxResult->merchantQueryTrnxDetail->TrnxStatus."</br>";
		
		print "<br>��ѯ������(ReturnCode):".$merchantQueryTrnxResult->merchantQueryTrnxDetail->ReturnCode."</br>";
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