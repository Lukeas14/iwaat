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
	<script type="text/javascript" src="/js/simpletip.js"></script>
		
	<title>I Want An App That...</title>
</head>
<body>

<div id="wrapper">

	<div id="content">
		
	<div id="header_account">
		<?php if($this->ion_auth->logged_in()): ?>
			<?php $profile = $this->ion_auth->user()->row();?>
			<span class="account_username"><?=$profile->username?></span>&nbsp;&nbsp;&nbsp;<a href="/account/profile">My Account</a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;<a href="/logout">Log out</a>
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
						<span class="">I</span>
						<span class="red">W</span>ant
						<span class="green">A</span>n
						<span class="blue">A</span>pp
						<span class="yellow">T</span>hat...
					</div>
				</a>
				<input class="text" type="text" name="q" class="text_field" autocomplete="off" value="<?=($this->input->get('q')) ? $this->input->get('q') : ''?>"/>
				
			<!--<ul id="search_ticker">
				<li><a href="/search?q=manages+my+twitter+feed">...manages my twitter feed</a></li>
				<li><a href="/search?q=backs+up+all+my+photos">...backs up all my photos</a></li>
				<li><a href="/search?q=tracks+my+daily+workouts">...tracks my daily workouts</a></li>
				<li><a href="/search?q=teaches+me+how+to+code">...teaches me how to code</a></li>
				<li><a href="/search?q=handles+customer+appointments">...handles customer appointments</a></li>
			</ul>->-->
				<Br/>
				<input class="submit" type="submit" value="Search"/>
				
			</form>
			<div id="header_text_left">
				<span>Search</span> for a web application that solves your specific problem.
			</div>
			<div id="header_text_right">
				<span>Browse</span> through our library of web apps by their category.
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
	