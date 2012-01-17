<?php $this->load->view('includes/header'); ?>

<div id="contact_us">
	
	<h1>Contact Us</h1>

	<form method="POST" action="">
		<p>
			<label for="name">Name:</label>
			<input type="text" name="name" value="<?=set_value('name')?>"/>
		</p>
		<p>
			<label for="email">Email:</label>
			<input type="text" name="email" value="<?=set_value('email')?>"/>
		</p>
		<p>
			<label for="message">Message</label>
			<textarea name="message" rows="10"><?=set_value('message')?></textarea>
		</p>
		<p>
			<label>&nbsp;</label>
			<input type="submit" class="submit" value="Send"/>
		</p>
	</form>
	
</div>

<?php $this->load->view('includes/footer'); ?>