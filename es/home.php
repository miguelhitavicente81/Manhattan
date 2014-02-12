<? session_start(); ?>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

	<meta name="viewport" content="width=device-width, initial-scale=1">

	<!-- Using the same favicon from perspectiva-alemania.com site -->
	<link rel="shortcut icon" href="http://www.perspectiva-alemania.com/wp-content/themes/perspectiva2013/bilder/favicon.png">
	<!-- Using the favicon for touch-devices shortcut -->
	<link rel="apple-touch-icon" href="../common/img/apple-touch-icon.png">

	<title>Inicio</title>

	<!-- Custom styles for this template -->
	<link href="../common/css/styles.css" rel="stylesheet">
	<link href="../common/css/design.css" rel="stylesheet">

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


		 
		<!-- /* En $myFile guardo el nombre del fichero php que WC está tratando en ese instante. Necesario para mostrar
		* el resto de menús de nivel 1 cuando navegue por ellos, y saber cuál es el activo (id='onlink')
		*/ -->
		<?php
		$myFile = 'home';
		$userRow = getDBrow('users', 'login', $_SESSION['loglogin']);
		?>




<div class="workspace">

		<div id="navbar" role="navigation">
			<ul class="nav nav-tabs">
				<?php 
				$mainKeysRow = getDBcompletecolumnID('key', 'mainNames', 'id');
				$mainNamesRow = getDBcompletecolumnID('esName', 'mainNames', 'id');
				$j = 0;
				foreach($mainKeysRow as $i){
					if(getDBsinglefield('active', $i, 'profile', $userRow['profile'])){
						if($myFile == $i){
							echo "<li class='active'><a href=$i.php>" . $mainNamesRow[$j] . "</a></li>";
							$j++;
						}
						else{
							echo "<li><a href=$i.php>" . $mainNamesRow[$j] . "</a></li>";
							$j++;
						}
					}
				}
				?>
			</ul>
		</div>


		<div class="panel panel-default" id="mainContent">
			<div class="panel-body" id="mainContent">
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

					if(($userRow['profile'] == 'Administrador') || ($userRow['profile'] == 'SuperAdmin')){
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
				</div> <!-- "rightbox" -->
			</div> <!-- "panel-body" -->
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
	<script src="../common/js/application.js"></script>

</body>
</html>
