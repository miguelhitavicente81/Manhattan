<?php session_start(); ?>
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
	<fieldset style="padding-top: 15%; border: solid 0px">
		<div id="stylized" class="myform">
		<?php 
		require_once($_SERVER['DOCUMENT_ROOT'] . '/common/library/functions.php');
		//Part of the code read when user is forced to change his/her password
		if($_POST['pdChange']){
			if(!checkPassChange($_POST['pdpass1'], $_POST['pdpass2'], $keyError)){
				?>
				<script type="text/javascript">
					alert('<?php echo $keyError; ?>');
					window.location.href='index.html';
				</script>
				<?php 
			}
			//That's when system generates new Blowfish password
			else{
				$newCryptedPass = blowfishCrypt($_POST['pdpass1']);
				if(!executeDBquery("UPDATE `users` SET `pass`='".$newCryptedPass."', `needPass`='0' WHERE `login`='".$_SESSION['loglogin']."'")){
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
					$_SESSION['lastupdate'] = date('Y-n-j H:i:s');
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
						echo crypt($_POST['logpasswd'], $userRow['pass']).' -- ';
						echo $userRow['pass'].'-';
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
					echo "<div class='bs-example'><div class='progress progress-striped active'><div class='bar' style='width: 60%;'><span class='sr-only'>Cargando …</span></div></div></div>";

					/*
					$_SESSION['lastupdate'] = date('Y-n-j H:i:s');
					$_SESSION['sessionexpiration'] = getDBsinglefield('value', 'otherOptions', 'key', 'sessionexpiration');
					*/
					//if($userRow['needPass']){
					//SI FUNCIONA LA SIGUIENTE COMPARACION NO NECESITARE LA LINEA ANTERIOR NI EL CAMPO "needPass" EN LA TABLA "users"
					//"needPass" se usa cuando un usuario recién creado entra por 1ª vez
					//if($userRow['passExpiration'] <= date('Y-n-j')){
					if(($userRow['passExpiration'] <= date('Y-m-j')) || ($userRow['needPass'])){
						echo 'el3';
						?>
						<div id="data">
							<h3>Debe cambiar la contraseña antes de continuar</h3>
							<hr class="long">
							<br/>
							<?php include '../common/passwdRestrictionsES.txt'; ?>
							<div id="stylized" class="myform">
								<!-- <form id="form" name="form" action="home.php" method="post" onsubmit="return checkPasswordES(pdpass1, pdpass2)"> -->
								<!-- <form id="form" name="form" action="updateExpiration.php" method="post" onsubmit="return checkPasswordES(pdpass1, pdpass2)"> -->
								<form id="form" name="form" action="validatefront.php" method="post">
									<h1>Cambio de contraseña</h1>
									<label>Nueva contraseña</label><input type="password" name="pdpass1" id="pdpass1" size="35"><br>
									<label>Repita contraseña</label><input type="password" name="pdpass2" id="pdpass2" size="35"><br>
									<!-- ME FALTA ACTUALIZAR EL CAMPO "passExpiration", POR LO QUE EL action DEBERIA LLAMAR A "updateExpiration()" Y ESTE IR LUEGO A "home.php" -->
									<input type="submit" value="Cambiar" name="pdChange"><br/>
									<br/>
								</form>
							</div> <!-- del "stylized" -->
							<br>
						</div>
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
							$_SESSION['lastupdate'] = date('Y-n-j H:i:s');
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
		<!-- <input type="button" style="text-align: center" value="Volver al inicio" onclick="window.location='./Login.html'"/> -->
		</div>
	</fieldset>

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
