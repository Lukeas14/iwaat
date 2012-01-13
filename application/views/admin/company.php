<?php
//echo"<pre>";print_r($company);echo"</pre>";
?>

<div class="right_wrapper">
	<h4>Add App</h4>
	<div class="company_info">
	<form action="/admin/add_application" method="POST">
		<input type="hidden" name="redirect_url" value="<?=$this->uri->uri_string()?>"/>
		<input type="hidden" name="company_id" value="<?=$company['id']?>"/>
		<p><label>Name:</label><input type="text" name="name" value="<?=$company['name']?>"/></p>
		<p><label>Tagline:</label><input style="width:100%" type="text" name="tagline" value="<?=$company['data']['description']?>"/></p>
		<p><label>Homepage:</label><input type="text" name="urls[homepage]" value="<?=$company['homepage_url']?>"/></p>
		<p><label>Blog:</label><input type="text" name="urls[blog]" value="<?=$company['blog_url']?>"/></p>
		<p><label>RSS:</label><input type="text" name="urls[rss]" value="<?=$company['blog_feed_url']?>"/></p>
		<p><label>Twitter:</label><input type="text" name="urls[twitter]" value="<?=$company['twitter_username']?>"/></p>
		<p><label>Tags:</label><textarea name="tags"><?=$company['data']['tag_list']?></textarea></p>
		<p><label>Description:</label><textarea rows="25" name="description"><?=strip_tags($company['data']['overview'])?></textarea></p>
		<p><label>Phone Number:</label><input type="text" name="phone_number" value="<?=$company['data']['phone_number']?>"/></p>
		<p><label>Launch Date:</label><input type="text" name="date_launched" value="<?=$company['data']['founded_year']?>-<?=$company['data']['founded_month']?>-<?=$company['data']['founded_day']?>"/></p>
		<p><label>Email:</label><input type="text" name="email" value="<?=$company['data']['email_address']?>"/></p>
		<p>
			<label>Logo:</label>
			<?php
			if(!empty($company['data']['image']['available_sizes'])):
			$logo = 'http://www.crunchbase.com/'.$company['data']['image']['available_sizes'][(count($company['data']['image']['available_sizes']) - 1)][1];
			else:
			$logo = '';
			endif;
			?>
			<input type="text" name="logo" value="<?=$logo?>"/>
			<img src="<?=$logo?>"/>
		</p>
		<p>
			<label>Screenshots:</label>
			
			<?php
			if(!empty($company['data']['screenshots'][0]['available_sizes'])):
			$screenshot = $company['data']['screenshots'][0]['available_sizes'][(count($company['data']['image']['available_sizes']) - 1)][1];
			?>
			<input type="text" name="screenshot[0]" value="http://www.crunchbase.com/<?=$screenshot?>"/>
			<img src="http://www.crunchbase.com/<?=$screenshot?>"/>
			<br/><br/>
			<?php
			endif;
			?>
			
			<?php
			if(!empty($company['data']['screenshots'][1]['available_sizes'])):
			$screenshot = $company['data']['screenshots'][1]['available_sizes'][(count($company['data']['image']['available_sizes']) - 1)][1];
			?>
			<input type="text" name="screenshot[1]" value="http://www.crunchbase.com/<?=$screenshot?>"/>
			<img src="http://www.crunchbase.com/<?=$screenshot?>"/>
			<br/><br/>
			<?php
			endif;
			?>
			
			<?php
			if(!empty($company['data']['screenshots'][2]['available_sizes'])):
			$screenshot = $company['data']['screenshots'][2]['available_sizes'][(count($company['data']['image']['available_sizes']) - 1)][1];
			?>
			<input type="text" name="screenshot[2]" value="http://www.crunchbase.com/<?=$screenshot?>"/>
			<img src="http://www.crunchbase.com/<?=$screenshot?>"/>
			<br/><br/>
			<?php
			endif;
			?>
		</p>
		<p>
			<input type="submit" name="submit" value="Submit"/>
		</p>
	</form>	
	</div>
</div>

<div class="left_wrapper">
	<h4>Company</h4>
	<div class="company_info">
		<?php if($company['completed'] == 'yes'): ?>
		<p style="color:#990000; font-weight:bold;">Completed</p>
		<?php else: ?>
		<form action="/admin/complete_company" method="post">
			<input type="hidden" name="company_id" value="<?=$company['id']?>"/>
			<input type="submit" name="submit" value="Complete"/>
		</form>
		<?php endif; ?>
		<p><label>Name:</label><?=$company['name']?></p>
		<p><label>Status:</label><?=$company['status']?></p>
		<p><label>Acquired:</label><?=$company['data']['acquired']?></p>
		<p><label>Tag Line:</label><?=$company['data']['description']?></p>
		<p><label>Website:</label><a href='<?=$company['homepage_url']?>' target='_blank'><?=$company['homepage_url']?></a></p>
		<p><label>Blog:</label><a href='<?=$company['blog_url']?>' target='_blank'><?=$company['blog_url']?></a></p>
		<p><label>Feed:</label><a href='<?=$company['blog_feed_url']?>' target='_blank'><?=$company['blog_feed_url']?></a></p>
		<p><label>Twitter:</label><a href='http://www.twitter.com/<?=$company['twitter_username']?>' target='_blank'><?=$company['twitter_username']?></a></p>
		<p><label>Description:</label><?=$company['data']['overview']?></p>
		<p><label>Phone Number:</label><?=$company['data']['phone_number']?></p>
		<p><label>Founding Year:</label><?=$company['data']['founded_year']?> - <?=$company['data']['founded_month']?> - <?=$company['data']['founded_day']?></p>
		<p><label>Tags:</label><?=$company['data']['tag_list']?></p>
		<p><label>Email:</label><?=$company['data']['email_address']?></p>
		<p><label>Competitors:</label><?=implode(", ", $company['data']['competitors'])?></p>
		<p><label>Office:</label><?=$company['data']['office']?></p>
		<p><label>Image:</label><img src='http://www.crunchbase.com/<?=$company['data']['image']['available_sizes'][(count($company['data']['image']) - 1)][1]?>'/></p>
		<p><label>Screenshots:</label><?=implode("<br/>", $company['screens'])?></p>
	</div>

	<h4>Apps</h4>
	<?php
	if(!empty($products)):

		foreach($products[0] as $product):
		$product = json_decode($product['data'], true);
	?>
	<div class="company_info">
		<p><label>Name:</label><?=$product['name']?></p>
		<p><label>Website:</label><a href="<?=$product['homepage_url']?>" target="_blank"><?=$product['homepage_url']?></a></p>
		<p><label>Tags:</label><?=$product['tag_list']?></p>
		<p><label>Twitter:</label><a href='http://www.twitter.com/<?=$product['twitter_username']?>' target='_blank'><?=$product['twitter_username']?></a></p>
		<p><label>Blog:</label><a href="<?=$product['blog_url']?>" target="_blank"><?=$product['blog_url']?></a></p>
		<p><label>Launch Date:</label><?=$product['launched_year']?> - <?=$product['launched_month']?> - <?=$product['launched_day']?></p>
		<p><label>Description:</label><?=$product['overview']?></p>
	</div>
	<?php
		endforeach;
	endif;
	?>
</div>
