<?php $this->load->view('includes/header'); ?>

<div id="change_password_wrapper">

	<h1>Change Password</h1>
	<form action="/account/change_password" method="post" accept-charset="utf-8">
	<div id="change_password_form">
		<p>
			<label for="old" style="width:150px; text-align:left; margin-left:0px;">Old Password:</label>
			<input type="password" name="old" value="<?=set_value('old')?>"/>
		</p>
		<p>
			<label for="new" style="width:150px; text-align:left; margin-left:0px;">New Password:</label>
			<input type="password" name="new" value="<?=set_value('new')?>"/>
		</p>
		<p>
			<label for="new_confirm" style="width:150px; text-align:left; margin-left:0px;">Confirm New Password:</label>
			<input type="password" name="new_confirm" value="<?=set_value('new_confirm')?>"/>
		</p>
		<p>
			<label style="width:150px; text-align:left; margin-left:0px;">&nbsp;</label>
			<input type="submit" class="submit" name="submit" value="Change Password"/>
			<a href="/account/profile" style="margin:0 0 0 10px;">Return to profile page</a>
		</p>
	</form>
	</div>
		
</div>

<?php $this->load->view('includes/footer'); ?>