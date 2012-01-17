<?php $this->load->view('includes/header'); ?>

<div id="homepage_search">
	<h1>I Want An App That...</h1>
	<div id="homepage_search_form_wrapper">
		<form action="/search" method="GET" accept-charset="utf-8" id="homepage_search_form">
			<input type="text" name="q" class="text_field"/>
			<input type="submit" name="s" value="Search"/>
		</form>
	</div>
</div>

<div id="homepage_categories">
<?php foreach($homepage_categories as $homepage_category): ?>
	<div class="homepage_category">
		<h2><?=$homepage_category['name']?></h2>
		<?php foreach($homepage_apps[$homepage_category['id']]['apps'] as $homepage_app): ?>
		<img src="/images/apps/<?=$this->app->get_app_image_directory($homepage_app['id'])?>/<?=$homepage_app['logo']?>" style="width:90px;"/>
		<?php endforeach; ?>
	</div>
<?php endforeach; ?>
	
</div>


<script type="text/javascript">
$(document).ready(function(){
	$("#homepage_search_form input.text_field").focus();	
})
</script>

<?php $this->load->view('includes/footer'); ?>