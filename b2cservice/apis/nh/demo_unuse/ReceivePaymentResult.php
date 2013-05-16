<!--<URL>http://www.163.com</URL>-->
<!--如果商户选择通知服务器通知支付结果，则该url设置成客户浏览器跳转的url，如不设置，客户浏览器将跳转到该页面-->
<html>
<head>
<title>TrustPay - 显示签名信息</title>
<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>
</head>
<body>
<?php
require_once (dirname(__FILE__) . "/../../uploads/include/common.inc.php");
require_once DEDEINC.'/payment/abchina.php';
require_once DEDEINC.'/memberlogin.class.php';
?>
<?php
// $data is assumed to contain the data to be signed
//$originData = "PE1TRz48TWVzc2FnZT48VHJ4UmVzcG9uc2U+PFJldHVybkNvZGU+NTAyMjEzPC9SZXR1cm5Db2RlPjxFcnJvck1lc3NhZ2U+0enWpM280M7R6dakwuu07c7zo6E8L0Vycm9yTWVzc2FnZT48L1RyeFJlc3BvbnNlPjwvTWVzc2FnZT48U2lnbmF0dXJlLUFsZ29yaXRobT5TSEExd2l0aFJTQTwvU2lnbmF0dXJlLUFsZ29yaXRobT48U2lnbmF0dXJlPktMaW1YaW9oYXNOaWJ3ZnpqNkE0cElFT2t1eGt3b09NbXlkRXNOdnpYb2JIVGdzZElhUWFoMzExUUxNYWVRZFBPWEtzeGgwOW0yemljZzFQcjYzUVlJZDk2cXVSOGtpMDlybnpPdFA1STlrNEF2N2krNmJqdlVUQWI2K1dxdjlaTlN4MHdTV2VYRm9Cd25RNDBUWU0wYW5FOXM1d1lheGdvVWVLOGZiZWlhYz08L1NpZ25hdHVyZT48L01TRz4=";
//$originData = "PE1TRz48TWVzc2FnZT48VHJ4UmVzcG9uc2U+PFJldHVybkNvZGU+MDAwMDwvUmV0dXJuQ29kZT48RXJyb3JNZXNzYWdlPjwvRXJyb3JNZXNzYWdlPjxFQ01lcmNoYW50VHlwZT5CMkM8L0VDTWVyY2hhbnRUeXBlPjxNZXJjaGFudElEPjIxMTAwMDAwNDYzM0EwMTwvTWVyY2hhbnRJRD48VHJ4VHlwZT5QYXlSZXN1bHQ8L1RyeFR5cGU+PE9yZGVyTm8+T04yMDEyMDIyNzAwMDI8L09yZGVyTm8+PEFtb3VudD4wLjAxPC9BbW91bnQ+PEJhdGNoTm8+MDAwMDYyPC9CYXRjaE5vPjxWb3VjaGVyTm8+MDAzMjE3PC9Wb3VjaGVyTm8+PEhvc3REYXRlPjIwMTIvMDIvMjc8L0hvc3REYXRlPjxIb3N0VGltZT4xNTo0MToxMjwvSG9zdFRpbWU+PE1lcmNoYW50UmVtYXJrcz5IaSE8L01lcmNoYW50UmVtYXJrcz48UGF5VHlwZT5QQVkwMTwvUGF5VHlwZT48Tm90aWZ5VHlwZT4wPC9Ob3RpZnlUeXBlPjxQYXlJUD4xMC4yMzMuNC43NjwvUGF5SVA+PFBheVJlZmVyZXI+IDwvUGF5UmVmZXJlcj48aVJzcFJlZj4zNjAyMjcwMDAxMTU8L2lSc3BSZWY+PC9UcnhSZXNwb25zZT48L01lc3NhZ2U+PFNpZ25hdHVyZS1BbGdvcml0aG0+U0hBMXdpdGhSU0E8L1NpZ25hdHVyZS1BbGdvcml0aG0+PFNpZ25hdHVyZT5Jb2w2VUhXaS9KS0xBUUR4M1lzYlRoYk9QZ1N2Tk1jRlh0SzR3MTkvSmdCQjRSRDZqeG1NbnZDeDI1b2xDbE9ud05GVlFGSG9FUnovaGsvaGkzbC8xcFgzTjhvZXoyTEFONy9OZ0h1alJVNWhia0JEQzVNL1hkNG5Gc1Y0Q1krSDhTeDFReEY0bE9ud3NIWWhjSHd5SGFSMGhkcXZNa29tdnJhNVh0ZnZhS289PC9TaWduYXR1cmU+PC9NU0c+";
$originData = $_POST["MSG"];
$xmlData = base64_decode($originData);
//echo "xmlData is: ".$xmlData;
//echo "</br> ";

//$newXMLData = htmlentities($xmlData);
//echo htmlentities($newXMLData);
//echo "</br> ";
//$xmlData = "<MSG><Message><TrxResponse><ReturnCode>502213</ReturnCode><ErrorMessage>验证图形验证码错误！</ErrorMessage></TrxResponse></Message><Signature-Algorithm>SHA1withRSA</Signature-Algorithm><Signature>KLimXiohasNibwfzj6A4pIEOkuxkwoOMmydEsNvzXobHTgsdIaQah311QLMaeQdPOXKsxh09m2zicg1Pr63QYId96quR8ki09rnzOtP5I9k4Av7i+6bjvUTAb6+Wqv9ZNSx0wSWeXFoBwnQ40TYM0anE9s5wYaxgoUeK8fbeiac=</Signature></MSG>";

//$doc = new DomDocument;
//$doc->loadHTML($xmlData);
//$ErrorMessage = $doc->getElementsByTagName('errormessage');
//foreach($ErrorMessage as $errormes)
//{
//	$ems = $errormes->nodeValue;
//	break;
//}
//echo $ems;
//echo "</br> ";


$retCodStartStr = "<ReturnCode>";
$retCodEndStr = "</ReturnCode>";
$retCodStartPos = strpos($xmlData,$retCodStartStr);
$retCodEndPos = strpos($xmlData,$retCodEndStr);
$retCode = substr($xmlData,$retCodStartPos+strlen("<ReturnCode>"),$retCodEndPos-$retCodStartPos-strlen("<ReturnCode>"));//注意不可使用$ReturnCode，否则会什么都不显示，可能是保留字
//echo $retCode;
//echo "</br> ";


$errMesStartStr = "<ErrorMessage>";
$errMesEndStr = "</ErrorMessage>";
$errMesStartPos = strpos($xmlData,$errMesStartStr);
$errMesEndPos = strpos($xmlData,$errMesEndStr);
$ErrorMessage = substr($xmlData,$errMesStartPos+strlen("<ErrorMessage>"),$errMesEndPos-$errMesStartPos-strlen("<ErrorMessage>"));
//echo $ErrorMessage;
//echo "</br> ";

$sigStartStr = "<Signature>";
$sigEndStr = "</Signature>";
$sigStartPos = strpos($xmlData,$sigStartStr);
$sigEndPos = strpos($xmlData,$sigEndStr);
$signature = substr($xmlData,$sigStartPos+strlen("<Signature>"),$sigEndPos-$sigStartPos-strlen("<Signature>"));
//echo $signature;
//echo "</br> ";

$mesStartStr = "<Message>";
$mesEndStr = "</Message>";
$mesStartPos = strpos($xmlData,$mesStartStr);
$mesEndPos = strpos($xmlData,$mesEndStr);
$message = substr($xmlData,$mesStartPos+strlen("<Message>"),$mesEndPos-$mesStartPos-strlen("<Message>"));
//echo $message;
//echo "</br> ";


//验证签名有效性
$data = $message;
$fp = fopen("/usr/local/tomcat7/bin/Certificate/MainServer.0001.pem", "r");
$pub_key = fread($fp, 8192);
//echo "</br>";
$pubkeyid = openssl_get_publickey($pub_key);
//echo "pubkeyid is: ".$pubkeyid;
//echo "</br> ";
//echo "signature is: ".$signature;
$sig=base64_decode($signature);
//echo "</br> ";
if(openssl_verify($data,$sig,$pubkeyid)==1)
{
	echo "签名验证成功";
	echo "</br>";
	if($retCode=="0000")
	{
		
		
        
		echo "交易成功！";
		echo "</br> ";
		//订单号
		$OrderNoStartStr = "<OrderNo>";
		$OrderNoEndStr = "</OrderNo>";
		$OrderNoStartPos = strpos($xmlData,$OrderNoStartStr);
		$OrderNoEndPos = strpos($xmlData,$OrderNoEndStr);
		$OrderNo = substr($xmlData,$OrderNoStartPos+strlen("<OrderNo>"),$OrderNoEndPos-$OrderNoStartPos-strlen("<OrderNo>"));
		echo "订单号：".$OrderNo;
		echo "</br> ";

		//订单金额
		$AmountStartStr = "<Amount>";
		$AmountEndStr = "</Amount>";
		$AmountStartPos = strpos($xmlData,$AmountStartStr);
		$AmountEndPos = strpos($xmlData,$AmountEndStr);
		$Amount = substr($xmlData,$AmountStartPos+strlen("<Amount>"),$AmountEndPos-$AmountStartPos-strlen("<Amount>"));
		echo "订单金额：".$Amount;
		echo "</br> ";
		/*
		//批次号
		$BatchNoStartStr = "<BatchNo>";
		$BatchNoEndStr = "</BatchNo>";
		$BatchNoStartPos = strpos($xmlData,$BatchNoStartStr);
		$BatchNoEndPos = strpos($xmlData,$BatchNoEndStr);
		$BatchNo = substr($xmlData,$BatchNoStartPos+strlen("<BatchNo>"),$BatchNoEndPos-$BatchNoStartPos-strlen("<BatchNo>"));
		echo "批次号：".$BatchNo;
		echo "</br> ";

		//传票号
		$VoucherNoStartStr = "<VoucherNo>";
		$VoucherNoEndStr = "</VoucherNo>";
		$VoucherNoStartPos = strpos($xmlData,$VoucherNoStartStr);
		$VoucherNoEndPos = strpos($xmlData,$VoucherNoEndStr);
		$VoucherNo = substr($xmlData,$VoucherNoStartPos+strlen("<VoucherNo>"),$VoucherNoEndPos-$VoucherNoStartPos-strlen("<VoucherNo>"));
		echo "批次号：".$VoucherNo;
		echo "</br> ";
		
		//会计日期
		$HostDateStartStr = "<HostDate>";
		$HostDateEndStr = "</HostDate>";
		$HostDateStartPos = strpos($xmlData,$HostDateStartStr);
		$HostDateEndPos = strpos($xmlData,$HostDateEndStr);
		$HostDate = substr($xmlData,$HostDateStartPos+strlen("<HostDate>"),$HostDateEndPos-$HostDateStartPos-strlen("<HostDate>"));
		echo "会计日期：".$HostDate;
		echo "</br> ";		
		
		//会计时间
		$HostTimeStartStr = "<HostTime>";
		$HostTimeEndStr = "</HostTime>";
		$HostTimeStartPos = strpos($xmlData,$HostTimeStartStr);
		$HostTimeEndPos = strpos($xmlData,$HostTimeEndStr);
		$HostTime = substr($xmlData,$HostTimeStartPos+strlen("<HostTime>"),$HostTimeEndPos-$HostTimeStartPos-strlen("<HostTime>"));
		echo "会计时间：".$HostTime;
		echo "</br> ";		
		
		//备注
		$MerchantRemarksStartStr = "<MerchantRemarks>";
		$MerchantRemarksEndStr = "</MerchantRemarks>";
		$MerchantRemarksStartPos = strpos($xmlData,$MerchantRemarksStartStr);
		$MerchantRemarksEndPos = strpos($xmlData,$MerchantRemarksEndStr);
		$MerchantRemarks = substr($xmlData,$MerchantRemarksStartPos+strlen("<MerchantRemarks>"),$MerchantRemarksEndPos-$MerchantRemarksStartPos-strlen("<MerchantRemarks>"));
		echo "备注：".$MerchantRemarks;
		echo "</br> ";		
		
		
		//支付方式
		$PayTypeStartStr = "<PayType>";
		$PayTypeEndStr = "</PayType>";
		$PayTypeStartPos = strpos($xmlData,$PayTypeStartStr);
		$PayTypeEndPos = strpos($xmlData,$PayTypeEndStr);
		$PayType = substr($xmlData,$PayTypeStartPos+strlen("<PayType>"),$PayTypeEndPos-$PayTypeStartPos-strlen("<PayType>"));
		echo "支付方式：".$PayType;
		echo "</br> ";				
		
		//通知方式
		$NotifyTypeStartStr = "<NotifyType>";
		$NotifyTypeEndStr = "</NotifyType>";
		$NotifyTypeStartPos = strpos($xmlData,$NotifyTypeStartStr);
		$NotifyTypeEndPos = strpos($xmlData,$NotifyTypeEndStr);
		$NotifyType = substr($xmlData,$NotifyTypeStartPos+strlen("<NotifyType>"),$NotifyTypeEndPos-$NotifyTypeStartPos-strlen("<NotifyType>"));
		echo "通知方式：".$NotifyType;
		echo "</br> ";			
		*/
		//todo：商户更新相关数据库操作
		
		$pay = new Abchina;
		$cfg_ml = new MemberLogin();
		
		//确认用户登录信息
        if($cfg_ml->IsLogin())
        {
            $pay->mid = $cfg_ml->M_ID;
        }
        else
        {
            $username = trim($username);
            $password = trim($password);
            
            if(empty($username) || $password)
            {
                ShowMsg("请选登录！","-1",0,2000);
                exit();
            }
            
            $rs = $cfg_ml->CheckUser($username,$password);
            if($rs==0)
            {
                ShowMsg("用户名不存在！","-1",0,2000);
                exit();
            }
            else if($rs==-1)
            {
                ShowMsg("密码错误！","-1",0,2000);
                exit();
            }
            $pay->mid = $cfg_ml->M_ID;
        }

		$pay->success_db($OrderNo);

	}
	else
	{
		echo "交易失败！";
		echo "</br> ";
		echo "错误码:".$retCode;
		echo "</br> ";
		echo "错误信息：".$ErrorMessage;
		echo "</br> ";
	}
}
else
{
	echo "签名验证失败，该通知内容不可信";
}


// free the key from memory
openssl_free_key($pubkeyid);
?> 
</body>
</html>
