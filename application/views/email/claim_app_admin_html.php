<html>
	<head></head>
	<body>
		
		<img src="http://www.iwaat.com/images/iwaat_logo_small.png"/>
		<br/>
		
		"<?=$app['name']?>" has been claimed by user <?=$user_profile->username?>.</strong>
		
		<br/><br/>

		Name: <?=$user_profile->username?>
		<br/>
		Email: <?=$user_profile->email?>
		<br/>
		App: <a href="http://www.iwaat.com/app/<?=$app['slug']?>"><?=$app['name']?></a>

		
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