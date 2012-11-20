<html>
<head>
	<title>PayPal Merchant SDK - DoDirectPayment API</title>
</head>
<body>
	<!--<form method="POST" action="DoDirectPayment.php" name="DoDirectPaymentForm">

		First name <input type="text" name="firstName" value="John">
		<br>Last name <input type="text" name="lastName" value="Doe">
		<br>Card type 
			<select name="creditCardType">
				<option value="Visa" selected="selected">Visa</option>
				<option value="MasterCard">MasterCard</option>
				<option value="Amex">American Express</option>
			</select>
		<br>Card number <input type="text" size="19" maxlength="19" name="creditCardNumber">
		<br>Expiry date <input name="expDateMonth" type="text" size="2"><input name="expDateYear" type="text" size="4">
		<br>CVV <input type="text" size="3" name="cvv2Number" value="962">
		<br>Amount <input type="text" size="5" maxlength="7" name="amount" value="1.00"> NOK			
		<br>Billing address <input type="text" size="25" maxlength="100" name="street" value="1 Main St">
		<br>City <input type="text" size="25" maxlength="40" name="city" value="San Jose">
		<br>State <input type="text" name="state" value="CA">
		<br>Zip <input type="text" size="10" maxlength="10" name="zip" value="95131"> (5 or 9 digits)
		<br>Country <input type="text" size="10" maxlength="10" name="country" value="NO">
		<br><button type="submit" name="DoDirectPaymentBtn">DoDirectPayment</button>
	</form>-->

<?php
	//extract data from the post
	/*extract($_POST);
	
	//set POST variables
	$fields = array(
		'USER'				=> 'merch_1353441316_biz_api1.gmail.com',
		'PWD'				=> '1353441362',
		'SIGNATURE'			=> 'Ai1PaghZh5FmBLCDCTQpwG8jB264AVcYN-vTmBMyVb59nFkRZ87ZZxzE',
		'METHOD'			=> 'DoDirectPayment',
		'VERSION'			=> '95',
		'IPADDRESS'			=> $_SERVER['REMOTE_ADDR'],   	#Buyer's IP address, recorded to detect possible fraud
		'AMT'				=> '23',    					#The amount authorized
		'ACCT'				=> '4641631486853053', 			#The credit card number
		'CREDITCARDTYPE'	=> 'VISA',    					#The type of credit card 
		'CVV2'				=> '123',   					#The CVV2 number
		'FIRSTNAME'			=> 'James',
		'LASTNAME'			=> 'Smith',
		'STREET'			=> 'FirstStreet',
		'CITY'				=> 'SanJose',
		'STATE'				=> 'CA',
		'ZIP'				=> '95131',
		'COUNTRYCODE'		=> 'US',
		'CURRENCYCODE'		=> 'USD',    					#The currency, e.g. US dollars
		'EXPDATE'			=> '052015',					#Expir
	);

	//open connection
	$ch = curl_init();
	
	//set the url, number of POST vars, POST data
	curl_setopt($ch, CURLOPT_URL, 'https://api-3t.sandbox.paypal.com/nvp');
	curl_setopt($ch, CURLOPT_POST, count($fields));
	curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($fields));
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	
	//execute post
	$result = curl_exec($ch);
	
	//close connection
	curl_close($ch);*/

	$result = 'TIMESTAMP=2012%2d11%2d20T20%3a00%3a20Z&CORRELATIONID=4986d2e273696&ACK=Success&VERSION=95&BUILD=4137385&AMT=23%2e00&CURRENCYCODE=USD&AVSCODE=X&CVV2MATCH=M&TRANSACTIONID=09617561PX030540W';
	
	parse_str($result, $data);
	var_export($data);

/*Request 
-------
Endpoint URL: https://api-3t.sandbox.paypal.com/nvp
HTTP method: POST
POST data:
USER=insert_merchant_user_name_here
&PWD=insert_merchant_password_here
&SIGNATURE=insert_merchant_signature_value_here
&METHOD=DoDirectPayment
&IPADDRESS=127.0.0.1   			#Buyer's IP address, recorded to detect possible fraud
&AMT=23    						#The amount authorized
&ACCT=4641631486853053   		#The credit card number
&CREDITCARDTYPE=VISA    		#The type of credit card 
&CVV2=123   					#The CVV2 number
&FIRSTNAME=James
&LASTNAME=Smith
&STREET=FirstStreet
&CITY=SanJose
&STATE=CA
&ZIP=95131
&COUNTRYCODE=US
&CURRENCYCODE=USD    			#The currency, e.g. US dollars
&EXPDATE=052015					#Expiration date of the credit card


Response
--------
&ACK=Success
&AMT=23%2e00
&CURRENCYCODE=USD
&AVSCODE=X
&CVV2MATCH=M
&TRANSACTIONID=4AA45196YV4521234
...*/

?>
</body>
</html>