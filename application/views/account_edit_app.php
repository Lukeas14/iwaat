<?php $this->load->view('includes/header'); ?>

<div id="account_wrapper">
	
	<?php $this->load->view('account_nav'); ?>
	
	<div id="account_content">
		
		<h1>Edit App - <?=$app['name']?></h1>
		
		<div id="admin_app_form">
			
		<?php echo form_open_multipart('');?>
			<input type="hidden" name="app_id" value="<?=$app['id']?>"/>
			
			<p>
				<label for="name">Name:</label>
				<input type="text" name="name" value="<?=set_value('name', $app['name'])?>"/>
				<a href="/app/<?=$app['slug']?>" target="_blank">View App</a>
			</p>
			<p>
				<label for="status">Status</label>
				<span><?=ucwords(str_replace('_', ' ', $app['status']))?></span>
			</p>
			<p>
				<label for="tagline">Tagline:</label>
				<input type="text" name="tagline" value="<?=set_value('tagline', $app['tagline'])?>"/>
			</p>
			<p>
				<label for="description">Description:</label>
				<textarea name="description" rows="8" style="width:450px"><?=set_value('description', $app['description'])?></textarea>
			</p>
			<p>
				<label for="date_launched">Date Launched:</label>
				<input type="text" class="date_launched" name="date_launched" value="<?=set_value('date_launched', (!empty($app['date_launched'])) ? date('m/d/Y', strtotime($app['date_launched'])) : '')?>"/>
			</p>
			<p>
				<label for="tags">Tags:</label>
				<input type="text" name="tags" value="<?=set_value('tags', implode(', ', $app['tags']))?>"/>
				<span style="float:none;">Separate by commas. (Max 5)</span>
			</p>
			<p>
				<label for="urls[homepage]">Homepage URL:</label>
				<input type="input" name="urls[homepage]" value="<?=set_value('urls[homepage]', (!empty($app['urls']['homepage'])) ? $app['urls']['homepage'] : '')?>"/>
				<?php if(!empty($app['urls']['homepage'])): ?>
					<a href="<?=$app['urls']['homepage']?>" target="_blank">View Homepage</a>
				<?php endif; ?>
			</p>
			<p>
				<label for="urls[blog]">Blog URL:</label>
				<input type="input" name="urls[blog]" value="<?=set_value('urls[blog]', (!empty($app['urls']['blog'])) ? $app['urls']['blog'] : '')?>"/>
				<?php if(!empty($app['urls']['blog'])): ?>
					<a href="<?=$app['urls']['blog']?>" target="_blank">View Blog</a>
				<?php endif; ?>
			</p>
			<p>
				<label for="urls[rss]">Blog RSS URL:</label>
				<input type="input" name="urls[rss]" value="<?=set_value('urls[rss]', (!empty($app['urls']['rss'])) ? $app['urls']['rss'] : '')?>"/>
				<?php if(!empty($app['urls']['rss'])): ?>
					<a href="<?=$app['urls']['rss']?>" target="_blank">View Blog RSS</a>
				<?php endif; ?>
			</p>
			<p>
				<label for="urls[twitter]">Twitter Handle:</label>
				<input type="input" name="urls[twitter]" value="<?=set_value('urls[twitter]', (!empty($app['urls']['twitter'])) ? $app['urls']['twitter'] : '')?>"/>
				<?php if(!empty($app['urls']['twitter'])): ?>
					<a href="http://www.twitter.com/<?=$app['urls']['twitter']?>" target="_blank">View Twitter</a>
				<?php endif; ?>
			</p>
			<p>
				<label for="logo">Logo:</label>
				<input type="file" class="file" name="logo" size="20"/>
				<?php if(!empty($app['images']['logo'][0]['source'])): ?>
				<span style="float:none;">Max size: 2MB</span>
				<br/>
				<label>&nbsp;</label>
				<img src="<?=$app['images']['logo'][0]['source']?>" style="margin-left:5px;"/>
				<?php endif; ?>
			</p>
			<p>
				<label></label>
				<input type="submit" class="submit" name="submit" value="Update"/>
			</p>
			
		</form>
		</div>
		
	</div>
	
</div>

<script type="text/javascript">
$(function() {
	$("#admin_app_form input.date_launched").datepicker();
});
</script>

<?php $this->load->view('includes/footer'); ?>