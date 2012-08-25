<h1>Claim <?=$app['name']?> as your app:</h1>

<div id="claim_app_logo">
	<img src="<?= (!empty($app['images']['logo'][0]['source'])) ? $app['images']['logo'][0]['source'] : ''?>"/>
</div>

<div id="claim_app_text">
	<?=$app['name']?> currently has no owner which means that any updates will come from the staff at IWAAT.com.  
	If you are the creator or an employee of <?=$app['name']?> you can claim this app, giving you the ability to edit names, descriptions and urls.
	<br/><br/>
	In order to claim this app we ask that you send an email to <a href="mailto:<?=ADMIN_EMAIL_ADDRESS?>"><?=ADMIN_EMAIL_ADDRESS?></a> from the domain listed on the app<?=(!empty($app['hostname'])) ? ' (ex. you@' . $app['hostname'] . ')' : ''?>.
</div>