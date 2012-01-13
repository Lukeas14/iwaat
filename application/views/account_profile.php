<?php $this->load->view('includes/header'); ?>

<div id="account_wrapper">
	
	<?php $this->load->view('account_nav'); ?>
	
	<div id="account_content">
		
		<h1>Edit My Profile</h1>
		<form action="" method="post" accept-charset="utf-8">
		<div class="account_profile_form">
			<p>
				<label for="first_name">First Name:</label>
				<input type="text" name="first_name" values=""/>
			</p>
			<p>
				<label for="last_name">Last Name:</label>
				<input type="text" name="last_name" values=""/>
			</p>
			<p>
				<label for="email">Email Address:</label>
				<input type="text" name="login_email" value="<?=set_value('login_email')?>"/>
			</p>
			<p>
				<label for="app_name">Password:</label>
				<p class="form_link"><a href="/account/reset_password">Reset Password</a></p>
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