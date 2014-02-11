<? session_start(); ?>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<title>Inicio</title>

	<!-- Custom styles for this template -->
	<link href="../common/css/styles.css" rel="stylesheet">
	<link href="../common/css/design.css" rel="stylesheet">

	<script src="../common/js/functions.js" type="text/javascript"></script>
	<!-- <script type="text/javascript" src="../js/jquery-1.10.1.min.js"></script> ONLY IF USED jQuery -->
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


		<!-- Static navbar -->
		<div class="navbar navbar-default navbar-fixed-top" role="navigation">
			<div class="top-page-color"></div>
			<div class="container-fluid">
				<div class="navbar-header">
					<a href="http://www.perspectiva-alemania.com/" title="Perspectiva Alemania">
						<img src="../common/img/logo.png" alt="Perspectiva Alemania">
					</a>
					<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
				</div>
				<div class="navbar-collapse collapse">
					<ul class="nav navbar-nav navbar-right">
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown">Menú <b class="caret"></b></a>
							<ul class="dropdown-menu">
								<li class="dropdown-header">Conectado como: <?php echo $_SESSION['loglogin']; ?></li>
								<li class="divider"></li>
								<li><a href="#">Configuración</a></li>
								<li><a href="#">Abrir incidencia</a></li>
								<li><a href="#">Revisar Curriculum</a></li>
								<li class="divider"></li>
								<li><a href="endsession.php">Salir</a></li>
							</ul>
						</li>
					</ul>
				</div><!--/.nav-collapse -->
			</div><!--/.container-fluid -->
		</div>	


		<?php 

/* En $myFile guardo el nombre del fichero php que WC está tratando en ese instante. Necesario para mostrar
* el resto de menús de nivel 1 cuando navegue por ellos, y saber cuál es el activo (id='onlink')
*/
$myFile = 'home';
$userRow = getDBrow('users', 'login', $_SESSION['loglogin']);
?>
<div id="mainmenu">
	<ul class="navbar1">
		<?php 
/*
if(($userRow['profile'] == 'Administrador') || ($userRow['profile'] == 'SuperAdmin')){
echo "<li><a href='home.php' id='onlink'>Inicio</a></li>";
echo "<li><a href='administration.php'>Administrar</a></li>";
}
elseif($userRow['profile'] == 'Lector'){
echo "<li><a href='home.php' id='onlink'>Inicio</a></li>";
}

else{
echo "<li><a href='home.php' id='onlink'>Inicio</a></li>";
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
		<div class="css-treeview">
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
//echo "<li><a href=home/$level2File.php>" . $subLevelMenu . "</a></li>";
							echo "<li><a href=home/$level2File.php>" . utf8_encode($subLevelMenu) . "</a></li>";
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
//echo "<li><a href=home/$level3File.php>" . $subLevelMenu . "</a></li>";
							echo "<li><a href=home/$level3File.php>" . utf8_encode($subLevelMenu) . "</a></li>";
						}
					}
				}
				?>
			</ul>
		</div>
	</div> <!-- "leftbox" -->

	<div class="rightbox">

		<!-- SI ES LA PRIMERA VEZ QUE SE CONECTA UN CANDIDATO, O LE VUELVEN A DAR ACCESO PARA RELLENAR OTRO CV, LE MANDAMOS DIRECTAMENTE AL FORMULARIO -->

		<?php 
		echo "<p><span id='leftmsg'>Noticias</span></p><hr>";
//if($userRow['profile'] == 'Administrador'){
		if(($userRow['profile'] == 'Administrador') || ($userRow['profile'] == 'SuperAdmin')){
//echo 'Noticias:<br>';
//echo 'Existen';
			if((getDBrowsnumber('cVitaes') == 0) || ($numPendingCVs = count($cvIDs = getDBcolumnvalue('id', 'cVitaes', 'cvStatus', 'pending')))){
				echo "No existen CVs por clasificar.";
			}
			else{
				echo "Existen <a href=./home/pendingCVs.php>" . $numPendingCVs . " </a> CVs por clasificar.";
			}
		}
		elseif($userRow['profile'] == 'Lector'){
			echo "-- DEFINIR SE SE QUIERE O NO QUE UN PERFIL \"Lector\" PUEDA REVISAR CVs --";
			if((getDBrowsnumber('cVitaes') == 0) || ($numPendingCVs = count($cvIDs = getDBcolumnvalue('id', 'cVitaes', 'cvStatus', 'pending')))){
				echo "No existen CVs por clasificar.";
			}
			else{
				echo "Existen <a href=./home/pendingCVs.php>" . $numPendingCVs . " </a> CVs por clasificar.";
			}
		}
		else{
			echo "-- SE ENTIENDE QUE SI UN \"\" TIENE ACCESO A LA ZONA PRIVADA DE LA PAGINA ES PORQUE SE LE HA CONCEDIDO PARA RELLENAR OTRO CV --";
			include 'blankform.php';

		}
		?>

	</div>
</div>
<?php

}//del "else" de $_SESSION.

?>


<!-- Footer bar & info
	================================================== -->
	<div id="footer">
		<div class="container">
			<p class="text-muted">&copy; Perspectiva Alemania, S.L.</p>
		</div>
	</div>


<!-- Scripts. Placed at the end of the document so the pages load faster.
	================================================== -->
	<!-- Bootstrap core JavaScript -->
	<script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
	<script src="//netdna.bootstrapcdn.com/bootstrap/3.1.0/js/bootstrap.min.js"></script>

	<!-- Site own functions -->
	<script src="../common/js/functions.js"></script>

	<!-- Do you wanna use tooltips or validation instead of? -->
	<!-- <script src="../common/js/application.js"></script> -->

</body>
</html>
