<? session_start(); ?>
<!-- ESTE FICHERO NO SE USA, AL MENOS DE MOMENTO, YA QUE NO SE PERMITIRA CREAR PERFILES: LOS QUE SON SERÁN LOS QUE HAYA -->
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
		<h3>Crear Nuevo Perfil</h3>
		<?php if(getDBsinglefield('AdmCurProfiles', 'administration', 'profile', $_SESSION['logprofile'])) ?>
			<a id="rightlink" href="AdmCurProfiles.php" style="position: relative;">Perfiles actuales</a>
		<hr class="long">
		<br>
		<p>Cuando demos de alta un nuevo perfil será cuando elijamos sus permisos. Al asignarle un proyecto lo estoy activando en ese proyecto.</p>
		<div id="stylized" class="myform">
			<form id="form" name="form" action="../library/ValidateForms.php" method="post">
				<h1>Inserte los datos</h1><br>
				<label>Nivel perfil:</label><select name="chooselevel">
					<option value="1">-- 1 --</option>
					<option value="2">-- 2 --</option>
					<option value="3">-- 3 --</option>
					<option value="4">-- 4 --</option>
					<option selected value="5">-- 5 --</option>
				</select>
				<label>Clave perfil:</label><input type="text" name="newname" size="30"><br>
				<label>Nombre perfil:</label><input type="text" name="newlarge" size="30"><br>
				<label>Comentario:</label><input type="text" name="newcomment" size="30"><br>
				<?php 
				//if(getDBrowsnumber('projects') == 0 || getDBactiverowsnumber('projects') == 0){
				//if(getDBactiverowsnumber('projects') < 1){
				if(getDBrowsnumber('projects') < 1){
					echo "<label>Proyecto asociado:</label><input type='text' name='chooseproject' size='30' value='No existen proyectos' disabled='disabled'><br>";
				}
				else{
					echo "<label>Proyecto asociado:</label>";
					echo "<select name='chooseproject'>";
						echo "<option value='0'>-- Seleccionar --</option>";
						$profilesNumCols = getDBnumcolumns('profiles');
						$projectNames = getDBcompletecolumnID('name', 'projects', 'codProject');
						$projectLargeNames = getDBcompletecolumnID('largeName', 'projects', 'codProject');
						$k = 0;
						for($i=7;$i<$profilesNumCols;$i++){
							echo "<option value=" . $projectNames[$k] . ">" . utf8_encode($projectLargeNames[$k]) . "</option>";
							$k++;
						}
					echo "</select><br>";
				}
				?>
				<input type="hidden" value="h_admnewprofile" name="hiddenfield">
				<input type="submit" value="Crear" name="createprofile"><br>
			</form>
		</div><!-- Fin del "stylized" -->
		</div><!-- Fin del "rightbox" -->
	</div><!-- Fin del "workspace" -->
	<?php
}//del "else" de $_SESSION.

?>

</body>
</html>
