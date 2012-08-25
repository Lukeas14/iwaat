<h1>Register to claim <?=$app['name']?> as your app:</h1>

<div id="claim_app_logo">
	<img src="<?= (!empty($app['images']['logo'][0]['source'])) ? $app['images']['logo'][0]['source'] : ''?>"/>
</div>

<div id="claim_app_text">
	<?=$app['name']?> currently has no owner which means that any updates will come from the staff at IWAAT.com.  
	If you are the creator or an employee of <?=$app['name']?> you can claim this app, giving you the ability to edit names, descriptions, urls and other data.
	<br/><br/>
	To speed up the process please ensure that the email you register with uses the same domain as the app you are claiming or the company that owns it.
	<br/>
	<?=(!empty($app['hostname'])) ? ' (ex. you@' . $app['hostname'] . ')' : ''?>.
</div>