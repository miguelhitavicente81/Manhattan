<?php session_start(); /*error_reporting (E_ALL & ~E_NOTICE & ~E_STRICT & ~E_DEPRECATED & ~E_WARNING); */ ?>
<html lang="es">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="">
	<meta name="author" content="David Alfonso Ginés Prieto, Miguel Hita Vicente y Miguel Ángel Melón Pérez">
	
	<title>Gestión de contraseña</title>

	<!-- Custom styles for this template -->
	<link href="../common/css/design.css" rel="stylesheet">
	<!-- <link href="../common/css/styles.css" rel="stylesheet">
	<link href="../common/css/docs.css" rel="stylesheet"> -->

	<!-- Using the same favicon from perspectiva-alemania.com site -->
	<link rel="shortcut icon" href="http://www.perspectiva-alemania.com/wp-content/themes/perspectiva2013/bilder/favicon.png">
	<!-- Using the favicon for touch-devices shortcut -->
	<link rel="apple-touch-icon" href="../common/img/apple-touch-icon.png">


</head>

<body>

		<?php 
		require_once($_SERVER['DOCUMENT_ROOT'] . '/common/library/functions.php');
		//Part of the code read when user is forced to change his/her password
		if($_POST['changePassword']){
			if(!checkPassChange($_POST['newPassword'], $_POST['confirmNewPassword'], $keyError)){
				?>
				<script type="text/javascript">
					alert('<?php echo $keyError; ?>');
					window.location.href='index.html';
				</script>
				<?php 
			}
			//That's when system generates new Blowfish password
			else{
				$newCryptedPass = blowfishCrypt($_POST['newPassword']);
				if(!executeDBquery("UPDATE `users` SET `pass`='".$newCryptedPass."', `needPass`='1' WHERE `login`='".$_SESSION['loglogin']."'")){
					//session_destroy(); DEBERIA DESTRUIR LA SESSION
					?>
					<script type="text/javascript">
						alert('No fue posible actualizar su contraseña.');
						window.location.href='index.html';
					</script>
					<?php 
				}
				else{
					$userRow = getDBrow('users', 'login', $_SESSION['loglogin']);
					$_SESSION['logprofile'] = $userRow['profile'];
					$_SESSION['lastupdate'] = date('Y-m-j H:i:s');
					$_SESSION['sessionexpiration'] = getDBsinglefield('value', 'otherOptions', 'key', 'sessionexpiration');
					?>
					<script type="text/javascript">
						window.location.href='home.php';
					</script>
					<?php 
				}
			}
		}
		/**************************************************************************************************************************/
		
		//Part of the code read when user tries to loggin
		else{
			//Firstly checks if both text fields were fulfilled or not
			if (isset($_POST['loglogin']) && !empty($_POST['loglogin']) && isset($_POST['logpasswd']) && !empty($_POST['logpasswd'])){
				$checkedUser = $_POST["loglogin"];
				$checkedPasswd = $_POST["logpasswd"];
				$userRow = getDBrow('users', 'login', $checkedUser);
				$profileRow = getDBrow('profiles', 'name', $userRow['profile']);
				if($userRow == 0){
					?>
					<script type="text/javascript">
						alert('Usuario inexistente o incorrecto.');
						window.location.href='index.html';
					</script>
					<?php 
				}
				/*
				if($checkedPasswd != ($userRow['pass'])){
					?>
					<script type="text/javascript">
						alert('Contraseña incorrecta.');
						window.location.href='index.html';
					</script>
					<?php 
				}
				*/
				//Then checks password
				if(!(crypt($_POST['logpasswd'], $userRow['pass']) == $userRow['pass'])){
					if(!$userRow['needPass']){
						//echo crypt($_POST['logpasswd'], $userRow['pass']).' -- ';
						//echo $userRow['pass'].'-';
						?>
						<script type="text/javascript">
							alert('Contraseña incorrecta.');
							window.location.href='index.html';
						</script>
						<?php 
					}
				}
				//Checks whether user profile is active
				if(!$profileRow['active']){
					?>
					<script type="text/javascript">
						alert('Perfil no activo.');
						window.location.href='index.html';
					</script>
					<?php 
				}
				//Checks whether user account is active
				if(!$userRow['active']){
					?>
					<script type="text/javascript">
						alert('Usuario no activo.');
						window.location.href='index.html';
					</script>
					<?php 
				}
				//Check whether user and profile's user are active (if not, will be blocked) his/her access
				//At the moment won't be used 'connected' field in 'users' table. Will use COOKIES
				/*
				elseif($userRow['connected']){
					?>
					<script type="text/javascript">
						alert('Usuario conectado.');
						window.location.href='index.html';
					</script>
					<?php 
				}
				*/
				else{
					//After all these checkings, user could be properly logged in. We start with procedure
					$_SESSION['loglogin'] = $checkedUser;
					
					//After all checkings, user is properly logged in, so it updates 'last connection' 
					//session_name($checkedUser);
					//Next sentence is actually written at the top 
					//session_start();
					//Don't know if will need these 2 vars in next php pages
					/*
					$_SESSION['loglogin'] = $checkedUser;
					$_SESSION['logprofile'] = $userRow['profile'];
					*/
					/*
					if(!executeDBquery("UPDATE `users` SET `lastConnection` = CURRENT_TIMESTAMP WHERE `login` = '".$checkedUser."'")){
						echo 'el1';
						?>
						<script type="text/javascript">
							alert('No se pudo actualizar la fecha de última conexión.');
							window.location.href='index.html';
						</script>
						<?php 
					}
					else{
					*/
					//But it still lasts to check whether the user needs change his/her password
					// echo "<div class='bs-example'><div class='progress progress-striped active'><div class='bar' style='width: 60%;'><span class='sr-only'>Cargando …</span></div></div></div>";

					/*
					$_SESSION['lastupdate'] = date('Y-m-j H:i:s');
					$_SESSION['sessionexpiration'] = getDBsinglefield('value', 'otherOptions', 'key', 'sessionexpiration');
					*/
					//if($userRow['needPass']){
					//SI FUNCIONA LA SIGUIENTE COMPARACION NO NECESITARE LA LINEA ANTERIOR NI EL CAMPO "needPass" EN LA TABLA "users"
					//"needPass" se usa cuando un usuario recién creado entra por 1ª vez
					//if($userRow['passExpiration'] <= date('Y-m-j')){
					if(($userRow['passExpiration'] <= date('Y-m-j')) || ($userRow['needPass'])){
						?>

							<div id="panel-warning" class="panel panel-warning center-block">
								<div class="panel-heading">
									<h3 class="panel-title">Debe cambiar la contraseña antes de continuar</h3>
								</div>
								
									<div class="well">
										<?php //include $_SERVER['DOCUMENT_ROOT'] . '/common/passwdRestrictionsES.txt'; ?>
									</div>
									<div class="panel-body">
										<form id="changePasswordForm" name="changePasswordForm" class="form-horizontal center-block" action="validatefront.php" method="post" onsubmit="return equalPassword(newPassword, confirmNewPassword)">
											<div class="form-group">
												<label for="newPassword" class="control-label">Nueva contraseña</label>
												<div class="center-block">
													<input type="password" class="form-control" name="newPassword" id="newPassword" placeholder="" required data-toggle="tooltip" title="Introduce la nueva contraseña" autocapitalize="off">
												</div>
											</div>
											<div class="form-group">
												<label for="confirmNewPassword" class="control-label">Repita contraseña</label>
												<div class="center-block">
													<input type="password" class="form-control" name="confirmNewPassword" id="confirmNewPassword" placeholder="" required data-toggle="tooltip" title="Confirma la nueva contraseña" autocapitalize="off">
												</div>

												<div class="pull-right">
													<button type="submit" class="btn btn-primary" name="changePassword">Cambiar</button>
												</div>
											</div>
										</form>
									</div>
								</div> <!-- id="panel-warning"  -->



						<?php
					}
					/* COMPROBAR SI A LA CONTRASEÑA LE QUEDA MENOS DEL TIEMPO DE AVERTENCIA (LO QUE TENDRE QUE INCLUIR EN LA TABLA "otherOptions")
					if($userRow['passExpiration']){
					}
					*/
					else{
						if(!executeDBquery("UPDATE `users` SET `lastConnection` = CURRENT_TIMESTAMP WHERE `login` = '".$checkedUser."'")){
							echo 'el1';
							?>
							<script type="text/javascript">
								alert('No se pudo actualizar la fecha de última conexión.');
								window.location.href='index.html';
							</script>
							<?php 
						}
						else{
							//$_SESSION['loglogin'] = $checkedUser;
							$_SESSION['logprofile'] = $userRow['profile'];
							$_SESSION['lastupdate'] = date('Y-m-j H:i:s');
							$_SESSION['sessionexpiration'] = getDBsinglefield('value', 'otherOptions', 'key', 'sessionexpiration');
							?>
							<script type="text/javascript">
								window.location.href='home.php';
							</script>
							<?php 
						}
					}
					//}
				}
			}
			//If any of the text fields (login/password) were not fulfilled...
			else{
				?>
				<script type="text/javascript">
					alert('Ha olvidado rellenar alguno de los campos.');
					window.location.href='index.html';
				</script>
				<?php 
			}
		}
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
	<script src="../common/js/functions.js"></script>
	<script src="../common/js/application.js"></script>
	<script src="../common/js/docs.min.js"></script>

</body>
</html>
