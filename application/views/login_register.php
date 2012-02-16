<?php $this->load->view('includes/header'); ?>

<div id="login_register_wrapper">

	<div id="login_wrapper">
		<h1>Login</h1>
		<form action="/login" method="post" accept-charset="utf-8">
		<div class="login_form">
			<p>
				<label for="email">Email Address:</label>
				<input type="text" name="login_email" value="<?=set_value('login_email')?>"/>
			</p>
			<p>
				<label for="app_name">Password:</label>
				<input type="password" name="login_password" value="<?=set_value('login_password')?>"/>
			</p>
			<p>
				<label form="remember">Remember Me:</label>
				<input type="checkbox" class="checkbox" name="login_remember" value="1"/>
			</p>
			<p>
				<label>&nbsp;</label>
				<input type="submit" class="submit" name="submit" value="Login"/>
				<a href="/account/forgot_password" style="margin:0 0 0 10px">Forgot Password?</a>
			</p>
		</form>
		</div>
	</div>

	<div id="register_wrapper">
		<h1>Register</h1>	
		<form action="/register" method="post">
		<div class="register_form">
			<p>
				<label for="register_first_name">First Name:</label>
				<input type="text" name="register_first_name" value="<?=set_value('register_first_name')?>"/>
			</p>
			<p>
				<label for="register_last_name">Last Name:</label>
				<input type="text" name="register_last_name" value="<?=set_value('register_last_name')?>"/>
			</p>
			<p>
				<label for="register_email">Email Address:</label>
				<input type="text" name="register_email" value="<?=set_value('register_email')?>"/>
			</p>
			<p>
				<label for="register_password">Password:</label>
				<input type="password" name="register_password" value="<?=set_value('register_password')?>"/>
			</p>
			<p>
				<label for="register_confirm_password">Confirm Password:</label>
				<input type="password" name="register_confirm_password" value="<?=set_value('register_confirm_password')?>"/>
			</p>
			<p>
				<label>&nbsp;</label>
				<input type="submit" class="submit" name="submit" value="Register"/>
			</p>
		</form>
		</div>
	</div>

</div>

<?php $this->load->view('includes/footer'); ?>