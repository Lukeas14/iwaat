<div id="account_nav_wrapper">
	<div id="account_nav">
		<a href="/account/profile" class="parent_nav <?=($this->uri->segment(2) == 'profile') ? 'selected' : ''?>">My Profile</a>
		<a href="/account/add_app" class="parent_nav <?=($this->uri->segment(2) == 'add_app') ? 'selected' : ''?>">Add an Application</a>
		<a class="parent_nav disabled">My Applications</a>
		<?php 
		if(!empty($user_apps['apps'])):
			foreach($user_apps['apps'] as $user_app): 
		?>
			<a href="/account/edit_app/<?=$user_app['slug']?>" class="sub_nav <?=($this->uri->segment(3) == $user_app['slug']) ? 'selected' : ''?>"><?=$user_app['name']?></a>
		<?php 
			endforeach; 
		else:
		?>
			<a class="sub_nav disabled">None</a>
		<?php
		endif;
		?>
	</div>

	<?php if($this->uri->segment(2) == 'add_app'): ?>
	<div id="add_app_rules">
		<h3>App Submission Guidelines</h3>
		<ol>
			<li>Fill out this form.</li>
			<li>We will review your app to ensure it fits within the guidelines below.</li>
			<li>If approved your app will be included in our search results, it's profile page will go live and we'll send you an email.  If not approved, we'll send you an email.</li>
		</ol>
		
		<h3>App Guidelines</h3>
		<ul>
			<li>In order to ensure that we provide the best results to our users, we approve apps according to the following guidelines:</li>
			<li>All sites must be "web application" in that they provide an interactive interface available via the web</li>
			<li>We currently are not accepting blogs, e-commerce sites, APIs or mobile/desktop apps.</li>
			<li>All apps must be live, public (not in a private alpha/beta) and high quality.</li>
			<li>These are all loose guidelines so if you're unsure submit your app anyways.</li>
		</ul>
	</div>
	<?php endif; ?>
</div>