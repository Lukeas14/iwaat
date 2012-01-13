<<?=$pagination_links?>

<table class="admin_list">
	<thead>
		<th>Company Name</th>
		<th>Status</th>
		<th>Popularity Index</th>
		<td>Completed</th>
	</thead>
	<tbody>
	<?php foreach($companies['companies'] as $company): ?>
		<tr>
			<td><a href='/admin/company/<?=$company['permalink']?>'><?=$company['name']?></a></td>
			<td><?=$company['status']?></td>
			<td><?=$company['popularity_index']?></td>
			<td>
				<form action="/admin/complete_company" method="post">
					<input type="hidden" name="company_id" value="<?=$company['id']?>"/>
					<input type="submit" name="submit" value="Complete"/>
				</form>
			</td>
		</tr>
	<?php endforeach; ?>
	</tbody>
</table>