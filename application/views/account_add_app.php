<?php $this->load->view('includes/header'); ?>

<div id="account_wrapper">
	
	<?php $this->load->view('account_nav'); ?>
	
	<div id="account_content">
		
		<h1>Add an Application</h1>
		
		<div id="admin_app_form">
			
		<?php echo form_open_multipart('');?>
			<p>
				<label for="name">Name:</label>
				<input type="text" name="name" value="<?=set_value('name')?>"/>
			</p>
			<p>
				<label for="tagline">Tagline:</label>
				<input type="text" name="tagline" value="<?=set_value('tagline')?>"/>
			</p>
			<p>
				<label for="description">Description:</label>
				<textarea name="description" rows="8" style="width:450px"><?=set_value('description')?></textarea>
			</p>
			<p>
				<label for="date_launched">Date Launched:</label>
				<input type="text" class="date_launched" name="date_launched" value="<?=set_value('date_launched')?>"/>
			</p>
			<p>
				<label for="tags">Tags:</label>
				<input type="text" name="tags" value="<?=set_value('tags')?>"/><span style="float:none;">Separate by commas. (Max 5)</span>
			</p>
			<p>
				<label for="urls[homepage]">Homepage URL:</label>
				<input type="input" name="urls[homepage]" value="<?=set_value('urls[homepage]')?>"/>
			</p>
			<p>
				<label for="urls[blog]">Blog URL:</label>
				<input type="input" name="urls[blog]" value="<?=set_value('urls[blog]')?>"/>
			</p>
			<p>
				<label for="urls[rss]">Blog RSS URL:</label>
				<input type="input" name="urls[rss]" value="<?=set_value('urls[rss]')?>"/>
			</p>
			<p>
				<label for="urls[twitter]">Twitter Handle:</label>
				<input type="input" name="urls[twitter]" value="<?=set_value('urls[twitter]')?>"/>
			</p>
			<p>
				<label for="logo">Logo:</label>
				<input type="file" class="file" name="logo" size="20"/><span style="float:none;">Max size: 2MB</span>
			</p>
			<p>
				<label></label>
				<input type="submit" class="submit" name="submit" value="Add an Application"/>
			</p>
			
		</form>
		</div>
		
	</div>
	
</div>

<?php $this->load->view('includes/footer'); ?>