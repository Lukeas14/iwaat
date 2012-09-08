<?php $this->load->view('includes/header'); ?>

<div class='add_discussion_wrapper'>

	<div class='add_discussion_left'>
		<?php $this->load->view('includes/app_box'); ?>
	</div>

	<div class='add_discussion_right'>
		<form action="" method="POST">

		<h1>
			Ask a Question About <?=$app['name']?>:
			<input type="submit" class="submit" value="Submit Question"/>
		</h1>

		<p>
			<label for="title">Title:</label>
			<input type="text" id="title" name="title" />
		</p>
		<p>
			<label for="question">Question:</label>
			<textarea id="question" name="question"></textarea>
		</p>

		</form>
	</div>

</div>

<script type="text/javascript">
$(function(){
	$("textarea#question").redactor({
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