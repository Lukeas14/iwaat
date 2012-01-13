<?php $this->load->view('includes/header'); ?>

Results for '<?=$keywords?>':
<br/>
<div id="search_results">
<?php 
	foreach($app_results->response->docs as $app):
		if(empty($app->popularity_index) || !is_numeric($app->popularity_index)) $app->popularity_index = 'N/A';
		$index_color = get_index_color($app->popularity_index);
		$app->description = truncate($app->description, 200);
?>
	<a class="search_result" href="/app/<?=$app->slug?>">
		<!--<img class="search_screenshot" src="<?=$app->screenshot?>"/>-->
		<div class="search_screenshot" style="background-image:url('<?=$app->screenshot_small?>')"></div>
		<!--<div class="search_popularity_index" style="background:#<?=$index_color?>; color:#FFF"><?=$app->popularity_index?></div>-->
		<!--<div class="search_details_wrapper"></div>-->
		<div class="search_details_left">
			<div class="search_logo"><img src="<?=$app->logo?>" /></div>
			<p class="search_name"><?=$app->name?></p>
		</div>
		<div class="search_details_right">
			<p class="search_description"><?=$app->description?></p>
		</div>
	</a>
<?php endforeach; ?>
</div>

<script type="text/javascript">
	
//Open up search result details on hover to show screenshot
$(".search_result").hover(function(){
	$(this).children(".search_details_left").animate({
		left:'-200'
	});
	$(this).children(".search_details_right").animate({
		right:'-200'
	});
}, function(){
	$(this).children(".search_details_left").animate({
		left:0
	});
	$(this).children(".search_details_right").animate({
		right:0
	});
});

</script>

<?php $this->load->view('includes/footer'); ?>
