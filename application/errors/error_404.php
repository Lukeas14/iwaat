<html>
<head>
<title>404 Page Notiuuu Found</title>
<style type="text/css">

body {
background:#EEE url('/images/background.png') repeat;
margin:				40px;
font-family:		Lucida Grande, Verdana, Sans-serif;
font-size:			12px;
color:				#000;
}

#content  {
width:316px;
margin:100px auto;
border:				#BBB 6px solid;
background:#FFF url('/images/iwaat_logo_small.png') top center no-repeat;
padding:			10px;
}

#content #logo_bg{
	position:relative;
	top:-10px;
	left:-10px;
	width:336px;
	height:60px;
	padding:5px 0 0 0;
	background:#315580;
	text-align:center;
}

img.iwaat_logo{
	margin:0 0 0 80px;
}
h1 {
font-weight:		normal;
font-size:			20px;
color:				#990000;
margin:				5px 0 35px 0;
}
</style>
</head>
<body>
	<div id="content">
		<div id="logo_bg">
			<img src='/images/iwaat_logo_small.png'/>
		</div>
		<h1><?php echo $heading; ?></h1>
		<?php echo $message; ?>
		You can either <a href="javascript:history.go(-1)"/>Go Back</a> or <a href="http://www.iwaat.com/">Go to the Homepage</a>.
	</div>
</body>
</html>