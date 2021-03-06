<?php session_start(); ?>
<html lang="es">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="">
	<meta name="author" content="David Alfonso Ginés Prieto, Miguel Hita Vicente y Miguel Ángel Melón Pérez">
	
	<title>CVs Revisados</title>

	<!-- Custom styles for this template -->
	<link href="../../common/css/design.css" rel="stylesheet">
	<!-- <link href="../../common/css/styles.css" rel="stylesheet"> -->
	<!-- <link href="../common/css/docs.css" rel="stylesheet"> -->

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
	//else{
	elseif($_SESSION['logprofile'] == 'SuperAdmin'){
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


		<!-- En $myFile guardo el nombre del fichero php que la APP está tratando en ese instante. Necesario para mostrar
		el resto de menús de nivel 1 cuando navegue por ellos, y saber cuál es el activo (id='onlink') -->
		<?php
			$myFile = 'home';
			$userRow = getDBrow('users', 'login', $_SESSION['loglogin']);

			$pendingCVs = getPendingCVs();


			if (isset($_POST['eCurCVsend'])) {

				//Unmounting "Lang:LangLv" structure for insert in DB
				$wholeLangInfo = explode('|',$_POST['eCCVlanguagesMerged']);

				$finalLang = "";
				$finalLangLv = "";
				foreach($wholeLangInfo as $key => $value) {
					$array = explode(':',$value);					
					$finalLang = $finalLang . $array[0] . '|';
					$finalLangLv = $finalLangLv . $array[1] . '|';
				}
				
				$finalLang = substr($finalLang, 0, -1);
				$finalLangLv = substr($finalLangLv, 0, -1);

				//Mounting experience information
				$string_experCompany = "";
				$string_experStart = "";
				$string_experEnd = "";
				$string_experPos = "";
				$string_experDesc = "";			

				for ($i=0; $i < $_POST['eCCV_counterExperience'] ; $i++) { 
					$string_experCompany = $string_experCompany . $_POST["eCCVexperCompany$i"] . '|';
					$string_experStart = $string_experStart . $_POST["eCCVexperStart$i"] . '|';
					$string_experEnd = $string_experEnd . $_POST["eCCVexperEnd$i"] . '|';
					$string_experPos = $string_experPos . $_POST["eCCVexperPos$i"] . '|';
					$string_experDesc = $string_experDesc . $_POST["eCCVexperDesc$i"] . '|';
				}	

				//Cleaning last '|'
				$string_experCompany = substr($string_experCompany, 0, -1);
				$string_experStart = substr($string_experStart, 0, -1);
				$string_experEnd = substr($string_experEnd, 0, -1);
				$string_experPos = substr($string_experPos, 0, -1);
				$string_experDesc = substr($string_experDesc, 0, -1);

				
				//Minimum security checkings, to avoid malformation in DB
				if(eregMySQLCheckDate(htmlentities($_POST['eCCVbirthdate'], ENT_QUOTES, 'UTF-8'))){
					$inDBBirthdate = trim(htmlentities($_POST['eCCVbirthdate'], ENT_QUOTES, 'UTF-8'));
				}
				else{
					$inDBBirthdate = '0000-00-00';
				}
				
				//Checks if every nationality included is valid or not
				if(htmlentities($_POST['eCCVnationalities'], ENT_QUOTES, 'UTF-8') == ''){
					$inDBNationalities = false;
				}
				else{
					//$inDBNationalities = isImplodedArrayInDB(htmlentities($_POST['eCCVnationalities'], ENT_QUOTES, 'UTF-8'), 'countries', 'key', '|');
					$inDBNationalities = isImplodedArrayInDBExcept(htmlentities($_POST['eCCVnationalities'], ENT_QUOTES, 'UTF-8'), 'countries', 'key', '|', 'Spain');
				}
				//echo 'La variable de Nacionalidades es...'.htmlentities($_POST['eCCVnationalities'], ENT_QUOTES, 'UTF-8').'<br>';
				
				//Nationalities should be searched in its corresponding DBTable
				//If any of the mandatory fields are bad formed DB won't be updated
				if((!checkFullNameES($_POST['eCCVname'], $_POST['eCCVsurname'], $outName, $outSurname, $checkError)) || ($inDBBirthdate == '0000-00-00') || 
				(!checkDNI_NIE(htmlentities($_POST['eCCVnie'], ENT_QUOTES, 'UTF-8'))) || (!$inDBNationalities) || 
				(!checkMobile(htmlentities($_POST['eCCVmobile'], ENT_QUOTES, 'UTF-8'))) || (!filter_var(htmlentities($_POST['eCCVmail'], ENT_QUOTES, 'UTF-8'), FILTER_VALIDATE_EMAIL)) ||
				(htmlentities($finalLang, ENT_QUOTES, 'UTF-8') == '' || htmlentities($finalLangLv, ENT_QUOTES, 'UTF-8') == '' || htmlentities($finalLangLv, ENT_QUOTES, 'UTF-8') == '%null%') ||
				(htmlentities($_POST['eCCVcareer'], ENT_QUOTES, 'UTF-8') == '')){
					/*
					echo 'Name: '.$outName.'<br>';
					echo 'Surname: '.$outSurname.'<br>';
					echo 'Name error: '.$checkError.'<br>';
					echo 'Nacimiento: '.$inDBBirthdate.'<br>';
					echo 'NIE: '.$_POST['eCCVnie'].'<br>';
					echo 'Nacionalidad: '.$_POST['eCCVnationalities'].'<br>';
					echo 'Móvil: '.$_POST['eCCVmobile'].'<br>';
					echo 'Mail: '.$_POST['eCCVmail'].'<br>';
					echo 'Idioma: '.$finalLang.'<br>';
					echo 'Tipo Idioma: '.$finalLangLv.'<br>';
					echo 'Carrera: '.$_POST['eCCVcareer'].'<br>';
					echo 'Ciudad: '.$_POST['eCCVcity'].'<br>';
					*/
					?>
					<script type="text/javascript">
						alert('Al menos 1 de los campos obligatorios no es correcto.');
						window.location.href='checkedCVs.php?codvalue=<?php echo $_POST['eCCVnie'];  ?>';
					</script>
					<?php 
				}
				else{
					$inDBOtherPhone = trim(htmlentities($_POST['eCCVphone'], ENT_QUOTES, 'UTF-8'));
					//echo 'Tfno tras trim...'.$inDBOtherPhone.'<br>';
					if(!checkPhone($inDBOtherPhone)){
						$inDBOtherPhone = '';
					}
					//echo 'Y ahora vale...'.$inDBOtherPhone.'<br>';
					//exit();
					$updateCVQuery = "	UPDATE `cvitaes` 
										SET `nie` = '".$_POST['eCCVnie']."',
											`cvStatus` = 'checked',
											`name` = '".$outName."',
											`surname` = '".$outSurname."',
											`birthdate` = '".$inDBBirthdate."',
											`nationalities` = '".htmlentities($_POST['eCCVnationalities'], ENT_QUOTES, 'UTF-8')."',
											`sex` = '".htmlentities($_POST['eCCVsex'], ENT_QUOTES, 'UTF-8')."',
											`addrType` = '".htmlentities($_POST['eCCVaddrtype'], ENT_QUOTES, 'UTF-8')."',
											`addrName` = '".htmlentities($_POST['eCCVaddrName'], ENT_QUOTES, 'UTF-8')."',
											`addrNum` = '".htmlentities($_POST['eCCVaddrNum'], ENT_QUOTES, 'UTF-8')."',
											`portal` = '".htmlentities($_POST['eCCVaddrPortal'], ENT_QUOTES, 'UTF-8')."',
											`stair` = '".htmlentities($_POST['eCCVaddrStair'], ENT_QUOTES, 'UTF-8')."',
											`addrFloor` = '".htmlentities($_POST['eCCVaddrFloor'], ENT_QUOTES, 'UTF-8')."',
											`addrDoor` = '".htmlentities($_POST['eCCVaddrDoor'], ENT_QUOTES, 'UTF-8')."',
											`phone` = '".$inDBOtherPhone."',
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
											`language` = '".htmlentities($finalLang, ENT_QUOTES, 'UTF-8')."',
											`langLevel` = '".htmlentities($finalLangLv, ENT_QUOTES, 'UTF-8')."',
											`education` = '".htmlentities($_POST['eCCVeducation'], ENT_QUOTES, 'UTF-8')."',
											`career` = '".htmlentities($_POST['eCCVcareer'], ENT_QUOTES, 'UTF-8')."',
											`experCompany` = '".htmlentities($string_experCompany, ENT_QUOTES, 'UTF-8')."',
											`experStart` = '".htmlentities($string_experStart, ENT_QUOTES, 'UTF-8')."',
											`experEnd` = '".htmlentities($string_experEnd, ENT_QUOTES, 'UTF-8')."',
											`experPos` = '".htmlentities($string_experPos, ENT_QUOTES, 'UTF-8')."',
											`experDesc` = '".htmlentities($string_experDesc, ENT_QUOTES, 'UTF-8')."',
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
							window.location.href='checkedCVs.php?codvalue=<?php echo $_POST['eCCVnie'];  ?>';
						</script>
						<?php 
					}
					else {
						?>
						<script type="text/javascript">
							alert('CV validado satisfactoriamente.');
							window.location.href='checkedCVs.php';
						</script>
						<?php
					}
				}
			}
			elseif(isset($_GET['hiddenGET'])){
				switch($_GET['hiddenGET']){
					case 'hDelCheckedCV':
						$checkedCVRow = getDBrow('cvitaes', 'id', $_GET['codvalue']);
						if(!deleteDBrow('users', 'login', $checkedCVRow['userLogin'])){
							unset ($_GET['codvalue']);
							unset ($checkedCVRow);
							?>
							<script type="text/javascript">
								alert('Error deleting user from checked CVs.');
								window.location.href='checkedCVs.php';
							</script>
							<?php 
						}
						elseif(!deleteDBrow('cvitaes', 'id', $_GET['codvalue'])){
							unset ($_GET['codvalue']);
							unset ($checkedCVRow);
							?>
							<script type="text/javascript">
								alert('Error deleting checked CV.');
								window.location.href='checkedCVs.php';
							</script>
							<?php 
						}
						else{
							$numCandidateUsers = getDBsinglefield('numUsers', 'profiles', 'name', 'Candidato');
							$numCandidateUsers--;
							executeDBquery("UPDATE `profiles` SET `numUsers`='".$numCandidateUsers."' WHERE `name`='Candidato'");
							$userDir = $_SERVER['DOCUMENT_ROOT'] . "/cvs/".$checkedCVRow['userLogin']."/";
							//chdir($userDir);
							$files  = scandir($userDir);
							foreach ($files as $value){
								unlink($userDir.$value);
							}
							rmdir($userDir);
						}
					break;
				}
				?>
				<script type="text/javascript">
					window.location.href='checkedCVs.php';
				</script>
				<?php 
			}//end of GET
			
			/**********************************     End of FORM validations     **********************************/
	
			/******************************     Start of WebPage code as showed     ******************************/
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
													if ($level2File == 'checkedCVs') 
														$badge = "<span class='badge'>$checkedCVs</span>";
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
								<h4 class="modal-title">Modificando CV YA VALIDADO... <?php echo $_GET['codvalue'] ?></h4>
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

							<form id="editedCV" class="form-horizontal" role="form" name="editedCV" autocomplete="off" method="post" action="checkedCVs.php">
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
											<input class="form-control" type='text' name='eCCVnie' value="<?php echo html_entity_decode($editedCVRow['nie']) ?>" onkeyup='this.value=this.value.toUpperCase();' readonly/>
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
											<input class="form-control" type='text' name='eCCVaddrNum' maxlength='4' value="<?php echo html_entity_decode($editedCVRow['addrNum']) ?>" onkeyup='this.value=this.value.toUpperCase();'>
										</div>
									</div>
										
									<div class="form-group" >  <!-- Portal -->	
										<label id="editCVLabel" class="control-label col-sm-2" for="eCCVaddrPortal">Portal: </label>										
										<div class="col-sm-10">
											<input class="form-control" type='text' name='eCCVaddrPortal' maxlength='4' value="<?php echo html_entity_decode($editedCVRow['portal']) ?>" onkeyup='this.value=this.value.toUpperCase();'>
										</div>
									</div>

									<div class="form-group" >  <!-- Escalera -->	
										<label id="editCVLabel" class="control-label col-sm-2" for="eCCVaddrStair">Escalera: </label>										
										<div class="col-sm-10">
											<input class="form-control" type='text' name='eCCVaddrStair' maxlength='4' value="<?php echo html_entity_decode($editedCVRow['stair']) ?>" onkeyup='this.value=this.value.toUpperCase();'>
										</div>
									</div>

									<div class="form-group" >  <!-- Piso -->
										<label id="editCVLabel" class="control-label col-sm-2" for="eCCVaddrFloor">Piso: </label>										
										<div class="col-sm-10">
											<input class="form-control" type='text' name='eCCVaddrFloor' maxlength='4' value="<?php echo html_entity_decode($editedCVRow['addrFloor']) ?>">
										</div>
									</div>

									<div class="form-group" >  <!-- Puerta -->
										<label id="editCVLabel" class="control-label col-sm-2" for="eCCVaddrDoor">Puerta: </label>										
										<div class="col-sm-10">
											<input class="form-control" type='text' name='eCCVaddrDoor' maxlength='4' value="<?php echo html_entity_decode($editedCVRow['addrDoor']) ?>" onkeyup='this.value=this.value.toUpperCase();'>
										</div>
									</div>		

									<div class="form-group" >  <!-- Código Postal -->
										<label id="editCVLabel" class="control-label col-sm-2" for="eCCVpostal">Código Postal: </label>										
										<div class="col-sm-10">
											<!-- <input class="form-control" type='text' name='eCCVpostal' maxlength='5' value="<?php echo html_entity_decode($editedCVRow['postalCode']) ?>"> -->
											<input class="form-control" type='text' name='eCCVpostal' maxlength='5' value="<?php echo $editedCVRow['postalCode'] ?>" onkeypress="return checkOnlyNumbers(event)">
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
											<!-- <input class="form-control" type='text' name='eCCVmobile' maxlength='9' value="< ?php echo html_entity_decode($editedCVRow['mobile']) ?>"> -->
											<input class="form-control" type='text' name='eCCVmobile' maxlength='9' value="<?php echo $editedCVRow['mobile'] ?>" onkeypress="return checkOnlyNumbers(event)">										
										</div>
									</div>	

									<div class="form-group" >  <!-- Otro Teléfono -->
										<label id="editCVLabel" class="control-label col-sm-2" for="eCCVphone">Otro Teléfono: </label>										
										<div class="col-sm-10">
											<!-- <input class="form-control" type='text' name='eCCVphone' value="< ?php echo html_entity_decode($editedCVRow['phone']) ?>"> -->
											<input class="form-control" type='text' name='eCCVphone' maxlength='18' placeholder='00[COD. PAIS]-NUMERO' value="<?php echo $editedCVRow['phone'] ?>" onkeypress="return checkDashedNumbers(event)">
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
											<!-- <input class="form-control" type='text' name='eCCVmarital' value="< ?php echo getDBsinglefield(getDBsinglefield('language', 'users', 'login', $_SESSION['loglogin']), 'maritalStatus', 'key', html_entity_decode($editedCVRow['marital'])) ?>"> -->
											<select class="form-control" name="eCCVmarital" >
												<?php 
												$userLang = getDBsinglefield('language', 'users', 'login', $_SESSION['loglogin']);
												$maritalStatus = getDBcompletecolumnID($userLang, 'maritalStatus', $userLang);
												foreach($maritalStatus as $i){
													//echo "<option value=" . getDBsinglefield('key', 'countries', $userLang, $i) . ">" . $i . "</option>";
													$keyMarital = getDBsinglefield('key', 'maritalStatus', $userLang, $i);
													if($keyMarital == $editedCVRow['marital']){
														echo "<option selected value=" . $keyMarital . ">" . $i . "</option>";
													}
													else{
														echo "<option value=" . $keyMarital . ">" . $i . "</option>";
													}
												}
												?>
											</select>
										</div>
									</div>

									<div class="form-group" >  <!-- Hijos -->
										<label id="editCVLabel" class="control-label col-sm-2" for="eCCVsons">Hijos: </label>										
										<div class="col-sm-10">
											<!-- <input class="form-control" type='text' name='eCCVsons' maxlength='2' value="< ?php echo html_entity_decode($editedCVRow['sons']) ?>"> -->
											<input class="form-control" type='number' name='eCCVsons' maxlength='2' min='0' value="<?php echo $editedCVRow['sons'] ?>">
										</div>
									</div>

									<div class="form-group" >  <!-- Idiomas -->
										<label id="editCVLabel" class="control-label col-sm-2" for="eCCVlanguagesMerged">Idiomas: </label>			
										<?php 
											$mergedLanguages = explode('|',$editedCVRow['language']);
											$mergedLangLevels = explode('|',$editedCVRow['langLevel']);
											$hashedLanguages = array_combine($mergedLanguages,$mergedLangLevels);
										?>							
										<div class="col-sm-10">
											<input class="form-control" type='text' name='eCCVlanguagesMerged' value="<?php foreach ($hashedLanguages as $lang => $lv) { echo html_entity_decode($lang) . ':' . html_entity_decode($lv) . '|'; } ?>" data-role='tagsinput'>
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

									<?php 
										$array_experCompany = explode('|',$editedCVRow['experCompany']);
										$array_experStart = explode('|',$editedCVRow['experStart']);
										$array_experEnd = explode('|',$editedCVRow['experEnd']);
										$array_experPos = explode('|',$editedCVRow['experPos']);
										$array_experDesc = explode('|',$editedCVRow['experDesc']);

										echo "<div class='form-group' >  <!-- Experiencia -->";
										echo "	<label id='editCVLabel' class='control-label col-sm-2' for='eCCVexperience'>Últimos años: </label>";
										echo "	<div class='col-sm-10'>";
										
										for ($counterExperience=0; $counterExperience < count($array_experCompany); $counterExperience++) { 
											echo "		<div class='panel panel-default'>";
											echo "			<div class='panel-heading'>";
											echo "				<h3 class='panel-title'>Experiencia #".($counterExperience+1) . "</h3>";
											echo "			</div>";
											echo "			<div class='panel-body'>";
											echo "				<div class='form-group'>";
											echo "					<label id='editCVLabel' class='control-label col-sm-2' for='eCCVexperCompany$counterExperience'>Compañía: </label>";
											echo " 					<div class='col-sm-10'>";
											echo "						<input class='form-control' type='text' name='eCCVexperCompany$counterExperience' value='" . html_entity_decode($array_experCompany[$counterExperience]) . "' >";
											echo " 					</div>";
											echo "				</div>";
											echo "				<div class='form-group'>";
											echo "					<label id='editCVLabel' class='control-label col-sm-2' for='eCCVexperStart$counterExperience'>Inicio: </label>";
											echo " 					<div class='col-sm-10'>";
											echo "						<input class='form-control' type='text' name='eCCVexperStart$counterExperience' value='" . html_entity_decode($array_experStart[$counterExperience]) . "' >";
											echo " 					</div>";
											echo "				</div>";											
											echo "				<div class='form-group'>";
											echo "					<label id='editCVLabel' class='control-label col-sm-2' for='eCCVexperEnd$counterExperience'>Final: </label>";
											echo " 					<div class='col-sm-10'>";
											echo "						<input class='form-control' type='text' name='eCCVexperEnd$counterExperience' value='" . html_entity_decode($array_experEnd[$counterExperience]) . "' >";
											echo " 					</div>";
											echo "				</div>";
											echo "				<div class='form-group'>";
											echo "					<label id='editCVLabel' class='control-label col-sm-2' for='eCCVexperPos$counterExperience'>Posición: </label>";
											echo " 					<div class='col-sm-10'>";
											echo "						<input class='form-control' type='text' name='eCCVexperPos$counterExperience' value='" . html_entity_decode($array_experPos[$counterExperience]) . "' >";
											echo " 					</div>";
											echo "				</div>";
											echo "				<div class='form-group'>";
											echo "					<label id='editCVLabel' class='control-label col-sm-2' for='eCCVexperDesc$counterExperience'>Descripción: </label>";
											echo " 					<div class='col-sm-10'>";
											echo "						<input class='form-control' type='text' name='eCCVexperDesc$counterExperience' value='" . html_entity_decode($array_experDesc[$counterExperience]) . "' >";
											echo " 					</div>";
											echo "				</div>";											
											echo "			</div>";
											echo "		</div>";
										}

										echo "	</div>";
										echo "</div>";
										echo "<input type='hidden' name='eCCV_counterExperience' value='$counterExperience' >";

									?>

									<div class="form-group" >  <!-- Salario Deseado -->
										<label id="editCVLabel" class="control-label col-sm-2" for="eCCVsalary">Salario deseado: </label>										
										<div class="col-sm-10 input-group">
											<!-- <input class="form-control" type='text' name='eCCVsalary' maxlength='7' value="< ?php echo html_entity_decode($editedCVRow['salary']) ?>"> -->
											<input class="form-control" type='text' name='eCCVsalary' maxlength='7' value="<?php echo html_entity_decode($editedCVRow['salary']) ?>" onkeypress="return checkOnlyNumbers(event)">
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
												if (preg_match("/\w+/i", $value)) {
													echo "<a href=downloadFileSingle.php?doc=".$userFilesDir.$value.">$value</a><br>";
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
									<button type="submit" class="btn btn-primary" name="eCurCVsend">Modificar CV previamente validado <span class="glyphicon glyphicon-ok"> </span></button>
								</div>
							</form>
						</div>
					</div>
				</div>	<!-- Modal HTML -->
				

				<div class="col-md-9 scrollable" role="main"> 
					<div class="bs-docs-section">

						<h2 class="page-header">CVs clasificados</h2>

					</span>

					<?php 

					if((getDBrowsnumber('cvitaes') == 0) || (count($cvIDs = getDBcolumnvalue('id', 'cvitaes', 'cvStatus', 'checked')) == 0)){
						echo 'No hay CVs clasificados';
					}
					else{
						?>
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
								echo "<td><a href='checkedCVs.php?codvalue=" . html_entity_decode($cvRow['nie']) . "'>" . html_entity_decode($cvRow['nie']) . "</a></td>";
								echo "<td>" . html_entity_decode($cvRow['name']) . "</td>";
								echo "<td>" . html_entity_decode($cvRow['surname']) . "</td>";
								echo "<td><a href='checkedCVs.php?codvalue=" . $cvRow['id'] . "&hiddenGET=hDelCheckedCV' onclick='return confirmCheckedCVDeletion();'>Borrar</a></td>";
								echo "</tr>";
							}
							?>
							</tbody>
						</table>
						<?php 
					}
					?>

				</div> <!-- bs-docs-section -->
			</div> <!-- col-md-9 scrollable role=main -->
		</div> <!-- row -->
	</div> <!-- class="container bs-docs-container" -->




	<?php

	} //del "else" de $_SESSION.
	//Any other non-SuperAdmin profile will be redirected to home.php
	else{
		?>
		<script type="text/javascript">
			window.location.href='../home.php';
		</script>
		<?php
	}

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
 			echo "			window.location.href='checkedCVs.php';";
			echo "		});";
			echo "	});  ";
			echo "</script> ";
		}
	?>	

</body>
</html>
