<?php
	$user = 'peirix_1355856228_biz_api1.gmail.com';
	$pwd = '1355856247';
	$sign = 'AFcWxV21C7fd0v3bYYYRCpSSRl31AknU3jZcB39pLJZnCbjtx6CiRMj9';
	if (isset($_POST["DoDirectPaymentBtn"]))
	{
		$fields = array(
			'USER'								=> $user,
			'PWD'								=> $pwd,
			'SIGNATURE'							=> $sign,
			'METHOD'							=> 'SetExpressCheckout',
			'VERSION'							=> '89',
			'cancelUrl'							=> 'http://www.palstrom.no/paypal?status=cancel',    #For use if the consumer decides not to proceed with payment 
			'returnUrl'							=> 'http://www.palstrom.no/paypal?status=ok',   #For use if the consumer proceeds with payment
			'PAYMENTREQUEST_0_CURRENCYCODE'		=> 'NOK',    #The currency, e.g. US dollars
			'PAYMENTREQUEST_0_PAYMENTACTION'	=> 'SALE',    #Payment for a sale  
			'PAYMENTREQUEST_0_AMT'				=> '2200',    #The amount authorized
			'L_PAYMENTREQUEST_0_ITEMCATEGORY0'	=> 'Digital',    #The item category must be set to Digital 
			'L_PAYMENTREQUEST_0_NAME0'			=> 'STERK innsending - Radio',
			'L_PAYMENTREQUEST_0_QTY0'			=> '1',
			'L_PAYMENTREQUEST_0_AMT0'			=> '600',
			'L_PAYMENTREQUEST_0_ITEMCATEGORY1'	=> 'Digital',    #The item category must be set to Digital 
			'L_PAYMENTREQUEST_0_NAME1'			=> 'STERK innsending - Web',
			'L_PAYMENTREQUEST_0_QTY1'			=> '2',
			'L_PAYMENTREQUEST_0_AMT1'			=> '800'
		);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'https://api-3t.sandbox.paypal.com/nvp');
		curl_setopt($ch, CURLOPT_VERBOSE, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($ch, CURLOPT_POST, count($fields));
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($fields));
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 120);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_HEADER, true);
		
		//execute post
		$result = curl_exec($ch);
		
		echo "INFO: " . curl_getinfo($ch, CURLINFO_HTTP_CODE);
		echo "ERROR: " . curl_error($ch);

		curl_close($ch);
		
		//$result = performCurl($fields);
		parse_str($result, $data);
		if ($data["ACK"] == "Success")
			echo("<script>location.href='https://www.sandbox.paypal.com/webscr?cmd=_express-checkout&token=" . $data["TOKEN"] . "';</script>");

		/*
		$items = array();
		$items[] = array(
			"name" => "STERK innsending - Radio",
			"amt" => 600,
			"qty" => 1
		);

		$items[] = array(
			"name" => "STERK innsending - Web",
			"amt" => 800,
			"qty" => 2
		);

		$paymentAmount = 2200;
		$currencyCodeType = "NOK";
		$returnURL = "http://www.palstrom.no/paypal/?status=ok";
		$cancelURL = "http://www.palstrom.no/paypal/?status=cancel";

		$resArray = SetExpressCheckoutDG($paymentAmount, $currencyCodeType, $paymentType,  $returnURL, $cancelURL, $items );
		$ack = strtoupper($resArray["ACK"]);
        if($ack == "SUCCESS" || $ack == "SUCCESSWITHWARNING")
        {
                $token = urldecode($resArray["TOKEN"]);
                RedirectToPayPalDG($token);
        } 
        else  
        {
                //Display a user friendly Error on the page using any of the following error information returned by PayPal
                $ErrorCode = urldecode($resArray["L_ERRORCODE0"]);
                $ErrorShortMsg = urldecode($resArray["L_SHORTMESSAGE0"]);
                $ErrorLongMsg = urldecode($resArray["L_LONGMESSAGE0"]);
                $ErrorSeverityCode = urldecode($resArray["L_SEVERITYCODE0"]);
                
                echo "SetExpressCheckout API call failed. ";
                echo "Detailed Error Message: " . $ErrorLongMsg;
                echo "Short Error Message: " . $ErrorShortMsg;
                echo "Error Code: " . $ErrorCode;
                echo "Error Severity Code: " . $ErrorSeverityCode;
        }
        */
	}
	else if (isset($_GET["PayerID"]))
	{
		$fields = array(
			'USER'		=> $user,
			'PWD'		=> $pwd,
			'SIGNATURE'	=> $sign,
			'METHOD'	=> 'GetExpressCheckoutDetails',
			'VERSION'	=> '89',
			'TOKEN'		=> $_GET["token"]
		);

		$result = performCurl($fields);
		parse_str($result, $data);
		foreach ($data as $d=>$v)
		{
			echo $d . " = " . $data[$d] . "<br>";
		}
	}

	function performCurl($fields)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'https://api-3t.sandbox.paypal.com/nvp');
		curl_setopt($ch, CURLOPT_POST, count($fields));
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($fields));
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		
		//execute post
		$result = curl_exec($ch);
		curl_close($ch);

		return $result;
	}
?>

<html>
<head>
	<title>PayPal Merchant SDK - DoDirectPayment API</title>
</head>
<body>
	<form method="POST" action="index.php" name="DoDirectPaymentForm">
		<br>u: peirix_1355856733_per@gmail.com
		<br>p: 355856689
		<br><button type="submit" name="DoDirectPaymentBtn">Betal!</button>
	</form>
</body>
</html>