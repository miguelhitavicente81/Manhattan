<?php 	
		session_start(); 
		error_reporting (E_ALL & ~E_NOTICE & ~E_STRICT & ~E_DEPRECATED & ~E_WARNING);
?>
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

				</div> <!-- col-md-3 -->


				<div class="col-md-9 scrollable" role="main"> 			

					<div class="bs-docs-section">

						<?php 
							$editedUserRow = getDBrow('users', 'id', $_GET['codvalue']);
						?>

						<!-- Modal HTML -->
						<div id="editUserModal" class="modal fade">
							<div class="modal-dialog">
								<div class="modal-content">
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
										<h4 class="modal-title">Usuario: <?php echo $editedUserRow['login'] ?></h4>
									</div>
									<form id="editedUser" class="form-horizontal" role="form" name="editedUser" autocomplete="off" method="post" action="editSelectedUser.php">
										<div class="modal-body">
											<div class="form-group">
												<label id="editedUserLabel" class="control-label col-sm-2" for="newUProfile">Identificador: </label> 
												<div class="col-sm-10">
													<input class="form-control" type='text' name='newUProfile' value="<?php echo $editedUserRow['id'] ?>" autocomplete="off" disabled />
													<input type='hidden' name='eUcodUser' value="<?php echo $editedUserRow['id'] ?>">
												</div>
											</div>
											<div class="form-group">
												<label id="editedUserLabel" class="control-label col-sm-2" for="eUlogin">Login: </label>
												<div class="col-sm-10">
													<input class="form-control" type='text' name='eUlogin' value="<?php echo $editedUserRow['login'] ?>" autocomplete="off"/>
												</div>
											</div>

											<div class="form-group">
												<label id="editedUserLabel" class="control-label col-sm-2" for="eUpasswd">Contraseña: </label>
												<div class="col-sm-10">
													<input class="form-control" type='password' name='eUpasswd' value="<?php echo $editedUserRow['pass'] ?>"  disabled />
												</div>
											</div>

											<?php 
												if($_SESSION['logprofile'] == 'SuperAdmin'){
													echo "<div class='form-group'>";
													echo "<label id='editedUserLabel' class='control-label col-sm-2' for='eUprofile'>Perfil: </label>";
													echo "<div class='col-sm-10'>";
													echo "<select class='form-control' name='eUprofile'>";
													$profNamesColumn = getDBcompletecolumnID('name', 'profiles', 'id');
													foreach($profNamesColumn as $i){
														if($i == $editedUserRow['profile']){
															echo "<option selected value=" . $i . ">" . $i . "</option>";
														}
														else{
															echo "<option value=" . $i . ">" . $i . "</option>";
														}
													}
													echo "</select>";
													echo "</div>";
													echo "</div>";
												}
												elseif($_SESSION['logprofile'] == 'Administrador'){
													echo "<div class='form-group'>";
													echo "<label id='editedUserLabel' class='control-label col-sm-2' for='eUprofile'>Perfil: </label>";
													echo "<div class='col-sm-10'>";
													echo "<select class='form-control' name='eUprofile'>";
													$profNamesColumn = getDBcompletecolumnID('name', 'profiles', 'id');
													foreach($profNamesColumn as $i){
														if($i != 'SuperAdmin'){
															if($i == $editedUserRow['profile']){
																echo "<option selected value=" . $i . ">" . $i . "</option>";
															}
															else{
																echo "<option value=" . $i . ">" . $i . "</option>";
															}
														}
													}
													echo "</select>";
													echo "</div>";
													echo "</div>";
												}
												else{
													echo "<div class='form-group'>";
													echo "<label id='editedUserLabel' class='control-label col-sm-2' for='eUprofile'>Perfil: </label>";	
													echo "<div class='col-sm-10'>";
													echo "<input class='form-control' type='text' name='eUprofile' value='" . $editedUserRow['profile'] . "' disabled />";
													echo "</div>";
													echo "</div>";
												}

												//If user has profile "Candidato" will show his/her NIE
												if($editedUserRow['profile'] == "Candidato"){
													echo "<div class='form-group'>";
													echo "<label id='editedUserLabel' class='control-label col-sm-2' for='eUuser'>NIE: </label>";
													echo "<div class='col-sm-10'>";
													echo "<input class='form-control' type='text' name='eUuser' value='" . getDBsinglefield('nie', 'cVitaes', 'userLogin', $editedUserRow['login']) . "' disabled /><br/>";
													echo "</div>";
													echo "</div>";
												}

												if($_SESSION['logprofile'] == 'SuperAdmin'){
													echo "<div class='form-group'>";
													echo "<label id='editedUserLabel' class='control-label col-sm-2' for='eUemployee'>Empleado: </label>";
													echo "<div class='col-sm-10'>";
													echo "<div class='radio-inline'>";
													if($editedUserRow['employee'] == 0){
														echo "<label id='noPadding' class='radio-inline'><input class='radio-inline' type='radio' name='eUemployee' value='0' checked>No</label>";
														echo "<label id='noPadding' class='radio-inline'><input class='radio-inline' type='radio' name='eUemployee' value='1'>Si</label>";
													}
													else{
														echo "<label id='noPadding' class='radio-inline'><input class='radio-inline' type='radio' name='eUemployee' value='0'>No</label>";
														echo "<label id='noPadding' class='radio-inline'><input class='radio-inline' type='radio' name='eUemployee' value='1' checked>Si</label>";
													}
													echo "</div>";
													echo "</div>";
													echo "</div>";
												}

												echo "<div class='form-group'>";
												echo "<label id='editedUserLabel' class='control-label col-sm-2' for='eUactive'>Activo: </label>";
												echo "<div class='col-sm-10'>";
												echo "<div class='radio-inline'>";
												if($editedUserRow['active'] == 0){
													echo "<label id='noPadding' class='radio-inline'><input class='radio-inline' type='radio' name='eUactive' value='0' checked>No</label>";
													echo "<label id='noPadding' class='radio-inline'><input class='radio-inline' type='radio' name='eUactive' value='1'>Si</label>";
												}
												else{
													echo "<label id='noPadding' class='radio-inline'><input class='radio-inline' type='radio' name='eUactive' value='0'>No</label>";
													echo "<label id='noPadding' class='radio-inline'><input class='radio-inline' type='radio' name='eUactive' value='1' checked>Si</label>";
												}
												echo "</div>";
												echo "</div>";
												echo "</div>";

												echo "<div class='form-group'>";
												echo "<label id='editedUserLabel' class='control-label col-sm-2' for='eUlanguage'>Idioma: </label>";
												echo "<div class='col-sm-10'>";
												echo "<select class='form-control' name='eUlanguage'>";													
												$languagesColumn = getDBcompletecolumnID('esName', 'siteLanguages', 'id');
												foreach($languagesColumn as $i){
													if($i == $editedUserRow['language']){
														echo "<option selected value=" . $i . ">" . $i . "</option>";
													}
													else{
														echo "<option value=" . $i . ">" . $i . "</option>";
													}
												}
												echo "</select>";
												echo "</div>";
												echo "</div>";

												echo "<div class='form-group'>";
												echo "<label id='editedUserLabel' class='control-label col-sm-2' for='eUcreated'>Creado: </label>";
												echo "<div class='col-sm-10'>";
												echo "<input class='form-control' type='text' name='eUcreated' value='" . $editedUserRow['created'] . "' disabled />";
												echo "</div>";
												echo "</div>";

												echo "<div class='form-group'>";
												echo "<label id='editedUserLabel' class='control-label col-sm-2' for='eUconnection'>Última conexión: </label>";
												echo "<div class='col-sm-10'>";
												echo "<input class='form-control' type='text' name='eUconnection' value='" . $editedUserRow['lastConnection'] . "' disabled />";
												echo "</div>";
												echo "</div>";

												echo "<div class='form-group'>";
												echo "<label id='editedUserLabel' class='control-label col-sm-2' for='eUexpiration'>Caducidad contraseña: </label>";
												echo "<div class='col-sm-10'>";
												echo "<input class='form-control' type='text' name='eUexpiration' value='" . $editedUserRow['passExpiration'] . "' disabled />";
												echo "</div>";
												echo "</div>";	

											?>

										</div>
										<div class="modal-footer">
											<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
											<button type="submit" class="btn btn-primary" name="eUsersend">Guardar cambios</button>
										</div>
									</form>
								</div>
							</div>
						</div>						
						
					</div> <!-- bs-docs-section -->
				</div> <!-- col-md-9 scrollable role=main -->
			</div> <!-- row -->
		</div> <!-- class="container bs-docs-container" -->

		<?php 
		if(!isset($_GET['codvalue'])){
			//QUE EL LOGIN NO ESTE REPETIDO, Y QUE ESTE NORMALIZADO
			$editedUserRow = getDBrow('users', 'id', $_POST['eUcodUser']);
			
			/***************  Block of code that validates content sent from the form. It is only acceded after clicking on 'eUsersend' SUBMIT  ***************/			
			
			/*************************************************************************************************/
			//1st case: eUlogin(0), eUprofile(0), eUlanguage(1)
			if(($_POST['eUlogin'] == $editedUserRow['login']) && ($_POST['eUprofile'] == $editedUserRow['profile']) && ($_POST['eUlanguage'] != $editedUserRow['language'])){
				if((!executeDBquery("UPDATE `users` SET `language` = '".$_POST['eUlanguage']."' WHERE `id` = '".$_POST['eUcodUser']."'"))){
					?>
					<script type="text/javascript">
						alert('Error ADEDITUSER001');
						window.location.href='editSelectedUser.php?codvalue=<?php echo $_POST['eUcodUser'];  ?>';
					</script>
					<?php 
				}
			}
			/*************************************************************************************************/
			//2nd case: eUlogin(0), eUprofile(1), eUlanguage(0)
			if(($_POST['eUlogin'] == $editedUserRow['login']) && ($_POST['eUprofile'] != $editedUserRow['profile']) && ($_POST['eUlanguage'] == $editedUserRow['language'])){
				if(!executeDBquery("UPDATE `users` SET `profile`='".$_POST['eUprofile']."' WHERE `id`='".$_POST['eUcodUser']."'")){
					?>
					<script type="text/javascript">
						alert('Error ADEDITUSER010');
						window.location.href='editSelectedUser.php?codvalue=<?php echo $_POST['eUcodUser'];  ?>';
					</script>
					<?php 
				}
			}
			
			/*************************************************************************************************/
			//3rd case: eUlogin(0), eUprofile(1), eUlanguage(1)
			if(($_POST['eUlogin'] == $editedUserRow['login']) && ($_POST['eUprofile'] != $editedUserRow['profile']) && ($_POST['eUlanguage'] != $editedUserRow['language'])){
				if(!executeDBquery("UPDATE `users` SET `profile`='".$_POST['eUprofile']."', `language` = '".$_POST['eUlanguage']."' WHERE `id`='".$_POST['eUcodUser']."'")){
					?>
					<script type="text/javascript">
						alert('Error ADEDITUSER011');
						window.location.href='editSelectedUser.php?codvalue=<?php echo $_POST['eUcodUser'];  ?>';
					</script>
					<?php 
				}
			}
			
			/*************************************************************************************************/
			//4th case: eUlogin(1), eUprofile(0), eUlanguage(0)
			if(($_POST['eUlogin'] != $editedUserRow['login']) && ($_POST['eUprofile'] == $editedUserRow['profile']) && ($_POST['eUlanguage'] == $editedUserRow['language'])){
				if(!normalizeLogin($_POST['eUlogin'])){
					?>
					<script type="text/javascript">
						alert('El login usado no cumple los requisitos válidos.');
						window.location.href=admCurUsers.php';
					</script>
					<?php
				}
				else{
					if(!executeDBquery("UPDATE `users` SET `login`='".$_POST['eUlogin']."' WHERE `id`='".$_POST['eUcodUser']."'")){
						?>
						<script type="text/javascript">
							alert('Error ADEDITUSER100');
							window.location.href='editSelectedUser.php?codvalue=<?php echo $_POST['eUcodUser'];  ?>';
						</script>
						<?php 
					}
				}
			}
			
			/*************************************************************************************************/
			//5th case: eUlogin(1), eUprofile(0), eUlanguage(1)
			if(($_POST['eUlogin'] != $editedUserRow['login']) && ($_POST['eUprofile'] == $editedUserRow['profile']) && ($_POST['eUlanguage'] != $editedUserRow['language'])){
				if(!normalizeLogin($_POST['eUlogin'])){
					?>
					<script type="text/javascript">
						alert('El login usado no cumple los requisitos válidos.');
						window.location.href=admCurUsers.php';
					</script>
					<?php
				}
				else{
					if(!executeDBquery("UPDATE `users` SET `login`='".$_POST['eUlogin']."', `language` = '".$_POST['eUlanguage']."' WHERE `id`='".$_POST['eUcodUser']."'")){
						?>
						<script type="text/javascript">
							alert('Error ADEDITUSER101');
							window.location.href='editSelectedUser.php?codvalue=<?php echo $_POST['eUcodUser'];  ?>';
						</script>
						<?php 
					}
				}
			}
			
			/*************************************************************************************************/
			//6th case: eUlogin(1), eUprofile(1), eUlanguage(0)
			if(($_POST['eUlogin'] != $editedUserRow['login']) && ($_POST['eUprofile'] != $editedUserRow['profile']) && ($_POST['eUlanguage'] == $editedUserRow['language'])){
				if(!normalizeLogin($_POST['eUlogin'])){
					?>
					<script type="text/javascript">
						alert('El login usado no cumple los requisitos válidos.');
						window.location.href=admCurUsers.php';
					</script>
					<?php
				}
				else{
					if(!executeDBquery("UPDATE `users` SET `login`='".$_POST['eUlogin']."', `profile`='".$_POST['eUprofile']."' WHERE `id`='".$_POST['eUcodUser']."'")){
						?>
						<script type="text/javascript">
							alert('Error ADEDITUSER110');
							window.location.href='editSelectedUser.php?codvalue=<?php echo $_POST['eUcodUser'];  ?>';
						</script>
						<?php 
					}
				}
			}
			
			/*************************************************************************************************/
			//7th case: eUlogin(1), eUprofile(1), eUlanguage(1)
			if(($_POST['eUlogin'] != $editedUserRow['login']) && ($_POST['eUprofile'] != $editedUserRow['profile']) && ($_POST['eUlanguage'] != $editedUserRow['language'])){
				if(!normalizeLogin($_POST['eUlogin'])){
					?>
					<script type="text/javascript">
						alert('El login usado no cumple los requisitos válidos.');
						window.location.href=admCurUsers.php';
					</script>
					<?php
				}
				else{
					if(!executeDBquery("UPDATE `users` SET `login`='".$_POST['eUlogin']."', `profile`='".$_POST['eUprofile']."', `language` = '".$_POST['eUlanguage']."' WHERE `id`='".$_POST['eUcodUser']."'")){
						?>
						<script type="text/javascript">
							alert('Error ADEDITUSER111');
							window.location.href='editSelectedUser.php?codvalue=<?php echo $_POST['eUcodUser'];  ?>';
						</script>
						<?php 
					}
				}
			}
			
			//Save whatever change made in any of the Radio buttons
			if(($_POST['eUemployee'] == $editedUserRow['employee']) && ($_POST['eUactive'] != $editedUserRow['active'])){
				if(!executeDBquery("UPDATE `users` SET `active`='".$_POST['eUactive']."' WHERE `id`='".$_POST['eUcodUser']."'")){
					?>
					<script type="text/javascript">
						alert('Error ADEDUSERADIO01');
						window.location.href='editSelectedUser.php?codvalue=<?php echo $_POST['eUcodUser'];  ?>';
					</script>
					<?php 
				}
			}
			elseif(($_POST['eUemployee'] != $editedUserRow['employee']) && ($_POST['eUactive'] == $editedUserRow['active'])){
				if(!executeDBquery("UPDATE `users` SET `employee`='".$_POST['eUemployee']."' WHERE `id`='".$_POST['eUcodUser']."'")){
					?>
					<script type="text/javascript">
						alert('Error ADEDUSERADIO10');
						window.location.href='editSelectedUser.php?codvalue=<?php echo $_POST['eUcodUser'];  ?>';
					</script>
					<?php 
				}
			}
			elseif(($_POST['eUemployee'] != $editedUserRow['employee']) && ($_POST['eUactive'] != $editedUserRow['active'])){
				if(!executeDBquery("UPDATE `users` SET `employee`='".$_POST['eUemployee']."', `active`='".$_POST['eUactive']."' WHERE `id`='".$_POST['eUcodUser']."'")){
					?>
					<script type="text/javascript">
						alert('Error ADEDUSERADIO11');
						window.location.href='editSelectedUser.php?codvalue=<?php echo $_POST['eUcodUser'];  ?>';
					</script>
					<?php 
				}
			}
			
			//If everything was OK...
			?>
			<script type="text/javascript">
				alert('El usuario <?php echo $editedUserRow['login']; ?> ha sido actualizado con éxito.');
				window.location.href='admCurUsers.php';
			</script>
			<?php
		
		/***************  Fin del bloque que valida el contenido enviado en el formulario  ***************/
		}
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

	<!-- Own document functions -->
	<!-- Show modal if password has to be changed -->
	<script type="text/javascript">
		$(document).ready(function(){
			$('#editUserModal').modal('show');
			$('#editUserModal').on('hidden.bs.modal', function () {
 				window.location.href='admCurUsers.php';
			});
		});  
	</script> 

</body>
</html>
