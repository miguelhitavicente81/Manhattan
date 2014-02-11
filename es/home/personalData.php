<? session_start(); ?>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<link href='http://fonts.googleapis.com/css?family=Ubuntu+Mono:400,700,400italic,700italic|Ubuntu:300,400,500,700,300italic,400italic,500italic,700italic|Ubuntu+Condensed&
subset=latin,latin-ext' rel='stylesheet' type='text/css'>
	<title>Inicio</title>
	<link href="../../common/css/styles.css" rel="stylesheet" type="text/css">
	<script src="../../common/js/functions.js" type="text/javascript"></script>
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
	$myFile = 'home';
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
			//DEBO HACER UNA FUNCION JAVASCRIPT checkPassword EN LUGAR DE ESTO, ASI EVITARE HACER CAMBIOS EN VARIOS SITIOS SI CAMBIAN LAS CONDICIONES
			if(isset($_POST['pdChange'])){
				if (isset($_POST['pdpass1']) && !empty($_POST['pdpass1']) && isset($_POST['pdpass2']) && !empty($_POST['pdpass2']) && ($_POST['pdpass1'] == $_POST['pdpass2'])){
					/*
					if(getDBsinglefield('pass', 'users', 'login', $_POST['pdpass1'])){
						?>
						<script type="text/javascript">
							alert('La nueva contraseña debe ser diferente a la última');
							window.location.href='personalData.php';
						</script>
						<?php
					}
					elseif($_POST['pdpass1'] != $_POST['pdpass2']){
						?>
						<script type="text/javascript">
							alert('Las contraseñas introducidas no coinciden');
							window.location.href='personalData.php';
						</script>
						<?php
					}
					elseif(strlen($_POST['pdpass1']) < 6 || strpos(trim($_POST['pdpass2']), " ") > 0){
						?>
						<script type="text/javascript">
							alert('La contraseña no puede contener espacios y debe tener, al menos, 6 caracteres');
							window.location.href='personalData.php';
						</script>
						<?php
					}
					*/
					if(checkPassword('pdpass1', $foundError)){
						executeDBquery("UPDATE `users` SET `pass`='".$_POST['pdpass1']."' WHERE `login`='".$userRow['login']."'");
					}
					else{
						?>
						<script type="text/javascript">
							alert('<?php echo $foundError; ?>');
							window.location.href='personalData.php';
						</script>
						<?php
					}
				}
			}
			?>
			<div id="data">
				<!-- EL BUZON DE SUGERENCIAS PUEDE ABRIR UN FORMULARIO QUE PERMITA ENVIAR UN CORREO AL ADMINISTRADOR -->
				<h3>Mis datos</h3>
				<hr class="long">
				<br/>
				<div id="stylized" class="myform">
					<form id="form" name="form" action="personalData.php" method="post" onsubmit="return equalPassword(pdpass1, pdpass2)">
						<h1>Cambio de contraseña</h1>
						<label>Nueva contraseña</label><input type="password" name="pdpass1" id="pdpass1" size="35"><br>
						<label>Repita contraseña</label><input type="password" name="pdpass2" id="pdpass2" size="35"><br>
						<input type="submit" value="Cambiar" name="pdChange"><br/>
						<br/>
					</form>
				</div> <!-- del "stylized" -->
				<br>
			</div>
		</div><!-- Fin del "rightbox" -->
	</div><!-- Fin del "workspace" -->
	<?php
}//del "else" de $_SESSION.

?>

</body>
</html>
