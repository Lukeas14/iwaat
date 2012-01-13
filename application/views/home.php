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

<div id="homepage_content">
	
</div>

<script type="text/javascript">
$(document).ready(function(){
	$("#homepage_search_form input.text_field").focus();	
})
</script>

<?php $this->load->view('includes/footer'); ?>