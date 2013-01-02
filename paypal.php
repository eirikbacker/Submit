<?php
	//get_header();

	if (isset($_POST["cvv2Number"]))
	{
		//Sett opp request for å reservere beløp på konto
		$fields = array(
			'USER'				=> 'post_1356206687_biz_api1.kf-trondheim.no',
			'PWD'				=> '1356206719',
			'SIGNATURE'			=> 'A9gD-q9opwyrbfpHib8jAcGPg4zyA2NBuxpE7Sbss7GDWXIn4QDypljC',
			'METHOD'			=> 'DoDirectPayment',
			'PAYMENTACTION'		=> 'Authorization',
			'VERSION'			=> '95',
			'IPADDRESS'			=> '127.0.0.1',   									#Buyer's IP address, recorded to detect possible fraud
			'AMT'				=> '23',    										#The amount authorized
			'ACCT'				=> $_POST["creditCardNumber"], 						#The credit card number '4547925979831641'
			'CREDITCARDTYPE'	=> 'VISA',    										#The type of credit card 
			'CVV2'				=> $_POST["cvv2Number"],   							#The CVV2 number '123'
			'CURRENCYCODE'		=> 'USD',    										#The currency, e.g. US dollars
			'EXPDATE'			=> $_POST["expDateMonth"] . $_POST["expDateYear"], 	#Expir '072017'
			'FIRSTNAME'			=> $_POST["firstname"],
			'LASTNAME'			=> $_POST["lastname"],
			'STREET'			=> 'FirstStreet', 	#hentes fra profil
			'CITY'				=> 'SanJose', 		#hentes fra profil
			'STATE'				=> 'CA', 			#hentes fra profil
			'ZIP'				=> '95131',	 		#hentes fra profil
			'COUNTRYCODE'		=> 'NO'
		);

		//open connection
		$ch = curl_init();
		
		//set the url, number of POST vars, POST data
		curl_setopt($ch, CURLOPT_URL, 'https://api-3t.sandbox.paypal.com/nvp');
		curl_setopt($ch, CURLOPT_POST, count($fields));
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($fields));
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0); 	 #Fjern disse to når vi går LIVE
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); #Altså denne også.
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		
		//execute post
		$result = curl_exec($ch);
		parse_str($result, $data);

		//echo "INFO: " . curl_getinfo($ch, CURLINFO_HTTP_CODE);
		//echo "ERROR: " . curl_error($ch);

		//close connection
		curl_close($ch);

		foreach ($data as $d=>$v)
		{
			echo $d . " = " . $data[$d] . "<br>";
		}

		if ($data["ACK"] == "Success")
		{
			echo "<h1>Reservert!</h1>";
		}
		else
		{
			echo "<h1>Ooops!</h1><p>";
			echo $data["L_LONGMESSAGE0"] . "</p>";
		}

		if ($data["ACK"] == "Success")
		{
			//Sett opp request for å trekke beløp på konto
			$fields = array(
				'USER'				=> 'post_1356206687_biz_api1.kf-trondheim.no',
				'PWD'				=> '1356206719',
				'SIGNATURE'			=> 'A9gD-q9opwyrbfpHib8jAcGPg4zyA2NBuxpE7Sbss7GDWXIn4QDypljC',
				'METHOD'			=> 'DoCapture',
				'VERSION'			=> '95',   					#Buyer's IP address, recorded to detect possible fraud
				'AMT'				=> '23',    				#The amount authorized
				'CURRENCYCODE'		=> 'USD',    				#The currency, e.g. US dollars
				'AUTHORIZATIONID' 	=> $data["TRANSACTIONID"],
				'COMPLETETYPE'		=> 'Complete'
			);

			$ch = curl_init();
		
			//set the url, number of POST vars, POST data
			curl_setopt($ch, CURLOPT_URL, 'https://api-3t.sandbox.paypal.com/nvp');
			curl_setopt($ch, CURLOPT_POST, count($fields));
			curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($fields));
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0); 	 #Fjern disse to når vi går LIVE
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); #Altså denne også.
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			
			//execute post
			$result = curl_exec($ch);
			parse_str($result, $data);

			//close connection
			curl_close($ch);

			foreach ($data as $d=>$v)
			{
				echo $d . " = " . $data[$d] . "<br>";
			}

			if ($data["ACK"] == "Success")
			{
				echo "<h1>Grattler!</h1>";
			}
			else
			{
				echo "<h1>Ooops!</h1>";
				echo $data["L_LONGMESSAGE0"];
			}

			echo "<p><a href='?'>Betal igjen</a></p>";
		
		}

	}
	else
	{
?>
<form method="post" class="form-horizontal">
	<div class="control-group">
		<label class="control-label" for="firstName">Navn</label>
		<div class="controls">
			<input type="text" class="span3" id="firstName" name="firstname" placeholder="Fornavn">
			<input type="text" class="span3" id="lastName" name="lastName" placeholder="Etternavn">
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="creditCardNumber">Kort</label>
		<div class="controls">
			<input type="text" class="span3" id="creditCardNumber" name="creditCardNumber" value="4547925979831641" placeholder="Kortnummer">			
			<select class="span1" id="expDateYear" name="expDateMonth">
				<?php foreach(range(1, 12) as $m){ ?>
					<option value="<?php echo str_pad($m, 2, '0', STR_PAD_LEFT) . ($m==7 ? '" selected="selected':''); ?>">
						<?php echo str_pad($m, 2, '0', STR_PAD_LEFT); ?>
					</option>
				<?php } ?>
			</select>
			<select class="span1" id="expDateMonth" name="expDateYear">
				<?php foreach(range($now = date('Y'), $now + 20) as $y){ ?>
					<option value="<?php echo $y . ($y==2017 ? '" selected="selected':''); ?>"><?php echo $y; ?></option>
				<?php } ?>
			</select>
			<input type="text" class="span1" id="cvv2Number" name="cvv2Number" placeholder="CVV" value="123" maxlength="3">
		</div>
	</div>
	<div class="control-group">
		<div class="controls">
			<input type="hidden" name="creditCardType" value="">
			<button type="submit" class="btn btn-primary" name="DoDirectPaymentBtn">Send inn og betal</button>
		</div>
	</div>
</form>

<?php
	}

	//get_footer();