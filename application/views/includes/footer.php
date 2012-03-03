	
</div>

	</div>
	

<div id="footer_wrapper">

	<div id="newsletter_signup_wrapper">
		<div id="newsletter_signup">
			<form id="newsletter_signup_form" name="newsletter_signup" action="" method="post">
				<p>Get private beta access to our upcoming features:<br/>
				<span>
					We take your online privacy seriously
					and will never share your info.
				</span>
				</p>
				<input type="text" class="newsletter_email" name="newsletter_email" value="Email Address..."/>
				<input type="submit" class="submit" value="Signup"/>
			</form>
		</div>
	</div>

	<div id="footer_background">
		<div id="footer">
			<div class="footer_buttons">
				<a href="/suggest_app" class="footer_button"><button>Suggest An App</button></a>
				<a href="/account/add_app" class="footer_button"><button>Add Your App</button></a>
			</div>
		</div>
	</div>
	<div class="color_bar_2"></div>
	<div id="footer_bottom">
		<p class="copyright_info">
			Copyright &copy; <?=date("Y");?> IWAAT.com.  All Rights Reserved
		</p>
		<p class="footer_links">
			<a href="/about_us">About Us</a>&nbsp;|&nbsp;<a href="/privacy_policy">Privacy Policy</a>&nbsp;|&nbsp;<a href="/terms_service">Terms of Service</a>&nbsp;|&nbsp;<a href="/contact_us">Contact Us</a>
		</p>
	</div>
</div>
	
<script type="text/javascript">
	
	var $newsletter_email = $("form#newsletter_signup_form input.newsletter_email");
	var newsletter_placeholder = "Email Address...";
	$newsletter_email.focus(function() {
		var input = $(this);
		if (input.val() == newsletter_placeholder) {
			input.val('');
			input.removeClass('placeholder');
			$(this).css('color','#777');
		}
	}).blur(function() {
		var input = $(this);
		if (input.val() == '' || input.val() == newsletter_placeholder) {
			input.addClass('placeholder');
			input.val(newsletter_placeholder);
			$(this).css('color','#999');
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
	
	
	var uvOptions = {};
	$(function(){
		var uv = document.createElement('script'); uv.type = 'text/javascript'; uv.async = true;
		uv.src = ('https:' == document.location.protocol ? 'https://' : 'http://') + 'widget.uservoice.com/XsrFY2SjjjUiTcLBMP5DQ.js';
		var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(uv, s);
	});


	var _gaq = _gaq || [];
	_gaq.push(['_setAccount', 'UA-1409197-7']);
	_gaq.push(['_trackPageview']);

	$(function(){
		var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
		ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
		var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
	});

	</script>
	
</body>
</html>