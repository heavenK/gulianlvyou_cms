<html>
<head>
<title>TrustPay - ϵͳ����</title>
<meta http-equiv='Content-Type' content='text/html; charset=GB2312'>
</head>
<body>
<?php 
	require("api.php");
	
	$function=9;
	
	$add = "http://www.dlgulian.com:8080/axis/services/B2CWarpper?wsdl";
	//$add = "http://187.61.1.5:8080/axis/services/B2CWarpper?wsdl";
	
	if($function==1)
	{
	//1��֧������
	
		$tOrderNo = "ON200905050015";
		$tOrderDesc = "";
		$tOrderDate = "2009/04/16";
		$tOrderTime = "12:55:30";
		$tOrderAmountStr = "0.01";
		$tOrderURL = "http://127.0.0.1/Merchant/MerchantQueryOrder.jsp?ON=ON200904160001&QueryType=1";
		$tProductType = "1";
		$tPaymentType = "1";
		$tNotifyType = "0";
		$tResultNotifyURL = "http://127.0.0.1/Merchant/MerchantResult.jsp";
		$tMerchantRemarks = "Hi!";
		$tPaymentLinkType = "1";
		//$merchantPayment = new MerchantPayment($add,$tOrderNo,$tOrderDesc,$tOrderDate,$tOrderTime,$tOrderAmountStr,$tOrderURL,$tProductType,$tPaymentType,$tNotifyType,$tResultNotifyURL,$tMerchantRemarks,$tPaymentLinkType);
		$merchantPaymentRequest = new MerchantPaymentRequest($tOrderNo,$tOrderDesc,$tOrderDate,$tOrderTime,$tOrderAmountStr,$tOrderURL,$tProductType,$tPaymentType,$tNotifyType,$tResultNotifyURL,$tMerchantRemarks,$tPaymentLinkType);
		$merchantPayment = new MerchantPayment($add,$merchantPaymentRequest);
		$merchantPaymentResult = $merchantPayment->invoke();
		//$merchantPayment->showResult();
		//��ʾ���
		if($merchantPaymentResult->isSucess==TRUE)
		{
			$PaymentURL = $merchantPaymentResult->paymentURL;
		}
		else
		{
			print("<br>Failed!!!"."</br>");
			print("<br>return code:".$merchantPaymentResult->returnCode."</br>"); 
			print("<br>Error Message:".$merchantPaymentResult->ErrorMessage."</br>");
		}
/*		
		if($PaymentURL!=null&&$PaymentURL!="")
		{
			echo "<SCRIPT LANGUAGE=JavaScript>";
			echo "location.href='$PaymentURL'";
			echo "</SCRIPT>";
		}
*/
?>		
		<script language=javascript>
		//	֧������ҳ����ת
			var redirectURL="<?=$PaymentURL?>";
			if(redirectURL!=null&&redirectURL!="")
			{
				location.href='<?=$PaymentURL?>';
			}
		</script>

<?php
	}//end ֧������
	else if($function==2)
	{
	//2��֧�������ѯ
		
		$tOrderNo = "ON200905050014";
		$tQueryType = "1";
		//$merchantQueryOrder = new MerchantQueryOrder($add,$tOrderNo,$tQueryType);
		$merchantQueryOrderRequest = new MerchantQueryOrderRequest($tOrderNo,$tQueryType);
		$merchantQueryOrder = new MerchantQueryOrder($add,$merchantQueryOrderRequest);
		$merchantQueryOrderResult = $merchantQueryOrder->invoke();
		//$merchantQueryOrder->showResult();
		//��ʾ���
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
			print "<br>������ϸ��</br>";
			for ($i = 0; $i < $count; $i++) 
			{	
				$item = $merchantQueryOrderResult->order->OrderItems[$i];
				print "<br>ProductID:".$item->ProductID."</br>";
				print "<br>ProductName:".$item->ProductName."</br>";
				print "<br>Qty:".$item->Qty."</br>";
				print "<br>UnitPrice��λ�۸�:".$item->UnitPrice."</br>";
			}
		}
		else
		{
			print("<br>Failed!!!"."</br>");
			print("<br>return code:".$merchantQueryOrderResult->returnCode."</br>"); 
			print("<br>Error Message:".$merchantQueryOrderResult->ErrorMessage."</br>");
		}
		
	}//end ֧�������ѯ
	else if($function==3)
	{
	//3��ȡ��֧��
		
		$tOrderNo = "ON200905050021";
		//$merchantVoidPayment = new MerchantVoidPayment($add,$tOrderNo);
		$merchantVoidPaymentRequest = new MerchantVoidPaymentRequest($tOrderNo);
		$merchantVoidPayment = new MerchantVoidPayment($add,$merchantVoidPaymentRequest);
		$merchantVoidPaymentResult = $merchantVoidPayment->invoke();
		//$merchantVoidPayment->showResult();	
		//��ʾ���
		if($merchantVoidPaymentResult->isSucess==TRUE)
		{
			print("<br>Sucess!!!"."</br>");
			print "<br>BatchNo:".$merchantVoidPaymentResult->voidPaymentResponseDetail->BatchNo."</br>";
			print "<br>HostDate:".$merchantVoidPaymentResult->voidPaymentResponseDetail->HostDate."</br>";
			print "<br>HostTime:".$merchantVoidPaymentResult->voidPaymentResponseDetail->HostTime."</br>";
			print "<br>OrderNo:".$merchantVoidPaymentResult->voidPaymentResponseDetail->OrderNo."</br>";
			print "<br>PayAmount:".$merchantVoidPaymentResult->voidPaymentResponseDetail->PayAmount."</br>";
			print "<br>TrxType:".$merchantVoidPaymentResult->voidPaymentResponseDetail->TrxType."</br>";
			print "<br>VoucherNo:".$merchantVoidPaymentResult->voidPaymentResponseDetail->VoucherNo."</br>";
		}
		else
		{
			print("<br>Failed!!!"."</br>");
			print("<br>return code:".$merchantVoidPaymentResult->returnCode."</br>"); 
			print("<br>Error Message:".$merchantVoidPaymentResult->ErrorMessage."</br>");
		}
		
	}//end ȡ��֧��
	else if($function==4)
	{
	//4���˻�
		
		$tTrxAmount = 0.01;
		$tOrderNo = "ON200905050023";
		//$merchantRefund = new MerchantRefund($add,$tOrderNo,$tTrxAmount);
		$merchantRefundRequest = new MerchantRefundRequest($tOrderNo,$tTrxAmount);
		$merchantRefund = new MerchantRefund($add,$merchantRefundRequest);
		$merchantRefundResult = $merchantRefund->invoke();
		//$merchantRefund->showResult();
		//��ʾ���
		if($merchantRefundResult->isSucess==TRUE)
		{
			print("<br>Sucess!!!"."</br>");
			print "<br>BatchNo:".$merchantRefundResult->merchantRefundResponseDetail->BatchNo."</br>";
			print "<br>HostDate:".$merchantRefundResult->merchantRefundResponseDetail->HostDate."</br>";
			print "<br>HostTime:".$merchantRefundResult->merchantRefundResponseDetail->HostTime."</br>";
			print "<br>OrderNo:".$merchantRefundResult->merchantRefundResponseDetail->OrderNo."</br>";
			print "<br>TrxAmount:".$merchantRefundResult->merchantRefundResponseDetail->TrxAmount."</br>";
			print "<br>TrxType:".$merchantRefundResult->merchantRefundResponseDetail->TrxType."</br>";
			print "<br>VoucherNo:".$merchantRefundResult->merchantRefundResponseDetail->VoucherNo."</br>";
		}
		else
		{
			print("<br>Failed!!!"."</br>");
			print("<br>return code:".$merchantRefundResult->returnCode."</br>"); 
			print("<br>Error Message:".$merchantRefundResult->ErrorMessage."</br>");
		}		
		
	}//end�˻�
	else if($function==5)
	{
	//5�����ؽ��׶��˵�
		
		$tSettleDate = "2009/05/04";
		//$merchantTrxSettle = new MerchantTrxSettle($add,$tSettleDate);
		$merchantTrxSettleRequest = new MerchantTrxSettleRequest($tSettleDate);
		$merchantTrxSettle = new MerchantTrxSettle($add,$merchantTrxSettleRequest);
		$merchantTrxSettleResult = $merchantTrxSettle->invoke();
		//$merchantTrxSettle->showResult();
		//��ʾ���
		if($merchantTrxSettleResult->isSucess==TRUE)
		{
			print("<br>Sucess!!!"."</br>");
			print "<br>SettleDate:".$merchantTrxSettleResult->merchantTrxSettleDetail->SettleDate."</br>";
			print "<br>SettleType:".$merchantTrxSettleResult->merchantTrxSettleDetail->SettleType."</br>";
			print "<br>NumOfPayments:".$merchantTrxSettleResult->merchantTrxSettleDetail->NumOfPayments."</br>";
			print "<br>SumOfPayAmount:".$merchantTrxSettleResult->merchantTrxSettleDetail->SumOfPayAmount."</br>";
			print "<br>NumOfRefunds:".$merchantTrxSettleResult->merchantTrxSettleDetail->NumOfRefunds."</br>";
			print "<br>SumOfRefundAmount:".$merchantTrxSettleResult->merchantTrxSettleDetail->SumOfRefundAmount."</br>";	
			$count = count($merchantTrxSettleResult->merchantTrxSettleDetail->settleItems);
			print "<br>������ϸ��</br>";
			for ($i = 0; $i < $count; $i++) 
			{	
				$item = $merchantTrxSettleResult->merchantTrxSettleDetail->settleItems[$i];
				print "<br>$i:".$item."</br>";
			}
		}
		else
		{
			print("<br>Failed!!!"."</br>");
			print("<br>return code:".$merchantTrxSettleResult->returnCode."</br>"); 
			print("<br>Error Message:".$merchantTrxSettleResult->ErrorMessage."</br>");
		}		
		
	}//end���ؽ��׶��˵�
	else if($function==6)
	{	
	//6������ָ��ʱ��ν��׶��˵�
	
		$tSettleDate = "2009/05/04";
		$tSettleStartHour = 0;
		$tSettleEndHour = 23;
		//$merchantTrxSettleByhour = new MerchantTrxSettleByhour($add,$tSettleDate,$tSettleStartHour,$tSettleEndHour);
		$merchantTrxSettleByHourRequest = new MerchantTrxSettleByHourRequest($tSettleDate,$tSettleStartHour,$tSettleEndHour);
		$merchantTrxSettleByhour = new MerchantTrxSettleByhour($add,$merchantTrxSettleByHourRequest);
		$merchantTrxSettleByHourResult = $merchantTrxSettleByhour->invoke();
		//$merchantTrxSettleByhour->showResult();
		
		//��ʾ���
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
			print "<br>������ϸ��</br>";
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
	
	}//end����ָ��ʱ��ν��׶��˵�
	else if($function==7)
	{	
	//7�������֤
	
		$tResultNotifyURL = "http://127.0.0.1:9080/b2cclient/IdentityVerifyResult.jsp";//�ĳ�php�Լ���ҳ��!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
		$tBankCardNo = "6228480010357523116";
		$tCertificateType = "I";
		$tCertificateNo = "340121198403084619";
		$tRequestDate = "2009/04/19";
		$tRequestTime = "09:10:10";
		//$identityVerify = new IdentityVerify($add,$tResultNotifyURL,$tBankCardNo,$tCertificateType,$tCertificateNo,$tRequestDate,$tRequestTime);
		$identityVerifyRequest = new IdentityVerifyRequest($tResultNotifyURL,$tBankCardNo,$tCertificateType,$tCertificateNo,$tRequestDate,$tRequestTime);
		$identityVerify = new IdentityVerify($add,$identityVerifyRequest);
		$identityVerifyResult = $identityVerify->invoke();
		//$identityVerify->showResult();
		//��ʾ���
		if($identityVerifyResult->isSucess==TRUE)
		{
			$identityVerifyURL = $identityVerifyResult->identityVerifyURL;
		}
		else
		{
			print("<br>Failed!!!"."</br>");
			print("<br>return code:".$identityVerifyResult->returnCode."</br>"); 
			print("<br>Error Message:".$identityVerifyResult->ErrorMessage."</br>");
		}
		if($identityVerifyURL!=null&&$identityVerifyURL!="")
		{//��ת�������֤ҳ��
			echo "<SCRIPT LANGUAGE=JavaScript>";
			echo "location.href='$identityVerifyURL'";
			echo "</SCRIPT>";
		}
	
	}//end�����֤
	else if($function==8)
	{	
	//8�������˿��
		
		$tSerialNumber = "ON0001";
		//$merchantBatchSend = new MerchantBatchSend($add,$tSerialNumber);
		$merchantBatchSendRequest = new MerchantBatchSendRequest($tSerialNumber);
		$merchantBatchSend = new MerchantBatchSend($add,$merchantBatchSendRequest);
		$merchantBatchSendResult = $merchantBatchSend->invoke();	
		//$merchantBatchSend->showResult();
		//��ʾ���
		if($merchantBatchSendResult->isSucess==TRUE)
		{
			print("<br>Sucess!!!"."</br>");
			print "<br>SendTime:".$merchantBatchSendResult->merchantBatchSendResDetail->SendTime."</br>";
			print "<br>SerialNumber:".$merchantBatchSendResult->merchantBatchSendResDetail->SerialNumber."</br>";
			print "<br>TrxType:".$merchantBatchSendResult->merchantBatchSendResDetail->TrxType."</br>";
		}
		else
		{
			print("<br>Failed!!!"."</br>");
			print("<br>return code:".$merchantBatchSendResult->returnCode."</br>"); 
			print("<br>Error Message:".$merchantBatchSendResult->ErrorMessage."</br>");
		}		
	}//end�����˿��
	else if($function==9)
	{	
	//9�������˿��ѯ
		
		$tSerialNumber = "ON0001";
		//$merchantQueryBatch = new MerchantQueryBatch($add,$tSerialNumber);
		$merchantQueryBatchRequest = new MerchantQueryBatchRequest($tSerialNumber);
		$merchantQueryBatch = new MerchantQueryBatch($add,$merchantQueryBatchRequest);
		$merchantQueryBatchResult = $merchantQueryBatch->invoke();	
		//$merchantQueryBatch->showResult();
		//��ʾ���
		if($merchantQueryBatchResult->isSucess==TRUE)
		{
			print("<br>Sucess!!!"."</br>");
			print "<br>SerialNumber:".$merchantQueryBatchResult->merchantQueryBatchResDetail->SerialNumber."</br>";
			print "<br>RefundAmount:".$merchantQueryBatchResult->merchantQueryBatchResDetail->RefundAmount."</br>";
			print "<br>RefundCount:".$merchantQueryBatchResult->merchantQueryBatchResDetail->RefundCount."</br>";
			print "<br>nStatus:".$merchantQueryBatchResult->merchantQueryBatchResDetail->nStatus."</br>";	
			$count = count($merchantQueryBatchResult->merchantQueryBatchResDetail->batchItems);
			print "<br>�����˿��ѯ��ϸ��</br>";
			for ($i = 0; $i < $count; $i++) 
			{	
				$item = $merchantQueryBatchResult->merchantQueryBatchResDetail->batchItems[$i];
				print "<br>OrderNo:".$item->OrderNo."</br>";
				print "<br>RefundAmount:".$item->RefundAmount."</br>";
				print "<br>OrderStatus:".$item->OrderStatus."</br>";
			}
		}
		else
		{
			print("<br>Failed!!!"."</br>");
			print("<br>return code:".$merchantQueryBatchResult->returnCode."</br>"); 
			print("<br>Error Message:".$merchantQueryBatchResult->ErrorMessage."</br>");
		}		
	}//end�����˿��ѯ
	
?>


</body>
</html>