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
	<meta name="author" content="David Alfonso Gin? Prieto, Miguel Hita Vicente y Miguel ?ngel Mel? P?ez">
	
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
						<h4 class="modal-title" id="exitRequestLabel">Cerrar sesi?</h4>
					</div>
					<div class="modal-body">
						?Est? seguro de que quieres salir?
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
						<button type="submit" class="btn btn-primary">S? cerrar sesi?</button>
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
function insert()
{
alert('Nota A?dida');
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
$enlace = mysqli_connect("localhost", "root", "", "PRJ2014001");

if (mysqli_connect_errno()) {
    printf("Fall la conexin: %s\n", mysqli_connect_error());
    exit();
}

$consulta = "SELECT * from cVitaes where nie like '$actual'" ;
if ($resultado = mysqli_query($enlace, $consulta)) {
while ($fila = $resultado->fetch_assoc()) {
		$pdf = new Cezpdf('A4'); // Seleccionamos tipo de hoja para el informe
		$pdf->selectFont('fonts/Helvetica.afm'); //seleccionamos fuente a utilizar
		$id[$fila['id']] = $fila['nie'];
		if ($fila['sex']==0){ $fila['sex'] = "hombre"; }
		if ($fila['sex']==1){ $fila['sex'] = "mujer"; }

	
									while (list($clave, $valor) = each($fila)) {
											echo "<b>$clave</b> $valor<br>";
											$pdf->ezText("<b>$clave</b> $valor");
											
									}
									if (strlen($nota)>0){$pdf->ezText("\n\nNOTA:\n\n$nota");
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
echo "NOTA <br>";
echo "<form name=formu id=formu action=viewCV.php?id_b=".$ida."&reportType=".$_GET[reportType]." method=post enctype=multipart/form-data>";
echo "<textarea name=nota rows=5 cols=40></textarea><br>";
echo "<input type=submit name=enviar value=Insertar Nota onclick=\"insert();\"/><br>";
if(strlen($id_o[$ind_n])>0)
//echo "<a href=visualizacv.php?id_bb=$ind_n>SIGUIENTE</a><br>";
echo "<a href=viewCV.php?id_bb=$ind_n>SIGUIENTE</a><br>";
if(strlen($id_o[$ind_p])>0)
//echo "<a href=visualizacv.php?id_bb=$ind_p>PREVIO </a><br>";
echo "<a href=viewCV.php?id_bb=$ind_p>PREVIO </a><br>";
$_SESSION["id_o"] = serialize($id_o);
$_SESSION["id"] = serialize($id);
?>





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
