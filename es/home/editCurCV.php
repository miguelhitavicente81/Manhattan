<?php session_start(); ?>
<html lang="es">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="">
	<meta name="author" content="David Alfonso Ginés Prieto, Miguel Hita Vicente y Miguel Ángel Melón Pérez">
	
	<title>Validando CV</title>

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
			window.location.href='index.html';
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
				window.location.href='endsession.php';
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
		</div>



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

						<h2 class="page-header">Validando el CV...</h2>

					</span>

					<?php 
						//Aunque debería ser
						//if(!isset($_GET['nie'])){
						if(!isset($_GET['codvalue'])){
							$editedCVRow = getDBrow('cVitaes', 'nie', $_POST['eCCVnie']);
							
						}
						/***************  Fin del bloque que valida el contenido enviado en el formulario  ***************/
						
						/***************  Aquí comienza el bloque que permite mostrar el formulario  ***************/
						else{
							//$editedCVRow = getDBrow('cVitaes', 'nie', $_GET['nie']);
							$editedCVRow = getDBrow('cVitaes', 'nie', $_GET['codvalue']);
							echo '<fieldset id="auto0">';
								echo '<form id="editedCV" name="editedCV" method="post" action=editCurCV.php">';
									echo "Nombre: <input type='text' name='eCCVname' value=" . $editedCVRow['name'] . " size='20' /><br/>";
									echo "Apellidos: <input type='text' name='eCCVsurname' value=" . $editedCVRow['surname'] . " size='20' /><br/>";
									echo "Fecha Nacimiento: <input type='date' name='eCCVbirthdate' value=" . $editedCVRow['birthdate'] . " size='10' /><br/>";
									echo "NIE: <input type='text' name='eCCVnie' value=" . $editedCVRow['nie'] . " size='10' /><br/>";
									echo "Nacionalidad: <input type='text' name='eCCVnationalities' value=" . $editedCVRow['nationalities'] . " size='10' /><br/>";
									if($editedCVRow['sex'] == 0){
										echo "Sexo: <input type='text' name='eCCVsex' value='Hombre' size='10' disabled /><br/>";
									}
									else{
										echo "Sexo: <input type='text' name='eCCVsex' value='Mujer' size='10' disabled /><br/>";
									}
									echo "Tipo de Dirección: <input type='text' name='eCCVaddrtype' value=" . $editedCVRow['addrType'] . " size='10' disabled />";
									echo "Nombre: <input type='text' name='eCCVaddrName' value=" . $editedCVRow['addrName'] . " size='50' disabled /><br/>";
									echo "Número: <input type='text' name='eCCVaddrNum' value=" . $editedCVRow['addrNum'] . " size='5' disabled />";
									echo "Portal: <input type='text' name='eCCVaddrPortal' value=" . $editedCVRow['portal'] . " size='5' disabled />";
									echo "Escalera: <input type='text' name='eCCVaddrStair' value=" . $editedCVRow['stair'] . " size='5' disabled />";
									echo "Piso: <input type='text' name='eCCVaddrFloor' value=" . $editedCVRow['addrFloor'] . " size='5' disabled />";
									echo "Puerta: <input type='text' name='eCCVaddrDoor' value=" . $editedCVRow['addrDoor'] . " size='5' disabled /><br/>";
									echo "Código Postal: <input type='text' name='eCCVpostal' value=" . $editedCVRow['postalCode'] . " size='5' disabled />";
									echo "Localidad: <input type='text' name='eCCVcity' value=" . $editedCVRow['city'] . " size='20' disabled />";
									echo "Provincia: <input type='text' name='eCCVprovince' value=" . $editedCVRow['province'] . " size='20' disabled />";
									echo "País: <input type='text' name='eCCVcountry' value=" . $editedCVRow['country'] . " size='20' disabled /><br/>";
									echo "Teléfono Fijo: <input type='text' name='eCCVphone' value=" . $editedCVRow['phone'] . " size='10' disabled /><br/>";
									echo "Teléfono Móvil: <input type='text' name='eCCVmobile' value=" . $editedCVRow['mobile'] . " size='10' disabled /><br/>";
									echo "Correo Electrónico: <input type='mail' name='eCCVmail' value=" . $editedCVRow['mail'] . " size='20' disabled /><br/>";
									echo "Carnet de Conducir: <input type='text' name='eCCVdrivingType' value=" . $editedCVRow['drivingType'] . " size='5' disabled />";
									echo "<input type='date' name='eCCVdrivingDate' value=" . $editedCVRow['drivingDate'] . " /><br/>";
									echo "Estado Civil: <input type='text' name='eCCVmarital' value=" . $editedCVRow['marital'] . " size='10' disabled /><br/>";
									echo "Hijos: <input type='text' name='eCCVsons' value=" . $editedCVRow['sons'] . " size='5' disabled /><br/>";
									echo "Documentos Adicionales: <input type='text' name='eCCV___' value=" . $editedCVRow['sons'] . " size='5' disabled /><br/>";
									echo "Idiomas: <input type='text' name='eCCV___' value=" . $editedCVRow['sons'] . " size='5' disabled /><br/>";
									echo "Profesiones: <input type='text' name='eCCV___' value=" . $editedCVRow['sons'] . " size='5' disabled /><br/>";
									echo "Formación: <input type='text' name='eCCV___' value=" . $editedCVRow['sons'] . " size='5' disabled /><br/>";
									echo "Experiencia Laboral: <input type='text' name='eCCV___' value=" . $editedCVRow['sons'] . " size='5' disabled /><br/>";
									echo "Otros detalles: <input type='textarea' name='eCCVotherDetails' value=" . $editedCVRow['otherDetails'] . " rows='5' cols='40' disabled /><br/>";
									echo "Habilidad 1: <input type='text' name='eCCVskill1' value=" . $editedCVRow['skill1'] . " size='20' disabled /><br/>";
									echo "Habilidad 2: <input type='text' name='eCCVskill2' value=" . $editedCVRow['skill2'] . " size='20' disabled /><br/>";
									echo "Habilidad 3: <input type='text' name='eCCVskill3' value=" . $editedCVRow['skill3'] . " size='20' disabled /><br/>";
									echo "Habilidad 4: <input type='text' name='eCCVskill4' value=" . $editedCVRow['skill4'] . " size='20' disabled /><br/>";
									echo "Habilidad 5: <input type='text' name='eCCVskill5' value=" . $editedCVRow['skill5'] . " size='20' disabled /><br/>";
									echo "Habilidad 6: <input type='text' name='eCCVskill6' value=" . $editedCVRow['skill6'] . " size='20' disabled /><br/>";
									echo "Habilidad 7: <input type='text' name='eCCVskill7' value=" . $editedCVRow['skill7'] . " size='20' disabled /><br/>";
									echo "Habilidad 8: <input type='text' name='eCCVskill8' value=" . $editedCVRow['skill8'] . " size='20' disabled /><br/>";
									echo "Habilidad 9: <input type='text' name='eCCVskill9' value=" . $editedCVRow['skill9'] . " size='20' disabled /><br/>";
									echo "Habilidad 10: <input type='text' name='eCCVskill10' value=" . $editedCVRow['skill10'] . " size='20' disabled /><br/>";
									echo "Comentarios: <input type='textarea' name='eCCVcomments' value=" . $editedCVRow['comments'] . " rows='5' cols='40' /><br/>";
									echo "Estado del Candidato: <select name='eCCVcandidateStatus'>";
										echo "<option value=''>Sin estado</option>";
										echo "<option value='available'>Disponible</option>";
										echo "<option value='working'>Colocado</option>";
										echo "<option value='discarded'>Descartado</option>";
									echo "</select><br/>";
									echo "Fecha CV: <input type='date' name='eCCVcvDate' value=" . $editedCVRow['cvDate'] . " disabled /><br/>";
									
									
									
									
									//Al guardarlo tendré que cambiar su 'cvStatus' a 'checked'
								echo '</form>';
							echo '</fieldset>';//del 'id=auto0'
							
						}
					
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
