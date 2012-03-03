<?php $this->load->view('includes/header'); ?>	

<div id="homepage_categories">
<?php foreach($homepage_categories as $homepage_category): ?>
	<?php $category_alt = (!isset($category_alt) || $category_alt == 'odd') ? 'even' : 'odd'; ?>
	<div class="homepage_category <?=$category_alt?>">
		<a href="/category/<?=$homepage_category['slug']?>" class="homepage_category_label <?=$category_alt?>"><?=$homepage_category['name']?></a>
		<?php foreach($homepage_apps[$homepage_category['id']]['apps'] as $homepage_app): ?>
		<a class="homepage_app" href="/app/<?=$homepage_app['slug']?>">
			<div class="homepage_app_logo"><img src="<?=get_app_image($homepage_app['id'], $homepage_app['logo'])?>" alt="<?=$homepage_app['name']?> logo"/></div>
			<div class="homepage_app_name"><?=$homepage_app['name']?></div>
		</a>
		
		<?php endforeach; ?>
	</div>
<?php endforeach; ?>
	
</div>


<script type="text/javascript">
$(document).ready(function(){
	
	$("#header.homepage input.text").focus();	
	
})
</script>

<?php $this->load->view('includes/footer'); ?>