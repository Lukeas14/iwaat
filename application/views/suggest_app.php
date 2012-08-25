<?php $this->load->view('includes/header'); ?>

<div id="suggest_app_wrapper">

	<h1>Suggest a Web Application</h1>
	
	<form action="" method="POST">
	<div class="suggest_app_form">
		<p>
			<label for="email">Your Email:</label>
			<input type="text" name="email" value="<?=set_value('email')?>"/>
		</p>
		<p>
			<label for="app_name">App Name:</label>
			<input type="text" name="app_name" value="<?=set_value('app_name')?>"/>
		</p>
		<p>
			<label for="app_url">App URL:</label>
			<input type="text" name="app_url" value="<?=set_value('app_url')?>"/>
		</p>
		<p>
			<label for="app_description">App Description:</label>
			<textarea name="app_description" rows="10"><?=set_value('app_description')?></textarea>
		</p>
		<p>
			<label></label>
			<input type="submit" class="submit" value="Suggest an App"/>
		</p>
	</form>
	</div>
		
</div>

<?php $this->load->view('includes/footer'); ?>