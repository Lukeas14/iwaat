<?php $this->load->view('includes/header'); ?>

<div id="hauth_register_wrapper">
	<h1>Complete your <?=$provider?> registration:</h1>

	<form action="" method="post" accept-charset="utf-8">
		<input type="hidden" name="provider" value="<?=$provider?>"/>
		<input type="hidden" name="<?=strtolower($provider)?>_id" value="<?=$user_data['hauth_id']?>"/>
		<input type="hidden" name="hauth_username" value="<?=$user_data['hauth_username']?>"/>
		<input type="hidden" name="hauth_description" value="<?=$user_data['hauth_description']?>"/>
	<div class="">
		<p>
			<label for="first_name">First Name:</label>
			<input type="text" name="first_name" maxlength="64" value="<?=set_value('first_name', $user_data['first_name'])?>"/>
		</p>
		<p>
			<label for="last_name">Last Name:</label>
			<input type="text" name="last_name" maxlength="64" value="<?=set_value('last_name', $user_data['last_name'])?>"/>
		</p>
		<p>
			<label for="email">Email:</label>
			<input type="text" name="email" value="<?=set_value('email', $user_data['email'])?>"/>
		</p>
		<p>
			<label>&nbsp;</label>
			<input type="submit" class="submit" value="Complete Registration"/>
		</p>
	</div>
</div>

<?php $this->load->view('includes/footer'); ?>