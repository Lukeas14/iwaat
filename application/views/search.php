<?php $this->load->view('includes/header'); ?>

<div id="search_wrapper">

	<h1>Search Results</h1>

	<div id="search_header">

		<div id="search_sort">
		<form action="/search" method="get">
			<input type="hidden" name="q" value="<?=$keywords?>"/>
			<strong>Sort By:</strong>
			<select name="sort">
				<option value="score|desc" <?=($this->input->get('sort') == 'score|desc') ? 'selected="selected"' : ''?>>Relevance</option>
				<option value="popularity_index|desc" <?=($this->input->get('sort') == 'popularity_index|desc') ? 'selected="selected"' : ''?>>Traction</option>
				<option value="time_added|desc" <?=($this->input->get('sort') == 'time_added|desc') ? 'selected="selected"' : ''?>>Newest</option>
			</select>
			<input type="submit" value="Go"/>
		</form>
		</div>

		<div id="search_pagination">
			<strong>Page:</strong>
			<?=$pagination_links?>
		</div>

	</div>

	<div id="search_results">
	<?php 
	if($app_total > 0):
		foreach($app_results->response->docs as $app):
			if(empty($app->popularity_index) || !is_numeric($app->popularity_index)) $app->popularity_index = 'N/A';
			$index_color = get_index_color($app->popularity_index);
			$app->description = truncate($app->description, 200);
			$app_score = ($app->score > 2) ? 2 : (($app->score < 0.2) ? 0.2 : $app->score);
			$score_width = (round((1 - ($app_score / 2)), 2) * 100) / 2;
			$score_offset = (100 - $score_width) / 2;
			
	?>
		<a class="search_result" href="/app/<?=$app->slug?>">
			<div class="search_screenshot" style="background-image:url('<?=$app->screenshot_small?>')"></div>
			<div class="search_details_left">
				<div class="search_logo"><img src="<?=$app->logo?>" alt="<?=$app->name?> logo"/></div>
				<p class="search_name"><?=$app->name?></p>
			</div>
			<div class="search_details_right">
				<p class="search_description"><?=$app->description?></p>
			</div>
			<div class="score_bar_wrapper_left"></div>
			<div class="score_bar_wrapper_right"></div>
			<div class="score_bar_left" style="width:<?=($score_width)?>%;"></div>
			<div class="score_bar_right" style="width:<?=($score_width)?>%;"></div>
		</a>
	<?php 
		endforeach;
	else:
	?>
		<p style="width:100%; text-align:center;">No results found.</p>
	<?php
	endif;
	?>
	</div>

	<div id="search_footer">
		<div id="search_sort">
		<form action="/search" method="get">
			<input type="hidden" name="q" value="<?=$keywords?>"/>
			<strong>Sort By:</strong>
			<select name="sort">
				<option value="score|desc" <?=($this->input->get('sort') == 'score|desc') ? 'selected="selected"' : ''?>>Relevance</option>
				<option value="popularity_index|desc" <?=($this->input->get('sort') == 'popularity_index|desc') ? 'selected="selected"' : ''?>>Traction</option>
				<option value="time_added|desc" <?=($this->input->get('sort') == 'time_added|desc') ? 'selected="selected"' : ''?>>Newest</option>
			</select>
			<input type="submit" value="Go"/>
		</form>
		</div>
		
		<div id="search_pagination">
			<?=$this->pagination->create_links()?>
		</div>
	</div>

</div>
	
<script type="text/javascript">
	
//Open up search result details on hover to show screenshot
$(".search_result").hover(function(){
	$(this).children(".search_details_left, .score_bar_wrapper_left, .score_bar_left").animate({
		left:'-=200'
	});
	$(this).children(".search_details_right, .score_bar_wrapper_right, .score_bar_right").animate({
		right:'-=200'
	});
}, function(){
	$(this).children(".search_details_left").animate({
		left:0
	});
	$(this).children(".score_bar_wrapper_left, .score_bar_left").animate({
		left:'5%'
	});
	$(this).children(".search_details_right").animate({
		right:0
	});
	$(this).children(".score_bar_wrapper_right, .score_bar_right").animate({
		right:'5%'
	});
});

</script>

<?php $this->load->view('includes/footer'); ?>
