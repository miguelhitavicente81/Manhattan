<?php session_start(); ?>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<link href='http://fonts.googleapis.com/css?family=Ubuntu+Mono:400,700,400italic,700italic|Ubuntu:300,400,500,700,300italic,400italic,500italic,700italic|Ubuntu+Condensed&
subset=latin,latin-ext' rel='stylesheet' type='text/css'>
	<title>Inicio</title>
	<link href="../../common/css/styles.css" rel="stylesheet" type="text/css">
	<script type="text/javascript" src="../../common/js/functions.js"></script>
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
else{
	$lastUpdate = $_SESSION['lastupdate'];
	$curUpdate = date('Y-n-j H:i:s');
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
	<div id="topbar" class="azul">
		<a style="float:left;" href="#">Opciones</a>
		<a style="float:center">Conectado como: <?php echo $_SESSION['loglogin']; ?></a>
		<a href="../endsession.php" style="float:right">Salir</a>
	</div>
	<?php 
	$myFile = 'administration';
	$userRow = getDBrow('users', 'login', $_SESSION['loglogin']);
	?>
	<div id="mainmenu">
	<ul class="navbar1">
		<?php 
		$mainKeysRow = getDBcompletecolumnID('key', 'mainNames', 'id');
		$mainNamesRow = getDBcompletecolumnID('esName', 'mainNames', 'id');
		$j = 0;
		foreach($mainKeysRow as $i){
			if(getDBsinglefield('active', $i, 'profile', $userRow['profile'])){
				if($myFile == $i){
					echo "<li><a href=../$i.php id='onlink'>" . utf8_encode($mainNamesRow[$j]) . "</a></li>";
					$j++;
				}
				else{
					echo "<li><a href=../$i.php>" . utf8_encode($mainNamesRow[$j]) . "</a></li>";
					$j++;
				}
			}
		}
		?>
	</ul>
	</div>

	<div class="workspace">
		<div class="leftbox"><!-- Este 'class' sirve para mostrar los submenús alineados a la izquierda en el nivel 2 -->
			<ul>
			<?php
			$namesTable = $myFile.'Names';
			$numCols = getDBnumcolumns($myFile);
			$myFileProfileRow = getDBrow($myFile, 'profile', $userRow['profile']);
			for($j=3;$j<$numCols;$j++){
				$colNamej = getDBcolumnname($myFile, $j);
				if(($myFileProfileRow[$j] == 1) && ($subLevelMenu = getDBsinglefield2('esName', $namesTable, 'key', $colNamej, 'level', '2'))){
					if(!getDBsinglefield2('esName', $namesTable, 'fatherKey', $colNamej, 'level', '3')){
						$level2File = getDBsinglefield('key', $namesTable, 'esName', $subLevelMenu);
						echo "<li><a href=./$level2File.php>" . $subLevelMenu . "</a></li>";
					}
					else{
						$arrayKeys = array();
						$arrayKeys = getDBcolumnvalue('key', $namesTable, 'fatherKey', $colNamej);
						$checkFinished = 0;
						$l = 1;
						foreach($arrayKeys as $k){
							if($checkFinished == 0){
								if(($myFileProfileRow[$j+$l] == 1) && (getDBsinglefield($k, $myFile, 'profile', $userRow['profile']))){
									$level3File = $k;
									$checkFinished = 1;
								}
								else{
									$l++;
								}
							}
						}
						echo "<li><a href=./$level3File.php>" . $subLevelMenu . "</a></li>";
					}
				}
			}
			?>
			</ul>
		</div>

		<div class="rightbox">
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
						window.location.href='editUser.php?codvalue=<?php echo $_POST['eUcodUser']; ?>';
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
						window.location.href='editUser.php?codvalue=<?php echo $_POST['eUcodUser']; ?>';
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
						window.location.href='editUser.php?codvalue=<?php echo $_POST['eUcodUser']; ?>';
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
						window.location.href=editUser.php?codvalue=<?php echo $_POST['eUcodUser'] ?>';
					</script>
					<?php
				}
				else{
					if(!executeDBquery("UPDATE `users` SET `login`='".$_POST['eUlogin']."' WHERE `id`='".$_POST['eUcodUser']."'")){
						?>
						<script type="text/javascript">
							alert('Error ADEDITUSER100');
							window.location.href='editUser.php?codvalue=<?php echo $_POST['eUcodUser']; ?>';
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
						window.location.href=editUser.php?codvalue=<?php echo $_POST['eUcodUser'] ?>';
					</script>
					<?php
				}
				else{
					if(!executeDBquery("UPDATE `users` SET `login`='".$_POST['eUlogin']."', `language` = '".$_POST['eUlanguage']."' WHERE `id`='".$_POST['eUcodUser']."'")){
						?>
						<script type="text/javascript">
							alert('Error ADEDITUSER101');
							window.location.href='editUser.php?codvalue=<?php echo $_POST['eUcodUser']; ?>';
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
						window.location.href=editUser.php?codvalue=<?php echo $_POST['eUcodUser'] ?>';
					</script>
					<?php
				}
				else{
					if(!executeDBquery("UPDATE `users` SET `login`='".$_POST['eUlogin']."', `profile`='".$_POST['eUprofile']."' WHERE `id`='".$_POST['eUcodUser']."'")){
						?>
						<script type="text/javascript">
							alert('Error ADEDITUSER110');
							window.location.href='editUser.php?codvalue=<?php echo $_POST['eUcodUser']; ?>';
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
						window.location.href=editUser.php?codvalue=<?php echo $_POST['eUcodUser'] ?>';
					</script>
					<?php
				}
				else{
					if(!executeDBquery("UPDATE `users` SET `login`='".$_POST['eUlogin']."', `profile`='".$_POST['eUprofile']."', `language` = '".$_POST['eUlanguage']."' WHERE `id`='".$_POST['eUcodUser']."'")){
						?>
						<script type="text/javascript">
							alert('Error ADEDITUSER111');
							window.location.href='editUser.php?codvalue=<?php echo $_POST['eUcodUser']; ?>';
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
						window.location.href='editUser.php?codvalue=<?php echo $_POST['eUcodUser']; ?>';
					</script>
					<?php 
				}
			}
			elseif(($_POST['eUemployee'] != $editedUserRow['employee']) && ($_POST['eUactive'] == $editedUserRow['active'])){
				if(!executeDBquery("UPDATE `users` SET `employee`='".$_POST['eUemployee']."' WHERE `id`='".$_POST['eUcodUser']."'")){
					?>
					<script type="text/javascript">
						alert('Error ADEDUSERADIO10');
						window.location.href='editUser.php?codvalue=<?php echo $_POST['eUcodUser']; ?>';
					</script>
					<?php 
				}
			}
			elseif(($_POST['eUemployee'] != $editedUserRow['employee']) && ($_POST['eUactive'] != $editedUserRow['active'])){
				if(!executeDBquery("UPDATE `users` SET `employee`='".$_POST['eUemployee']."', `active`='".$_POST['eUactive']."' WHERE `id`='".$_POST['eUcodUser']."'")){
					?>
					<script type="text/javascript">
						alert('Error ADEDUSERADIO11');
						window.location.href='editUser.php?codvalue=<?php echo $_POST['eUcodUser']; ?>';
					</script>
					<?php 
				}
			}
			
			//If everything was OK...
			?>
			<script type="text/javascript">
				alert('El usuario <?php echo $editedUserRow['login']; ?> ha sido actualizado con éxito.');
				window.location.href='editUser.php?codvalue=<?php echo $_POST['eUcodUser'] ?>';
			</script>
			<?php
		
		/***************  Fin del bloque que valida el contenido enviado en el formulario  ***************/
		}
		
		/***************  Aquí comienza el bloque que permite mostrar el formulario  ***************/
		else{
			$editedUserRow = getDBrow('users', 'id', $_GET['codvalue']);
			echo "<h3>Editando el usuario \"" . $editedUserRow['login'] . "\"</h3><hr class='long'><br>";
			echo '<fieldset id="auto2">';
				echo '<form id="editedUser" name="editedUser" method="post" action=editUser.php">';
					echo "Identificador: <input type='text' name='eUcod' value=" . $editedUserRow['id'] . " size='5' disabled /><br/>";
					//echo "Login: <input type='text' name='eUlogin' value='" . $editedUserRow['login'] . "' size='20' disabled /><br/>";
					echo "Login: <input type='text' name='eUlogin' value='" . $editedUserRow['login'] . "' size='20'><br/>";
					echo "Contraseña: <input type='password' name='eUpasswd' value='" . $editedUserRow['pass'] . "' size='20' disabled /><br/>";
					//echo "Nombre: <input type='text' name='eUname' value='" . utf8_encode($editedUserRow['name']) . "' size='20' /><br/>";
					//echo "Apellidos: <input type='text' name='ECUsurname' value='" . utf8_encode($editedUserRow['surname']) . "' size='20' /><br/>";
					//Otra cosa, el usuario Administrador NO podrá cambiar su perfil. De hacerlo, luego no podría volver a ser Administrador
					if($_SESSION['logprofile'] == 'SuperAdmin'){
						echo "Perfil: <select name='eUprofile'>";
						$profNamesColumn = getDBcompletecolumnID('name', 'profiles', 'id');
						foreach($profNamesColumn as $i){
							if($i == $editedUserRow['profile']){
								echo "<option selected value=" . utf8_encode($i) . ">" . utf8_encode($i) . "</option>";
							}
							else{
								echo "<option value=" . utf8_encode($i) . ">" . utf8_encode($i) . "</option>";
							}
						}
						echo "</select><br/>";
					}
					elseif($_SESSION['logprofile'] == 'Administrador'){
						echo "Perfil: <select name='eUprofile'>";
						$profNamesColumn = getDBcompletecolumnID('name', 'profiles', 'id');
						foreach($profNamesColumn as $i){
							if($i != 'SuperAdmin'){
								if($i == $editedUserRow['profile']){
									echo "<option selected value=" . utf8_encode($i) . ">" . utf8_encode($i) . "</option>";
								}
								else{
									echo "<option value=" . utf8_encode($i) . ">" . utf8_encode($i) . "</option>";
								}
							}
						}
						echo "</select><br/>";
					}
					else{
						echo "Perfil: <input type='text' name='eUprofile' value='" . utf8_encode($editedUserRow['profile']) . "' size='20' disabled /><br/>";
					}
					//If user has profile "Candidato" will show his/her NIE
					if($editedUserRow['profile'] == "Candidato"){
						echo "NIE: <input type='text' name='eUuser' value='" . getDBsinglefield('nie', 'cVitaes', 'userLogin', $editedUserRow['login']) . "' size='20' disabled /><br/>";
					}
					//ES MUY POSIBLE QUE ACABE QUITANDO LO DE EMPLEADO
					if($_SESSION['logprofile'] == 'SuperAdmin'){
						echo "<label>Empleado: </label>";
						if($editedUserRow['employee'] == 0){
							echo "<input type='radio' name='eUemployee' value='0' checked>No";
							echo "<input type='radio' name='eUemployee' value='1'>Si<br>";
						}
						else{
							echo "<input type='radio' name='eUemployee' value='0'>No";
							echo "<input type='radio' name='eUemployee' value='1' checked>Si<br>";
						}
					}
					echo "<label>Activo: </label>";
					if($editedUserRow['active'] == 0){
						echo "<input type='radio' name='eUactive' value='0' checked>No";
						echo "<input type='radio' name='eUactive' value='1'>Si<br>";
					}
					else{
						echo "<input type='radio' name='eUactive' value='0'>No";
						echo "<input type='radio' name='eUactive' value='1' checked>Si<br>";
					}
					/*
					echo "Idioma: <select name='eUlanguage'>";
						$languagesColumn = getDBcompletecolumnID('esName', 'siteLanguages', 'id');
						$j = 0;
						foreach($languagesColumn as $i){
							if($i == $editedUserRow['language']){
								echo "<option selected value=" . $j . ">" . utf8_encode($languagesColumn[$j]) . "</option>";
							}
							else{
								echo "<option value=" . $j . ">" . utf8_encode($languagesColumn[$j]) . "</option>";
							}
							$j++;
						}
					echo "</select><br/>";
					*/
					echo "Idioma: <select name='eUlanguage'>";
						$languagesColumn = getDBcompletecolumnID('esName', 'siteLanguages', 'id');
						//EN BD DEBE GUARDARSE EL "key" PARA PODER LUEGO LEERLO EN CUALQUIER IDIOMA
						foreach($languagesColumn as $i){
							if($i == $editedUserRow['language']){
								echo "<option selected value=" . utf8_encode($i) . ">" . utf8_encode($i) . "</option>";
							}
							else{
								echo "<option value=" . utf8_encode($i) . ">" . utf8_encode($i) . "</option>";
							}
						}
					echo "</select><br/>";
					/*
					echo "Conectado: ";
					if($editedUserRow['connected'] == 0){
						echo "<input type='checkbox' disabled /><br/>";
					}
					else {
						echo "<input type='checkbox' checked='checked' disabled /><br/>";
					}
					*/
					echo "Creado: <input type='text' name='eUcreated' value='" . $editedUserRow['created'] . "' size='20' disabled /><br/>";
					echo "Última conexión: <input type='text' name='eUconnection' value='" . $editedUserRow['lastConnection'] . "' size='20' disabled /><br/>";
					echo "Caducidad Contraseña: <input type='text' name='eUexpiration' value='" . $editedUserRow['passExpiration'] . "' size='20' disabled /><br/>";
		
					echo "<input type='hidden' name='eUcodUser' value=" . $editedUserRow['id'] . ">";
					//echo "<a style='float:left' href='DelCurUser.php?codvalue=" . $editedUserRow['id'] . "' onclick='return confirmUserDeletion();'>Borrar usuario (IMPLEMENTAR)</a>";
					echo "<input type='submit' name='eUsersend' value='Guardar' />";
				echo '</form>';
			echo '</fieldset>';//del 'id=auto2'
		}
		?>
		</div><!-- Fin del "rightbox" -->
	</div><!-- Fin del "workspace" -->
	<?php
}//del "else" de $_SESSION.

?>

</body>
</html>
