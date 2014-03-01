<?php 
		session_start();
		error_reporting (E_ALL & ~E_NOTICE & ~E_STRICT & ~E_DEPRECATED & ~E_WARNING);
		set_time_limit(1800);
		set_include_path('../../common/0.12-rc12/src/' . PATH_SEPARATOR . get_include_path());
		require_once($_SERVER['DOCUMENT_ROOT'] . '/common/library/functions.php');
		include 'Cezpdf.php';
?>
<html lang="es">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="">
	<meta name="author" content="David Alfonso Ginés Prieto, Miguel Hita Vicente y Miguel Ángel Melón Pérez">
	
	<title>CVs encontrados</title>
	
	<!-- Custom styles for this template -->
	<link href="../../common/css/design.css" rel="stylesheet">
	<!-- <link href="../../common/css/styles.css" rel="stylesheet"> -->
	<!-- <link href="../common/css/docs.css" rel="stylesheet"> -->

	<!-- Using the same favicon from perspectiva-alemania.com site -->
	<link rel="shortcut icon" href="http://www.perspectiva-alemania.com/wp-content/themes/perspectiva2013/bilder/favicon.png">
	<!-- Using the favicon for touch-devices shortcut -->
	<link rel="apple-touch-icon" href="../../common/img/apple-touch-icon.png">
	

</head>

<body>
	<?php
	
	$output_dir = $_SERVER['DOCUMENT_ROOT'] . "/cvs/";
	if (!$_SESSION['loglogin']){
		?>
		<script type="text/javascript">
			window.location.href='../index.html';
		</script>
		<?php
	}
	else {
		$lastUpdate = $_SESSION['lastupdate'];
		$curUpdate = date('Y-m-j H:i:s');
		$elapsedTime = (strtotime($curUpdate)-strtotime($lastUpdate));
		if($elapsedTime > $_SESSION['sessionexpiration']){
			?>
			<script type="text/javascript">
				window.location.href='../endsession.php';
			</script>
			<?php
		}
		else{
			$_SESSION['lastupdate'] = $curUpdate;
			unset($lastUpdate);
			unset($curUpdate);
			unset($elapsedTime);
		}
		require_once($_SERVER['DOCUMENT_ROOT'] . '/common/library/functions.php');
		?>


		<!-- Static navbar -->
		<div id="header" class="navbar navbar-default navbar-fixed-top" role="navigation" id="fixed-top-bar">
			<div id="top_line" class="top-page-color"></div>
			<div class="container-fluid">
				<div class="navbar-header">
					<a href="http://www.perspectiva-alemania.com/" title="Perspectiva Alemania">
						<img src="../../common/img/logo.png" alt="Perspectiva Alemania">
					</a>
				</div>
				<!-- <div class="navbar-collapse collapse"> -->
				<div class="nav navbar-nav navbar-right">
					<li class="dropdown">
						<button type="button" class="navbar-toggle always-visible" data-toggle="dropdown">
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
						</button>
						<ul class="dropdown-menu">
							<li class="dropdown-header">Conectado como: <?php echo $_SESSION['loglogin']; ?></li>
							<li class="divider"></li>
							<li><a href="../administration.php">Configuración</a></li>
							<li><a href="#">Abrir incidencia</a></li>
							<li><a href="#">Revisar Curriculum</a></li>
							<li class="divider"></li>
							<li><a data-toggle="modal" data-target="#exitRequest" href="#exitRequest">Salir</a></li>
						</ul>
					</li>
				</div>
				<!-- </div><!--/.nav-collapse -->
			</div><!--/.container-fluid -->
		</div>	<!--/Static navbar -->


		<!-- exitRequest Modal -->
		<div id="exitRequest" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="exitRequestLabel" aria-hidden="true">
			<div class="modal-dialog">
				<form class="modal-content" action="../endsession.php">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h4 class="modal-title" id="exitRequestLabel">Cerrar sesión</h4>
					</div>
					<div class="modal-body">
						¿Estás seguro de que quieres salir?
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
						<button type="submit" class="btn btn-primary">Sí, cerrar sesión</button>
					</div>
				</form>
			</div>
		</div> <!-- exitRequest Modal -->



		<!-- /* En $myFile guardo el nombre del fichero php que WC est?tratando en ese instante. Necesario para mostrar
		* el resto de men?s de nivel 1 cuando navegue por ellos, y saber cu? es el activo (id='onlink')
		*/ -->
		<?php
			$myFile = 'home';
			$userRow = getDBrow('users', 'login', $_SESSION['loglogin']);

			$pendingCVs = getPendingCVs();
		?>



		<script type="text/javascript">
			function insert() {
				alert('Nota Añadida');
			}
		</script> 


		<?php
		$nota=$_POST['nota'];

		$output_dir = $_SERVER['DOCUMENT_ROOT'] . "/cvs/";

		class Creport extends Cezpdf{
			function Creport($p,$o){
				$this->__construct($p, $o,'none',array());
			}
		}
		$id_ac=$_GET["id_b"];
		$id_aco=$_GET["id_bb"];
		$id =unserialize($_SESSION["id"]);
		$id_o =unserialize($_SESSION["id_o"]);
		$custom_elements =unserialize($_SESSION["custom"]);
		$n_custom_elements = count($custom_elements);
		if(strlen($id_ac)>0){$actual=$id[$id_ac];$ida=$id_ac;}
		if(strlen($id_aco)>0){$actual=$id_o[$id_aco];$ida=$id_aco;}
		$i=0;
		foreach ($id_o as $valor){
			if($valor == $actual){
				$ind_a=$i;
				$h=$i-1;
				$ind_p=$h;
				$j=$i+1;
				$ind_n=$j;
			}
			$i++;
		}
		$enlace = connectDB();
		$output_dir = $_SERVER['DOCUMENT_ROOT'] . "/cvs/";

		$consulta = "SELECT * from cVitaes where nie like '$actual'" ;
		if ($resultado = mysqli_query($enlace, $consulta)) {
			$texto = "";
			while ($fila = $resultado->fetch_assoc()) {
				$pdf = new Cezpdf('A4'); // Seleccionamos tipo de hoja para el informe
				$pdf->selectFont('fonts/Helvetica.afm'); //seleccionamos fuente a utilizar
				$id[$fila['id']] = $fila['nie'];
				if ($fila['sex']==0){ $fila['sex'] = "hombre"; }
				if ($fila['sex']==1){ $fila['sex'] = "mujer"; }

				// Añadido tras el merge de Miguel Hita
				$imagen="chica.jpg";

				$texto = $texto . "<img src=../../cvs/".$imagen." width=\"100px\" height=\"100px\"\/>";
				//print"<img src=../../cvs/".$imagen." width=\"100px\" height=\"100px\"\/>";
				$pdf->ezImage("$output_dir/chica2.jpg",0,0,'none','right');
				// Añadido tras el merge de Miguel Hita


				while (list($clave, $valor) = each($fila)) {

					$texto = $texto . "<b>$clave</b> $valor<br>";
					$pdf->ezText("<b>$clave</b> $valor");

				}
				if (strlen($nota)>0){$pdf->ezText("\n\n<b>NOTA:</b>\n\n".utf8_decode($nota));
			}
			$documento_pdf = $pdf->ezOutput();
			chdir($output_dir);
			$pdf_file_name = "";
			$pdf_file_name = $fila['userLogin'];
			$nf=$pdf_file_name.".pdf";
			$fichero = fopen($nf,'wb') or die ("No se abrio $nf") ;
			fwrite ($fichero, $documento_pdf);
			fclose ($fichero);
		}
		}

		?>


		<div id="main-content" class="cvViewer bs-docs-container">
			<div class="row container-fluid cvViewer">
				<div class="panel panel-default cvViewer tooltip-demo col-md-8" role="main"> <!-- Panel -->
					<div class="btn-group pull-right">
						<?php 	if(strlen($id_o[$ind_p])>0) 
									echo "<a href='viewCV.php?id_bb=$ind_p' class='btn btn-default btn-sm' data-toggle='tooltip' data-original-title='Anterior'><span class='glyphicon glyphicon-chevron-left'></span></a>";
								else 
									echo "<a class='btn btn-default btn-sm' disabled><span class='glyphicon glyphicon-chevron-left'></span></a>";
						?>
							<a href="<?php echo "../../cvs/".$nf ?>" class="btn btn-default btn-sm" data-toggle='tooltip' data-original-title='Descargar CV en PDF'><span class='glyphicon glyphicon-download-alt'></span></a>
						<?php 	if(strlen($id_o[$ind_n])>0) 
									echo "<a href='viewCV.php?id_bb=$ind_n' class='btn btn-default btn-sm' data-toggle='tooltip' data-original-title='Siguiente'><span class='glyphicon glyphicon-chevron-right'></span></a>";
								else 
									echo "<a class='btn btn-default btn-sm' disabled><span class='glyphicon glyphicon-chevron-right'></span></a>";

							$_SESSION["id_o"] = serialize($id_o);
							$_SESSION["id"] = serialize($id);
						?>
					</div>
					<div class="panel-heading">
						<h3 class="panel-title">CV de fulanito de tal</h3>
					</div>
					<div class="panel-body scrollable" > <!-- panel-body -->
						<?php echo $texto; ?>
					</div> <!-- panel-body -->
				</div> <!-- Panel -->

				<div class="panel panel-default col-md-3">
					<div class="panel-heading">
						<h3 class="panel-title">Añadir nota</h3>
					</div>
					<div class="panel-body" > <!-- panel-body -->
						<?php
							echo "<form name='formu' id='formu' class='form-horizontal' action='viewCV.php?id_b=".$ida."&reportType=".$_GET[reportType]."' method='post' enctype='multipart/form-data'>";
							echo "<textarea class='form-control' name='nota' rows='10' cols='40'></textarea>";

							echo "<div id='form_submit' class='form-group pull-right' style='margin: 1px; margin-top: 10px;'>";
							echo "		<button type='submit' name='enviar' class='btn btn-primary' onclick='insert();'>Insertar nota   <span class='glyphicon glyphicon-pencil'> </span></button>";
							echo "</div>";

						?>
					</div> <!-- panel-body -->
				</div>
			</div>
		</div>


		<?php

		} //del "else" de $_SESSION.

		?>


<!-- Footer bar & info
	================================================== -->
	<div id="footer" class="hidden-xs hidden-sm" >
		<div class="container">
			<p class="text-muted">&copy; Perspectiva Alemania, S.L.</p>
		</div>
	</div>


<!-- Scripts. Placed at the end of the document so the pages load faster.
	================================================== -->
	<!-- Bootstrap core JavaScript -->
	<script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
	<script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>

	<!-- Site own functions -->
	<script src="../../common/js/functions.js"></script>
	<script src="../../common/js/application.js"></script>
	<script src="../../common/js/docs.min.js"></script>

</body>
</html>
