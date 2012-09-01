<?php $this->load->view('includes/header'); ?>

<div id="claim_app_wrapper">

	<h1>You just claimed the app  <?=$app['name']?>!</h1>

	<div id="claim_app_logo">
		<img src="<?= (!empty($app['images']['logo'][0]['source'])) ? $app['images']['logo'][0]['source'] : ''?>"/>
	</div>

	<div id="claim_app_text">
		Will we now review this claim to ensure that you are indeed the creator or an employee of <?=$app['name']?>. 
		Once approved you will be able to edit names, descriptions, urls and other data displayed on your app's profile.
		<br/><br/>
		To speed up the process please ensure that the email linked to your <a href='/account/profile'>account</a> uses the same domain as the app you are claiming or the company that owns it.
		<br/>
		<?=(!empty($app['hostname'])) ? ' (ex. you@' . $app['hostname'] . ')' : ''?>.
	</div>
</div>

<?php $this->load->view('includes/footer'); ?>