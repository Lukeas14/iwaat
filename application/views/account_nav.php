<div id="account_nav">
	<a href="/account/profile" class="parent_nav <?=($this->uri->segment(2) == 'profile') ? 'selected' : ''?>">My Profile</a>
	<a href="/account/add_app" class="parent_nav <?=($this->uri->segment(2) == 'add_app') ? 'selected' : ''?>">Add an Application</a>
	<a class="parent_nav">My Applications</a>
	<!--<a href="/account/add_an_app" class="sub_nav <?=($this->uri->segment(2) == 'add_an_app') ? 'selected' : ''?>">Add an Application</a>-->
</div>