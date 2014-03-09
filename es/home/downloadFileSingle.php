<?php
        session_start();
        error_reporting (E_ALL & ~E_NOTICE & ~E_STRICT & ~E_DEPRECATED & ~E_WARNING);
        require_once($_SERVER['DOCUMENT_ROOT'] . '/common/library/functions.php');
$dir=$_SERVER['DOCUMENT_ROOT'] . "/cvs/pa_000011/";
$file=$_SERVER['DOCUMENT_ROOT'] . "/cvs/pa_000011/".$_GET[doc];
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="'.basename($file).'"');
header('Content-Length: ' . filesize($file));
readfile($file);
?>

