<?php 
	session_start();
	error_reporting (E_ALL & ~E_NOTICE & ~E_STRICT & ~E_DEPRECATED & ~E_WARNING);
	set_time_limit(1800);
	set_include_path('../../common/0.12-rc12/src/' . PATH_SEPARATOR . get_include_path());
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
		require_once '../library/functions.php';
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



		<!-- /* En $myFile guardo el nombre del fichero php que WC está tratando en ese instante. Necesario para mostrar
		* el resto de menús de nivel 1 cuando navegue por ellos, y saber cuál es el activo (id='onlink')
		*/ -->
		<?php
		$myFile = 'home';
		$userRow = getDBrow('users', 'login', $_SESSION['loglogin']);
		?>



		<div id="main-content" class="container bs-docs-container">
			<div class="row">
				<div class="col-md-3">
						<div id="sidebar-navigation-list" class="bs-sidebar hidden-print affix-top" role="complementary">
						<ul class="nav bs-sidenav">
							<?php 
							$mainKeysRow = getDBcompletecolumnID('key', 'mainNames', 'id');
							$mainNamesRow = getDBcompletecolumnID('esName', 'mainNames', 'id');
							$j = 0;
							foreach($mainKeysRow as $i){
								if(getDBsinglefield('active', $i, 'profile', $userRow['profile'])){
									if($myFile == $i){
										echo "<li class='active'><a href=../$i.php id='onlink'>" . $mainNamesRow[$j] . "</a>";
										$j++;

										echo "<ul class='nav'>";

										$namesTable = $myFile.'Names';
										$numCols = getDBnumcolumns($myFile);
										$myFileProfileRow = getDBrow($myFile, 'profile', $userRow['profile']);
										for($k=3;$k<$numCols;$k++) {
											$colNamej = getDBcolumnname($myFile, $k);
											if(($myFileProfileRow[$k] == 1) && ($subLevelMenu = getDBsinglefield2('esName', $namesTable, 'key', $colNamej, 'level', '2'))) {
												if(!getDBsinglefield2('esName', $namesTable, 'fatherKey', $colNamej, 'level', '3')){
													$level2File = getDBsinglefield('key', $namesTable, 'esName', $subLevelMenu);
													// Because the file we are is a level 2 file, we do this comparision to make active element in list if it's this same file
													if ($level2File == basename(__FILE__, '.php'))
														echo "<li class='active'><span class='badge'>$k</span><a href=$level2File.php>" . $subLevelMenu . "</a></li>";
													else
														echo "<li><span class='badge'>$k</span><a href=$level2File.php>" . $subLevelMenu . "</a></li>";
												}
												else{
													$arrayKeys = array();
													$arrayKeys = getDBcolumnvalue('key', $namesTable, 'fatherKey', $colNamej);
													$checkFinished = 0;
													$l = 1;
													foreach($arrayKeys as $key){
														if($checkFinished == 0){
															if(($myFileProfileRow[$j+$l] == 1) && (getDBsinglefield($key, $myFile, 'profile', $userRow['profile']))){
																$level3File = $key;
																$checkFinished = 1;
															}
															else{
																$l++;
															}
														}
													}
													echo "<li><span class='badge'>$k</span><a href=home/$level3File.php>" . $subLevelMenu . "</a></li>";
												}
											}
										}

										echo "</ul> <!-- class='nav' -->";
										echo "</li> <!-- class='active' -->";

									}

									else{
										echo "<li><a href=../$i.php>" . $mainNamesRow[$j] . "</a></li>";
										$j++;
									}
								}
							}
							?>
						</ul> <!-- class="nav bs-sidenav" -->
						</div> <!-- id="sidebar-navigation-list"  -->
				</div> <!-- col-md-3 -->

				<div class="col-md-9 scrollable" role="main"> 
					<div class="bs-docs-section">

						<h1 class="page-header">Resultados de la búsqueda

						<!-- <p class="lead"> -->
						<small>

						</small></span></h1>

						<?php
							include 'Cezpdf.php';

							class Creport extends Cezpdf{
								function Creport($p,$o){
									$this->__construct($p, $o,'none',array());
								}
							}

							$enlace = connectDB();

							$consulta = "SELECT * FROM `cvitaes` where `nie` like '%$_POST[blankNIE]%' and `nationalities` like '%$_POST[blankNationality]%' and `sex` like '%$_POST[blankSex]%' and `drivingType` like '%$_POST[drivingType]%' and `marital` like '%$_POST[civilStatus]%' and `sons` like '%$_POST[blankSons]%' and `language` like '%$_POST[blankLanguages]%' and `occupation` like '%$_POST[blankJob]%';";

							if ($resultado = mysqli_query($enlace, $consulta)) {

								/* Obtener la informacin de campo de todas las columnas */
								$info_campo = mysqli_fetch_fields($resultado);
								$j=0;
								$info_campo = mysqli_fetch_fields($resultado);
								echo "<table id='resultTable' class='table table-striped table-hover'>";

								echo "<thead>";
								echo "	<tr>";
								foreach ($info_campo as $valor) {
									if (($valor->name == id) || ($valor->name == nie) || ($valor->name == name) || ($valor->name == surname)||($valor->name == occupation) ) {
										echo "		<th>$valor->name</th>";
									}
								}
								echo "	</tr>";
								echo "</thead>";

								while ($fila = $resultado->fetch_row()) {
									$pdf = new Cezpdf('A4'); // Seleccionamos tipo de hoja para el informe
									$pdf->selectFont('fonts/Helvetica.afm'); //seleccionamos fuente a utilizar
									$info_campo = mysqli_fetch_fields($resultado);
									$i=0;
									$j=0;

									$pdf_file_name = "";
									$pdf_file_name = $fila[3] . "_" . $fila[4];
									
									foreach ($info_campo as $valor) {
										chop($valor->name);
										if ($valor->name == id){ $id[$fila[$i]] = $fila[++$i]; }
										if (($valor->name==sex) && ($fila[$j]==0)){ $fila[$j] = "hombre"; }
										if (($valor->name==sex) && ($fila[$j]==1)){ $fila[$j] = "mujer"; }
										$pdf->ezText("<b>$valor->name</b> $fila[$j]");
										$i++;
										$j++;
									}

/*									if ($j%2==0){
										echo "<tr><td>";
									}
									else{
										echo "<tr class=alt><td>";
									}*/

									echo "<tr>";
									echo "	<td>$fila[0]</td>";
									echo "	<td><a href=viewCV.php?id_b=$fila[0] target=_blank>$fila[1]</a></td>";
									echo "	<td>$fila[3]</td>";
									echo "	<td>$fila[4]</td>";
									echo "	<td>$fila[30]</td>";
									echo "</tr>";

									$documento_pdf = $pdf->ezOutput();
									#$nf="/Applications/XAMPP/xamppfiles/temp/cvs/cv_$pdf_file_name.pdf";
									$nf="../../cvs/$pdf_file_name.pdf";
									// $nf="/Applications/XAMPP/xamppfiles/temp/cvs/cv_$pdf_file_name.pdf";
									$cvs_path = $_SERVER['DOCUMENT_ROOT'] . "/Manhattan/cvs/"; 
									$nf= $cvs_path . "cv_$pdf_file_name.pdf";
									//echo '-->'.$nf.'<--'."\n";
									//echo "Path: " . $cvs_path . "Nombre fichero: " . $nf;
									
									//$fichero = fopen(utf8_decode("$nf"),'wb');
									$fichero = fopen($nf,'wb');
									//echo "-->".utf8_decode($nf).'<--';
									fwrite ($fichero, $documento_pdf);
									fclose ($fichero);
									$nf="";
									$j++;
								}

								echo "</table>";
								
								mysqli_free_result($resultado);
							}
							
							$numero=rand();

							# Limpiamos los PDFs generados
							//`cd ../../common/cvs/ && tar cf cvs$numero.zip *.pdf`;
							$cvs_path = $_SERVER['DOCUMENT_ROOT'] . "/Manhattan/cvs/";
							`cd /Applications/XAMPP/htdocs/Manhattan/cvs && tar -cf cvs$numero.zip *.pdf`; 
							//`cd $cvs_path && tar cf cvs$numero.zip *.pdf`;
							//`rm -rf ../../common/cvs/*.pdf`;

							$i=0;
							foreach ($id as $valor) {
								$id_o[$i]=$valor;
								$i++;
							}

							echo "<form id='downloadSearchReport' name='downloadSearchReport' class='form-horizontal' method='post' action='downloadFile.php?doc=cvs$numero.zip'>";
							echo "	<div id='form_download' class='form-group pull-right' style='margin: 1px;'>";
							echo "		<button type='submit' name='downloadSearchReportButton' class='btn btn-success' >Descargar Informe   <span class='glyphicon glyphicon-download-alt'> </span></button>";
							echo "	</div>";
							echo "</form>";

							#echo "<a href=downloadFile.php?doc=cvs$numero.zip>descargar</a>";

							$_SESSION["id_o"] = serialize($id_o);
							$_SESSION["id"] = serialize($id);
						?>		

					</div> <!-- bs-docs-section -->
				</div> <!-- col-md-9 scrollable role=main -->
			</div> <!-- row -->
		</div> <!-- class="container bs-docs-container" -->



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
