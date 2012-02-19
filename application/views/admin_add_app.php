<?php $this->load->view('includes/header'); ?>

<div id="admin_wrapper">
	
	<?php $this->load->view('admin_nav'); ?>
	
	<div id="admin_content">
		
		<h1 style="margin-bottom:5px;">Add an App</h1>
		
		<div id="admin_app_form">
			
		<?php echo form_open_multipart('');?>
			
			<p>
				<label for="name">Name:</label>
				<input type="text" name="name" value="<?=set_value('name')?>"/>
			</p>
			<p>
				<label for="status">Status</label>
				<select name="status">
					<option value="active" <?=set_select('status','active', false)?>>Active</option>
					<option value="inactive" <?=set_select('status','inactive', true)?>>Inactive</option>
					<option value="pending_review" <?=set_select('status','pending_reviews', false)?>>Pending Review</option>
				</select>
			</p>
			<p>
				<label for="owner_id">Owner</label>
				<select name="owner_id">
					<option disabled>Select an Owner...</option>
				<?php foreach($users as $user): ?>
					<option value="<?=$user['id']?>" <?=set_select('owner_id', $user['id'], false)?>><?=$user['username']?> - <?=$user['email']?></option>
				<?php endforeach; ?>
				</select>
			</p>
			<p>
				<label></label>
				<input type="submit" class="submit" name="submit" value="Add App"/>
			</p>
		</form>
		
		</div>
		
	</div>
	
</div>

<?php $this->load->view('includes/footer'); ?>