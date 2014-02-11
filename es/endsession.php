<?php 
session_start();

if(!$_SESSION['loglogin']){
	?>
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
	<script type="text/javascript">
		alert('Sesión cerrada.');
		window.location.href='index.html';
	</script>
	<?php
}

?>