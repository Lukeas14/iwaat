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
width:500px;
margin:100px auto;
border:				#BBB 6px solid;
background-color:	#fff;
padding:			10px;
}

img.iwaat_logo{
	margin:0 0 0 80px;
}
h1 {
font-weight:		normal;
font-size:			20px;
color:				#990000;
margin:				35px 0 35px 0;
}
</style>
</head>
<body>
	<div id="content">
		<img class="iwaat_logo" src="/images/iwaat_logo.png"/>
		<br/>
		<h1><?php echo $heading; ?></h1>
		<?php echo $message; ?>
		You can either <a href="javascript:history.go(-1)"/>Go Back</a> or <a href="http://www.iwaat.com/">Go to the Homepage</a>.
	</div>
</body>
</html>