<?php $this->load->view('includes/header'); ?>
<?php //echo"<pre>";print_r($app);echo"</pre>"; ?>

<div id="admin_wrapper">
	
	<?php $this->load->view('admin_nav'); ?>
	
	<div id="admin_content">
		
		<h1 style="margin-bottom:5px;"><?=$app['name']?></h1>
		
		<a href="<?=$http_referer?>" style="font-size:11px;">< Back</a>
		
		<div id="admin_app_form">
			
		<?php echo form_open_multipart('');?>
			<input type="hidden" name="app_id" value="<?=$app['id']?>"/>
			
			<p>
				<label for="id">ID:</label>
				<span><?=$app['id']?></span>
			</p>
			<p>
				<label for="name">Name:</label>
				<input type="text" name="name" value="<?=set_value('name', $app['name'])?>"/>
				<a href="/app/<?=$app['slug']?>" target="_blank">View App</a>
			</p>
			<p>
				<label for="status">Status</label>
				<select name="status">
					<option value="active" <?=set_select('status','active', ($app['status'] == 'active') ? true : false)?>>Active</option>
					<option value="inactive" <?=set_select('status','inactive', ($app['status'] == 'inactive') ? true : false)?>>Inactive</option>
					<option value="pending_review" <?=set_select('status','pending_reviews', ($app['status'] == 'pending_review') ? true : false)?>>Pending Review</option>
				</select>
			</p>
			<p>
				<label for="owner_id">Owner:</label>
				<select name="owner_id">
				<?php foreach($users as $user): ?>
					<option value="<?=$user->id?>" <?=set_select('owner_id', $user->id, ($app['owner_id'] == $user->id) ? true : false)?>><?=$user->username?> - <?=$user->email?></option>
				<?php endforeach; ?>
				</select>
			</p>
			<p>
				<label for="category_id">Category:</label>
				<select name="category_id">
					<option value="null" <?=(empty($app['category_id'])) ? "selected='selected'" : ""?> disabled="disabled">Select a Category...</option>
				<?php foreach($categories as $category): ?>
					<option value="<?=$category['id']?>" <?=set_select('category_id', $category['id'], ($app['category_id'] == $category['id']) ? true : false)?>><?=$category['name']?></option>
				<?php endforeach; ?>
				</select>
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
				<label for="phone_number">Phone Number:</label>
				<input type="text" name="phone_number" value="<?=set_value('phone_number', $app['phone_number'])?>"/>
			</p>
			<p>
				<label for="email">Email:</label>
				<input type="text" name="email" value="<?=set_value('email', $app['email'])?>"/>
			</p>
			<p>
				<label for="date_launched">Date Launched:</label>
				<input type="text" class="date_launched" name="date_launched" value="<?=set_value('date_launched', (!empty($app['date_launched'])) ? date('m/d/Y', strtotime($app['date_launched'])) : '')?>"/>
			</p>
			<p>
				<label for="">Last Import:</label>
				<span><?=date('m/d/Y', strtotime($app['last_import']))?></span>
			</p>
			<p>
				<label for="date_added">Date Added:</label>
				<span><?=date('m/d/Y', strtotime($app['time_added']))?></span>
			</p>
			
			<p>
				<label for="popularity_index">Traction Index:</label>
				<span style="display:inline-block; float:left; margin:12px 0 0 7px;"><?=$app['popularity_index']?></span>
			</p>
			<p>
				<label for="tags">Tags:</label>
				<input type="text" name="tags" value="<?=set_value('tags', implode(', ', $app['tags']))?>"/>
			</p>
			<p>
				<label for="urls[homepage]">Homepage URL:</label>
				<input type="input" name="urls[homepage]" value="<?=set_value('urls[homepage]', (!empty($app['urls']['homepage'])) ? $app['urls']['homepage'] : '')?>"/>
				<?php if(!empty($app['urls']['homepage'])): ?>
					<a href="<?=$app['urls']['homepage']?>" target="_blank">View Homepage</a>
				<?php endif; ?>
			</p>
			<p>
				<label for="urls[affiliate]">Affiliate URL:</label>
				<input type="input" name="urls[affiliate]" value="<?=set_value('urls[affiliate]', (!empty($app['urls']['affiliate'])) ? $app['urls']['affiliate'] : '')?>"/>
				<?php if(!empty($app['urls']['affiliate'])): ?>
					<a href="<?=$app['urls']['affiliate']?>" target="_blank">Test Affiliate URL</a>
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
				<br/>
				<label>&nbsp;</label>
				<img src="<?=$app['images']['logo'][0]['source']?>" style="margin-left:5px;"/>
				<?php endif; ?>
			</p>
			<p>
				<label for="screenshot">Screenshot:</label>
				<span><a id="generate_screenshot" href="">Generate Screenshot</a></span>
				<!--<input type="file" class="file" name="screenshot" size="20"/>-->
				<br/><br/>
				<?php if(!empty($app['images']['screenshot_small'][0]['source'])): ?>
				<br/>
				<label>&nbsp;</label>
				<img src="<?=$app['images']['screenshot_small'][0]['source']?>" style="margin-left:5px;"/>
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
	
	var generating_screenshot = false;
	$("#generate_screenshot").click(function(e){
		e.preventDefault();
		
		if(generating_screenshot === true) return;
		else generating_screenshot = true;
		
		var homepage_url = '<?=(!empty($app['urls']['homepage'])) ? $app['urls']['homepage'] : ''?>';
		if(!homepage_url){
			$(this).css('color','#666666').text("No homepage URL specified.");
			return;
		}
		var screenshot_download_url = '<?=sprintf(SCREENSHOT_API_URL, SCREENSHOT_LARGE_WIDTH, SCREENSHOT_LARGE_HEIGHT, (!empty($app['urls']['homepage'])) ? $app['urls']['homepage'] : '')?>';
		var screenshot_save_url = '/admin/generate_screenshot?app_id=<?=$app['id']?>&app_slug=<?=$app['slug']?>&homepage_url=' + encodeURIComponent(homepage_url);
		var screenshot_queue_url = '/admin/generate_screenshot_queue?app_id=<?=$app['id']?>&homepage_url=' + encodeURIComponent(homepage_url);
		$.ajax({
			url: screenshot_queue_url,
			type: 'GET',
			dataType: 'html',
			success: function(data){
				console.log(data);
			}
		});
		
		var $screenshot_image = $("<img style='width:1px; height:1px;'/>").attr('src', screenshot_download_url);
		$("#generate_screenshot").parent().append($screenshot_image);
				
		$(this).css('color', '#666666');
		
		
		function countdown(count){
			if(count > 0){
				$("#generate_screenshot").text("Generating Screenshot... (Please Wait... " + count + ")");
				
				setTimeout(function(){
					countdown(count - 1);
				}, 1000);
			}
			else{
				window.location = screenshot_save_url;
			}
		}
		countdown(90);
	});
});
</script>

<?php $this->load->view('includes/footer'); ?>