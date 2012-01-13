<?php $this->load->view('includes/header'); ?>

<div id="admin_wrapper">
	
	<?php $this->load->view('admin_nav'); ?>
	
	<div id="admin_content">
		
		<h1>Applications</h1>
		
		<?=$pagination_links?>
		
		<table id="admin_apps">
			
			<thead>
				<th id="id">ID</th>
				<th>Name</th>
				<th>Status</th>
			</thead>
			
			<tbody>
			<?php foreach($apps['apps'] as $app): ?>
				<tr>
					<td><?=$app['id']?></td>
					<td><a href="/admin/app/<?=$app['slug']?>"><?=$app['name']?></a></td>
					<td><?=ucwords(str_replace('_',' ',$app['status']))?></td>
				</tr>
			<?php endforeach; ?>
			</tbody>
			
		</table>
		
	</div>
	
</div>

<?php $this->load->view('includes/footer'); ?>