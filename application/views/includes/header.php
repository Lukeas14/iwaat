<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:fb="http://ogp.me/ns/fb#">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	
	<link rel="stylesheet" href="/css/style.css" type="text/css" media="screen"/>
	<link rel="stylesheet" href="/css/jquery-ui-1.8.16.custom.css" type="text/css" media="screen"/>
	<link href='http://fonts.googleapis.com/css?family=Jura' rel='stylesheet' type='text/css'>

	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
	<script type="text/javascript" src="/js/jquery-ui-1.8.16.custom.min.js"></script>
	<script type="text/javascript" src="/js/script.js"></script>
	<script type="text/javascript" src="/js/qtip.js"></script>
		
	<title><?=(!empty($meta['title'])) ? $meta['title'] : "I Want An App That... | IWAAT.com"?></title>
	<meta name="description" content="<?=(!empty($meta['description'])) ? $meta['description'] : 'IWAAT.com is a web app discovery engine. Our mission is to help you discover and research apps that you may never come across otherwise. At the same time, we hope to provide application creators with increased visibility to potential users.'?>"/>

	<meta property="fb:admins" content="jlukeas"/>
	<meta property="fb:app_id" content="153127361451027"/>
	<meta property="og:image" content="http://www.iwaat.com/images/iwaat_avatar.png"/>
</head>
<body>

<div id="wrapper">

	<div id="content">
		
	<div id="header_account">
		<?php if($this->ion_auth->logged_in()): ?>
			<?php $profile = $this->ion_auth->user()->row();?>
			<span class="account_username"><?=$profile->username?></span>
			&nbsp;&nbsp;&nbsp;
			<?php if($this->ion_auth->is_admin()): ?>
			<a href="/admin">Admin</a>
			&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
			<?php endif; ?>
			<a href="/account/profile">My Account</a>
			&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
			<a href="/logout">Log out</a>
		<?php else: ?>
			<a href="/login_register">Log in / Register</a>
		<?php endif; ?>
	</div>
		
	<div id="header_wrapper">
		
		<div class="color_bar_1"></div>
		<div id="header" class="<?=(isset($is_homepage) && $is_homepage === true) ? 'homepage' : ''?>">
			<form action="/search" method="GET" accept-charset="utf-8">
				<a href="/">
					<div id="logo">
						<img src="<?=(isset($is_homepage) && $is_homepage === true) ? '/images/iwaat_logo.png' : '/images/iwaat_logo_small.png'?>"/>
					</div>
				</a>
				<input class="text" type="text" name="q" class="text_field" autocomplete="off" maxlength="255" value="<?=($this->input->get('q')) ? $this->input->get('q') : ''?>"/>
				<Br/>
				<input class="submit" type="submit" value="Search"/>
				
			</form>
			<div id="header_text_left">
				<span>Search</span> for a web application that solves your specific problem.
			</div>
			<?php if(isset($is_homepage) && $is_homepage === true): ?>
				<div id="header_social_buttons" >

					<!-- FB like button-->
					<div id="header_fb_like">
						<div class="fb-like" data-href="http://www.iwaat.com" data-send="false" data-width="50" data-show-faces="false" data-colorscheme="dark"></div>
					</div>
					
					<!-- Tweet button-->
					<a href="https://twitter.com/share" class="twitter-share-button" data-url="http://www.iwaat.com" data-via="IWantAnAppThat" data-count="none">Tweet</a>
					<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
					
					<br/>

					<!-- Google plus one button -->
					<g:plusone annotation="none"></g:plusone>
					<!-- Place this render call where appropriate -->
					<script type="text/javascript">
					  (function() {
					    var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
					    po.src = 'https://apis.google.com/js/plusone.js';
					    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
					  })();
					</script>

					<div id="fb-root"></div>
					<script>(function(d, s, id) {
					  var js, fjs = d.getElementsByTagName(s)[0];
					  if (d.getElementById(id)) return;
					  js = d.createElement(s); js.id = id;
					  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=153127361451027";
					  fjs.parentNode.insertBefore(js, fjs);
					}(document, 'script', 'facebook-jssdk'));</script>
				</div>
			<?php endif; ?>
			<div id="header_text_right">
				<span>Browse</span> through our library of web apps by category.
			</div>
		</div>
	</div>
		
	<?php
	$controller_notifications = (!empty($notifications)) ? $notifications : array();
	$notifications = get_notifications($controller_notifications);
	if(!empty($notifications)):
	?>
	<div id="notifications_wrapper">
		<div id="notifications_background"></div>
		<div id="notifications">
			<?php 
			foreach($notifications as $note_type => $note_array):
				foreach($note_array as $note_val):
			?>
				<p class="notification <?=$note_type?>"><?=$note_val?></p>
			<?php 
				endforeach;
			endforeach;
			?>
		</div>
	</div>
	<?php
	endif;
	?>
	