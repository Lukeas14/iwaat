<?php $this->load->view('includes/header'); ?>

<div id="forgot_password_wrapper">

	<h1>Forgot Password?</h1>
	<p>Please enter your email address and we will send you an email about how to reset your password.</p>
	<form action="/account/forgot_password" method="post" accept-charset="utf-8">
	<div id="forgot_password_form">
		<p>
			<label for="email" style="width:100px; text-align:left; margin-left:0px;">Email Address:</label>
			<input type="text" name="email" value="<?=set_value('email')?>"/>
		</p>
		<p>
			<label style="width:100px; text-align:left; margin-left:0px;">&nbsp;</label>
			<input type="submit" class="submit" name="submit" value="Reset Password"/>
			<a href="/login_register" style="margin:0 0 0 10px;">Return to login page</a>
		</p>
	</form>
	</div>
		
</div>

<?php $this->load->view('includes/footer'); ?>