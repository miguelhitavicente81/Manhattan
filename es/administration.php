<? session_start(); ?>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<link href='http://fonts.googleapis.com/css?family=Ubuntu+Mono:400,700,400italic,700italic|Ubuntu:300,400,500,700,300italic,400italic,500italic,700italic|Ubuntu+Condensed&
subset=latin,latin-ext' rel='stylesheet' type='text/css'>
	<title>Administración</title>
	<link href="../common/css/styles.css" rel="stylesheet" type="text/css">
	<script src="../common/js/functions.js" type="text/javascript"></script>
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
else{
	$lastUpdate = $_SESSION['lastupdate'];
	$curUpdate = date('Y-n-j H:i:s');
	$elapsedTime = (strtotime($curUpdate)-strtotime($lastUpdate));
	if($elapsedTime > $_SESSION['sessionexpiration']){
		?>
		<script type="text/javascript">
			window.location.href='endsession.php';
		</script>
		<?php
	}
	else{
		$_SESSION['lastupdate'] = $curUpdate;
		unset($lastUpdate);
		unset($curUpdate);
		unset($elapsedTime);
	}
	require_once './library/functions.php';
	?>
	<div id="topbar" class="azul">
		<a style="float:left;" href="#">Opciones</a>
		<a style="float:center">Conectado como: <?php echo $_SESSION['loglogin']; ?></a>
		<a href="endsession.php" style="float:right">Salir</a>
	</div>
	<?php 
	$myFile = 'administration';
	$userRow = getDBrow('users', 'login', $_SESSION['loglogin']);
	?>
	<div id="mainmenu">
	<ul class="navbar1">
		<?php 
		/*
		if(($userRow['profile'] == 'Administrador') || ($userRow['profile'] == 'SuperAdmin')){
			echo "<li><a href='home.php'>Inicio</a></li>";
			echo "<li><a href='administration.php' id='onlink'>Administrar</a></li>";
		}
		elseif($userRow['profile'] == 'Lector'){
			echo "<li><a href='home.php'>Inicio</a></li>";
		}
		
		else{
			echo "<li><a href='home.php'>Inicio</a></li>";
		}
		*/
		$mainKeysRow = getDBcompletecolumnID('key', 'mainNames', 'id');
		$mainNamesRow = getDBcompletecolumnID('esName', 'mainNames', 'id');
		$j = 0;
		foreach($mainKeysRow as $i){
			if(getDBsinglefield('active', $i, 'profile', $userRow['profile'])){
				if($myFile == $i){
					echo "<li><a href=$i.php id='onlink'>" . utf8_encode($mainNamesRow[$j]) . "</a></li>";
					$j++;
				}
				else{
					echo "<li><a href=$i.php>" . utf8_encode($mainNamesRow[$j]) . "</a></li>";
					$j++;
				}
			}
		}
		?>
	</ul>
	</div>

	<div class="workspace">
		<div class="leftbox">
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
						echo "<li><a href=administration/$level2File.php>" . $subLevelMenu . "</a></li>";
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
						echo "<li><a href=administration/$level3File.php>" . $subLevelMenu . "</a></li>";
					}
				}
			}
			?>
			</ul>
		</div>

		<div class="rightbox">
			<!-- 
		<p>
			<span id="leftmsg">En este menú puede ajustar la configuración de Web Central, así como añadir, eliminar o modificar elementos ya existentes</span>
		</p>
		<hr>
		-->
			<!-- 
		<div id="rightboxbar">
		<!-- <div class="rightboxbar"> -->
			<!-- <p>En este menú puede ajustar la configuración de Web Central, así como añadir, eliminar o modificar elementos ya existentes</p> -->
			<!-- <span>En este menú puede ajustar la configuración de Web Central, así como añadir, eliminar o modificar elementos ya existentes</span> -->
			<!-- <h3>En este menú puede ajustar la configuración de Web Central, así como añadir, eliminar o modificar elementos ya existentes</h3><hr> -->
			<!-- 
			< ?php 
			/*
			$codiTicket = $_GET['codvalue'];
			$iTicketRow = getDBrow('itickets', 'nticket', $codiTicket);
			echo $codiTicket.': '.utf8_encode($iTicketRow['tittle']);
			*/
			?>
			
		</div><!-- Fin del "rightboxbar" -- >
		-->
			<h3>En este menú puede ajustar la configuración de Web Central,
				así como añadir, eliminar o modificar elementos ya existentes</h3>
			<hr class="long">
			<p>Podría incluso tener enlaces a todo el árbol de directorio (Como
				sucede en la consola de Weblogic) o a "Secciones frecuentes"</p>
		</div>
		<!-- Fin del "rightbox" -->
	</div>
	<!-- Fin del "workspace" -->
	<?php
}//del "else" de $_SESSION.

?>

</body>
</html>
