<?php
$filea=$_GET["doc"];
$file = "/Applications/XAMPP/xamppfiles/temp/cvs/$filea";
header ("Content-Type: application/octet-stream");
header ("Accept-Ranges: bytes");
header ("Content-Length: ".filesize($file));
header ("Content-Disposition: attachment; filename=".$filea);
readfile($file);
?>
