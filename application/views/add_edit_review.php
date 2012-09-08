<?php $this->load->view('includes/header'); ?>

<div class='add_discussion review'>

	<div class='app_box'>

		<?php if(!empty($app['images']['logo'][0]['source'])): ?>
			<a class="app_logo" href="/app/<?=$app['slug']?>">
				<img src="<?=$app['images']['logo'][0]['source']?>" alt="<?=$app['name']?> logo"/>
			</a>
		<?php endif; ?>

		<a href="/app/<?=$app['slug']?>" class='app_name'><?=$app['name']?></a>

		<p class="app_description">
			<?=truncate($app['description'], 500)?>
			<a href="/app/<?=$app['slug']?>">Read More</a>
		</p>

	</div>
	
	<div class='add_review'>
		<form action="" method="POST">

		<h1>
			<?php if($user_app_review): ?>
				Edit Your Review for <?=$app['name']?>
			<?php else: ?>
				Write a Review for <?=$app['name']?>:
			<?php endif; ?>

			<input type="submit" class="submit" value="Submit Review"/>
		</h1>

		<p>
			<label for="title">Title:</label>
			<input type="text" id="title" name="title" value="<?=set_value('title', $user_app_review['title'])?>" />
		</p>
		<p>
			<label for="review">Review:</label>
			<textarea id="review" name="review"><?=set_value('text', $user_app_review['text'])?></textarea>
		</p>
		<p>
			<label>&nbsp;</label>
			
		</p>

		</form>

	</div>

</div>

<script type="text/javascript">
$(function(){
	$("textarea#review").redactor({
		buttons: redactor_config.buttons,
		imageUpload: '/ajax/discussion_image_upload',
		uploadCrossDomain: true,
		autoresize: true,
		uploadFields: {
			app_id: <?=$app['id']?>
		}
	});
});
</script>

<?php $this->load->view('includes/footer'); ?>