<html>
<head>
<title>TrustPay - ũ������֧��ƽ̨-�̻��ӿڷ���-���ؽ��׼�¼����</title>
<meta http-equiv='Content-Type' content='text/html; charset=GB2312'>
</head>
<body>
<?php 
	require("api_b2b.php");

	//$add = "http://www.gulianlvyou.com:8080/axis/services/B2BWrapper?wsdl";
	$add = "http://www.gulianlvyou.com:8080/axis_b2b/services/B2BWrapper?wsdl";

	$MerchantTrnxDate = $_POST['MerchantTrnxDate'];
	$tMerchantRemarks = iconv("GBK","UTF-8",$_POST['MerchantRemarks']);

	$merchantDownloadTrnxRequest = new MerchantDownloadTrnxRequest($MerchantTrnxDate,$tMerchantRemarks);
	$merchantDownloadTrnx = new MerchantDownloadTrnx($add,$merchantDownloadTrnxRequest);
	$merchantDownloadTrnxResult = $merchantDownloadTrnx->invoke();
	//$merchantFundTransfer->showResult();
	//��ʾ���
	if($merchantDownloadTrnxResult->isSucess==TRUE)
	{
		print("<br>���ؽ��׼�¼����"."</br>");
		$count = count($merchantDownloadTrnxResult->merchantDownloadTrnxDetail->Records);
		if($count==0)
		{
			print("<br>ָ����������û�н��׼�¼</br>");
		}
		else
		{
			print "<br>���		��������	�̻����ױ��	���׽��	����ʱ��	����״̬</br>";
			for($i=0;$i<$count;$i++)
			{
				print "<br>Records[$i]:".$merchantDownloadTrnxResult->merchantDownloadTrnxDetail->Records[$i]."</br>";
			}
		}
	}
	else
	{
		print("<br>Failed!!!"."</br>");
		print("<br>return code:".$merchantDownloadTrnxResult->returnCode."</br>"); 
		print("<br>Error Message:".$merchantDownloadTrnxResult->ErrorMessage."</br>");
	}


?>
</body>
</html>