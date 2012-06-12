<?php $this->load->view('includes/header'); ?>

<div id="account_wrapper">
	
	<?php $this->load->view('account_nav'); ?>
	
	<div id="account_content">
		
		<h1>Edit My Profile</h1>
		<form action="" method="post" accept-charset="utf-8">
		<div class="account_profile_form">
			<p>
				<label for="first_name">First Name:</label>
				<input type="text" name="first_name" maxlength="64" value="<?=set_value('first_name', $user_profile->first_name)?>"/>
			</p>
			<p>
				<label for="last_name">Last Name:</label>
				<input type="text" name="last_name" maxlength="64"  value="<?=set_value('last_name', $user_profile->last_name)?>"/>
			</p>
			<p>
				<label for="email">Email Address:</label>
				<input type="text" name="email"  value="<?=set_value('email', $user_profile->email)?>"/>
			</p>
			<p>
				<label for="app_name">Password:</label>
				<?php if($this->ion_auth->is_user_password_set()): ?>
					<a style="float:left; display:block; margin:12px 0 0 5px;" href="/account/change_password">Change Password</a>
				<?php else: ?>
					<a style="float:left; display:block; margin:12px 0 0 5px;" href="/account/set_password">Set Password</a>
				<?php endif; ?>
			</p>
			<p>
				<label>Facebook:</label>
				<?php if($user_profile->facebook_id == 0): ?>
					<a class="social_connect_button" id="connect_fb" href="/hauth/connect/Facebook"></a>
				<?php elseif($this->ion_auth->login_options() >= 2): ?>
					<a class="social_connect_button disconnectable" id="fb_connected" href="/hauth/disconnect/Facebook"></a>
				<?php else: ?>
					<a class="social_connect_button" id="fb_connected"></a>
				<?php endif; ?>
			</p>
			<p>
				<label>Twitter:</label>
				<?php if($user_profile->twitter_id == 0): ?>
					<a class="social_connect_button" id="connect_tw" href="/hauth/connect/Twitter"></a>
				<?php elseif($this->ion_auth->login_options() >= 2): ?>
					<a class="social_connect_button disconnectable" id="tw_connected" href="/hauth/disconnect/Twitter"></a>
				<?php else: ?>
					<a class="social_connect_button" id="tw_connected"></a>
				<?php endif; ?>
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