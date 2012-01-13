<div id="admin_nav">
	<a href="/admin/users" class="parent_nav <?=($this->uri->segment(2) == 'users') ? 'selected' : ''?>">Users</a>
	<a href="/admin/apps" class="parent_nav <?=($this->uri->segment(2) == 'apps') ? 'selected' : ''?>">Apps</a>
	<a href="/admin/add_app" class="parent_nav <?=($this->uri->segment(2) == 'add_app') ? 'selected' : ''?>">Add an App</a>
</div>