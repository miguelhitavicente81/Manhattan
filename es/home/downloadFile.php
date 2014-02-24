<?php
	$filea=$_GET["doc"];
	$file = "../../cvs/$filea";
	echo "Fichero montado: " . $file;
	header ("Content-Type: application/octet-stream");
	header ("Accept-Ranges: bytes");
	header ("Content-Length: ".filesize($file));
	header ("Content-Disposition: attachment; filename=".$filea);
	readfile($file);
?>
