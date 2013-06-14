<?php
/*
MerchantFundTransfer
*/
class MerchantFundTransfer{
	protected  
		$address,
		$tMerchantTrnxNo,
		$tTrnxAmount1,
		$tTrnxDate,
		$tTrnxTime,
		$tAccountDBNo,
		$tAccountDBName,
		$tAccountDBBank,
		$tResultNotifyURL,
		$tMerchantRemarks,
		$tTrnxOpr,
		$tOrderItems,
		$tMemoItems;
		
	private $res;

	public function __construct($address,$merchantFundTransferRequest)
	{
		$this->address = $address;
		$this->tMerchantTrnxNo = $merchantFundTransferRequest->tMerchantTrnxNo;
		$this->tTrnxAmount = $merchantFundTransferRequest->tTrnxAmount;
		$this->tTrnxDate = $merchantFundTransferRequest->tTrnxDate;
		$this->tTrnxTime = $merchantFundTransferRequest->tTrnxTime;
		$this->tAccountDBNo = $merchantFundTransferRequest->tAccountDBNo;
		$this->tAccountDBName = $merchantFundTransferRequest->tAccountDBName;
		$this->tAccountDBBank = $merchantFundTransferRequest->tAccountDBBank;
		$this->tResultNotifyURL = $merchantFundTransferRequest->tResultNotifyURL;
		$this->tMerchantRemarks = $merchantFundTransferRequest->tMerchantRemarks;
		$this->tTrnxOpr = $merchantFundTransferRequest->tTrnxOpr;
		$this->tOrderItems = $merchantFundTransferRequest->tOrderItems;
		$this->tMemoItems = $merchantFundTransferRequest->tMemoItems;
	}	
	public function invoke()
	{
		//print("<br>"."!!!!!!"."</br>");
		$client=new SoapClient($this->address, array("style"=> SOAP_RPC,"use"=> SOAP_ENCODED, "trace"=>0,"exceptions" =>0));
		$client->soap_defencoding='utf-8';
		$client->decode_utf8=false;
		$result = $client->merchantFundTransfer_php($this->tMerchantTrnxNo,$this->tTrnxAmount,$this->tTrnxDate,$this->tTrnxTime,$this->tAccountDBNo,$this->tAccountDBName,$this->tAccountDBBank,$this->tResultNotifyURL,$this->tMerchantRemarks,$this->tTrnxOpr,$this->tOrderItems,$this->tMemoItems);
		$this->res = (array)$result;
		return $this->generateResponse();
	}

	private function generateResponse()
	{
		if($this->res[1]==TRUE)
		{
			$paymentURL = $this->res[0];
		}
		$merchantFundTransferResult = new MerchantFundTransferResult($paymentURL,$this->res[1],iconv("UTF-8", "GBK",$this->res[2]),iconv("UTF-8", "GBK",$this->res[3]));
		return $merchantFundTransferResult;
	}
	
	public function showResult()
	{
		if($this->res[1]==TRUE)
		{
			print("<br>Sucess!!!"."</br>");
			print "<br>PaymentURL:".$this->res[0]."</br>";
		}
		else
		{
			print("<br>Failed!!!"."</br>");
			print("<br>return code:".iconv("UTF-8", "GBK",$this->res[2])."</br>"); 
			print("<br>Error Message:".iconv("UTF-8", "GBK",$this->res[3])."</br>");
		}	
	}
}
class MerchantFundTransferRequest
{
	public		
		$tMerchantTrnxNo,
		$tTrnxAmount,
		$tTrnxDate,
		$tTrnxTime,		
		$tAccountDBNo,
		$tAccountDBName,
		$tAccountDBBank,
		$tResultNotifyURL,
		$tMerchantRemarks,
		$tTrnxOpr,
		$tOrderItems,
		$tMemoItems;
	public function __construct($tMerchantTrnxNo,$tTrnxAmount,$tTrnxDate,$tTrnxTime,$tAccountDBNo,$tAccountDBName,$tAccountDBBank,$tResultNotifyURL,$tMerchantRemarks,$tTrnxOpr,$tOrderItems,$tMemoItems)
	{
		$this->tMerchantTrnxNo = $tMerchantTrnxNo;
		$this->tTrnxAmount = $tTrnxAmount;
		$this->tTrnxDate = $tTrnxDate;
		$this->tTrnxTime = $tTrnxTime;		
		$this->tAccountDBNo = $tAccountDBNo;
		$this->tAccountDBName = $tAccountDBName;
		$this->tAccountDBBank = $tAccountDBBank;
		$this->tResultNotifyURL = $tResultNotifyURL;
		$this->tMerchantRemarks = $tMerchantRemarks;
		$this->tTrnxOpr = $tTrnxOpr;
		$this->tOrderItems = $tOrderItems;
		$this->tMemoItems = $tMemoItems;
	}	
}
class MerchantFundTransferResult{
	public $paymentURL,$isSucess,$returnCode,$ErrorMessage;
	public function __construct($paymentURL,$isSucess,$returnCode,$ErrorMessage)
	{
		$this->paymentURL=$paymentURL;
		$this->isSucess=$isSucess;
		$this->returnCode=$returnCode;
		$this->ErrorMessage=$ErrorMessage;
	}
}


/*
MerchantQueryTrnx
*/
class MerchantQueryTrnx{
	protected  
		$address,
		$tMerchantTrnxNo,
		$tMerchantRemarks;
		
	private $res;

	public function __construct($address,$merchantQueryTrnxRequest)
	{
		$this->address = $address;
		$this->tMerchantTrnxNo = $merchantQueryTrnxRequest->tMerchantTrnxNo;
		$this->tMerchantRemarks = $merchantQueryTrnxRequest->tMerchantRemarks;
	}	
	public function invoke()
	{
		//print("<br>"."!!!!!!"."</br>");
		$client=new SoapClient($this->address, array("style"=> SOAP_RPC,"use"=> SOAP_ENCODED, "trace"=>0,"exceptions" =>0));
		$client->soap_defencoding='utf-8';
		$client->decode_utf8=false;
		$result = $client->merchantQueryTrnx_php($this->tMerchantTrnxNo,$this->tMerchantRemarks);
		$this->res = (array)$result;
		return $this->generateResponse();
	}

	private function generateResponse()
	{
		if($this->res[1]==TRUE)
		{
			$merchantQueryTrnxDetail = new  MerchantQueryTrnxDetail($this->res[0][0],$this->res[0][1],$this->res[0][2],$this->res[0][3],$this->res[0][4],$this->res[0][5],$this->res[0][6],$this->res[0][7],$this->res[0][8],$this->res[0][9],$this->res[0][10],$this->res[0][11],$this->res[0][12],$this->res[0][13],$this->res[0][14]);
		}
		$merchantQueryTrnxResult = new MerchantQueryTrnxResult($merchantQueryTrnxDetail,$this->res[1],iconv("UTF-8", "GBK",$this->res[2]),iconv("UTF-8", "GBK",$this->res[3]));
		return $merchantQueryTrnxResult;
	}
	
	public function showResult()
	{
		if($this->res[1]==TRUE)
		{
			print("<br>Sucess!!!"."</br>");
			
			
		}
		else
		{
			print("<br>Failed!!!"."</br>");
			print("<br>return code:".iconv("UTF-8", "GBK",$this->res[2])."</br>"); 
			print("<br>Error Message:".iconv("UTF-8", "GBK",$this->res[3])."</br>");
		}	
	}
}
class MerchantQueryTrnxRequest
{
	public		
		$tMerchantTrnxNo,
		$tMerchantRemarks;
	public function __construct($tMerchantTrnxNo,$tMerchantRemarks)
	{
		$this->tMerchantTrnxNo = $tMerchantTrnxNo;
		$this->tMerchantRemarks = $tMerchantRemarks;
	}	
}
class MerchantQueryTrnxResult{
	public $merchantQueryTrnxDetail,$isSucess,$returnCode,$ErrorMessage;
	public function __construct($merchantQueryTrnxDetail,$isSucess,$returnCode,$ErrorMessage)
	{
		$this->merchantQueryTrnxDetail=$merchantQueryTrnxDetail;
		$this->isSucess=$isSucess;
		$this->returnCode=$returnCode;
		$this->ErrorMessage=$ErrorMessage;
	}
}

class MerchantQueryTrnxDetail{
	public $MerchantID,$CorporationCustomerNo,$MerchantTrnxNo,$TrnxSN,$TrnxType,$TrnxAMT,$AccountNo,$AccountName,$AccountBank,$AccountDBNo,$AccountDBName,$AccountDBBank,$TrnxTime,$TrnxStatus,$ReturnCode;
	public function __construct($MerchantID,$CorporationCustomerNo,$MerchantTrnxNo,$TrnxSN,$TrnxType,$TrnxAMT,$AccountNo,$AccountName,$AccountBank,$AccountDBNo,$AccountDBName,$AccountDBBank,$TrnxTime,$TrnxStatus,$ReturnCode)
	{
		$this->MerchantID=$MerchantID;
		$this->CorporationCustomerNo=$CorporationCustomerNo;
		$this->MerchantTrnxNo=$MerchantTrnxNo;
		$this->TrnxSN=$TrnxSN;
		$this->TrnxType=$TrnxType;
		$this->TrnxAMT=$TrnxAMT;
		$this->AccountNo=$AccountNo;
		$this->AccountName=iconv("UTF-8", "GBK",$AccountName);
		$this->AccountBank=$AccountBank;
		$this->AccountDBNo=$AccountDBNo;
		$this->AccountDBName=iconv("UTF-8", "GBK",$AccountDBName);
		$this->AccountDBBank=$AccountDBBank;
		$this->TrnxTime=$TrnxTime;
		$this->TrnxStatus=$TrnxStatus;
		$this->ReturnCode=$ReturnCode;
	}
}


/*
MerchantDownloadTrnx
*/
class MerchantDownloadTrnx{
	protected  
		$address,
		$tMerchantTrnxDate,
		$tMerchantRemarks;
		
	private $res;

	public function __construct($address,$merchantDownloadTrnxRequest)
	{
		$this->address = $address;
		$this->tMerchantTrnxDate = $merchantDownloadTrnxRequest->tMerchantTrnxDate;
		$this->tMerchantRemarks = $merchantDownloadTrnxRequest->tMerchantRemarks;
	}	
	public function invoke()
	{
		//print("<br>"."!!!!!!"."</br>");
		$client=new SoapClient($this->address, array("style"=> SOAP_RPC,"use"=> SOAP_ENCODED, "trace"=>0,"exceptions" =>0));
		$client->soap_defencoding='utf-8';
		$client->decode_utf8=false;
		$result = $client->merchantDownloadTrnx_php($this->tMerchantTrnxDate,$this->tMerchantRemarks);
		$this->res = (array)$result;
		return $this->generateResponse();
	}

	private function generateResponse()
	{	
		if($this->res[1]==TRUE)
		{
			$merchantDownloadTrnxDetail = new  MerchantDownloadTrnxDetail($this->res[0]);
		}
		$merchantDownloadTrnxResult = new MerchantDownloadTrnxResult($merchantDownloadTrnxDetail,$this->res[1],iconv("UTF-8", "GBK",$this->res[2]),iconv("UTF-8", "GBK",$this->res[3]));
		return $merchantDownloadTrnxResult;
	}
	
	public function showResult()
	{
		if($this->res[1]==TRUE)
		{
			print("<br>Sucess!!!"."</br>");
			
			
		}
		else
		{
			print("<br>Failed!!!"."</br>");
			print("<br>return code:".iconv("UTF-8", "GBK",$this->res[2])."</br>"); 
			print("<br>Error Message:".iconv("UTF-8", "GBK",$this->res[3])."</br>");
		}	
	}
}
class MerchantDownloadTrnxRequest
{
	public		
		$tMerchantTrnxDate,
		$tMerchantRemarks;
	public function __construct($tMerchantTrnxDate,$tMerchantRemarks)
	{
		$this->tMerchantTrnxDate = $tMerchantTrnxDate;
		$this->tMerchantRemarks = $tMerchantRemarks;
	}	
}
class MerchantDownloadTrnxResult{
	public $merchantDownloadTrnxDetail,$isSucess,$returnCode,$ErrorMessage;
	public function __construct($merchantDownloadTrnxDetail,$isSucess,$returnCode,$ErrorMessage)
	{
		$this->merchantDownloadTrnxDetail=$merchantDownloadTrnxDetail;
		$this->isSucess=$isSucess;
		$this->returnCode=$returnCode;
		$this->ErrorMessage=$ErrorMessage;
	}
}

class MerchantDownloadTrnxDetail{
	public $Records;
	public function __construct($Records)
	{
		$this->Records=$Records;
	}
}



/*
MerchantQueryTrnxBatch
*/
class MerchantQueryTrnxBatch{
	protected  
		$address,
		$tTrnxStatus,
		$tStDate,
		$tEndDate;
		
	private $res;

	public function __construct($address,$merchantQueryTrnxBatchRequest)
	{
		$this->address = $address;
		$this->tTrnxStatus = $merchantQueryTrnxBatchRequest->tTrnxStatus;
		$this->tStDate = $merchantQueryTrnxBatchRequest->tStDate;
		$this->tEndDate = $merchantQueryTrnxBatchRequest->tEndDate;
		
	}	
	public function invoke()
	{
		//print("<br>"."!!!!!!"."</br>");
		$client=new SoapClient($this->address, array("style"=> SOAP_RPC,"use"=> SOAP_ENCODED, "trace"=>0,"exceptions" =>0));
		$client->soap_defencoding='utf-8';
		$client->decode_utf8=false;
		$result = $client->merchantQueryTrnxBatch_php($this->tTrnxStatus,$this->tStDate,$this->tEndDate);
		$this->res = (array)$result;
		return $this->generateResponse();
	}

	private function generateResponse()
	{	
		if($this->res[1]==TRUE)
		{
			$merchantQueryTrnxBatchItems = array();
			$count = count($this->res[0]);
			for ($i = 0; $i < $count; $i++) 
			{	
				$merchantQueryTrnxBatchItem	= new MerchantQueryTrnxBatchItem($this->res[0][$i][0],$this->res[0][$i][1],$this->res[0][$i][2],$this->res[0][$i][3],$this->res[0][$i][4],$this->res[0][$i][5]);
				$merchantQueryTrnxBatchItems[] = $merchantQueryTrnxBatchItem;
			}
			$merchantQueryTrnxBatchDetail = new  MerchantQueryTrnxBatchDetail($merchantQueryTrnxBatchItems);
		}
		$merchantQueryTrnxBatchResult = new MerchantQueryTrnxBatchResult($merchantQueryTrnxBatchDetail,$this->res[1],iconv("UTF-8", "GBK",$this->res[2]),iconv("UTF-8", "GBK",$this->res[3]));
		return $merchantQueryTrnxBatchResult;
	}
	
	public function showResult()
	{
		if($this->res[1]==TRUE)
		{
			print("<br>Sucess!!!"."</br>");
			
			
		}
		else
		{
			print("<br>Failed!!!"."</br>");
			print("<br>return code:".iconv("UTF-8", "GBK",$this->res[2])."</br>"); 
			print("<br>Error Message:".iconv("UTF-8", "GBK",$this->res[3])."</br>");
		}	
	}
}
class MerchantQueryTrnxBatchRequest
{
	public		
		$tTrnxStatus,
		$tStDate,
		$tEndDate;
	public function __construct($tTrnxStatus,$tStDate,$tEndDate)
	{
		$this->tTrnxStatus = $tTrnxStatus;
		$this->tStDate = $tStDate;
		$this->tEndDate = $tEndDate;
	}	
}
class MerchantQueryTrnxBatchResult{
	public $merchantQueryTrnxBatchDetail,$isSucess,$returnCode,$ErrorMessage;
	public function __construct($merchantQueryTrnxBatchDetail,$isSucess,$returnCode,$ErrorMessage)
	{
		$this->merchantQueryTrnxBatchDetail=$merchantQueryTrnxBatchDetail;
		$this->isSucess=$isSucess;
		$this->returnCode=$returnCode;
		$this->ErrorMessage=$ErrorMessage;
	}
}

class MerchantQueryTrnxBatchDetail{
	public $merchantQueryTrnxBatchItems;
	public function __construct($merchantQueryTrnxBatchItems)
	{
		$this->merchantQueryTrnxBatchItems=$merchantQueryTrnxBatchItems;
	}
}

class MerchantQueryTrnxBatchItem{
	public $MerchantTrnxNo,$TrnxTime,$PayAccountName,$PayAccount,$TrnxAmount,$TrnxStatus;
	public function __construct($MerchantTrnxNo,$TrnxTime,$PayAccountName,$PayAccount,$TrnxAmount,$TrnxStatus)
	{
		$this->MerchantTrnxNo = $MerchantTrnxNo;
		$this->TrnxTime = $TrnxTime;
		$this->PayAccountName = iconv("UTF-8", "GBK",$PayAccountName);
		$this->PayAccount = $PayAccount;
		$this->TrnxAmount = $TrnxAmount;
		$this->TrnxStatus = iconv("UTF-8", "GBK",$TrnxStatus);
	}
}



/*
MerchantPrintTrnxVoucher
*/
class MerchantPrintTrnxVoucher{
	protected  
		$address,
		$tMerchantTrnxNo;
		
	private $res;

	public function __construct($address,$merchantPrintTrnxVoucherRequest)
	{
		$this->address = $address;
		$this->tMerchantTrnxNo = $merchantPrintTrnxVoucherRequest->tMerchantTrnxNo;
	}	
	public function invoke()
	{
		//print("<br>"."!!!!!!"."</br>");
		$client=new SoapClient($this->address, array("style"=> SOAP_RPC,"use"=> SOAP_ENCODED, "trace"=>0,"exceptions" =>0));
		$client->soap_defencoding='utf-8';
		$client->decode_utf8=false;
		$result = $client->merchantPrintTrnxVoucher_php($this->tMerchantTrnxNo);
		$this->res = (array)$result;
		return $this->generateResponse();
	}

	private function generateResponse()
	{
		if($this->res[1]==TRUE)
		{
			$merchantPrintTrnxVoucherDetail = new  MerchantPrintTrnxVoucherDetail($this->res[0][0],$this->res[0][1],$this->res[0][2],$this->res[0][3],$this->res[0][4],$this->res[0][5],$this->res[0][6],$this->res[0][7],$this->res[0][8],$this->res[0][9],$this->res[0][10],$this->res[0][11],$this->res[0][12]);
		}
		$merchantPrintTrnxVoucherResult = new MerchantPrintTrnxVoucherResult($merchantPrintTrnxVoucherDetail,$this->res[1],iconv("UTF-8", "GBK",$this->res[2]),iconv("UTF-8", "GBK",$this->res[3]));
		return $merchantPrintTrnxVoucherResult;
	}
	
	public function showResult()
	{
		if($this->res[1]==TRUE)
		{
			print("<br>Sucess!!!"."</br>");
			
			
		}
		else
		{
			print("<br>Failed!!!"."</br>");
			print("<br>return code:".iconv("UTF-8", "GBK",$this->res[2])."</br>"); 
			print("<br>Error Message:".iconv("UTF-8", "GBK",$this->res[3])."</br>");
		}	
	}
}
class MerchantPrintTrnxVoucherRequest
{
	public		
		$tMerchantTrnxNo;
	public function __construct($tMerchantTrnxNo)
	{
		$this->tMerchantTrnxNo = $tMerchantTrnxNo;
	}	
}
class MerchantPrintTrnxVoucherResult{
	public $merchantPrintTrnxVoucherDetail,$isSucess,$returnCode,$ErrorMessage;
	public function __construct($merchantPrintTrnxVoucherDetail,$isSucess,$returnCode,$ErrorMessage)
	{
		$this->merchantPrintTrnxVoucherDetail=$merchantPrintTrnxVoucherDetail;
		$this->isSucess=$isSucess;
		$this->returnCode=$returnCode;
		$this->ErrorMessage=$ErrorMessage;
	}
}

class MerchantPrintTrnxVoucherDetail{
	public $VoucherNo,$AccountName,$AccountNo,$AccountBank,$AccountDBName,$AccountDBNo,$AccountDBBank,$TrnxAMT,$TrnxSN,$MerchantTrnxNo,$TrnxDate,$TrnxTime,$PrtTime;
	public function __construct($VoucherNo,$AccountName,$AccountNo,$AccountBank,$AccountDBName,$AccountDBNo,$AccountDBBank,$TrnxAMT,$TrnxSN,$MerchantTrnxNo,$TrnxDate,$TrnxTime,$PrtTime)
	{
		$this->VoucherNo=$VoucherNo;
		$this->AccountName=iconv("UTF-8", "GBK",$AccountName);
		$this->AccountNo=$AccountNo;
		$this->AccountBank=iconv("UTF-8", "GBK",$AccountBank);
		$this->AccountDBName=iconv("UTF-8", "GBK",$AccountDBName);
		$this->AccountDBNo=$AccountDBNo;
		$this->AccountDBBank=$AccountDBBank;
		$this->TrnxAMT=$TrnxAMT;
		$this->TrnxSN=$TrnxSN;
		$this->MerchantTrnxNo=$MerchantTrnxNo;
		$this->TrnxDate=$TrnxDate;
		$this->TrnxTime=$TrnxTime;
		$this->PrtTime=$PrtTime;		
	}
}
?>