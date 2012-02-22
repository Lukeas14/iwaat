<?php $this->load->view('includes/header'); ?>	

<div id="newsletter_signup_wrapper">
	<div id="newsletter_signup">
		<form id="newsletter_signup_form" name="newsletter_signup" action="" method="post">
			<p>Be the first to know about our upcoming features:<br/>
			<span>
				We take your online privacy seriously
				and will never share your email.
			</span>
			</p>
			<input type="text" class="newsletter_email" name="newsletter_email" value="Email Address..."/>
			<input type="submit" class="submit" value="Signup"/>
		</form>
	</div>
</div>

<div id="homepage_categories">
<?php foreach($homepage_categories as $homepage_category): ?>
	<?php $category_alt = (!isset($category_alt) || $category_alt == 'odd') ? 'even' : 'odd'; ?>
	<div class="homepage_category <?=$category_alt?>">
		<a href="/category/<?=$homepage_category['slug']?>" class="homepage_category_label <?=$category_alt?>"><?=$homepage_category['name']?></a>
		<?php foreach($homepage_apps[$homepage_category['id']]['apps'] as $homepage_app): ?>
		<a class="homepage_app" href="/app/<?=$homepage_app['slug']?>">
			<div class="homepage_app_logo"><img src="<?=get_app_image($homepage_app['id'], $homepage_app['logo'])?>"/></div>
			<div class="homepage_app_name"><?=$homepage_app['name']?></div>
		</a>
		
		<?php endforeach; ?>
	</div>
<?php endforeach; ?>
	
</div>


<script type="text/javascript">
$(document).ready(function(){
	$("#header.homepage input.text").focus();	
	
	var $newsletter_email = $("form#newsletter_signup_form input.newsletter_email");
	var newsletter_placeholder = "Email Address...";
	$newsletter_email.focus(function() {
	  var input = $(this);
	  if (input.val() == newsletter_placeholder) {
		input.val('');
		input.removeClass('placeholder');
	  }
	}).blur(function() {
	  var input = $(this);
	  if (input.val() == '' || input.val() == newsletter_placeholder) {
		input.addClass('placeholder');
		input.val(newsletter_placeholder);
	  }
	}).blur();
	
	$("form#newsletter_signup_form").submit(function(){
		var _this = $(this);
		var email = $("form#newsletter_signup_form input.newsletter_email").val();
		
		$.ajax({
			type:		'POST',
			url:		'/add_newsletter_email',
			dataType:	'html',
			data:		{'newsletter_email':email}
		}).done(function(status){
			_this.html("<p>Thank you for submitting your email.<br/>We'll keep in touch.</p>");
		});
		
		
		return false;
	});
})
</script>

<?php $this->load->view('includes/footer'); ?>