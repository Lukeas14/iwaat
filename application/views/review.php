<?php $this->load->view('includes/header'); ?>

<div class='review_wrapper'>
	
	<div class='review_left'>

		<div class='user_box'>
			<a class='user_name' href="/user/<?=$user->slug?>"><?=$user->username?></a>
		</div>

		<div class='app_box'>

			<?php if(!empty($app['images']['logo'][0]['source'])): ?>
				<a class="app_logo" href="/app/<?=$app['slug']?>">
					<img src="<?=$app['images']['logo'][0]['source']?>" alt="<?=$app['name']?> logo"/>
				</a>
			<?php endif; ?>

			<a href="/app/<?=$app['slug']?>" class='app_name'><?=$app['name']?></a>

			<p class="app_description">
				<?=truncate($app['description'], 300)?>
				<a href="/app/<?=$app['slug']?>">Read More</a>
			</p>

		</div>

	</div>

	<div class='review_right'>

		<h1>
			<?=get_possessive($user->username)?> Review of <?=$app['name']?>
			<?php if($user_profile['id'] == $review['user_id']): ?>
				<a href="/discussions/add_review/<?=$app['slug']?>"><button>Edit Review</button></a>
			<?php endif; ?>
			<br/>
			<p class='review_metadata'>Written <?=get_relative_time($review['time_posted']->sec)?> (<?=date('g:i A - F j, Y', $review['time_posted']->sec)?>)</p>
		</h1>

		<br/>

		

		<div class='review_text'>
			<h2><?=$review['title']?></h2>
			<p><?=$review['text']?></p>
		</div>

	</div>

</div>

<?php $this->load->view('includes/footer'); ?>