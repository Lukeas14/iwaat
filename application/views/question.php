<?php $this->load->view('includes/header'); ?>

<div class='question_wrapper'>
	
	<div class='question_left'>

		<?php $this->load->view('includes/user_box') ?>
		<?php $this->load->view('includes/app_box') ?>

	</div>

	<div class='question_right'>

		<h1>
			Question: <?=$question['title']?>
			<?php if($user_profile['id'] == $question['user_id']): ?>
				<a href="/question/edit/<?=$question['display_id']?>"><button>Edit Question</button></a>
			<?php endif; ?>
			<br/>
			<p class='question_metadata' title="<?=date('F j, Y', $question['time_posted']->sec)?>">Asked <?=get_relative_time($question['time_posted']->sec)?></p>
		</h1>

		<div class='question_text'>
			<p><?=$question['text']?></p>
		</div>

		<h1>
			Answers:
		</h1>

	</div>

</div>

<?php $this->load->view('includes/footer'); ?>