<html>
<head>
<title>TrustPay - 农行网上支付平台-商户接口范例-下载交易记录请求</title>
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
	//显示结果
	if($merchantDownloadTrnxResult->isSucess==TRUE)
	{
		print("<br>下载交易记录请求"."</br>");
		$count = count($merchantDownloadTrnxResult->merchantDownloadTrnxDetail->Records);
		if($count==0)
		{
			print("<br>指定的日期里没有交易记录</br>");
		}
		else
		{
			print "<br>序号		交易类型	商户交易编号	交易金额	交易时间	交易状态</br>";
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