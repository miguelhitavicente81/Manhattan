<?php
	session_start();
	error_reporting (E_ALL & ~E_NOTICE & ~E_STRICT & ~E_DEPRECATED & ~E_WARNING);
	require_once($_SERVER['DOCUMENT_ROOT'] . '/common/library/functions.php');
	$id_ac=$_GET["id_b"];
	$id_aco=$_GET["id_bb"];
	$id =unserialize($_SESSION["id"]);
	$id_o =unserialize($_SESSION["id_o"]);	
	$zip = new ZipArchive();
	$numero = date("YmdHis");
	$output_dir = $_SERVER['DOCUMENT_ROOT'] . "/cvs/";
	$filezip = $output_dir . $numero . ".zip";
	$enlace = connectDB();


	foreach ($id_o as $valor) {
		$consulta = "SELECT * from cvitaes where nie like '$valor'" ;
		if ($resultado = mysqli_query($enlace, $consulta)) {

			while ($fila = $resultado->fetch_assoc()) {
				
				

				if($zip->open($filezip,ZIPARCHIVE::CREATE)===true) {
					chdir("$output_dir");
					echo getcwd() . "\n";
					$nf = $fila['userLogin'] . ".pdf";
					echo "--->$nf<br>";
					$zip->addFile($nf);
					$zip->close();
					unlink($nf);
					$nf="";	
					
					
					
				}
			}
		}
	}

	$desc = date("YmdHis");
	header ("Content-Type: application/octet-stream");
	header ("Accept-Ranges: bytes");
	header ("Content-Length: ".filesize($filezip));
	header ("Content-Disposition: attachment; filename=".$desc.".zip");
	readfile($filezip);
?>
