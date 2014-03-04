<?php session_start(); ?>
<html lang="es">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="">
	<meta name="author" content="David Alfonso Ginés Prieto, Miguel Hita Vicente y Miguel Ángel Melón Pérez">
	
	<title>Gestión de usuarios</title>

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
													if ($level3File == basename(__FILE__, '.php')) 
														echo "<li class='active'><a href=$level3File.php>" . $subLevelMenu . "</a></li>";
													else
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

					<div class="bs-docs-section">

						<h2 class="page-header">Gestión de usuarios</h2>

						</span>


						<?php 
						//if(isset($_POST['NewUsubmit'])){ SI FUERA NECESARIO
						if(isset($_POST['newUsubmit'])){
							if (isset($_POST['newUName']) && !empty($_POST['newUName'])){
								$newUser = $_POST['newUName'];
								if(strpos(trim($newUser), " ") > 0){
									$newUser = str_replace(' ', '', $newUser);
								}
								$newUser = dropAccents($newUser);
								if(getDBsinglefield('login', 'users', 'login', $newUser)){
									?>
									<script type="text/javascript">
										alert('El usuario que se intenta crear ya existe');
										window.location.href='admCurUsers.php';
									</script>
									<?php
								}
								else{
									//Genero una contraseña aleatoria
									$initialPass = getRandomPass();
									//GENERAR LA FECHA DE CADUCIDAD QUE ESTARA INDICADA POR UN VALOR EN LA TABLA "otherOptions"
									$expirationDate = addMonthsToDate(getDBsinglefield('value', 'otherOptions', 'key', 'expirationMonths'));
									if(!executeDBquery("INSERT INTO `users` (`id`, `login`, `pass`, `profile`, `active`, `language`, `needPass`, `created`, `passExpiration`) VALUES 
									(NULL, '".utf8_decode($newUser)."', '".$initialPass."', '".utf8_decode($_POST['newUProfile'])."', '1', '".utf8_decode($_POST['newULanguage'])."', '1', CURRENT_TIMESTAMP, '".$expirationDate."')")){
									
										?>
										<script type="text/javascript">
											alert('Error al insertar el nuevo usuario');
											window.location.href='admCurUsers.php';
										</script>
										<?php
									}
									else{
										//SUMAR +1 AL PERFIL DEL USUARIO
										$profileUsers = getDBsinglefield('numUsers', 'profiles', 'name', $_POST['newUProfile']);
										$profileUsers += 1;
										executeDBquery("UPDATE `profiles` SET `numUsers`='".$profileUsers."' WHERE `name`='".$_POST['newUProfile']."'");
										?>
										<script type="text/javascript">
											//alert('Usuario creado con éxito');
											alert('Usuario creado con éxito. Su contraseña por defecto es: <?php echo $initialPass; ?>');
											window.location.href='admCurUsers.php';
										</script>
										<?php
									}
								}
							}
						}
						
						
						
						
						if(isset($_POST['newUsubmitC'])){
								$user_number = getDBsinglefield('numUsers', 'profiles', 'name', 'Candidato');
								$user_number=$user_number+1;
								$user_number=sprintf("%06d",$user_number);
								$newUser="pa_".$user_number;
								$newUser = dropAccents($newUser);
								if(getDBsinglefield('login', 'users', 'login', $newUser)){
									?>
									<script type="text/javascript">
										alert('El usuario que se intenta crear ya existe');
										window.location.href='admCurUsers.php';
									</script>
									<?php
								}
								else{
									//Genero una contraseña aleatoria
									$initialPass = getRandomPass();
									//GENERAR LA FECHA DE CADUCIDAD QUE ESTARA INDICADA POR UN VALOR EN LA TABLA "otherOptions"
									$expirationDate = addMonthsToDate(getDBsinglefield('value', 'otherOptions', 'key', 'expirationMonths'));
									if(!executeDBquery("INSERT INTO `users` (`id`, `login`, `pass`, `profile`, `active`, `language`, `needPass`, `created`, `passExpiration`) VALUES 
									(NULL, '".utf8_decode($newUser)."', '".$initialPass."', 'Candidato', '1', 'spanish', '1', CURRENT_TIMESTAMP, '".$expirationDate."')")){
										?>
										<script type="text/javascript">
											alert('Error al insertar el nuevo usuario');
											window.location.href='admCurUsers.php';
										</script>
										<?php
									}
									else{
										//SUMAR +1 AL PERFIL DEL USUARIO
										$profileUsers = getDBsinglefield('numUsers', 'profiles', 'name', 'Candidato');
										$profileUsers += 1;
										executeDBquery("UPDATE `profiles` SET `numUsers`='".$profileUsers."' WHERE `name`='Candidato'");
										?>
										<script type="text/javascript">
											//alert('Usuario creado con éxito');
											alert('Usuario creado con éxito <?php echo $newUser;?>. Su contraseña por defecto es: <?php echo $initialPass; ?>');
											window.location.href='admCurUsers.php';
										</script>
										<?php
									}
								}
							
						}
						
						
						
						?>

					<?php 
						if($_SESSION['logprofile'] == 'SuperAdmin'){
					?>
							<div class="panel panel-default"> <!-- Panel de Usuarios Existentes -->
								<div class="panel-heading">
									<h3 class="panel-title">Usuarios Existentes</h3>
								</div>
								<div class="panel-body">
									<div class="table-responsive">
										<table id="usersTable" class="table table-striped table-hover">
											<thead>
												<tr>
													<th>Id</th>
													<th>Login</th>
													<th>Perfil</th>
													<th>Empleado</th>
													<th>Activo</th>
													<th>Idioma</th>
													<th>Creado</th>
													<th>Ultima conexión</th>
													<th>Acción</th>
												</tr>
											</thead>
											<tbody>
												<?php
													$numUsers = getDBrowsnumber('users');
													for($i=1; $i<=$numUsers; $i++){
														$showedUserRow = getDBrow('users', 'id', $i);
														echo "<tr>";
														echo "<td>" . $showedUserRow['id'] . "</td>";
														//echo "<td><a href='editUser.php?codvalue=" . $showedUserRow['id'] . "'>" . $showedUserRow['login'] . "</a></td>";
														echo "<td><a href='editSelectedUser.php?codvalue=" . $showedUserRow['id'] . "'>" . $showedUserRow['login'] . "</a></td>";
														echo "<td>" . $showedUserRow['profile'] . "</td>";
														if($showedUserRow['employee'] == 1){
															echo "<td>Sí</td>";
														}
														else{
															echo "<td>No</td>";
														}
														if($showedUserRow['active']){
															echo "<td>Sí</td>";
														}
														else{
															echo "<td>No</td>";
														}
														echo "<td>" . $showedUserRow['language'] . "</td>";
														echo "<td>" . $showedUserRow['created'] . "</td>";
														echo "<td>" . $showedUserRow['lastConnection'] . "</td>";
														echo "<td>" . $showedUserRow['passExpiration'] . "</td>";
														echo "<td><a href=''>Borrar</a></td>";
														echo "</tr>";
													}
												?>
											</tbody>
										</table>
									</div>

									<div class="container-fluid center-block">
										<h4>Nuevo Usuario</h4>

										<form class="form-inline" role="form" name="newUser" action="admCurUsers.php" method="post">
											<div class="form-group">
												<label class="sr-only" for="newUName">Usuario</label>
												<input type="text" class="form-control" size="6" name="newUName" placeholder="Usuario" />
											</div>
											<div class="form-group">
												<label class="sr-only" for="newUProfile">Perfil</label>
												<select name="newUProfile" class="form-control">
													<option selected disabled value=''>Perfil</option>
													<?php 
														$profNames = getDBcompletecolumnID('name', 'profiles', 'id');
														foreach($profNames as $i){
															if ($i != 'Candidato'){
															echo "<option value=" . $i . ">" . $i . "</option>";}
														}
													?>
												</select>
											</div>
											<div class="form-group">
												<label class="sr-only" for="newULanguage">Idioma</label>
												<select name="newULanguage" class="form-control">
													<option selected disabled value=''>Idioma</option>
													<?php 
														$siteLanguages = getDBcompletecolumnID('esName', 'siteLanguages', 'id');
														foreach($siteLanguages as $i){
															echo "<option value=" . $i . ">" . $i . "</option>";
														}
													?>
												</select>
											</div>

											<button type="submit" class="btn btn-primary" name="newUsubmit" value="Añadir">Añadir</button>
											<button type="submit" class="btn btn-primary pull-right" name="newUsubmitC" value="AñadirC">Crear Candidato</button>
										</form>
									
									</div>
									
								</div>
							</div> <!-- Panel de Usuarios existentes -->	

						<?php 
						}
						elseif($_SESSION['logprofile'] == 'Administrador'){
						?>


							<div class="panel panel-default"> <!-- Panel de Usuarios Existentes -->
								<div class="panel-heading">
									<h3 class="panel-title">Usuarios Existentes</h3>
								</div>
								<div class="panel-body">
									<div class="table-responsive">
										<table id="usersTable" class="table table-striped table-hover">
											<thead>
												<tr>
													<th>Id</th>
													<th>Login</th>
													<th>Perfil</th>
													<th>Activo</th>
													<th>Idioma</th>
													<th>Creado</th>
													<th>Ultima conexión</th>
													<th>Caduca Password</th>
												</tr>
											</thead>
											<tbody>
												<?php
													$numUsers = getDBrowsnumber('users');
													for($i=1; $i<=$numUsers; $i++){
														$showedUserRow = getDBrow('users', 'id', $i);
														echo "<tr>";
														echo "<td>" . $showedUserRow['id'] . "</td>";
														//echo "<td><a href='editUser.php?codvalue=" . $showedUserRow['id'] . "'>" . $showedUserRow['login'] . "</a></td>";
														echo "<td><a href='editSelectedUser.php?codvalue=" . $showedUserRow['id'] . "'>" . $showedUserRow['login'] . "</a></td>";
														echo "<td>" . $showedUserRow['profile'] . "</td>";
														if($showedUserRow['active']){
															echo "<td>Sí</td>";
														}
														else{
															echo "<td>No</td>";
														}
														echo "<td>" . $showedUserRow['language'] . "</td>";
														echo "<td>" . $showedUserRow['created'] . "</td>";
														echo "<td>" . $showedUserRow['lastConnection'] . "</td>";
														echo "<td>" . $showedUserRow['passExpiration'] . "</td>";
														echo "</tr>";
													}
												?>
											</tbody>
										</table>
									</div>

									<div class="container-fluid center-block">
										<h4>Nuevo Usuario</h4>

										<form class="form-inline" role="form" name="newUser" action="admCurUsers.php" method="post">
											<div class="form-group">
												<label class="sr-only" for="newUName">Usuario</label>
												<input type="text" class="form-control" size="6" name="newUName" placeholder="Usuario" />
											</div>
											<div class="form-group">
												<label class="sr-only" for="newUProfile">Perfil</label>
												<select name="newUProfile" class="form-control">
													<option selected disabled value=''>Perfil</option>
													<?php 
														$profNames = getDBcompletecolumnID('name', 'profiles', 'id');
														foreach($profNames as $i){
															if(($i != 'SuperAdmin') && ($i != 'Candidato')){
																echo "<option value=" . $i . ">" . $i . "</option>";}
														}
													?>
												</select>
											</div>
											<div class="form-group">
												<label class="sr-only" for="newULanguage">Idioma</label>
												<select name="newULanguage" class="form-control">
													<option selected disabled value=''>Idioma</option>
													<?php 
														$siteLanguages = getDBcompletecolumnID('esName', 'siteLanguages', 'id');
														foreach($siteLanguages as $i){
															echo "<option value=" . $i . ">" . $i . "</option>";
														}
													?>
												</select>
											</div>

											<button type="submit" class="btn btn-primary" name="newUsubmit" value="Añadir">Añadir</button>
											<button type="submit" class="btn btn-primary pull-right" name="newUsubmitC" value="AñadirC">Crear Candidato</button>
										</form>
										
									</div>
								</div>
							</div> <!-- Panel de Usuarios existentes -->	
						
						<?php 
						}
						else{
							echo "No dispone de permisos para visualizar esta página";
							echo "<button onclick='location.href=\"../home.php\"'>Inicio</button>";  
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
