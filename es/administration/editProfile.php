<?php session_start(); ?>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<link href='http://fonts.googleapis.com/css?family=Ubuntu+Mono:400,700,400italic,700italic|Ubuntu:300,400,500,700,300italic,400italic,500italic,700italic|Ubuntu+Condensed&
subset=latin,latin-ext' rel='stylesheet' type='text/css'>
	<title>Inicio</title>
	<link href="../../common/css/styles.css" rel="stylesheet" type="text/css">
	<script type="text/javascript" src="../../common/js/functions.js"></script>
	<script type="text/javascript" src="../../common/js/jquery-1.10.1.min.js"></script>
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
	require_once '../library/functions.php';
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
		//QUE EL NOMBRE DEL PERFIL NO ESTE REPETIDO. PASAR EL NOMBRE POR EL NORMALIZADOR
		if(!isset($_GET['codvalue'])){
			$editedProfileRow = getDBrow('profiles', 'id', $_POST['ePcodUser']);
			if(($_POST['ePname'] == $editedProfileRow['name']) && ($_POST['ePactive'] != $editedProfileRow['active'])){
				if(!executeDBquery("UPDATE `profiles` SET `active`='".$_POST['ePactive']."' WHERE `id`='".$_POST['ePcodUser']."'")){
					?>
					<script type="text/javascript">
						alert('Error ADEDPROFILE01');
						window.location.href='editProfile.php?codvalue=<?php echo $_POST['ePcodUser']; ?>';
					</script>
					<?php 
				}
			}
			elseif(($_POST['ePname'] != $editedProfileRow['name']) && ($_POST['ePactive'] == $editedProfileRow['active'])){
				if(!executeDBquery("UPDATE `profiles` SET `name`='".$_POST['ePname']."' WHERE `id`='".$_POST['ePcodUser']."'")){
					?>
					<script type="text/javascript">
						alert('Error ADEDPROFILE10');
						window.location.href='editProfile.php?codvalue=<?php echo $_POST['ePcodUser']; ?>';
					</script>
					<?php 
				}
			}
			elseif(($_POST['ePname'] != $editedProfileRow['name']) && ($_POST['ePactive'] != $editedProfileRow['active'])){
				if(!executeDBquery("UPDATE `profiles` SET `name`='".$_POST['ePname']."', `active`='".$_POST['ePactive']."' WHERE `id`='".$_POST['ePcodUser']."'")){
					?>
					<script type="text/javascript">
						alert('Error ADEDPROFILE11');
						window.location.href='editProfile.php?codvalue=<?php echo $_POST['ePcodUser']; ?>';
					</script>
					<?php 
				}
			}
			
			//If everything was OK...
			?>
			<script type="text/javascript">
				alert('El perfil <?php echo $editedProfileRow['name']; ?> ha sido actualizado con éxito.');
				window.location.href='editProfile.php?codvalue=<?php echo $_POST['ePcodUser'] ?>';
			</script>
			<?php
		
		/***************  Fin del bloque que valida el contenido enviado en el formulario  ***************/
		
		}
		/*
		//elseif...
		if(isset($_GET['hiddenfield'])){
			switch($_GET['hiddenfield']){
				case 'activate':
					if(getDBsinglefield('active', 'profiles', 'id', $_GET['codvalue'])){
						executeDBquery("UPDATE `profiles` SET `active`='0' WHERE `id`='".$_GET['codvalue']."'");
					}
					else{
						executeDBquery("UPDATE `profiles` SET `active`='1' WHERE `id`='".$_GET['codvalue']."'");
					}
					?>
					<script type="text/javascript">
						window.location.href='admCurProfiles.php';
					</script>
					<?php
				break;
				
				case 'delete':
					if(deleteDBrow('profiles', 'id', $_GET['codvalue'])){
						?>
						<script type="text/javascript">
							window.location.href='admCurProfiles.php';
						</script>
						<?php
					}
				break;
			}
		}
		*/
		else{
			$editedProfileRow = getDBrow('profiles', 'id', $_GET['codvalue']);
			echo "<h3>Editando el perfil \"" . $editedProfileRow['name'] . "\"</h3><hr class='long'><br>";
			echo '<fieldset id="auto2">';
				echo '<form id="editedProfile" name="editedProfile" method="post" action=editProfile.php">';
					if($_SESSION['logprofile'] == 'SuperAdmin'){
						echo "Identificador: <input type='text' name='ePcod' value=" . $editedProfileRow['id'] . " size='5' disabled /><br/>";
						if($editedProfileRow['name'] == 'SuperAdmin'){
							echo "Nombre: <input type='text' name='ePname' value='" . $editedProfileRow['name'] . "' size='20' disabled><br/>";
							if($editedProfileRow['active']){
								//echo "Activo: <input type='checkbox' name='ePactive' value='" . $editedProfileRow['active'] . "' disabled><br/>";
								echo "Activo: <input type='checkbox' name='ePactive' checked disabled><br/>";
							}
							else{
								echo "Activo: <input type='checkbox' name='ePactive' disabled><br/>";
							}
						}
						else{
							echo "Nombre: <input type='text' name='ePname' value='" . $editedProfileRow['name'] . "' size='20'><br/>";
							if($editedProfileRow['active']){
								//echo "Activo: <input type='checkbox' name='ePactive' value='" . $editedProfileRow['active'] . "' disabled><br/>";
								echo "Activo: <input type='checkbox' name='ePactive' checked><br/>";
							}
							else{
								echo "Activo: <input type='checkbox' name='ePactive'><br/>";
							}
						}
						echo "Creado: <input type='text' name='ePcreated' value='" . $editedProfileRow['created'] . "' size='20' disabled /><br/>";
						echo "Nº Usuarios: <input type='text' name='ePnumUsers' value=" . $editedProfileRow['numUsers'] . " size='5' disabled /><br/>";
					}
					elseif($_SESSION['logprofile'] == 'Administrador'){
						echo "Identificador: <input type='text' name='ePcod' value=" . $editedProfileRow['id'] . " size='5' disabled /><br/>";
						echo "Nombre: <input type='text' name='ePname' value='" . $editedProfileRow['name'] . "' size='20' disabled><br/>";
						if($editedProfileRow['active']){
							//echo "Activo: <input type='checkbox' name='ePactive' value='" . $editedProfileRow['active'] . "' disabled><br/>";
							echo "Activo: <input type='checkbox' name='ePactive' checked disabled><br/>";
						}
						else{
							echo "Activo: <input type='checkbox' name='ePactive' disabled><br/>";
						}
						//echo "Activo: <input type='checkbox' name='ePactive' value='" . $editedProfileRow['active'] . "' disabled><br/>";
						echo "Creado: <input type='text' name='ePcreated' value='" . $editedProfileRow['created'] . "' size='20' disabled /><br/>";
						echo "Nº Usuarios: <input type='text' name='ePnumUsers' value=" . $editedProfileRow['numUsers'] . " size='5' disabled /><br/>";
					}
					else{
						echo "No tiene permisos para ver esta página";
					}
		
					echo "<input type='hidden' name='ePcodUser' value=" . $editedProfileRow['id'] . ">";
					//echo "<a style='float:left' href='DelCurUser.php?codvalue=" . $editedProfileRow['id'] . "' onclick='return confirmUserDeletion();'>Borrar usuario (IMPLEMENTAR)</a>";
					echo "<input type='submit' name='eProfileSend' value='Guardar' />";
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
