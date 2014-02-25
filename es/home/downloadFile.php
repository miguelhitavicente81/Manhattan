<?php
	$filea=$_GET["doc"];
	$file = "$filea";
	$desc=date("YmdHis");
	header ("Content-Type: application/octet-stream");
	header ("Accept-Ranges: bytes");
	header ("Content-Length: ".filesize($file));
	header ("Content-Disposition: attachment; filename=".$desc.".zip");
	readfile($file);
?>
