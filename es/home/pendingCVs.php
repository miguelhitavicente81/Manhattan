<?php session_start(); ?>
<html lang="es">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="">
	<meta name="author" content="David Alfonso Ginés Prieto, Miguel Hita Vicente y Miguel Ángel Melón Pérez">
	
	<title>CVs Pendientes</title>

	<!-- Custom styles for this template -->
	<link href="../../common/css/design.css" rel="stylesheet">

	<!-- Using the same favicon from perspectiva-alemania.com site -->
	<link rel="shortcut icon" href="http://www.perspectiva-alemania.com/wp-content/themes/perspectiva2013/bilder/favicon.png">
	<!-- Using the favicon for touch-devices shortcut -->
	<link rel="apple-touch-icon" href="../../common/img/apple-touch-icon.png">




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
	else {
		$lastUpdate = $_SESSION['lastupdate'];
		$curUpdate = date('Y-m-d H:i:s');
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


		<!-- Static navbar -->
		<div id="header" class="navbar navbar-default navbar-fixed-top" role="navigation" id="fixed-top-bar">
			<div id="top_line" class="top-page-color"></div>
			<div class="container-fluid">
				<div class="navbar-header">
					<a href="http://www.perspectiva-alemania.com/" title="Perspectiva Alemania">
						<img src="../../common/img/logo.png" alt="Perspectiva Alemania">
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
							<li><a href="../home/personalData.php">Configuración personal</a></li>
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
				<form class="modal-content" action="../endsession.php">
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



		<!-- /* En $myFile guardo el nombre del fichero php que WC está tratando en ese instante. Necesario para mostrar
		* el resto de menús de nivel 1 cuando navegue por ellos, y saber cuál es el activo (id='onlink')
		*/ -->
		<?php
			$myFile = 'home';
			$userRow = getDBrow('users', 'login', $_SESSION['loglogin']);

			$pendingCVs = getPendingCVs();

			if (isset($_POST['eCurCVsend'])) {
				$updateCVQuery = "	UPDATE `cvitaes` 
									SET `nie` = '".htmlentities($_POST['eCCVnie'], ENT_QUOTES, 'UTF-8')."',
										`cvStatus` = 'checked',
										`name` = '".htmlentities($_POST['eCCVname'], ENT_QUOTES, 'UTF-8')."',
										`surname` = '".htmlentities($_POST['eCCVsurname'], ENT_QUOTES, 'UTF-8')."',
										`birthdate` = '".htmlentities($_POST['eCCVbirthdate'], ENT_QUOTES, 'UTF-8')."',
										`nationalities` = '".htmlentities($_POST['eCCVnationalities'], ENT_QUOTES, 'UTF-8')."',
										`sex` = '".htmlentities($_POST['eCCVsex'], ENT_QUOTES, 'UTF-8')."',
										`addrType` = '".htmlentities($_POST['eCCVaddrtype'], ENT_QUOTES, 'UTF-8')."',
										`addrName` = '".htmlentities($_POST['eCCVaddrName'], ENT_QUOTES, 'UTF-8')."',
										`addrNum` = '".htmlentities($_POST['eCCVaddrNum'], ENT_QUOTES, 'UTF-8')."',
										`portal` = '".htmlentities($_POST['eCCVaddrPortal'], ENT_QUOTES, 'UTF-8')."',
										`stair` = '".htmlentities($_POST['eCCVaddrStair'], ENT_QUOTES, 'UTF-8')."',
										`addrFloor` = '".htmlentities($_POST['eCCVaddrFloor'], ENT_QUOTES, 'UTF-8')."',
										`addrDoor` = '".htmlentities($_POST['eCCVaddrDoor'], ENT_QUOTES, 'UTF-8')."',
										`phone` = '".htmlentities($_POST['eCCVphone'], ENT_QUOTES, 'UTF-8')."',
										`postalCode` = '".htmlentities($_POST['eCCVpostal'], ENT_QUOTES, 'UTF-8')."',
										`country` = '".htmlentities($_POST['eCCVcountry'], ENT_QUOTES, 'UTF-8')."',
										`province` = '".htmlentities($_POST['eCCVprovince'], ENT_QUOTES, 'UTF-8')."',
										`city` = '".htmlentities($_POST['eCCVcity'], ENT_QUOTES, 'UTF-8')."',
										`mobile` = '".htmlentities($_POST['eCCVmobile'], ENT_QUOTES, 'UTF-8')."',
										`mail` = '".htmlentities($_POST['eCCVmail'], ENT_QUOTES, 'UTF-8')."',
										`drivingType` = '".htmlentities($_POST['eCCVdrivingType'], ENT_QUOTES, 'UTF-8')."',
										`drivingDate` = '".htmlentities($_POST['eCCVdrivingDate'], ENT_QUOTES, 'UTF-8')."',
										`marital` = '".htmlentities($_POST['eCCVmarital'], ENT_QUOTES, 'UTF-8')."',
										`sons` = '".htmlentities($_POST['eCCVsons'], ENT_QUOTES, 'UTF-8')."',
										`language` = '".htmlentities($_POST['eCCVlanguages'], ENT_QUOTES, 'UTF-8')."',
										`langLevel` = '".htmlentities($_POST['eCCVlangLevels'], ENT_QUOTES, 'UTF-8')."',
										`education` = '".htmlentities($_POST['eCCVeducation'], ENT_QUOTES, 'UTF-8')."',
										`career` = '".htmlentities($_POST['eCCVcareer'], ENT_QUOTES, 'UTF-8')."',
										`experCompany` = '".htmlentities($_POST['eCCVsons'], ENT_QUOTES, 'UTF-8')."',
										`experStart` = '".htmlentities($_POST['eCCVsons'], ENT_QUOTES, 'UTF-8')."',
										`experEnd` = '".htmlentities($_POST['eCCVsons'], ENT_QUOTES, 'UTF-8')."',
										`experPos` = '".htmlentities($_POST['eCCVsons'], ENT_QUOTES, 'UTF-8')."',
										`experDesc` = '".htmlentities($_POST['eCCVsons'], ENT_QUOTES, 'UTF-8')."',
										`otherDetails` = '".htmlentities($_POST['eCCVotherDetails'], ENT_QUOTES, 'UTF-8')."',
										`skill1` = '".htmlentities($_POST['eCCVskill1'], ENT_QUOTES, 'UTF-8')."',
										`skill2` = '".htmlentities($_POST['eCCVskill2'], ENT_QUOTES, 'UTF-8')."',
										`skill3` = '".htmlentities($_POST['eCCVskill3'], ENT_QUOTES, 'UTF-8')."',
										`skill4` = '".htmlentities($_POST['eCCVskill4'], ENT_QUOTES, 'UTF-8')."',
										`skill5` = '".htmlentities($_POST['eCCVskill5'], ENT_QUOTES, 'UTF-8')."',
										`skill6` = '".htmlentities($_POST['eCCVskill6'], ENT_QUOTES, 'UTF-8')."',
										`skill7` = '".htmlentities($_POST['eCCVskill7'], ENT_QUOTES, 'UTF-8')."',
										`skill8` = '".htmlentities($_POST['eCCVskill8'], ENT_QUOTES, 'UTF-8')."',
										`skill9` = '".htmlentities($_POST['eCCVskill9'], ENT_QUOTES, 'UTF-8')."',
										`skill10` = '".htmlentities($_POST['eCCVskill10'], ENT_QUOTES, 'UTF-8')."',
										`cvDate` = '".htmlentities($_POST['eCCVcvDate'], ENT_QUOTES, 'UTF-8')."',
										`salary` = '".htmlentities($_POST['eCCVsalary'], ENT_QUOTES, 'UTF-8')."',
										`comments` = '".htmlentities($_POST['eCCVcomments'], ENT_QUOTES, 'UTF-8')."',
										`candidateStatus` = '".htmlentities($_POST['eCCVcandidateStatus'], ENT_QUOTES, 'UTF-8')."'
									WHERE `nie` = '".htmlentities($_POST['eCCVnie'], ENT_QUOTES, 'UTF-8')."';";
				
				if((!executeDBquery($updateCVQuery))){
					?>
					<script type="text/javascript">
						alert('Error revisando CV');
						window.location.href='pendingCVs.php?codvalue=<?php echo $_POST['eCCVnie'];  ?>';
					</script>
					<?php 
				}
				else {
					?>
					<!-- <script type="text/javascript">
						window.location.href='pendingCVs.php';
					</script> -->
					<?php
				}
			}
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
										echo "<li class='active'><a href=../$i.php id='onlink'>" . $mainNamesRow[$j] . "</a>";
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
													// Because the file we are is a level 2 file, we do this comparision to make active element in list if it's this same file
													if ($level2File == 'pendingCVs') 
														$badge = "<span class='badge'>$pendingCVs</span>";
													else
														$badge = "";
													if ($level2File == basename(__FILE__, '.php')) 
														echo "<li class='active'>$badge<a href=$level2File.php>" . $subLevelMenu . "</a></li>";
													else
														echo "<li>$badge<a href=$level2File.php>" . $subLevelMenu . "</a></li>";
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
													echo "<li><a href=home/$level3File.php>" . $subLevelMenu . "</a></li>";
												}
											}
										}

										echo "</ul> <!-- class='nav' -->";
										echo "</li> <!-- class='active' -->";

									}

									else{
										echo "<li><a href=../$i.php>" . $mainNamesRow[$j] . "</a></li>";
										$j++;
									}
								}
							}
							?>
						</ul> <!-- class="nav bs-sidenav" -->
					</div> <!-- id="sidebar-navigation-list"  -->
				</div> <!-- col-md-3 -->


				<!-- Modal HTML -->
				<div id="editCVModal" class="modal fade bs-example-modal-lg">
					<div class="modal-dialog modal-lg">
						<div class="modal-content panel-info">
							<div class="modal-header panel-heading">
								<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
								<h4 class="modal-title">Validando CV <?php echo $_GET['codvalue'] ?></h4>
							</div>

							<?php	
								$editedCVRow = getDBrow('cvitaes', 'nie', $_GET['codvalue']);
								$userFilesDir = $_SERVER['DOCUMENT_ROOT'] . "/cvs/".html_entity_decode($editedCVRow['userLogin'])."/";
								
								if(!ifCreateDir($userFilesDir, 0777)){
									?>
									<script type="text/javascript">
										alert('Error retrieving User Directory Information. Please contact administrator.');
										window.location.href='../home.php';
									</script>
									<?php 
								}
								?>

							<form id="editedCV" class="form-horizontal" role="form" name="editedCV" autocomplete="off" method="post" action="pendingCVs.php">
								<div class="modal-body">

									<div class="form-group"> <!-- Nombre -->
										<label id="editCVLabel" class="control-label col-sm-2" for="eCCVname">Nombre: </label> 
										<div class="col-sm-10">
											<input class="form-control" type='text' name='eCCVname' value="<?php echo html_entity_decode($editedCVRow['name']) ?>" autocomplete="off" />
										</div>
									</div>

									<div class="form-group"> <!-- Apellidos -->
										<label id="editCVLabel" class="control-label col-sm-2" for="eCCVsurname">Apellidos: </label>
										<div class="col-sm-10">
											<input class="form-control" type='text' name='eCCVsurname' value="<?php echo html_entity_decode($editedCVRow['surname']) ?>" autocomplete="off"/>
										</div>
									</div>

									<div class="form-group"> <!-- Fecha de Nacimiento -->
										<label id="editCVLabel" class="control-label col-sm-2" for="eCCVbirthdate">Fecha de nacimiento: </label>
										<div class="col-sm-10">
											<input class="form-control" type='date' name='eCCVbirthdate' value="<?php echo html_entity_decode($editedCVRow['birthdate']) ?>"  />
										</div>
									</div>

									<div class="form-group">  <!-- NIE -->
										<label id="editCVLabel" class="control-label col-sm-2" for="eCCVnie">DNI/NIE: </label>
										<div class="col-sm-10">
											<input class="form-control" type='text' name='eCCVnie' value="<?php echo html_entity_decode($editedCVRow['nie']) ?>"  />
										</div>
									</div>

									<div class="form-group">  <!-- Nacionalidad -->
										<label id="editCVLabel" class="control-label col-sm-2" for="eCCVnationalities">Nacionalidad: </label>
										<div class="col-sm-10">
											<input class="form-control" type='text' name='eCCVnationalities' value="<?php echo html_entity_decode($editedCVRow['nationalities']) ?>" data-role='tagsinput' />
										</div>
									</div>

									<div class="form-group"> <!-- Sexo -->
										<label id="editCVLabel" class="control-label col-sm-2" for="eCCVsex">Sexo: </label>
										<div class="col-sm-10">
											<div class='radio-inline'>
												<?php
													if(html_entity_decode($editedCVRow['sex']) == 0){
														echo "<label id='noPadding' class='radio-inline'><input class='radio-inline' type='radio' name='eCCVsex' value='0' checked>Hombre</label>";
														echo "<label id='noPadding' class='radio-inline'><input class='radio-inline' type='radio' name='eCCVsex' value='1'>Mujer</label>";
													}
													else {
														echo "<label id='noPadding' class='radio-inline'><input class='radio-inline' type='radio' name='eCCVsex' value='0'>Hombre</label>";
														echo "<label id='noPadding' class='radio-inline'><input class='radio-inline' type='radio' name='eCCVsex' value='1' checked>Mujer</label>";
													}
												?>
											</div>
										</div>
									</div>
															
									<div class="form-group">  <!-- Tipo Dirección -->
										<label id="editCVLabel" class="control-label col-sm-2" for="eCCVaddrtype">Tipo de dirección: </label>
										<div class="col-sm-10">
											<input class="form-control" type='text' name='eCCVaddrtype' value="<?php echo html_entity_decode($editedCVRow['addrType']) ?>">
										</div>
									</div>
									
									<div class="form-group">  <!-- Nombre Dirección -->
										<label id="editCVLabel" class="control-label col-sm-2" for="eCCVaddrName">Nombre dirección: </label>
										<div class="col-sm-10">
											<input class="form-control" type='text' name='eCCVaddrName' value="<?php echo html_entity_decode($editedCVRow['addrName']) ?>">
										</div>
									</div>

									<div class="form-group" >  <!-- Número -->
										<label id="editCVLabel" class="control-label col-sm-2" for="eCCVaddrNum">Número: </label>										
										<div class="col-sm-10">
											<input class="form-control" type='text' name='eCCVaddrNum' value="<?php echo html_entity_decode($editedCVRow['addrNum']) ?>">
										</div>
									</div>
										
									<div class="form-group" >  <!-- Portal -->	
										<label id="editCVLabel" class="control-label col-sm-2" for="eCCVaddrPortal">Portal: </label>										
										<div class="col-sm-10">
											<input class="form-control" type='text' name='eCCVaddrPortal' value="<?php echo html_entity_decode($editedCVRow['portal']) ?>">
										</div>
									</div>

									<div class="form-group" >  <!-- Escalera -->	
										<label id="editCVLabel" class="control-label col-sm-2" for="eCCVaddrStair">Escalera: </label>										
										<div class="col-sm-10">
											<input class="form-control" type='text' name='eCCVaddrStair' value="<?php echo html_entity_decode($editedCVRow['stair']) ?>">
										</div>
									</div>

									<div class="form-group" >  <!-- Piso -->
										<label id="editCVLabel" class="control-label col-sm-2" for="eCCVaddrFloor">Piso: </label>										
										<div class="col-sm-10">
											<input class="form-control" type='text' name='eCCVaddrFloor' value="<?php echo html_entity_decode($editedCVRow['addrFloor']) ?>">
										</div>
									</div>

									<div class="form-group" >  <!-- Puerta -->
										<label id="editCVLabel" class="control-label col-sm-2" for="eCCVaddrDoor">Puerta: </label>										
										<div class="col-sm-10">
											<input class="form-control" type='text' name='eCCVaddrDoor' value="<?php echo html_entity_decode($editedCVRow['addrDoor']) ?>">										
										</div>
									</div>		

									<div class="form-group" >  <!-- Código Postal -->
										<label id="editCVLabel" class="control-label col-sm-2" for="eCCVpostal">Código Postal: </label>										
										<div class="col-sm-10">
											<input class="form-control" type='text' name='eCCVpostal' value="<?php echo html_entity_decode($editedCVRow['postalCode']) ?>">										
										</div>
									</div>		

									<div class="form-group" >  <!-- Localidad -->
										<label id="editCVLabel" class="control-label col-sm-2" for="eCCVcity">Localidad: </label>										
										<div class="col-sm-10">
											<input class="form-control" type='text' name='eCCVcity' value="<?php echo html_entity_decode($editedCVRow['city']) ?>">										
										</div>
									</div>	

									<div class="form-group" >  <!-- Provincia -->
										<label id="editCVLabel" class="control-label col-sm-2" for="eCCVprovince">Provincia: </label>										
										<div class="col-sm-10">
											<input class="form-control" type='text' name='eCCVprovince' value="<?php echo html_entity_decode($editedCVRow['province']) ?>">										
										</div>
									</div>	

									<div class="form-group" >  <!-- País -->
										<label id="editCVLabel" class="control-label col-sm-2" for="eCCVcountry">País: </label>										
										<div class="col-sm-10">
											<input class="form-control" type='text' name='eCCVcountry' value="<?php echo html_entity_decode($editedCVRow['country']) ?>">										
										</div>
									</div>

									<div class="form-group" >  <!-- Teléfono Móvil -->
										<label id="editCVLabel" class="control-label col-sm-2" for="eCCVmobile">Teléfono Móvil: </label>										
										<div class="col-sm-10">
											<input class="form-control" type='text' name='eCCVmobile' value="<?php echo html_entity_decode($editedCVRow['mobile']) ?>">										
										</div>
									</div>	

									<div class="form-group" >  <!-- Otro Teléfono -->
										<label id="editCVLabel" class="control-label col-sm-2" for="eCCVphone">Otro Teléfono: </label>										
										<div class="col-sm-10">
											<input class="form-control" type='text' name='eCCVphone' value="<?php echo html_entity_decode($editedCVRow['phone']) ?>">										
										</div>
									</div>

									<div class="form-group" >  <!-- Correo Electrónico -->
										<label id="editCVLabel" class="control-label col-sm-2" for="eCCVmail">Correo Electrónico: </label>										
										<div class="col-sm-10">
											<input class="form-control" type='mail' name='eCCVmail' value="<?php echo html_entity_decode($editedCVRow['mail']) ?>">										
										</div>
									</div>

									<div class="form-group" >  <!-- Carnet de Conducir -->
										<label id="editCVLabel" class="control-label col-sm-2" for="eCCVdrivingType">Carnet de Conducir: </label>										
										<div class="col-sm-10">
											<input class="form-control" type='text' name='eCCVdrivingType' value="<?php echo html_entity_decode($editedCVRow['drivingType']) ?>">
											<input class='form-control' type='date' name='eCCVdrivingDate' value="<?php echo html_entity_decode($editedCVRow['drivingDate']) ?>">
										</div>
									</div>

									<div class="form-group" >  <!-- Estado Civil -->
										<label id="editCVLabel" class="control-label col-sm-2" for="eCCVmarital">Estado Civil: </label>										
										<div class="col-sm-10">
											<input class="form-control" type='text' name='eCCVmarital' value="<?php echo getDBsinglefield(getDBsinglefield('language', 'users', 'login', $_SESSION['loglogin']), 'maritalStatus', 'key', html_entity_decode($editedCVRow['marital'])) ?>">
										</div>
									</div>

									<div class="form-group" >  <!-- Hijos -->
										<label id="editCVLabel" class="control-label col-sm-2" for="eCCVsons">Hijos: </label>										
										<div class="col-sm-10">
											<input class="form-control" type='text' name='eCCVsons' value="<?php echo html_entity_decode($editedCVRow['sons']) ?>">
										</div>
									</div>

									<div class="form-group" >  <!-- Idiomas -->
										<label id="editCVLabel" class="control-label col-sm-2" for="eCCVlanguages">Idiomas: </label>										
										<div class="col-sm-10">
											<input class="form-control" type='text' name='eCCVlanguages' value="<?php echo html_entity_decode($editedCVRow['language']) ?>" data-role='tagsinput'>
											<input class="form-control" type='text' name='eCCVlangLevels' value="<?php echo html_entity_decode($editedCVRow['langLevel']) ?>" data-role='tagsinput'>										
										</div>
									</div>

									<div class="form-group" >  <!-- Educación -->
										<label id="editCVLabel" class="control-label col-sm-2" for="eCCVeducation">Educación: </label>										
										<div class="col-sm-10">
											<input class="form-control" type='text' name='eCCVeducation' value="<?php echo html_entity_decode($editedCVRow['education']) ?>" data-role='tagsinput'>										
										</div>
									</div>

									<div class="form-group" >  <!-- Profesión -->
										<label id="editCVLabel" class="control-label col-sm-2" for="eCCVcareer">Profesiones desempeñadas: </label>	<!-- Se puede omitir -->									
										<div class="col-sm-10">
											<input class="form-control" type='text' name='eCCVcareer' value='<?php echo html_entity_decode($editedCVRow['career']) ?>' data-role='tagsinput'>										
										</div>
									</div>

									<div class="form-group" >  <!-- Experiencia -->
										<label id="editCVLabel" class="control-label col-sm-2" for="eCCVexperience">Últimos años: </label>										
										<div class="col-sm-10">
											<input class="form-control" type='text' name='eCCVexperCompany' value="<?php echo html_entity_decode($editedCVRow['experCompany']) ?>" data-role='tagsinput'>
											<input class="form-control" type='text' name='eCCVexperStart' value="<?php echo html_entity_decode($editedCVRow['experStart']) ?>" data-role='tagsinput'>			
											<input class="form-control" type='text' name='eCCVexperEnd' value="<?php echo html_entity_decode($editedCVRow['experEnd']) ?>" data-role='tagsinput'>
											<input class="form-control" type='text' name='eCCVexperPos' value="<?php echo html_entity_decode($editedCVRow['experPos']) ?>" data-role='tagsinput'>
											<input class="form-control" type='text' name='eCCVexperDesc' value="<?php echo html_entity_decode($editedCVRow['experDesc']) ?>" data-role='tagsinput'>
										</div>
									</div>

									<div class="form-group" >  <!-- Salario Deseado -->
										<label id="editCVLabel" class="control-label col-sm-2" for="eCCVsalary">Salario deseado: </label>										
										<div class="col-sm-10 input-group">
											<input class="form-control" type='text' name='eCCVsalary' value="<?php echo html_entity_decode($editedCVRow['salary']) ?>">
											<span class="input-group-addon">€uros/año</span>
										</div>
									</div>										

									<div class="form-group" >  <!-- Otros Detalles -->
										<label id="editCVLabel" class="control-label col-sm-2" for="eCCVotherDetails">Otros Detalles: </label>										
										<div class="col-sm-10">
											<input class="form-control" type='text' name='eCCVotherDetails' value="<?php echo html_entity_decode($editedCVRow['otherDetails']) ?>">
										</div>
									</div>		

									<div class="form-group" >  <!-- Ficheros -->
										<label id="editCVLabel" class="control-label col-sm-2" for="eCCVfiles">Ficheros: </label>		
										<div class="col-sm-10">
										<?php
											$userFilesArray  = scandir($userFilesDir);
											foreach ($userFilesArray as $value){
												echo '|-> '.$value.' <-|';
												if (preg_match("/\w+/i", $value)) {
													echo "<a href=downloadFileSingle.php?doc=".$value.">$value</a><br>";
												}
											}
											?>		
										</div>						
									</div>	

									<div class="panel panel-default">
										<div class="panel-heading">
											<h3 class="panel-title">Habilidades del candidato</h3>
										</div>
										<div class="panel-body">
											<?php
											for ($i=1; $i <= 10; $i++) { 
												echo "<div class='form-group' >  <!-- Habilidad ".$i." -->";
												echo "	<label id='editCVLabel' class='control-label col-sm-2' for='eCCVskill".$i."'>#".$i.": </label>";
												echo "	<div class='col-sm-10'>";
												echo "		<input class='form-control' type='text' name='eCCVskill".$i."' value='".html_entity_decode($editedCVRow["skill$i"])."'>";
												echo "	</div>";
												echo "</div>";
											}
											?>
										</div>
									</div>

									<div class="form-group" >  <!-- Comentarios -->
										<label id="editCVLabel" class="control-label col-sm-2" for="eCCVcomments">Comentarios: </label>	
										<div class="col-sm-10">
											<textarea class="form-control" type='text' name='eCCVcomments' value="<?php echo html_entity_decode($editedCVRow['comments']) ?>"><?php echo html_entity_decode($editedCVRow['comments']) ?></textarea>
										</div>
									</div>	

									<div class="form-group" >  <!-- Estado del Candidato -->
										<label id="editCVLabel" class="control-label col-sm-2" for="eCCVcandidateStatus">Estado del Candidato: </label>	
										<div class="col-sm-10">
											<select class="form-control" name='eCCVcandidateStatus'>
												<option value=''>Sin estado</option>
												<option value='available'>Disponible</option>
												<option value='working'>Colocado</option>
												<option value='discarded'>Descartado</option>
											</select>
										</div>
									</div>	

									<div class="form-group"> <!-- Fecha de CV -->
										<label id="editCVLabel" class="control-label col-sm-2" for="eCCVcvDate">Fecha CV: </label>
										<div class="col-sm-10">
											<input class="form-control" type='text' name='eCCVcvDate' value="<?php echo html_entity_decode($editedCVRow['cvDate']) ?>" readonly>
										</div>
									</div>

								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
									<button type="submit" class="btn btn-primary" name="eCurCVsend">Validar CV <span class="glyphicon glyphicon-ok"> </span></button>
								</div>
							</form>
						</div>
					</div>
				</div>	<!-- Modal HTML -->



				<div class="col-md-9 scrollable" role="main"> 
					<div class="bs-docs-section">

						<h2 class="page-header">CVs pendientes de clasificar</h2>

					</span>

					<?php 

						if((getDBrowsnumber('cvitaes') == 0) || (count($cvIDs = getDBcolumnvalue('id', 'cvitaes', 'cvStatus', 'pending')) == 0)){
						echo 'No hay CVs por clasificar';
					}
					else{
						?>
						<div class="table-responsive">
							<table class="table table-striped table-hover">
								<thead>
									<tr>
										<th>NIE</th>
										<th>Nombre</th>
										<th>Apellidos</th>
										<th>Acción (Eliminar)</th>
									</tr>
								</thead>

								<tbody>
								<?php 
								foreach($cvIDs as $i){
									$cvRow = getDBrow('cvitaes', 'id', $i);
									echo "<tr>";
									echo "<td><a href='pendingCVs.php?codvalue=" . html_entity_decode($cvRow['nie']) . "'>" . html_entity_decode($cvRow['nie']) . "</a></td>";
									echo "<td>" . html_entity_decode($cvRow['name']) . "</td>";
									echo "<td>" . html_entity_decode($cvRow['surname']) . "</td>";
									echo "<td><a href='delCurCV.php?codvalue=" . html_entity_decode($cvRow['nie']) . "'>Borrar</a></td>";
									echo "</tr>";
								}
								?>
								</tbody>
							</table>
						</div>
						<?php 
					}
					?>

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
	<script src="https://code.jquery.com/jquery-1.11.0.min.js"></script>
	<script src="https://netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>

	<!-- Site own functions -->
	<script src="../../common/js/functions.js"></script>
	<script src="../../common/js/application.js"></script>
	<script src="../../common/js/docs.min.js"></script>
	<script src="../../common/js/bootstrap-tagsinput.js"></script>

	<!-- Own document functions -->
	<!-- Show modal if password has to be changed -->
	<?php 

		if (isset($_GET['codvalue'])) {
			echo "<script type='text/javascript'>";
			echo "	$(document).ready(function(){";
			echo "		$('#editCVModal').modal('show');";
			echo "		$('#editCVModal').on('hidden.bs.modal', function () {";
 			echo "			window.location.href='pendingCVs.php';";
			echo "		});";
			echo "	});  ";
			echo "</script> ";
		}
	?>	

</body>
</html>
