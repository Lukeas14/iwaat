<?php $this->load->view('includes/header'); ?>

<div id="account_wrapper">
	
	<?php $this->load->view('account_nav'); ?>
	
	<div id="account_content">
		
		<h1>Edit My Profile</h1>
		<form action="" method="post" accept-charset="utf-8">
		<div class="account_profile_form">
			<p>
				<label for="first_name">First Name:</label>
				<input type="text" name="first_name"  value="<?=set_value('first_name', $user_profile->first_name)?>"/>
			</p>
			<p>
				<label for="last_name">Last Name:</label>
				<input type="text" name="last_name"  value="<?=set_value('last_name', $user_profile->last_name)?>"/>
			</p>
			<p>
				<label for="email">Email Address:</label>
				<input type="text" name="email"  value="<?=set_value('email', $user_profile->email)?>"/>
			</p>
			<p>
				<label for="app_name">Password:</label>
				<a style="float:left; display:block; margin:12px 0 0 5px;" href="/account/change_password">Change Password</a>
			</p>
			<p>
				<label>&nbsp;</label>
				<input type="submit" class="submit" name="submit" value="Update"/>
			</p>
		</form>
		</div>
		
	</div>
	
</div>

<?php $this->load->view('includes/footer'); ?>