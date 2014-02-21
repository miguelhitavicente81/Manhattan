<? session_start(); ?>
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
							$strippedString = stripAccents($_POST['newLLkey']);
							/*
							if(!executeDBquery("INSERT INTO `languageLevel` (`id`, `key`, `enName`, `esName`, `deName`) VALUES
							(NULL, '".utf8_decode($_POST['newLLkey'])."', '".utf8_decode($_POST['newLLenName'])."', '".utf8_decode($_POST['newLLesName'])."', '".utf8_decode($_POST['newLLdeName'])."')")){
							*/
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
							$strippedString = stripAccents($_POST['newLangkey']);
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
						if((empty($_POST['newStudytypeskey'])) || strpos(trim($_POST['newStudytypeskey']), " ") > 0 || (empty($_POST['newStudyTypesenName'])) || (empty($_POST['newStudyTypesesName'])) || (empty($_POST['newStudyTypesdeName']))){
							?>
							<script type="text/javascript">
								alert('Todos los campos deben estar rellenos, y la Clave no puede contener espacios.');
								window.location.href='admGenOptions.php';
							</script>
							<?php 
						}
						else{
							$strippedString = stripAccents($_POST['newStudytypeskey']);
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
				
				
				
			<h3>Conjunto de configuraciones generales</h3>
			<hr class="long">
			<table class="tabla1">
				<tr>
					<th colspan="6">Nivel de Idiomas</th>
				</tr>
				<tr>
					<th>Id</th>
					<th>Clave</th>
					<th>Nombre (Ing)</th>
					<th>Nombre (Esp)</th>
					<th>Nombre (Ale)</th>
					<th>Acción</th>
				</tr>
				<?php
				$langLevelNumRows = getDBrowsnumber('languageLevel');
				for($i=1;$i<=$langLevelNumRows;$i++){
					$langLevelRow = getDBrow('languageLevel', 'id', $i);
					echo "<tr>";
					echo "<td>" . $langLevelRow['id'] . "</td>";
					echo "<td>" . utf8_encode($langLevelRow['key']) . "</td>";
					echo "<td>" . utf8_encode($langLevelRow['enName']) . "</td>";
					echo "<td>" . utf8_encode($langLevelRow['esName']) . "</td>";
					echo "<td>" . utf8_encode($langLevelRow['deName']) . "</td>";
					//echo "<td><a href='EditCurProfile.php?codvalue=" . $profileRow['cod_profile'] . "'>" . utf8_encode($profileRow['largeName']) . "</a></td>";
					/*echo "<td><a href=''>Borrar</a></td>";*/
					//echo '<td><a href="javascript:confirmLangLevelDeletion().php">Borrar</a></td>'; SACA FUERA DE LA TABLA EL ENLACE
					echo "<td>Borrar</td>";
					/*
					?>
					<td><a href="javascript:confirmProjectDeletion('DelCurProject.php', <?php echo $projectrow['codProject'] ?>)">Delete</a></td>
					<?php
					*/ 
					echo "</tr>";
					/*
					?>
					<td><a href=''>Borrar</a></td>
					</tr>
					<?php
					*/
				}
				?>
			</table>
			<fieldset id="auto1">
				<legend>Nuevo Nivel de idiomas</legend>
				<form name="newLangLevel" action="admGenOptions.php" method="post">
					<input type="text" name="newLLkey" size="6" placeholder="Clave" />
					<input type="text" name="newLLenName" size="15" placeholder="Nombre Inglés" />
					<input type="text" name="newLLesName" size="15" placeholder="Nombre Español" />
					<input type="text" name="newLLdeName" size="15" placeholder="Nombre Alemán" />
					<input type="hidden" value="hNewLLsubmit" name="hiddenfield">
					<input type="submit" value="Incluir" name="newLLsubmit">
				</form>
			</fieldset>
			
			
			
			<table class="tabla1">
				<tr>
					<th colspan="6">Idiomas</th>
				</tr>
				<tr>
					<th>Id</th>
					<th>Clave</th>
					<th>Nombre (Ing)</th>
					<th>Nombre (Esp)</th>
					<th>Nombre (Ale)</th>
					<th>Acción</th>
				</tr>
				<?php 
				$langNumRows = getDBrowsnumber('languages');
				for($i=1;$i<=$langNumRows;$i++){
					$langRow = getDBrow('languages', 'id', $i);
					echo "<tr>";
					echo "<td>" . $langRow['id'] . "</td>";
					echo "<td>" . utf8_encode($langRow['key']) . "</td>";
					echo "<td>" . utf8_encode($langRow['enName']) . "</td>";
					echo "<td>" . utf8_encode($langRow['esName']) . "</td>";
					echo "<td>" . utf8_encode($langRow['deName']) . "</td>";
					echo "<td><a href=''>Borrar</a></td>";
				}
				?>
			</table>
			
			<fieldset id="auto1">
				<legend>Idiomas</legend>
				<form name="newLanguage" action="admGenOptions.php" method="post">
					<input type="text" name="newLangkey" size="6" placeholder="Clave" />
					<input type="text" name="newLangenName" size="15" placeholder="Nombre Inglés" />
					<input type="text" name="newLangesName" size="15" placeholder="Nombre Español" />
					<input type="text" name="newLangdeName" size="15" placeholder="Nombre Alemán" />
					<input type="hidden" value="hNewLangsubmit" name="hiddenfield">
					<input type="submit" value="Incluir" name="newLangsubmit">
				</form>
			</fieldset>
			
			
			
			<table class="tabla1">
				<tr>
					<th colspan="6">Tipo de Estudios</th>
				</tr>
				<tr>
					<th>Id</th>
					<th>Clave</th>
					<th>Nombre (Ing)</th>
					<th>Nombre (Esp)</th>
					<th>Nombre (Ale)</th>
					<th>Acción</th>
				</tr>
				<?php 
				$studyTypesNumRows = getDBrowsnumber('studyTypes');
				for($i=1;$i<=$studyTypesNumRows;$i++){
					$studyTypesRow = getDBrow('studyTypes', 'id', $i);
					echo "<tr>";
					echo "<td>" . $studyTypesRow['id'] . "</td>";
					echo "<td>" . utf8_encode($studyTypesRow['key']) . "</td>";
					echo "<td>" . utf8_encode($studyTypesRow['enName']) . "</td>";
					echo "<td>" . utf8_encode($studyTypesRow['esName']) . "</td>";
					echo "<td>" . utf8_encode($studyTypesRow['deName']) . "</td>";
					echo "<td><a href=''>Borrar</a></td>";
				}
				?>
			</table>
			
			<fieldset id="auto1">
				<legend>Tipo de Estudios</legend>
				<form name="newStudyTypes" action="admGenOptions.php" method="post">
					<input type="text" name="newStudyTypeskey" size="6" placeholder="Clave" />
					<input type="text" name="newStudyTypesenName" size="15" placeholder="Nombre Inglés" />
					<input type="text" name="newStudyTypesesName" size="15" placeholder="Nombre Español" />
					<input type="text" name="newStudyTypesdeName" size="15" placeholder="Nombre Alemán" />
					<input type="hidden" value="hNewStudyTypessubmit" name="hiddenfield">
					<input type="submit" value="Incluir" name="newStudyTypessubmit">
				</form>
			</fieldset>
			
			
			<!-- EN ESTA TABLA DE OPCIONES GENERALES TAMBIÉN HABRÁ QUE INCLUIR ALGUNOS DATOS MÁS, COMO... 
					Tiempo de antelación con el que se avisará al usuario de que su contraseña va a expirar
					-->
			<?php 
			//Ñapa para que no vean la tabla siguiente...
			if($_SESSION['loglogin'] == 'super'){
			?>
			<table class="tabla1">
				<tr>
					<th>Id</th>
					<th>Nombre</th>
					<th>Comentario</th>
					<th>Valor</th>
				</tr>
				<?php 
				$oOptionsNumRows = getDBrowsnumber('otherOptions');
				for ($i=1; $i<=$oOptionsNumRows; $i++){
					$oOptionsRow = getDBrow('otherOptions', 'id', $i);
					echo "<tr>";
					//echo "<td><a href='EditCurUser.php?codvalue=" . $userrow[0] . "'>" . $userrow[1] . "</a></td>";
					echo "<td>" . $i . "</td>";
					echo "<td>" . utf8_encode($oOptionsRow['name']) . "</td>";
					echo "<td>" . utf8_encode($oOptionsRow['comment']) . "</td>";
					echo "<td>" . $oOptionsRow['value'] . "</td>";
					echo "</tr>";
				}
				?>
			</table>
			
			<p>
				EN LA TABLA SUPERIOR DEBO INCLUIR:<br/>
				- QUE AL PINCHAR SOBRE CADA VALOR SEA POSIBLE MODIFICARLO, RESTRINGIENDO LOS DATOS INTRODUCIDOS (SI ES UN NÚMERO NO ME DEBE DEJAR INTRODUCIR LETRAS)<br/>
			</p>
			<?php 
			}
			?>
		</div><!-- Fin del "rightbox" -->
	</div><!-- Fin del "workspace" -->
	<?php
}//del "else" de $_SESSION.

?>

</body>
</html>
