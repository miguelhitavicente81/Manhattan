<?php session_start(); ?>
<html lang="es">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="">
	<meta name="author" content="">
	
	<title>Inicio</title>

	<!-- Custom styles for this template -->
	<link href="../common/css/styles.css" rel="stylesheet">
	<link href="../common/css/design.css" rel="stylesheet">

	<!-- Using the same favicon from perspectiva-alemania.com site -->
	<link rel="shortcut icon" href="http://www.perspectiva-alemania.com/wp-content/themes/perspectiva2013/bilder/favicon.png">
	<!-- Using the favicon for touch-devices shortcut -->
	<link rel="apple-touch-icon" href="../common/img/apple-touch-icon.png">


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
	else {
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

		<!-- Tabbed navbar inside Workspace -->
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
		</div> <!-- Tabbed navbar --> 

		<!-- Main view inside Workspace -->
		<div class="panel panel-default transparent">

			<!-- Panel inside Workspace -->
			<div class="panel-body sidebar-parent scrollable no-padding"> 

				
				<div class="col-sm-3 col-md-2 sidebar transparent">
					<ul class="nav nav-sidebar">
						<?php
							$namesTable = $myFile.'Names';
							$numCols = getDBnumcolumns($myFile);
							$myFileProfileRow = getDBrow($myFile, 'profile', $userRow['profile']);
							for($j=3;$j<$numCols;$j++) {
								$colNamej = getDBcolumnname($myFile, $j);
								if(($myFileProfileRow[$j] == 1) && ($subLevelMenu = getDBsinglefield2('esName', $namesTable, 'key', $colNamej, 'level', '2'))) {
									if(!getDBsinglefield2('esName', $namesTable, 'fatherKey', $colNamej, 'level', '3')){
										$level2File = getDBsinglefield('key', $namesTable, 'esName', $subLevelMenu);
										echo "<li><a href=home/$level2File.php>" . $subLevelMenu . "</a></li>";
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
										echo "<li><a href=home/$level3File.php>" . $subLevelMenu . "</a></li>";
									}
								}
							}
						?>
					</ul>
				</div> <!-- col-sm-3 col-md-2 sidebar -->
				

				<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">

					<h1 class="page-header">Noticias</span></h1>

					<?php 

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

					<h2 class="sub-header">Section title</h2>

					<div class="table-responsive">
						<table class="table table-striped">
							<thead>
								<tr>
									<th>#</th>
									<th>Header</th>
									<th>Header</th>
									<th>Header</th>
									<th>Header</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>1,001</td>
									<td>Lorem</td>
									<td>ipsum</td>
									<td>dolor</td>
									<td>sit</td>
								</tr>
								<tr>
									<td>1,002</td>
									<td>amet</td>
									<td>consectetur</td>
									<td>adipiscing</td>
									<td>elit</td>
								</tr>
								<tr>
									<td>1,003</td>
									<td>Integer</td>
									<td>nec</td>
									<td>odio</td>
									<td>Praesent</td>
								</tr>
								<tr>
									<td>1,003</td>
									<td>libero</td>
									<td>Sed</td>
									<td>cursus</td>
									<td>ante</td>
								</tr>
								<tr>
									<td>1,004</td>
									<td>dapibus</td>
									<td>diam</td>
									<td>Sed</td>
									<td>nisi</td>
								</tr>
								<tr>
									<td>1,005</td>
									<td>Nulla</td>
									<td>quis</td>
									<td>sem</td>
									<td>at</td>
								</tr>
								<tr>
									<td>1,006</td>
									<td>nibh</td>
									<td>elementum</td>
									<td>imperdiet</td>
									<td>Duis</td>
								</tr>
								<tr>
									<td>1,007</td>
									<td>sagittis</td>
									<td>ipsum</td>
									<td>Praesent</td>
									<td>mauris</td>
								</tr>
								<tr>
									<td>1,008</td>
									<td>Fusce</td>
									<td>nec</td>
									<td>tellus</td>
									<td>sed</td>
								</tr>
								<tr>
									<td>1,009</td>
									<td>augue</td>
									<td>semper</td>
									<td>porta</td>
									<td>Mauris</td>
								</tr>
								<tr>
									<td>1,010</td>
									<td>massa</td>
									<td>Vestibulum</td>
									<td>lacinia</td>
									<td>arcu</td>
								</tr>
								<tr>
									<td>1,011</td>
									<td>eget</td>
									<td>nulla</td>
									<td>Class</td>
									<td>aptent</td>
								</tr>
								<tr>
									<td>1,012</td>
									<td>taciti</td>
									<td>sociosqu</td>
									<td>ad</td>
									<td>litora</td>
								</tr>
								<tr>
									<td>1,013</td>
									<td>torquent</td>
									<td>per</td>
									<td>conubia</td>
									<td>nostra</td>
								</tr>
								<tr>
									<td>1,014</td>
									<td>per</td>
									<td>inceptos</td>
									<td>himenaeos</td>
									<td>Curabitur</td>
								</tr>
								<tr>
									<td>1,015</td>
									<td>sodales</td>
									<td>ligula</td>
									<td>in</td>
									<td>libero</td>
								</tr>
							</tbody>
						</table>
					</div> <!-- table-responsive -->
				</div> <!-- col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main -->
			
			</div> <!-- panel-body --> 
		</div> <!-- panel -->
	</div> <!-- workspace -->

	<?php

	} //del "else" de $_SESSION.

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
