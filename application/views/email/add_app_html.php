<html>
	<head></head>
	<body>
		
		<img src="http://www.iwaat.com/images/iwaat_logo_small.png"/>
		<h2>Hi <?=$user_profile->username?>,</h2>
		
		<strong>Thanks for submitting "<?=$app_data['name']?>" to the I Want An App That... (IWAAT) library.</strong>  
		We are currently reviewing your application to ensure it fits within our guidelines.  
		We'll send you another email within the next 48 hours with the updated status.
		
		<br/><br/>
		
		If you have any concerns, suggestions, or comments feel free to email me at <a href="mailto:<?=ADMIN_EMAIL_ADDRESS?>" target="_blank"><?=ADMIN_EMAIL_ADDRESS?></a>, or just reply to this message.
		
		<br/><br/>
		Enjoy,
		<br/>
		Justin Lucas, Founder of IWAAT.com
		
		<br/><br/>
		
		<p style="font-size:10px; color:grey">
		&copy; IWAAT.com, All rights reserved.
		<br/>
		To unsubscribe from all future emails, please edit your <a href="http://www.iwaat.com/account/profile" target="_blank">profile</a>.
		</p>
	</body>
</html>