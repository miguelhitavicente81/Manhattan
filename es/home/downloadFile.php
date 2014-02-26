<?php
	$file = $_SERVER['DOCUMENT_ROOT'] . '/cvs/' . $_GET['doc'];
	header ("Content-Type: application/octet-stream");
	header ("Accept-Ranges: bytes");
	header ("Content-Length: ".filesize($file));
	header ("Content-Disposition: attachment; filename=" . $_GET['doc']);
	readfile($file);
?>
