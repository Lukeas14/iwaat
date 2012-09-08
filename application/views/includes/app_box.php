<div class='app_box'>

	<?php if(!empty($app['images']['logo'][0]['source'])): ?>
		<a class="app_logo" href="/app/<?=$app['slug']?>">
			<img src="<?=$app['images']['logo'][0]['source']?>" alt="<?=$app['name']?> logo"/>
		</a>
	<?php endif; ?>

	<div class='app_data'>
		<a href="/app/<?=$app['slug']?>" class='app_name'><?=$app['name']?></a>

		<p class="app_description">
			<?=truncate($app['description'], 300)?>
			<a href="/app/<?=$app['slug']?>">Read More</a>
		</p>
	</div>
	
</div>