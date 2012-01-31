<?php $this->load->view('includes/header'); ?>
<?php
//echo"<pre>";print_r($app['related_apps']);echo"</pre>";
?>
<div class="app_wrapper">

	<div class="app_header">
		<div class="screenshot_large">
				<img src="<?=$app['images']['screenshot_large'][0]['source']?>"/>
		</div>
		<div class="app_info_wrapper"></div>
		<div class="app_info">
			<a class="app_logo" href="<?=$app['urls']['homepage']?>" target="_blank" rel="nofollow" onclick="return external_link('<?=(!empty($app['urls']['affiliate'])) ? $app['urls']['affiliate'] : $app['urls']['homepage']?>')">
			<?php if(!empty($app['images']['logo'][0]['source'])): ?>
				<img src="<?=$app['images']['logo'][0]['source']?>" />
			<?php endif; ?>
			</a>
			<div class="app_description">
				<p><?=$app['description']?></p>
			</div>
		</div>
	</div>

	<div class="app_metadata">
		<h1 class="app_name">
			<?=$app['name']?>
			<?php if(!empty($app['tagline'])): ?>
			<span class="app_tagline">  <?=$app['tagline']?></span>
			<?php endif; ?>
		</h1>
		
		<div class="app_index_wrapper">
			<div class="app_index" style="background:#<?=get_index_color($app['popularity_index'])?>"><?=$app['popularity_index']?></div>
			<p class="app_index_label">Traction Index</p>
		</div>
		
		<?php if(!empty($app['urls']['homepage'])): ?>
		<div class="app_social_buttons">
			<div class="app_google">
				<g:plusone size="tall" href="<?=$app['urls']['homepage']?>"></g:plusone>
			</div>
			
			<div class="app_facebook">
				<div id="fb-root"></div>
				<div class="fb-like" data-href="<?=$app['urls']['homepage']?>" data-send="false" data-layout="box_count" data-width="100" data-show-faces="false"></div>
			</div>

			<div class="app_twitter">
				<a href="https://twitter.com/share" class="twitter-share-button" data-url="<?=$app['urls']['homepage']?>" data-count="vertical" data-via="JLukeas">Tweet</a>
			</div>
		</div>
		<?php endif; ?>
		
		<div class="app_urls">
			<?php if(!empty($app['urls']['homepage'])): ?>
			<p class="app_url_homepage">
				<label>Homepage:</label><a href="<?=$app['urls']['homepage']?>" target="_blank" rel="nofollow" onclick="return external_link('<?=(!empty($app['urls']['affiliate'])) ? $app['urls']['affiliate'] : $app['urls']['homepage']?>')"><?=$app['urls']['homepage']?></a>
			</p>
			<?php endif; ?>

			<?php if(!empty($app['urls']['blog']) || !empty($app['urls']['rss'])): ?>
			<p class="app_url_blog">
				<label>Blog:</label>
				<?php if(!empty($app['urls']['blog'])): ?>
				<a href="<?=$app['urls']['blog']?>" target="_blank" rel="nofollow">Blog</a>
				<?php endif; ?>
				
				<?php if(!empty($app['urls']['blog']) && !empty($app['urls']['rss'])): ?>&nbsp;&nbsp;~&nbsp;&nbsp;<?php endif; ?>
				
				<?php if(!empty($app['urls']['rss'])): ?>
				<a href="<?=$app['urls']['rss']?>" target="_blank" rel="nofollow">RSS</a>
				<?php endif; ?>
			</p>
			<?php endif; ?>
			
			<?php if(!empty($app['urls']['twitter'])): ?>
			<p class="app_url_twitter">
				<label>Twitter:</label><a href="http://www.twitter.com/<?=$app['urls']['twitter']?>" target="_blank" rel="nofollow">@<?=$app['urls']['twitter']?></a>
			</p>
			<?php endif; ?>
			
			<?php if(!empty($app['date_launched']) && strtotime($app['date_launched']) > 343311693): ?>
			<p class="app_url_homepage">
				<label>Launched:</label><?=get_relative_time($app['date_launched'])?>
			</p>
			<?php endif; ?>
		</div>
		
		
	</div>
	
	<div class="app_media">
		<h2>News</h2>
		
		<?php if(empty($app['media']['blog_rss_feed']) && empty($app['media']['twitter_feed'])): ?>
		<div class="app_media_wrapper app_big_wrapper">
			<p class="app_media_empty">No news available.</p>
		</div>
		<?php endif; ?>
		
		<?php if(!empty($app['media']['blog_rss_feed'])): ?>
			<div class="app_media_wrapper app_blog_wrapper <?=(empty($app['media']['twitter_feed'])) ? 'app_big_wrapper' : ''?>">
			<?php foreach($app['media']['blog_rss_feed'] as $blog_item): ?>
				<div class="app_media_item app_blog_item">
					<a class="blog_item_title" href="<?=$blog_item['permalink']?>" target="_blank" rel="nofollow"><span class="blog_item_time"><?=(!empty($blog_item['datetime'])) ? get_relative_time($blog_item['datetime']) : '';?></span> <?=$blog_item['title']?></a>
					<p class="blog_item_text"><?=truncate($blog_item['description'], 300)?> <a href="<?=$blog_item['permalink']?>" target="_blank" rel="nofollow">Read More</a></p>
				</div>
			<?php endforeach; ?>
			</div>
		<?php endif; ?>
		
		<?php if(!empty($app['media']['twitter_feed'])): ?>
			<div class="app_media_wrapper app_twitter_wrapper <?=(empty($app['media']['blog_rss_feed'])) ? 'app_big_wrapper' : ''?>">
			<?php foreach($app['media']['twitter_feed'] as $twitter_item): ?>
				<div class="app_media_item app_twitter_item">
					<a href="http://twitter.com/<?=$twitter_item['from_user']?>" rel="nofollow"><img src="<?=$twitter_item['profile_image_url']?>"/></a>
					<p class="twitter_item_text"><?=$twitter_item['text']?></p>
				</div>
			<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</div>
	
	<div class="related_apps">
		<h2>Related Apps</h2>
		<div class="related_apps_wrapper">
		<?php foreach($app['related_apps'] as $related_app): ?>
			<a class="related_app" href="/app/<?=$related_app->slug?>">
				<div class="related_app_logo"><img src="<?=$related_app->logo?>"/></div>
				<div class="related_app_name"><?=$related_app->name?></div>
			</a>
		<?php endforeach; ?>
		</div>
	</div>
	
</div>

<script type="text/javascript">
$(document).ready(function(){
	//Load Facebook button
	(function(d, s, id) {(function(d, s, id) {
	  var js, fjs = d.getElementsByTagName(s)[0];
	  if (d.getElementById(id)) {return;}
	  js = d.createElement(s); js.id = id;
	  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=153127361451027";
	  fjs.parentNode.insertBefore(js, fjs);
	}(document, 'script', 'facebook-jssdk'));
	  var js, fjs = d.getElementsByTagName(s)[0];
	  if (d.getElementById(id)) {return;}
	  js = d.createElement(s); js.id = id;
	  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=153127361451027";
	  fjs.parentNode.insertBefore(js, fjs);
	}(document, 'script', 'facebook-jssdk'));
	
	//Load Twitter button
	$.getScript("//platform.twitter.com/widgets.js");
	
	//Load Google Plus
	(function() {
	  var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
	  po.src = 'https://apis.google.com/js/plusone.js';
	  var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
	})();
});

</script>

<?php $this->load->view('includes/footer'); ?>
