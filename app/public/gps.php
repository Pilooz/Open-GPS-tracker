<?php
    if ( $_SERVER['REQUEST_METHOD']=='GET' && realpath(__FILE__) == realpath( $_SERVER['SCRIPT_FILENAME'] ) ) {        
		header( 'location: /index.php' );
    }
?>
<!doctype html>
<html lang="sv">
<head>
	<meta charset="utf-8">
	<title>Open GPS-tracker</title>
	<script src="js/vendor/umbrella.js"></script>
	<script src="js/gps.js"></script>
	<link rel="stylesheet" href="css/gps.css" />
	<meta name="viewport" content="width=device-width,user-scalable=no" />
</head>
<body>

<div id="page">

	<div id="status">
		<p class="stopped">Not tracking</p>
	</div>
	<button id="start">Start tracking</button>
	<button id="stop" disabled="disabled">Stop tracking</button>
	<div id="msg" style="font-size: 0.8em;"><p class="ok"></p></div>

</div>

</body>
</html>