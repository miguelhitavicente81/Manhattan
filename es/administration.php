<? session_start(); ?>
<html lang="es">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="">
	<meta name="author" content="David Alfonso Ginés Prieto, Miguel Hita Vicente y Miguel Ángel Melón Pérez">
	
	<title>Administración</title>

	<!-- Custom styles for this template -->
	<link href="../common/css/design.css" rel="stylesheet">
	<!-- <link href="../common/css/styles.css" rel="stylesheet">
	<link href="../common/css/docs.css" rel="stylesheet"> -->

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
		<div id="header" class="navbar navbar-default navbar-fixed-top" role="navigation" id="fixed-top-bar">
			<div id="top_line" class="top-page-color"></div>
			<div class="container-fluid">
				<div class="navbar-header">
					<a href="http://www.perspectiva-alemania.com/" title="Perspectiva Alemania">
						<img src="../common/img/logo.png" alt="Perspectiva Alemania">
					</a>
				</div>
				<!-- <div class="navbar-collapse collapse"> -->
					<div class="nav navbar-nav navbar-right">
						<li class="dropdown">
							<button type="button" class="navbar-toggle always-visible" data-toggle="dropdown">
								<span class="icon-bar"></span>
								<span class="icon-bar"></span>
								<span class="icon-bar"></span>
							</button>
							<ul class="dropdown-menu">
								<li class="dropdown-header">Conectado como: <?php echo $_SESSION['loglogin']; ?></li>
								<li class="divider"></li>
								<li><a href="administration.php">Configuración</a></li>
								<li><a href="#">Abrir incidencia</a></li>
								<li><a href="#">Revisar Curriculum</a></li>
								<li class="divider"></li>
								<li><a data-toggle="modal" data-target="#exitRequest" href="#exitRequest">Salir</a></li>
							</ul>
						</li>
					</div>
				<!-- </div><!--/.nav-collapse -->
			</div><!--/.container-fluid -->
		</div>	<!--/Static navbar -->


		<!-- exitRequest Modal -->
		<div id="exitRequest" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="exitRequestLabel" aria-hidden="true">
			<div class="modal-dialog">
				<form class="modal-content" action="endsession.php">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h4 class="modal-title" id="exitRequestLabel">Cerrar sesión</h4>
					</div>
					<div class="modal-body">
						¿Estás seguro de que quieres salir?
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
						<button type="submit" class="btn btn-primary">Sí, cerrar sesión</button>
					</div>
				</form>
			</div>
		</div>


		<?php 
		$myFile = 'administration';
		$userRow = getDBrow('users', 'login', $_SESSION['loglogin']);
		?>


		<div id="main-content" class="container bs-docs-container">
			<div class="row">
				<div class="col-md-3">
					<div id="sidebar-navigation-list" class="bs-sidebar hidden-print affix-top" role="complementary">
						<ul class="nav bs-sidenav">
							<?php 
							$mainKeysRow = getDBcompletecolumnID('key', 'mainNames', 'id');
							$mainNamesRow = getDBcompletecolumnID('esName', 'mainNames', 'id');
							$j = 0;
							foreach($mainKeysRow as $i){
								if(getDBsinglefield('active', $i, 'profile', $userRow['profile'])){
									if($myFile == $i){
										echo "<li class='active'><a href=$i.php id='onlink'>" . $mainNamesRow[$j] . "</a>";
										$j++;

										echo "<ul class='nav'>";

										$namesTable = $myFile.'Names';
										$numCols = getDBnumcolumns($myFile);
										$myFileProfileRow = getDBrow($myFile, 'profile', $userRow['profile']);
										for($k=3;$k<$numCols;$k++) {
											$colNamej = getDBcolumnname($myFile, $k);
											if(($myFileProfileRow[$k] == 1) && ($subLevelMenu = getDBsinglefield2('esName', $namesTable, 'key', $colNamej, 'level', '2'))) {
												if(!getDBsinglefield2('esName', $namesTable, 'fatherKey', $colNamej, 'level', '3')){
													$level2File = getDBsinglefield('key', $namesTable, 'esName', $subLevelMenu);
													echo "<li><span class='badge'>$k</span><a href=administration/$level2File.php>" . $subLevelMenu . "</a></li>";
												}
												else{
													$arrayKeys = array();
													$arrayKeys = getDBcolumnvalue('key', $namesTable, 'fatherKey', $colNamej);
													$checkFinished = 0;
													$l = 1;
													foreach($arrayKeys as $key){
														if($checkFinished == 0){
															if(($myFileProfileRow[$j+$l] == 1) && (getDBsinglefield($key, $myFile, 'profile', $userRow['profile']))){
																$level3File = $key;
																$checkFinished = 1;
															}
															else{
																$l++;
															}
														}
													}
													echo "<li><span class='badge'>$k</span><a href=administration/$level3File.php>" . $subLevelMenu . "</a></li>";
												}
											}
										}

										echo "</ul> <!-- class='nav' -->";
										echo "</li> <!-- class='active' -->";

									}

									else{
										echo "<li><a href=$i.php>" . $mainNamesRow[$j] . "</a></li>";
										$j++;
									}
								}
							}
							?>
						</ul> <!-- class="nav bs-sidenav" -->
					</div> <!-- id="sidebar-navigation-list"  -->
				</div> <!-- col-md-3 -->

				<div class="col-md-9 scrollable" role="main"> 
					<div class="bs-docs-section">

						<h1 class="page-header">Administración &amp; Configuración</span></h1>
							<p class="lead">
							En este menú puede ajustar la configuración de Web Central,
							así como añadir, eliminar o modificar elementos ya existentes</p>
							<hr class="long">
							<p>Podría incluso tener enlaces a todo el árbol de directorio (Como
							sucede en la consola de <code>Weblogic</code>) o a "Secciones frecuentes"</p>

						<h2 class="page-header">Administración &amp; Configuración</span></h2>
							<p class="lead">
							En este menú puede ajustar la configuración de Web Central,
							así como añadir, eliminar o modificar elementos ya existentes</p>
							<hr class="long">
							<p>Podría incluso tener enlaces a todo el árbol de directorio (Como
							sucede en la consola de <code>Weblogic</code>) o a "Secciones frecuentes"</p>

						<h3 class="page-header">Administración &amp; Configuración</span></h3>
							<p class="lead">
							En este menú puede ajustar la configuración de Web Central,
							así como añadir, eliminar o modificar elementos ya existentes</p>
							<hr class="long">
							<p>Podría incluso tener enlaces a todo el árbol de directorio (Como
							sucede en la consola de <code>Weblogic</code>) o a "Secciones frecuentes"</p>

						<h4 class="page-header">Administración &amp; Configuración</span></h4>
							<p class="lead">
							En este menú puede ajustar la configuración de Web Central,
							así como añadir, eliminar o modificar elementos ya existentes</p>
							<hr class="long">
							<p>Podría incluso tener enlaces a todo el árbol de directorio (Como
							sucede en la consola de <code>Weblogic</code>) o a "Secciones frecuentes"</p>

						<h5 class="page-header">Administración &amp; Configuración</span></h5>
							<p class="lead">
							En este menú puede ajustar la configuración de Web Central,
							así como añadir, eliminar o modificar elementos ya existentes</p>
							<hr class="long">
							<p>Podría incluso tener enlaces a todo el árbol de directorio (Como
							sucede en la consola de <code>Weblogic</code>) o a "Secciones frecuentes"</p>

						<h6 class="page-header">Administración &amp; Configuración</span></h6>
							<p class="lead">
							En este menú puede ajustar la configuración de Web Central,
							así como añadir, eliminar o modificar elementos ya existentes</p>
							<hr class="long">
							<p>Podría incluso tener enlaces a todo el árbol de directorio (Como
							sucede en la consola de <code>Weblogic</code>) o a "Secciones frecuentes"</p>
						</div> <!-- bs-docs-section -->
					</div> <!-- col-md-9 scrollable role=main -->
				</div> <!-- row -->
			</div> <!-- class="container bs-docs-container" -->
	

	<?php

		} //del "else" de $_SESSION.

	?>


<!-- Footer bar & info
	================================================== -->
	<div id="footer" class="hidden-xs hidden-sm" >
		<div class="container">
			<p class="text-muted">&copy; Perspectiva Alemania, S.L.</p>
		</div>
	</div>


<!-- Scripts. Placed at the end of the document so the pages load faster.
	================================================== -->
	<!-- Bootstrap core JavaScript -->
	<script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
	<script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>

	<!-- Site own functions -->
	<script src="../common/js/functions.js"></script>
	<script src="../common/js/application.js"></script>
	<script src="../common/js/docs.min.js"></script>

</body>
</html>
