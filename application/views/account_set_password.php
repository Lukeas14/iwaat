<?php $this->load->view('includes/header'); ?>

<div id="set_password_wrapper">

	<h1>Set Password</h1>
	<form action="/account/set_password" method="post" accept-charset="utf-8">
	<div id="set_password_form">
		<p>
			<label for="new" style="width:150px; text-align:left; margin-left:0px;">New Password:</label>
			<input type="password" name="new_password" value="<?=set_value('new_password')?>"/>
		</p>
		<p>
			<label for="new_confirm" style="width:150px; text-align:left; margin-left:0px;">Confirm New Password:</label>
			<input type="password" name="new_password_confirm" value="<?=set_value('new_password_confirm')?>"/>
		</p>
		<p>
			<label style="width:150px; text-align:left; margin-left:0px;">&nbsp;</label>
			<input type="submit" class="submit" name="submit" value="Set Password"/>
			<a href="/account/profile" style="margin:0 0 0 10px;">Return to profile page</a>
		</p>
	</form>
	</div>
		
</div>

<?php $this->load->view('includes/footer'); ?>