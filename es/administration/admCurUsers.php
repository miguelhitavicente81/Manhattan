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
		<div class="leftbox">
			<!-- Este 'class' sirve para mostrar los submenús alineados a la izquierda en el nivel 2 -->
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
		//if(isset($_POST['hNewUsubmit'])){ SI FUERA NECESARIO
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
		?>
		<!--
		< ?php 
		echo "<p><span id='leftmsg'>Usuarios Existentes";
		if(getDBsinglefield('admNewUser', 'administration', 'profile', $_SESSION['logprofile'])){
			echo "<span id='rightlink' style='float: right;'><a href=AdmNewUser.php>Nuevo usuario</a></span>";
		}
		echo "</span></p><hr>";
		?>
		-->
		<p><span id="leftmsg">Usuarios Existentes</span></p><hr><br>
		<?php 
		if($_SESSION['logprofile'] == 'SuperAdmin'){
			?>
			<table class="tabla1">
				<tr>
					<th>Id</th>
					<th>Login</th>
					<th>Perfil</th>
					<th>Empleado</th>
					<th>Activo</th>
					<th>Idioma</th>
					<th>Creado</th>
					<th>Ultima conexión</th>
					<th>Caduca Password</th>
					<th>Acción</th>
				</tr>
			<?php
			$numUsers = getDBrowsnumber('users');
			for($i=1; $i<=$numUsers; $i++){
				$showedUserRow = getDBrow('users', 'id', $i);
				echo "<tr>";
				echo "<td>" . $showedUserRow['id'] . "</td>";
				echo "<td><a href='editUser.php?codvalue=" . $showedUserRow['id'] . "'>" . $showedUserRow['login'] . "</a></td>";
				echo "<td>" . utf8_encode($showedUserRow['profile']) . "</td>";
				if($showedUserRow['employee'] == 1){
					echo "<td>Si</td>";
				}
				else{
					echo "<td>No</td>";
				}
				if($showedUserRow['active']){
					echo "<td>Si</td>";
				}
				else{
					echo "<td>No</td>";
				}
				echo "<td>" . utf8_encode($showedUserRow['language']) . "</td>";
				echo "<td>" . $showedUserRow['created'] . "</td>";
				echo "<td>" . $showedUserRow['lastConnection'] . "</td>";
				echo "<td>" . $showedUserRow['passExpiration'] . "</td>";
				echo "<td><a href=''>Borrar</a></td>";
				echo "</tr>";
			}
			?>
			</table>
			<fieldset id="auto1">
				<legend>Nuevo Usuario</legend>
				<form name="newUser" action="admCurUsers.php" method="post">
					<input type="text" name="newUName" size="15" placeholder="Usuario" />
					Perfil: <select name="newUProfile">
						<?php 
						$profNames = getDBcompletecolumnID('name', 'profiles', 'id');
						foreach($profNames as $i){
							echo "<option value=" . utf8_encode($i) . ">" . utf8_encode($i) . "</option>";
						}
						?>
					</select>
					Idioma: <select name="newULanguage">
						<?php 
						$siteLanguages = getDBcompletecolumnID('esName', 'siteLanguages', 'id');
						foreach($siteLanguages as $i){
							echo "<option value=" . utf8_encode($i) . ">" . utf8_encode($i) . "</option>";
						}
						?>
					</select>
					<input type="submit" value="Añadir" name="newUsubmit">
				</form>
			</fieldset>
			<?php 
		}
		elseif($_SESSION['logprofile'] == 'Administrador'){
			?>
			<table class="tabla1">
				<tr>
					<th>Id</th>
					<th>Login</th>
					<th>Perfil</th>
					<th>Activo</th>
					<th>Idioma</th>
					<th>Creado</th>
					<th>Ultima conexión</th>
					<th>Caduca Password</th>
					<th>Acción</th>
				</tr>
			<?php
			$numUsers = getDBrowsnumber('users');
			for($i=2; $i<=$numUsers; $i++){
				$showedUserRow = getDBrow('users', 'id', $i);
				echo "<tr>";
				echo "<td>" . $showedUserRow['id'] . "</td>";
				echo "<td><a href='editUser.php?codvalue=" . $showedUserRow['id'] . "'>" . $showedUserRow['login'] . "</a></td>";
				echo "<td>" . utf8_encode($showedUserRow['profile']) . "</td>";
				if($showedUserRow['active']){
					echo "<td>Si</td>";
				}
				else{
					echo "<td>No</td>";
				}
				echo "<td>" . utf8_encode($showedUserRow['language']) . "</td>";
				echo "<td>" . $showedUserRow['created'] . "</td>";
				echo "<td>" . $showedUserRow['lastConnection'] . "</td>";
				echo "<td>" . $showedUserRow['passExpiration'] . "</td>";
				echo "<td><a href=''>Borrar</a></td>";
				echo "</tr>";
			}
			?>
			</table>
			<fieldset id="auto1">
				<legend>Nuevo Usuario</legend>
				<form name="newUser" action="admCurUsers.php" method="post">
					<input type="text" name="newUName" size="15" placeholder="Usuario" />
					Perfil: <select name="newUProfile">
						<?php 
						//$profNames = getDBcompletecolumnNotID('name', 'profiles', 'id', 'name', 'SuperAdmin');
						$profNames = getDBcompletecolumnID('name', 'profiles', 'id');
						foreach($profNames as $i){
							if($i != 'SuperAdmin'){
								echo "<option value=" . utf8_encode($i) . ">" . utf8_encode($i) . "</option>";
							}
						}
						?>
					</select>
					Idioma: <select name="newULanguage">
						<?php 
						$siteLanguages = getDBcompletecolumnID('esName', 'siteLanguages', 'id');
						foreach($siteLanguages as $i){
							echo "<option value=" . utf8_encode($i) . ">" . utf8_encode($i) . "</option>";
						}
						?>
					</select>
					<!-- <input type="hidden" value="hNewUsubmit" name="hiddenfield"> -->
					<input type="submit" value="Añadir" name="newUsubmit">
				</form>
			</fieldset>
			<?php 
		}
		else{
			echo "No dispone de permisos para visualizar esta página";
			echo "<button onclick='location.href=\"../home.php\"'>Inicio</button>";  
		}
		?>
		</div><!-- Fin del "rightbox" -->
	</div><!-- Fin del "workspace" -->
	<?php
}//del "else" de $_SESSION.

?>

</body>
</html>
