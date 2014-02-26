<?php
$filea=$_GET["doc"];
$file = "/Applications/XAMPP/htdocs/Manhattan/cvs/$filea";
//$file = "../../cvs/$filea";
$cvs_path = $_SERVER['DOCUMENT_ROOT'] . "/Manhattan/cvs/";
$file = $cvs_path . $filea;
header ("Content-Type: application/octet-stream");
header ("Accept-Ranges: bytes");
header ("Content-Length: ".filesize($file));
header ("Content-Disposition: attachment; filename=".$filea);
readfile($file);
?>
