<?php session_start(); ?>
<html lang="es">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="">
	<meta name="author" content="David Alfonso Ginés Prieto, Miguel Hita Vicente y Miguel Ángel Melón Pérez">
	
	<title>Configuración general</title>

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
							<li><a href="../administration.php">Configuración</a></li>
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
			$myFile = 'administration';
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
													if ($level2File == 'pendingCVs') 
														$badge = "<span class='badge'>$pendingCVs</span>";
													else
														$badge = "";
													if ($level2File == basename(__FILE__, '.php')) 
														echo "<li class='active'>$badge<a href=$level2File.php>" . $subLevelMenu . "</a></li>";
													else
														echo "<li>$badge<a href=$level2File.php>" . $subLevelMenu . "</a></li>";
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
													echo "<li><a href=$level3File.php>" . $subLevelMenu . "</a></li>";
												}
											}
										}

										echo "</ul> <!-- class='nav' -->";
										echo "</li> <!-- class='active' -->";

									}

									else{ 
										if ($i == 'home')
											echo "<li><span class='badge'>$pendingCVs</span><a href=../$i.php>" . $mainNamesRow[$j] . " </a></li>";
										else 
											echo "<li><a href=../$i.php>" . $mainNamesRow[$j] . " </a></li>";

										$j++;
									}
								}
							}
							?>
						</ul> <!-- class="nav bs-sidenav" -->
					</div> <!-- id="sidebar-navigation-list"  -->
				</div> <!-- col-md-3 -->


				<div class="col-md-9 scrollable" role="main"> 

					<?php
					if(isset($_POST['hiddenfield'])){
						switch ($_POST['hiddenfield']){
							case 'hNewLLsubmit':
								if((empty($_POST['newLLkey'])) || strpos(trim($_POST['newLLkey']), " ") > 0 || (empty($_POST['newLLenName'])) || (empty($_POST['newLLesName'])) || (empty($_POST['newLLdeName']))){
									?>
									<script type="text/javascript">
										alert('Todos los campos deben estar rellenos, y la Clave no puede contener espacios.');
										window.location.href='admGenOptions.php';
									</script>
									<?php 
								}
								else{
									$strippedString = dropAccents($_POST['newLLkey']);
									if(!executeDBquery("INSERT INTO `languageLevel` (`id`, `key`, `enName`, `esName`, `deName`) VALUES
									(NULL, '".$strippedString."', '".utf8_decode($_POST['newLLenName'])."', '".utf8_decode($_POST['newLLesName'])."', '".utf8_decode($_POST['newLLdeName'])."')")){
										?>
										<script type="text/javascript">
											alert('Error al incluir el nuevo Nivel de idioma.');
											window.location.href='admGenOptions.php';
										</script>
										<?php 
									}
								}
							break;

							case 'hNewLangsubmit':
								if((empty($_POST['newLangkey'])) || strpos(trim($_POST['newLangkey']), " ") > 0 || (empty($_POST['newLangenName'])) || (empty($_POST['newLangesName'])) || (empty($_POST['newLangdeName']))){
									?>
									<script type="text/javascript">
										alert('Todos los campos deben estar rellenos, y la Clave no puede contener espacios.');
										window.location.href='admGenOptions.php';
									</script>
									<?php 
								}
								else{
									$strippedString = dropAccents($_POST['newLangkey']);
									if(!executeDBquery("INSERT INTO `languages` (`id`, `key`, `enName`, `esName`, `deName`) VALUES
									(NULL, '".$strippedString."', '".utf8_decode($_POST['newLangenName'])."', '".utf8_decode($_POST['newLangesName'])."', '".utf8_decode($_POST['newLangdeName'])."')")){
										?>
										<script type="text/javascript">
											alert('Error al incluir el nuevo Idioma.');
											window.location.href='admGenOptions.php';
										</script>
										<?php 
									}
								}
							break;

							case 'hNewStudyTypessubmit':
								if((empty($_POST['newStudyTypeskey'])) || strpos(trim($_POST['newStudyTypeskey']), " ") > 0 || (empty($_POST['newStudyTypesenName'])) || (empty($_POST['newStudyTypesesName'])) || (empty($_POST['newStudyTypesdeName']))){
									?>
									<script type="text/javascript">
										alert('Todos los campos deben estar rellenos, y la Clave no puede contener espacios.');
										window.location.href='admGenOptions.php';
									</script>
									<?php 
								}
								else{
									$strippedString = dropAccents($_POST['newStudyTypeskey']);
									if(!executeDBquery("INSERT INTO `languages` (`id`, `key`, `enName`, `esName`, `deName`) VALUES
									(NULL, '".$strippedString."', '".utf8_decode($_POST['newStudyTypesenName'])."', '".utf8_decode($_POST['newStudyTypesesName'])."', '".utf8_decode($_POST['newStudyTypesdeName'])."')")){
										?>
										<script type="text/javascript">
											alert('Error al incluir el nuevo Tipo de estudios.');
											window.location.href='admGenOptions.php';
										</script>
										<?php 
									}
								}
							break;
						}
					}
					?>				

					<div class="bs-docs-section">

						<h2 class="page-header">Conjunto de configuraciones generales</h2>

						</span>

						<div class="panel panel-default"> <!-- Panel de Idiomas -->
							<div class="panel-heading">
								<h3 class="panel-title">Idiomas</h3>
							</div>
							<div class="panel-body">
								<div class="table-responsive">
									<table class="table table-striped table-hover">
										<thead>
											<tr>
												<th>Id</th>
												<th>Clave</th>
												<th>Nombre (Ing)</th>
												<th>Nombre (Esp)</th>
												<th>Nombre (Ale)</th>
												<th>Acción</th>
											</tr>
										</thead>
										<tbody>
											<?php 
											$langNumRows = getDBrowsnumber('languages');
											for($i=1;$i<=$langNumRows;$i++){
												$langRow = getDBrow('languages', 'id', $i);
												echo "<tr>";
												echo "<td>" . $langRow['id'] . "</td>";
												echo "<td>" . $langRow['key'] . "</td>";
												echo "<td>" . $langRow['enName'] . "</td>";
												echo "<td>" . $langRow['esName'] . "</td>";
												echo "<td>" . $langRow['deName'] . "</td>";
												echo "<td><a href=''>Borrar</a></td>";
											}
											?>
										</tbody>
									</table>
								</div>

								<div class="container-fluid center-block">
									<h4>Nuevo Idioma</h4>
									<form class="form-inline" role="form" name="newLanguage" action="admGenOptions.php" method="post">
										<div class="form-group">
											<label class="sr-only" for="newLLkey">Clave</label>
											<input type="text" class="form-control" size="6" name="newLangkey" placeholder="Clave" />
										</div>
										<div class="form-group">
											<label class="sr-only" for="newLLenName">Nombre Inglés</label>
											<input type="text" class="form-control" name="newLangenName" placeholder="Nombre Inglés" />
										</div>							
										<div class="form-group">
											<label class="sr-only" for="newLLesName">Nombre Español</label>
											<input type="text" class="form-control" name="newLangesName" placeholder="Nombre Español" />
										</div>
										<div class="form-group">
											<label class="sr-only" for="newLLdeName">Nombre Alemán</label>
											<input type="text" class="form-control" name="newLangdeName" placeholder="Nombre Alemán" />
										</div>	
										<input type="hidden" value="hNewLangsubmit" name="hiddenfield">
										<button type="submit" class="btn btn-primary" name="newLangsubmit" value="Incluir">Incluir</button>
									</form>
								</div>

							</div>
						</div> <!-- Panel de Idiomas -->	

						<div class="panel panel-default"> <!-- Panel de Nivel de Idiomas -->
							<div class="panel-heading">
								<h3 class="panel-title">Nivel de Idiomas</h3>
							</div>
							<div class="panel-body">						
								<div class="table-responsive">
									<table class="table table-striped table-hover">
										<thead>
											<tr>
												<th>Id</th>
												<th>Clave</th>
												<th>Nombre (Ing)</th>
												<th>Nombre (Esp)</th>
												<th>Nombre (Ale)</th>
												<th>Acción</th>
											</tr>
										</thead>
										<tbody>
											<?php
											$langLevelNumRows = getDBrowsnumber('languageLevel');
											for($i=1;$i<=$langLevelNumRows;$i++){
												$langLevelRow = getDBrow('languageLevel', 'id', $i);
												echo "<tr>";
												echo "<td>" . $langLevelRow['id'] . "</td>";
												echo "<td>" . $langLevelRow['key'] . "</td>";
												echo "<td>" . $langLevelRow['enName'] . "</td>";
												echo "<td>" . $langLevelRow['esName'] . "</td>";
												echo "<td>" . $langLevelRow['deName'] . "</td>";
												echo "<td>Borrar</td>";
												echo "</tr>";
											}
											?>
										</tbody>
									</table>
								</div>

								<div class="container-fluid center-block">
									<h4>Nuevo nivel de idiomas</h4>
									<form class="form-inline" role="form" name="newLangLevel" action="admGenOptions.php" method="post">
										<div class="form-group">
											<label class="sr-only" for="newLLkey">Clave</label>
											<input type="text" class="form-control" size="6" name="newLLkey" placeholder="Clave" />
										</div>
										<div class="form-group">
											<label class="sr-only" for="newLLenName">Nombre Inglés</label>
											<input type="text" class="form-control" name="newLLenName" placeholder="Nombre Inglés" />
										</div>							
										<div class="form-group">
											<label class="sr-only" for="newLLesName">Nombre Español</label>
											<input type="text" class="form-control" name="newLLesName" placeholder="Nombre Español" />
										</div>
										<div class="form-group">
											<label class="sr-only" for="newLLdeName">Nombre Alemán</label>
											<input type="text" class="form-control" name="newLLdeName" placeholder="Nombre Alemán" />
										</div>	
										<input type="hidden" value="hNewLLsubmit" name="hiddenfield">
										<button type="submit" class="btn btn-primary" name="newLLsubmit" value="Incluir">Incluir</button>
									</form>
								</div>
							</div>
						</div> <!-- Panel de Nivel de Idiomas -->		

						
						<div class="panel panel-default"> <!-- Panel de Estudios -->		
							<div class="panel-heading">
								<h3 class="panel-title">Estudios</h3>
							</div>
							<div class="panel-body">
								<div class="table-responsive">
									<table class="table table-striped table-hover">
										<thead>
											<tr>
												<th>Id</th>
												<th>Clave</th>
												<th>Nombre (Ing)</th>
												<th>Nombre (Esp)</th>
												<th>Nombre (Ale)</th>
												<th>Acción</th>
											</tr>
										</thead>
										<tbody>
											<?php
											$langLevelNumRows = getDBrowsnumber('studyTypes');
											for($i=1;$i<=$langLevelNumRows;$i++){
												$studyTypesRow = getDBrow('studyTypes', 'id', $i);
												echo "<tr>";
												echo "<td>" . $studyTypesRow['id'] . "</td>";
												echo "<td>" . $studyTypesRow['key'] . "</td>";
												echo "<td>" . $studyTypesRow['enName'] . "</td>";
												echo "<td>" . $studyTypesRow['esName'] . "</td>";
												echo "<td>" . $studyTypesRow['deName'] . "</td>";
												echo "<td>Borrar</td>";
												echo "</tr>";
											}
											?>
										</tbody>
									</table>
								</div>

								<div class="container-fluid center-block">
									<h4>Nuevo tipo de estudios</h4>
									<form class="form-inline" role="form" name="newStudyTypes" action="admGenOptions.php" method="post">
										<div class="form-group">
											<label class="sr-only" for="newStudyTypeskey">Clave</label>
											<input type="text" class="form-control" name="newStudyTypeskey" size="6" placeholder="Clave" />
										</div>
										<div class="form-group">
											<label class="sr-only" for="newStudyTypesenName">Nombre Inglés</label>
											<input type="text" class="form-control" name="newStudyTypesenName" placeholder="Nombre Inglés" />
										</div>							
										<div class="form-group">
											<label class="sr-only" for="newStudyTypesesName">Nombre Español</label>
											<input type="text" class="form-control" name="newStudyTypesesName" placeholder="Nombre Español" />
										</div>
										<div class="form-group">
											<label class="sr-only" for="newStudyTypesdeName">Nombre Alemán</label>
											<input type="text" class="form-control" name="newStudyTypesdeName" placeholder="Nombre Alemán" />
										</div>	
										<input type="hidden" value="hNewStudyTypessubmit" name="hiddenfield">
										<button type="submit" class="btn btn-primary" name="newStudyTypessubmit" value="Incluir">Incluir</button>
									</form>
								</div>

							</div>
						</div> <!-- Panel de Estudios -->		

						<?php 
						//Ñapa para que no vean la tabla siguiente...
						if($_SESSION['loglogin'] == 'super'){
						?>
							<div class="panel panel-default"> <!-- Panel Otras Opciones -->
								<div class="panel-heading">
									<h3 class="panel-title">Otras Opciones</h3>
								</div>
								<div class="panel-body">
									<div class="table-responsive">
										<table class="table table-striped table-hover">
											<thead>
												<tr>
													<th>Id</th>
													<th>Nombre</th>
													<th>Comentario</th>
													<th>Valor</th>
												</tr>
											</thead>

											<tbody>
												<?php 
												$oOptionsNumRows = getDBrowsnumber('otherOptions');
												for ($i=1; $i<=$oOptionsNumRows; $i++){
													$oOptionsRow = getDBrow('otherOptions', 'id', $i);
													echo "<tr>";
													//echo "<td><a href='EditCurUser.php?codvalue=" . $userrow[0] . "'>" . $userrow[1] . "</a></td>";
													echo "<td>" . $i . "</td>";
													echo "<td>" . $oOptionsRow['name'] . "</td>";
													echo "<td>" . $oOptionsRow['comment'] . "</td>";
													echo "<td>" . $oOptionsRow['value'] . "</td>";
													echo "</tr>";
												}
												?>
											</tbody>
										</table>
									</div>
								</div>
							</div> <!-- Panel Otras Opciones -->

						<?php 
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

			
