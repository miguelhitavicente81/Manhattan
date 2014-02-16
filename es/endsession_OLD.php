<?php session_start(); ?>

<html lang="es">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="">
	<meta name="author" content="">
	
	<title>Inicio</title>

	<!-- Custom styles for this template -->
	<link href="../common/css/styles.css" rel="stylesheet">
	<link href="../common/css/design.css" rel="stylesheet">
	<link href="../common/css/docs.css" rel="stylesheet">

	<!-- Using the same favicon from perspectiva-alemania.com site -->
	<link rel="shortcut icon" href="http://www.perspectiva-alemania.com/wp-content/themes/perspectiva2013/bilder/favicon.png">
	<!-- Using the favicon for touch-devices shortcut -->
	<link rel="apple-touch-icon" href="../common/img/apple-touch-icon.png">


</head>

<body>

	<?php if(!$_SESSION['loglogin']) { ?>
	<script type="text/javascript">
		window.location.href='index.html';
	</script>
	<?php
}
else{
	require_once './library/functions.php';
	//executeDBquery("UPDATE `users` SET `connected` = '0' WHERE `login` = '".$_SESSION['loglogin']."'");
	session_destroy();
	?>

	<div class="alert alert-warning fade in">
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
		<strong>Holy guacamole!</strong> Best check yo self, you're not looking too good.
	</div>

	<script type="text/javascript">
		//alert('Sesión cerrada.');
		window.location.href='index.html';
	</script>
	<?php
}

?>

</body>