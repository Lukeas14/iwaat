<?php $this->load->view('includes/header'); ?>

<div id="account_wrapper">
	
	<?php $this->load->view('account_nav'); ?>
	
	<div id="account_content">
		
		<h1>Add an App List</h1>
		<form action="account/add_app_list" method="POST" accept-charset="utf-8">
			<p>
				<label for="title" style="padding-top:10px">Title:</label>
				<input type="text" name="title"/>
				<input type="submit" class="submit" value="App App List" style="display:inline-block;" />
			</p>
		</form>

		<div class="user_app_list_divider">&nbsp;</div>

		<h1>Your App Lists</h1>
		<?php if($user_app_lists['total_app_lists'] > 0): ?>
			<ul id="user_app_lists">
			<?php foreach($user_app_lists['app_lists'] as $user_app_list): ?>
				<li class="user_app_list <?=alternator('even','odd')?>">
					<p class="app_list_title"> 
						<?=$user_app_list['title']?>
					</p>
					<a href="/account/edit_app_list/<?=$user_app_list['id']?>">
						<button>Edit App List</button>
					</a>
				</li>
			<?php endforeach; ?>
			</ul>
		<?php else: ?>
			<p>No app lists.</p>
		<?php endif; ?>
		
	</div>
	
</div>

<?php $this->load->view('includes/footer'); ?>