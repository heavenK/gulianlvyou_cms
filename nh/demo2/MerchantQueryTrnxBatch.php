<html>
<head>
<title>TrustPay - ũ������֧��ƽ̨-�̻��ӿڷ���-���׼�¼������ѯ����</title>
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
	//��ʾ���
	if($merchantQueryTrnxBatchResult->isSucess==TRUE)
	{
		print("<br>���׼�¼������ѯ����"."</br>");
		
		$count = count($merchantQueryTrnxBatchResult->merchantQueryTrnxBatchDetail->merchantQueryTrnxBatchItems);
		print("<br>Total:".$count."</br>");
		for ($i = 0; $i < $count; $i++) 
		{	
			print("<br>Items".$i.":</br>");
			print "<br>������(MerchantTrnxNo):".$merchantQueryTrnxBatchResult->merchantQueryTrnxBatchDetail->merchantQueryTrnxBatchItems[$i]->MerchantTrnxNo."</br>";
			print "<br>����ʱ��(TrnxTime):".$merchantQueryTrnxBatchResult->merchantQueryTrnxBatchDetail->merchantQueryTrnxBatchItems[$i]->TrnxTime."</br>";
			print "<br>����˻���(PayAccountName):".$merchantQueryTrnxBatchResult->merchantQueryTrnxBatchDetail->merchantQueryTrnxBatchItems[$i]->PayAccountName."</br>";
			print "<br>����ʺ�(PayAccount):".$merchantQueryTrnxBatchResult->merchantQueryTrnxBatchDetail->merchantQueryTrnxBatchItems[$i]->PayAccount."</br>";
			print "<br>���׽��(TrnxAmount):".$merchantQueryTrnxBatchResult->merchantQueryTrnxBatchDetail->merchantQueryTrnxBatchItems[$i]->TrnxAmount."</br>";
			print "<br>����״̬(TrnxStatus):".$merchantQueryTrnxBatchResult->merchantQueryTrnxBatchDetail->merchantQueryTrnxBatchItems[$i]->TrnxStatus."</br>";			
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