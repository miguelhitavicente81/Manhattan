<?php
	$file = $_GET["doc"];

	$desc = date("YmdHis");
	header ("Content-Type: application/octet-stream");
	header ("Accept-Ranges: bytes");
	header ("Content-Length: ".filesize($file));
	header ("Content-Disposition: attachment; filename=".$desc.".zip");
	readfile($file);
?>
