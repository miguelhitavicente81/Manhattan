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
							<li><a href="../home/personalData.php">Configuración personal</a></li>
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


		<?php
			$myFile = 'home';
			$userRow = getDBrow('users', 'login', $_SESSION['loglogin']);

			$pendingCVs = getPendingCVs();
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
														echo "<li class='active'><a href=$level2File.php>" . $subLevelMenu . "</a></li>";
													else
														echo "<li><a href=$level2File.php>" . $subLevelMenu . "</a></li>";
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
													echo "<li><a href=home/$level3File.php>" . $subLevelMenu . "</a></li>";
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

					<?php 
						//Aunque debería ser
						//if(!isset($_GET['nie'])){
						if(!isset($_GET['codvalue'])){
							$editedCVRow = getDBrow('cvitaes', 'nie', $_POST['eCCVnie']);
							
							
							
							
							
							
							
						}
						/***************  Fin del bloque que valida el contenido enviado en el formulario  ***************/
						
						/***************  Aquí comienza el bloque que permite mostrar el formulario  ***************/
						else{
							//$editedCVRow = getDBrow('cvitaes', 'nie', $_GET['nie']);
							$editedCVRow = getDBrow('cvitaes', 'nie', $_GET['codvalue']);
							echo '<fieldset id="auto0">';
								echo '<form id="editedCV" class="form-horizontal" role="form" name="editedCV" method="post" action=editCurCV.php">';
									echo "<div class='form-group'>";
										echo "<label class='control-label col-xs-12 col-sm-2'>Nombre</label>";
										echo "<div class='col-xs-12 col-sm-6'>";
											echo "<input class='form-control' type='text' name='eCCVname' value='" . $editedCVRow['name'] . "' />";
										echo "</div>";
									echo "</div>";
									echo "<div class='form-group'>";
										echo "<label class='control-label col-xs-12 col-sm-2'>Apellidos</label>";
										echo "<div class='col-xs-12 col-sm-6'>";
											echo "<input class='form-control' type='text' name='eCCVsurname' value='" . $editedCVRow['surname'] . "' />";
										echo "</div>";
									echo "</div>";
									echo "<div class='form-group'>";										
										echo "<label class='control-label col-xs-12 col-sm-2'>NIE</label>";
										echo "<div class='col-xs-12 col-sm-6'>";
											echo "<input class='form-control' type='text' name='eCCVnie' value='" . $editedCVRow['nie'] . "' />";
										echo "</div>";
									echo "</div>";
									echo "<div class='form-group'>";										
										echo "<label class='control-label col-xs-12 col-sm-2'>Fecha de nacimiento</label>";
										echo "<div class='col-xs-12 col-sm-6'>";
											echo "<input class='form-control' type='date' name='eCCVbirthdate' value='" . $editedCVRow['birthdate'] . "' />";
										echo "</div>";
									echo "</div>";
									echo "<div class='form-group'>";										
										echo "<label class='control-label col-xs-12 col-sm-2'>Nacionalidad</label>";
										echo "<div class='col-xs-12 col-sm-6'>";
											echo "<input class='form-control' type='text' name='eCCVnationalities' value='" . $editedCVRow['nationalities'] . "' />";
										echo "</div>";
									echo "</div>";
									echo "<div class='form-group'>";										
										echo "<label class='control-label col-xs-12 col-sm-2'>Sexo</label>";
										echo "<div class='col-xs-12 col-sm-6'>";
											if($editedCVRow['sex'] == 0){
												echo "<input class='form-control' type='text' name='eCCVsex' disabled value='Hombre'>";
											}
											else{
												echo "<input class='form-control' type='text' name='eCCVsex' disabled value='Mujer'>";
											}
										echo "</div>";
									echo "</div>";
									echo "<div class='form-group'>";										
										echo "<label class='control-label col-xs-12 col-sm-2'>Tipo de dirección</label>";
										echo "<div class='col-xs-12 col-sm-6'>";
											echo "<input class='form-control' type='text' name='eCCVaddrtype' disabled value='" . $editedCVRow['addrType'] . "'>";
										echo "</div>";
									echo "</div>";
									echo "<div class='form-group'>";										
										echo "<label class='control-label col-xs-12 col-sm-2'>Nombre dirección</label>";
										echo "<div class='col-xs-12 col-sm-6'>";
											echo "<input class='form-control' type='text' name='eCCVaddrName' disabled value='" . $editedCVRow['addrName'] . "'>";
										echo "</div>";
									echo "</div>";
									echo "<div class='form-group'>";										
										echo "<label class='control-label col-xs-12 col-sm-2'>Número</label>";
										echo "<div class='col-xs-12 col-sm-6'>";											
											echo "<input class='form-control' type='text' name='eCCVaddrNum' disabled value='" . $editedCVRow['addrNum'] . "'>";
										echo "</div>";
									echo "</div>";
									echo "<div class='form-group'>";										
										echo "<label class='control-label col-xs-12 col-sm-2'>Portal</label>";
										echo "<div class='col-xs-12 col-sm-6'>";
											echo "<input class='form-control' type='text' name='eCCVaddrPortal' disabled value='" . $editedCVRow['portal'] . "'>";
										echo "</div>";
									echo "</div>";
									echo "<div class='form-group'>";										
										echo "<label class='control-label col-xs-12 col-sm-2'>Escalera</label>";
										echo "<div class='col-xs-12 col-sm-6'>";
											echo "<input class='form-control' type='text' name='eCCVaddrStair' disabled value='" . $editedCVRow['stair'] . "'>";
										echo "</div>";
									echo "</div>";
									echo "<div class='form-group'>";										
										echo "<label class='control-label col-xs-12 col-sm-2'>Piso</label>";
										echo "<div class='col-xs-12 col-sm-6'>";
											echo "<input class='form-control' type='text' name='eCCVaddrFloor' disabled value='" . $editedCVRow['addrFloor'] . "'>";
										echo "</div>";
									echo "</div>";
									echo "<div class='form-group'>";										
										echo "<label class='control-label col-xs-12 col-sm-2'>Puerta</label>";
										echo "<div class='col-xs-12 col-sm-6'>";
											echo "<input class='form-control' type='text' name='eCCVaddrDoor' disabled value='" . $editedCVRow['addrDoor'] . "'>";
										echo "</div>";
									echo "</div>";
									echo "<div class='form-group'>";										
										echo "<label class='control-label col-xs-12 col-sm-2'>Código Postal</label>";
										echo "<div class='col-xs-12 col-sm-6'>";
											echo "<input class='form-control' type='text' name='eCCVpostal' disabled value='" . $editedCVRow['postalCode'] . "'>";
										echo "</div>";
									echo "</div>";
									echo "<div class='form-group'>";										
										echo "<label class='control-label col-xs-12 col-sm-2'>Localidad</label>";
										echo "<div class='col-xs-12 col-sm-6'>";
											echo "<input class='form-control' type='text' name='eCCVcity' disabled value='" . $editedCVRow['city'] . "'>";
										echo "</div>";
									echo "</div>";
									echo "<div class='form-group'>";										
										echo "<label class='control-label col-xs-12 col-sm-2'>Provincia</label>";
										echo "<div class='col-xs-12 col-sm-6'>";
											echo "<input class='form-control' type='text' name='eCCVprovince' disabled value='" . $editedCVRow['province'] . "'>";
										echo "</div>";
									echo "</div>";
									echo "<div class='form-group'>";										
										echo "<label class='control-label col-xs-12 col-sm-2'>País</label>";
										echo "<div class='col-xs-12 col-sm-6'>";
											echo "<input class='form-control' type='text' name='eCCVcountry' disabled value='" . $editedCVRow['country'] . "'>";
										echo "</div>";
									echo "</div>";
									echo "<div class='form-group'>";										
										echo "<label class='control-label col-xs-12 col-sm-2'>Teléfono Fijo</label>";
										echo "<div class='col-xs-12 col-sm-6'>";
											echo "<input class='form-control' type='text' name='eCCVphone' disabled value='" . $editedCVRow['phone'] . "'>";
										echo "</div>";
									echo "</div>";
									echo "<div class='form-group'>";										
										echo "<label class='control-label col-xs-12 col-sm-2'>Teléfono Móvil</label>";
										echo "<div class='col-xs-12 col-sm-6'>";
											echo "<input class='form-control' type='text' name='eCCVmobile' disabled value='" . $editedCVRow['mobile'] . "'>";
										echo "</div>";
									echo "</div>";
									echo "<div class='form-group'>";										
										echo "<label class='control-label col-xs-12 col-sm-2'>Correo Electrónico</label>";
										echo "<div class='col-xs-12 col-sm-6'>";
											echo "<input class='form-control' type='mail' name='eCCVmail' disabled value='" . $editedCVRow['mail'] . "'>";
										echo "</div>";
									echo "</div>";
									echo "<div class='form-group'>";										
										echo "<label class='control-label col-xs-12 col-sm-2'>Carnet de Conducir</label>";
										echo "<div class='col-xs-12 col-sm-6'>";									
											echo "<input class='form-control' type='text' name='eCCVdrivingType' disabled value='" . $editedCVRow['drivingType'] . "'>";
											echo "<input class='form-control' type='date' name='eCCVdrivingDate' disabled value='" . $editedCVRow['drivingDate'] . "'>";
										echo "</div>";
									echo "</div>";
									echo "<div class='form-group'>";										
										echo "<label class='control-label col-xs-12 col-sm-2'>Estado Civil</label>";
										echo "<div class='col-xs-12 col-sm-6'>";											
										echo "<input class='form-control' type='text' name='eCCVmarital' disabled value='" . getDBsinglefield(getDBsinglefield('language', 'users', 'login', $_SESSION['loglogin']), 'maritalStatus', 'key', $editedCVRow['marital']) . "'>";
										echo "</div>";
									echo "</div>";
									echo "<div class='form-group'>";										
										echo "<label class='control-label col-xs-12 col-sm-2'>Hijos</label>";
										echo "<div class='col-xs-12 col-sm-6'>";											
											echo "<input class='form-control' type='text' name='eCCVsons' disabled value='" . $editedCVRow['sons'] . "'>";
										echo "</div>";
									echo "</div>";
									echo "<div class='form-group'>";										
										echo "<label class='control-label col-xs-12 col-sm-2'>Documentos Adicionales</label>";
										echo "<div class='col-xs-12 col-sm-6'>";
											echo "<input class='form-control' type='text' name='eCCV___' disabled value='" . $editedCVRow['sons'] . "'>";
										echo "</div>";
									echo "</div>";
									echo "<div class='form-group'>";										
										echo "<label class='control-label col-xs-12 col-sm-2'>Idiomas</label>";
										echo "<div class='col-xs-12 col-sm-6'>";
											echo "<input class='form-control' type='text' name='eCCV___' disabled value='" . $editedCVRow['sons'] . "'>";
										echo "</div>";
									echo "</div>";
									echo "<div class='form-group'>";										
										echo "<label class='control-label col-xs-12 col-sm-2'>Profesiones</label>";
										echo "<div class='col-xs-12 col-sm-6'>";
											echo "<input class='form-control' type='text' name='eCCV___' disabled value='" . $editedCVRow['sons'] . "'>";
										echo "</div>";
									echo "</div>";
									echo "<div class='form-group'>";										
										echo "<label class='control-label col-xs-12 col-sm-2'>Formación</label>";
										echo "<div class='col-xs-12 col-sm-6'>";
											echo "<input class='form-control' type='text' name='eCCV___' disabled value='" . $editedCVRow['sons'] . "'>";
										echo "</div>";
									echo "</div>";
									echo "<div class='form-group'>";										
										echo "<label class='control-label col-xs-12 col-sm-2'>Experiencia Laboral</label>";
										echo "<div class='col-xs-12 col-sm-6'>";											
											echo "<input class='form-control' type='text' name='eCCV___' disabled value='" . $editedCVRow['sons'] . "'>";
										echo "</div>";
									echo "</div>";
									echo "<div class='form-group'>";										
										echo "<label class='control-label col-xs-12 col-sm-2'>Otros detalles</label>";
										echo "<div class='col-xs-12 col-sm-6'>";											
											echo "<input class='form-control' type='textarea' name='eCCVotherDetails' rows='5' cols='40' disabled value='" . $editedCVRow['otherDetails'] . "'>";
										echo "</div>";
									echo "</div>";
									echo "<div class='form-group'>";										
										echo "<label class='control-label col-xs-12 col-sm-2'>Habilidad 1</label>";
										echo "<div class='col-xs-12 col-sm-6'>";											
											echo "<input class='form-control' type='text' name='eCCVskill1' disabled value='" . $editedCVRow['skill1'] . "'>";
										echo "</div>";											
									echo "</div>";
									echo "<div class='form-group'>";										
										echo "<label class='control-label col-xs-12 col-sm-2'>Habilidad 2</label>";
										echo "<div class='col-xs-12 col-sm-6'>";											
											echo "<input class='form-control' type='text' name='eCCVskill2' disabled value='" . $editedCVRow['skill2'] . "'>";
										echo "</div>";											
									echo "</div>";
									echo "<div class='form-group'>";										
										echo "<label class='control-label col-xs-12 col-sm-2'>Habilidad 3</label>";
										echo "<div class='col-xs-12 col-sm-6'>";											
											echo "<input class='form-control' type='text' name='eCCVskill3' disabled value='" . $editedCVRow['skill3'] . "'>";
										echo "</div>";											
									echo "</div>";
									echo "<div class='form-group'>";										
										echo "<label class='control-label col-xs-12 col-sm-2'>Habilidad 4</label>";
										echo "<div class='col-xs-12 col-sm-6'>";											
											echo "<input class='form-control' type='text' name='eCCVskill4' disabled value='" . $editedCVRow['skill4'] . "'>";
										echo "</div>";											
									echo "</div>";
									echo "<div class='form-group'>";										
										echo "<label class='control-label col-xs-12 col-sm-2'>Habilidad 5</label>";
										echo "<div class='col-xs-12 col-sm-6'>";											
											echo "<input class='form-control' type='text' name='eCCVskill5' disabled value='" . $editedCVRow['skill5'] . "'>";
										echo "</div>";											
									echo "</div>";
									echo "<div class='form-group'>";										
										echo "<label class='control-label col-xs-12 col-sm-2'>Habilidad 6</label>";
										echo "<div class='col-xs-12 col-sm-6'>";											
											echo "<input class='form-control' type='text' name='eCCVskill6' disabled value='" . $editedCVRow['skill6'] . "'>";
										echo "</div>";											
									echo "</div>";
									echo "<div class='form-group'>";										
										echo "<label class='control-label col-xs-12 col-sm-2'>Habilidad 7</label>";
										echo "<div class='col-xs-12 col-sm-6'>";											
											echo "<input class='form-control' type='text' name='eCCVskill7' disabled value='" . $editedCVRow['skill7'] . "'>";
										echo "</div>";											
									echo "</div>";
									echo "<div class='form-group'>";										
										echo "<label class='control-label col-xs-12 col-sm-2'>Habilidad 8</label>";
										echo "<div class='col-xs-12 col-sm-6'>";											
											echo "<input class='form-control' type='text' name='eCCVskill8' disabled value='" . $editedCVRow['skill8'] . "'>";
										echo "</div>";											
									echo "</div>";
									echo "<div class='form-group'>";										
										echo "<label class='control-label col-xs-12 col-sm-2'>Habilidad 9</label>";
										echo "<div class='col-xs-12 col-sm-6'>";											
											echo "<input class='form-control' type='text' name='eCCVskill9' disabled value='" . $editedCVRow['skill9'] . "'>";
										echo "</div>";											
									echo "</div>";
									echo "<div class='form-group'>";										
										echo "<label class='control-label col-xs-12 col-sm-2'>Habilidad 10</label>";
										echo "<div class='col-xs-12 col-sm-6'>";											
											echo "<input class='form-control' type='text' name='eCCVskill10' disabled value='" . $editedCVRow['skill10'] . "'>";
										echo "</div>";											
									echo "</div>";



									echo "<div class='form-group'>";										
										echo "<label class='control-label col-xs-12 col-sm-2'>Comentarios</label>";
										echo "<div class='col-xs-12 col-sm-6'>";	
											echo "<textarea class='form-control' name='eCCVcomments' value='" . $editedCVRow['comments'] . "' rows='5' cols='40'>" . $editedCVRow['comments'] . "</textarea>";
										echo "</div>";											
									echo "</div>";

									echo "<div class='form-group'>";										
										echo "<label class='control-label col-xs-12 col-sm-2'>Estado del Candidato</label>";
										echo "<div class='col-xs-12 col-sm-6'>";	
											echo "<select class='form-control' name='eCCVcandidateStatus'>";
												echo "<option value=''>Sin estado</option>";
												echo "<option value='available'>Disponible</option>";
												echo "<option value='working'>Colocado</option>";
												echo "<option value='discarded'>Descartado</option>";
											echo "</select>";
										echo "</div>";											
									echo "</div>";

									echo "<div class='form-group'>";										
										echo "<label class='control-label col-xs-12 col-sm-2'>Fecha CV</label>";
										echo "<div class='col-xs-12 col-sm-6'>";									
											echo "<input class='form-control' type='date' name='eCCVcvDate' value=" . $editedCVRow['cvDate'] . " disabled />";
										echo "</div>";											
									echo "</div>";						
									
									
									
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
