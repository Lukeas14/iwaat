<?php $this->load->view('includes/header'); ?>

<div id='user_profile_wrapper'>
	
	<div id='user_profile_left'>

		<div class='user_box'>

			<img class='user_avatar' src='<?=get_user_avatar($user, 'large')?>'/>
			
			<div class='user_data'>
				<p class='user_name'><?=$user['username']?></p>
				</a>

			</div>

		</div>

	</div>

	<div id='user_profile_right'>

		<h1><?=get_possessive($user['username'])?> Discussions</h1>

		<div class='user_discussions'>
		<?php foreach($user_discussions as $discussion): ?>
			<div class="discussion <?=$discussion['type']?>">
				<div class="discussion_type"><?=$discussion_types[$discussion['type']]['name']?></div>
				<div class="discussion_header">
					<a class="discussion_title" href='/review/'><?=$discussion['title']?></a>
					<p class="discussion_time" title="<?=date('F j, Y', $discussion['time_posted']->sec)?>"><?=get_relative_time($discussion['time_posted']->sec)?></p>
				</div>
				<p class="discussion_text"><?=truncate(strip_tags($discussion['text']), 300)?></p>
			</div>
		<?php endforeach; ?>
		</div>

	</div>

</div>

<?php $this->load->view('includes/footer'); ?>