<?php session_start(); ?>
<html lang="es">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="">
	<meta name="author" content="David Alfonso Ginés Prieto, Miguel Hita Vicente y Miguel Ángel Melón Pérez">
	
	<title>Buscar CVs</title>
	
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
	else {
		$lastUpdate = $_SESSION['lastupdate'];
		$curUpdate = date('Y-m-j H:i:s');
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
							<li><a href="../administration.php">Configuración</a></li>
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
		</div> <!-- exitRequest Modal -->



		<!-- /* En $myFile guardo el nombre del fichero php que WC está tratando en ese instante. Necesario para mostrar
		* el resto de menús de nivel 1 cuando navegue por ellos, y saber cuál es el activo (id='onlink')
		*/ -->
		<?php
		$myFile = 'home';
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
													if ($level2File == basename(__FILE__, '.php'))
														echo "<li class='active'><span class='badge'>$k</span><a href=$level2File.php>" . $subLevelMenu . "</a></li>";
													else
														echo "<li><span class='badge'>$k</span><a href=$level2File.php>" . $subLevelMenu . "</a></li>";
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
													echo "<li><span class='badge'>$k</span><a href=home/$level3File.php>" . $subLevelMenu . "</a></li>";
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

				<div class="col-md-9 scrollable" role="main"> 
					<div class="bs-docs-section">

						<h2 class="page-header">Buscar CVs</h2>

						<div class="panel panel-default">
							<div class="panel-heading">
								<h2 class="panel-title">Introduzca criterios de búsqueda</h2>
							</div>
							<div class="panel-body">
								
								<form id="searchForm" name="searchForm" class="form-horizontal" method="post" action="searchResult.php" autocomplete="off" autocapitalize="off" enctype="multipart/form-data" onsubmit="return comprobar()";>
									<div id="form_WordKey" class="form-group">
										<label for="blankWordKey" class="control-label col-xs-3">Palabra Clave</label>
										<div class="col-xs-9">
											<input type="text" class="form-control" name="blankWordKey" id="blankWordKey" maxlength="12" placeholder="Max. 12 caracteres" autofocus>
										</div>
									</div> <!-- id="form_WordKey" -->
									<div id="form_NIE" class="form-group">
										<label for="blankNIE" class="control-label col-xs-3">NIE</label>
										<div class="col-xs-9">
											<input type="text" class="form-control" name="blankNIE" id="blankNIE" maxlength="12" placeholder="Max. 12 caracteres" autofocus>
										</div>
									</div> <!-- id="form_NIE" -->
									
									

									<div id="form_Driving" class="form-group">
										<label for="drivingType" class="control-label col-xs-3">Carné de Conducir</label>
										<div class="col-xs-2">
											<select name="drivingType" class="form-control">
													<option selected disabled value=''>Tipo</option>
													<option value="1">AM</option>
													<option value="2">A</option>
													<option value="3">A1</option>
													<option value="4">A2</option>
													<option value="5">B</option>
													<option value="6">C</option>
													<option value="7">C1</option>
													<option value="8">D</option>
													<option value="9">D1</option>
													<option value="10">E</option>
													<option value="11">BTP</option>
												</select>
										</div>
										<div class="col-xs-7">
											<input type="date" class="form-control" name="drivingDate" name="drivingDate" />
										</div>
									</div> <!-- id="form_Driving" -->			

									<div id="form_Nationality" class="form-group">
										<label for="blankNationality" class="control-label col-xs-3">Nacionalidad</label>
										<div class="col-xs-9">
											<select name="blankNationality" class="form-control">
												<option selected disabled value=''></option>
												<option value="Afghanistan"> Afghanistan </option>
												<option value="Albania"> Albania </option>
												<option value="Algeria"> Algeria </option>
												<option value="American Samoa"> American Samoa </option>
												<option value="Andorra"> Andorra </option>
												<option value="Angola"> Angola </option>
												<option value="Anguilla"> Anguilla </option>
												<option value="Antigua and Barbuda"> Antigua and Barbuda </option>
												<option value="Argentina"> Argentina </option>
												<option value="Armenia"> Armenia </option>
												<option value="Aruba"> Aruba </option>
												<option value="Australia"> Australia </option>
												<option value="Austria"> Austria </option>
												<option value="Azerbaijan"> Azerbaijan </option>
												<option value="The Bahamas"> The Bahamas </option>
												<option value="Bahrain"> Bahrain </option>
												<option value="Bangladesh"> Bangladesh </option>
												<option value="Barbados"> Barbados </option>
												<option value="Belarus"> Belarus </option>
												<option value="Belgium"> Belgium </option>
												<option value="Belize"> Belize </option>
												<option value="Benin"> Benin </option>
												<option value="Bermuda"> Bermuda </option>
												<option value="Bhutan"> Bhutan </option>
												<option value="Bolivia"> Bolivia </option>
												<option value="Bosnia and Herzegovina"> Bosnia and Herzegovina </option>
												<option value="Botswana"> Botswana </option>
												<option value="Brazil"> Brazil </option>
												<option value="Brunei"> Brunei </option>
												<option value="Bulgaria"> Bulgaria </option>
												<option value="Burkina Faso"> Burkina Faso </option>
												<option value="Burundi"> Burundi </option>
												<option value="Cambodia"> Cambodia </option>
												<option value="Cameroon"> Cameroon </option>
												<option value="Canada"> Canada </option>
												<option value="Cape Verde"> Cape Verde </option>
												<option value="Cayman Islands"> Cayman Islands </option>
												<option value="Central African Republic"> Central African Republic </option>
												<option value="Chad"> Chad </option>
												<option value="Chile"> Chile </option>
												<option value="People's Republic of China"> People's Republic of China </option>
												<option value="Republic of China"> Republic of China </option>
												<option value="Christmas Island"> Christmas Island </option>
												<option value="Cocos (Keeling) Islands"> Cocos (Keeling) Islands </option>
												<option value="Colombia"> Colombia </option>
												<option value="Comoros"> Comoros </option>
												<option value="Congo"> Congo </option>
												<option value="Cook Islands"> Cook Islands </option>
												<option value="Costa Rica"> Costa Rica </option>
												<option value="Cote d'Ivoire"> Cote d'Ivoire </option>
												<option value="Croatia"> Croatia </option>
												<option value="Cuba"> Cuba </option>
												<option value="Cyprus"> Cyprus </option>
												<option value="Czech Republic"> Czech Republic </option>
												<option value="Denmark"> Denmark </option>
												<option value="Djibouti"> Djibouti </option>
												<option value="Dominica"> Dominica </option>
												<option value="Dominican Republic"> Dominican Republic </option>
												<option value="Ecuador"> Ecuador </option>
												<option value="Egypt"> Egypt </option>
												<option value="El Salvador"> El Salvador </option>
												<option value="Equatorial Guinea"> Equatorial Guinea </option>
												<option value="Eritrea"> Eritrea </option>
												<option value="Estonia"> Estonia </option>
												<option value="Ethiopia"> Ethiopia </option>
												<option value="Falkland Islands"> Falkland Islands </option>
												<option value="Faroe Islands"> Faroe Islands </option>
												<option value="Fiji"> Fiji </option>
												<option value="Finland"> Finland </option>
												<option value="France"> France </option>
												<option value="French Polynesia"> French Polynesia </option>
												<option value="Gabon"> Gabon </option>
												<option value="The Gambia"> The Gambia </option>
												<option value="Georgia"> Georgia </option>
												<option value="Germany"> Germany </option>
												<option value="Ghana"> Ghana </option>
												<option value="Gibraltar"> Gibraltar </option>
												<option value="Greece"> Greece </option>
												<option value="Greenland"> Greenland </option>
												<option value="Grenada"> Grenada </option>
												<option value="Guadeloupe"> Guadeloupe </option>
												<option value="Guam"> Guam </option>
												<option value="Guatemala"> Guatemala </option>
												<option value="Guernsey"> Guernsey </option>
												<option value="Guinea"> Guinea </option>
												<option value="Guinea-Bissau"> Guinea-Bissau </option>
												<option value="Guyana"> Guyana </option>
												<option value="Haiti"> Haiti </option>
												<option value="Honduras"> Honduras </option>
												<option value="Hong Kong"> Hong Kong </option>
												<option value="Hungary"> Hungary </option>
												<option value="Iceland"> Iceland </option>
												<option value="India"> India </option>
												<option value="Indonesia"> Indonesia </option>
												<option value="Iran"> Iran </option>
												<option value="Iraq"> Iraq </option>
												<option value="Ireland"> Ireland </option>
												<option value="Israel"> Israel </option>
												<option value="Italy"> Italy </option>
												<option value="Jamaica"> Jamaica </option>
												<option value="Japan"> Japan </option>
												<option value="Jersey"> Jersey </option>
												<option value="Jordan"> Jordan </option>
												<option value="Kazakhstan"> Kazakhstan </option>
												<option value="Kenya"> Kenya </option>
												<option value="Kiribati"> Kiribati </option>
												<option value="North Korea"> North Korea </option>
												<option value="South Korea"> South Korea </option>
												<option value="Kosovo"> Kosovo </option>
												<option value="Kuwait"> Kuwait </option>
												<option value="Kyrgyzstan"> Kyrgyzstan </option>
												<option value="Laos"> Laos </option>
												<option value="Latvia"> Latvia </option>
												<option value="Lebanon"> Lebanon </option>
												<option value="Lesotho"> Lesotho </option>
												<option value="Liberia"> Liberia </option>
												<option value="Libya"> Libya </option>
												<option value="Liechtenstein"> Liechtenstein </option>
												<option value="Lithuania"> Lithuania </option>
												<option value="Luxembourg"> Luxembourg </option>
												<option value="Macau"> Macau </option>
												<option value="Macedonia"> Macedonia </option>
												<option value="Madagascar"> Madagascar </option>
												<option value="Malawi"> Malawi </option>
												<option value="Malaysia"> Malaysia </option>
												<option value="Maldives"> Maldives </option>
												<option value="Mali"> Mali </option>
												<option value="Malta"> Malta </option>
												<option value="Marshall Islands"> Marshall Islands </option>
												<option value="Martinique"> Martinique </option>
												<option value="Mauritania"> Mauritania </option>
												<option value="Mauritius"> Mauritius </option>
												<option value="Mayotte"> Mayotte </option>
												<option value="Mexico"> Mexico </option>
												<option value="Micronesia"> Micronesia </option>
												<option value="Moldova"> Moldova </option>
												<option value="Monaco"> Monaco </option>
												<option value="Mongolia"> Mongolia </option>
												<option value="Montenegro"> Montenegro </option>
												<option value="Montserrat"> Montserrat </option>
												<option value="Morocco"> Morocco </option>
												<option value="Mozambique"> Mozambique </option>
												<option value="Myanmar"> Myanmar </option>
												<option value="Nagorno-Karabakh"> Nagorno-Karabakh </option>
												<option value="Namibia"> Namibia </option>
												<option value="Nauru"> Nauru </option>
												<option value="Nepal"> Nepal </option>
												<option value="Netherlands"> Netherlands </option>
												<option value="Netherlands Antilles"> Netherlands Antilles </option>
												<option value="New Caledonia"> New Caledonia </option>
												<option value="New Zealand"> New Zealand </option>
												<option value="Nicaragua"> Nicaragua </option>
												<option value="Niger"> Niger </option>
												<option value="Nigeria"> Nigeria </option>
												<option value="Niue"> Niue </option>
												<option value="Norfolk Island"> Norfolk Island </option>
												<option value="Turkish Republic of Northern Cyprus"> Turkish Republic of Northern Cyprus </option>
												<option value="Northern Mariana"> Northern Mariana </option>
												<option value="Norway"> Norway </option>
												<option value="Oman"> Oman </option>
												<option value="Pakistan"> Pakistan </option>
												<option value="Palau"> Palau </option>
												<option value="Palestine"> Palestine </option>
												<option value="Panama"> Panama </option>
												<option value="Papua New Guinea"> Papua New Guinea </option>
												<option value="Paraguay"> Paraguay </option>
												<option value="Peru"> Peru </option>
												<option value="Philippines"> Philippines </option>
												<option value="Pitcairn Islands"> Pitcairn Islands </option>
												<option value="Poland"> Poland </option>
												<option value="Portugal"> Portugal </option>
												<option value="Puerto Rico"> Puerto Rico </option>
												<option value="Qatar"> Qatar </option>
												<option value="Romania"> Romania </option>
												<option value="Russia"> Russia </option>
												<option value="Rwanda"> Rwanda </option>
												<option value="Saint Barthelemy"> Saint Barthelemy </option>
												<option value="Saint Helena"> Saint Helena </option>
												<option value="Saint Kitts and Nevis"> Saint Kitts and Nevis </option>
												<option value="Saint Lucia"> Saint Lucia </option>
												<option value="Saint Martin"> Saint Martin </option>
												<option value="Saint Pierre and Miquelon"> Saint Pierre and Miquelon </option>
												<option value="Saint Vincent and the Grenadines"> Saint Vincent and the Grenadines </option>
												<option value="Samoa"> Samoa </option>
												<option value="San Marino"> San Marino </option>
												<option value="Sao Tome and Principe"> Sao Tome and Principe </option>
												<option value="Saudi Arabia"> Saudi Arabia </option>
												<option value="Senegal"> Senegal </option>
												<option value="Serbia"> Serbia </option>
												<option value="Seychelles"> Seychelles </option>
												<option value="Sierra Leone"> Sierra Leone </option>
												<option value="Singapore"> Singapore </option>
												<option value="Slovakia"> Slovakia </option>
												<option value="Slovenia"> Slovenia </option>
												<option value="Solomon Islands"> Solomon Islands </option>
												<option value="Somalia"> Somalia </option>
												<option value="Somaliland"> Somaliland </option>
												<option value="South Africa"> South Africa </option>
												<option value="South Ossetia"> South Ossetia </option>
												<option value="Spain"> Spain </option>
												<option value="Sri Lanka"> Sri Lanka </option>
												<option value="Sudan"> Sudan </option>
												<option value="Suriname"> Suriname </option>
												<option value="Svalbard"> Svalbard </option>
												<option value="Swaziland"> Swaziland </option>
												<option value="Sweden"> Sweden </option>
												<option value="Switzerland"> Switzerland </option>
												<option value="Syria"> Syria </option>
												<option value="Taiwan"> Taiwan </option>
												<option value="Tajikistan"> Tajikistan </option>
												<option value="Tanzania"> Tanzania </option>
												<option value="Thailand"> Thailand </option>
												<option value="Timor-Leste"> Timor-Leste </option>
												<option value="Togo"> Togo </option>
												<option value="Tokelau"> Tokelau </option>
												<option value="Tonga"> Tonga </option>
												<option value="Transnistria Pridnestrovie"> Transnistria Pridnestrovie </option>
												<option value="Trinidad and Tobago"> Trinidad and Tobago </option>
												<option value="Tristan da Cunha"> Tristan da Cunha </option>
												<option value="Tunisia"> Tunisia </option>
												<option value="Turkey"> Turkey </option>
												<option value="Turkmenistan"> Turkmenistan </option>
												<option value="Turks and Caicos Islands"> Turks and Caicos Islands </option>
												<option value="Tuvalu"> Tuvalu </option>
												<option value="Uganda"> Uganda </option>
												<option value="Ukraine"> Ukraine </option>
												<option value="United Arab Emirates"> United Arab Emirates </option>
												<option value="United Kingdom"> United Kingdom </option>
												<option value="United States"> United States </option>
												<option value="Uruguay"> Uruguay </option>
												<option value="Uzbekistan"> Uzbekistan </option>
												<option value="Vanuatu"> Vanuatu </option>
												<option value="Vatican City"> Vatican City </option>
												<option value="Venezuela"> Venezuela </option>
												<option value="Vietnam"> Vietnam </option>
												<option value="British Virgin Islands"> British Virgin Islands </option>
												<option value="US Virgin Islands"> US Virgin Islands </option>
												<option value="Wallis and Futuna"> Wallis and Futuna </option>
												<option value="Western Sahara"> Western Sahara </option>
												<option value="Yemen"> Yemen </option>
												<option value="Zambia"> Zambia </option>
												<option value="Zimbabwe"> Zimbabwe </option>
												<option value="other"> Other </option>
											</select>
										</div>
									</div> <!-- id="form_Nationality" -->		

									<div id="form_Status" class="form-group">
										<label for="civilStatus" class="control-label col-xs-3">Estado Civil</label>
										<div class="col-xs-9">
											<select name="civilStatus" class="form-control">
												<option selected disabled value="">-- Estado --</option>
												<option value="1">Soltero/a</option>
												<option value="2">Casado/a</option>
												<option value="3">Divorciado/a</option>
												<option value="4">Viudo/a</option>
												<option value="5">Separado/a</option>
											</select>
										</div>
									</div> <!-- id="form_Status" -->			

									<div id="form_genre" class="form-group">
										<label for="blankSex" class="control-label col-xs-3">Sexo</label>
										<div class="col-xs-3" style="padding: 10px;">
											<label><input type="radio" name="blankSex" value="0">Hombre</label>
										</div>
										<div class="col-xs-3" style="padding: 10px;">
											<label><input type="radio" name="blankSex" value="1">Mujer</label>
										</div>
									</div> <!-- id="form_genre" -->		

									<div id="form_childrens" class="form-group">
										<label for="blankSons" class="control-label col-xs-3">Hijos</label>
										<div class="col-xs-9">
											<input type="number" class="form-control" name="blankSons" id="blankSons" maxlength="2">
										</div>
									</div> <!-- id="form_NIE" -->


									<div id="form_Languages" class="form-group">
										<label for="blankLanguages" class="control-label col-xs-3">Nivel de Idiomas</label>
										<div class="col-xs-4">
											<select name="blankLanguages" class="form-control">
												<option selected disabled value=''>Idioma</option>
												<option value="german">Alemán</option>
												<option value="english">Inglés</option>
												<option value="spanish">Español</option>
												<option value="french">Francés</option>
												<option value="portuguese">Portugués</option>
												<option value="italian">Italiano</option>
											</select>
										</div>
										<div class="col-xs-5">
											<select name="languagelevel" class="form-control">
												<option selected disabled value="">Sin conocimientos</option>
												<option value="basic">Básico hablado y escrito</option>
												<option value="medium">Medio hablado y escrito</option>
												<option value="high">Alto hablado y escrito</option>
												<option value="bilingual">Bilingüe</option>
										</select>
										</div>
									</div> <!-- id="form_languages" -->		

									<div id="form_Profession" class="form-group">
										<label for="blankJob" class="control-label col-xs-3">Profesión</label>
										<div class="col-xs-9">
											<input type="text" class="form-control" name="blankJob" id="blankJob" maxlength="12" placeholder="Profesión actual">
										</div>
									</div> <!-- id="form_Profession" -->

									<div id="form_Title" class="form-group">
										<label for="titleType" class="control-label col-xs-3">Formación</label>
										<div class="col-xs-3">
											<select name="tittletype" class="form-control">
												<option selected disabled value="">Sin estudios</option>
												<option value="1">Educación obligatoria</option>
												<option value="2">Bachillerato</option>
												<option value="3">Formación profesional</option>
												<option value="4">Diplomatura</option>
												<option value="5">Licenciatura</option>
											</select>
										</div>
										<div class="col-xs-6">
											<input type="text" class="form-control" name="tittles" placeholder="Estudios" />	
										</div>
									</div> <!-- id="form_Title" -->

									<div id="report_set" class="panel panel-default">
  										<div class="panel-body">
											<div id="form_report" class="form-group">
												<label for="reportType" class="control-label col-xs-3">Tipo de Informe</label>
												<div class="col-xs-3" style="padding: 10px;">
													<label><input type="radio" name="reportType" value="full_report" onclick="test(2);" checked>Completo</label>
												</div>
												<div class="col-xs-3" style="padding: 10px;">
													<label><input type="radio" name="reportType" value="blind_report" onclick="test(2);">Ciego</label>
												</div>
												<div class="col-xs-3" style="padding: 10px;">
													<label><input type="radio" name="reportType" value="custom_report" onclick="test(1);">Personalizado</label>
												</div>										
											</div> <!-- id="form_report" -->	

											<hr>									

											<div id="form_custom_report" class="form-group">
												<label><input type="checkbox" name="per[]" value="name" disabled> Nombre</label>
												<label><input type="checkbox" name="per[]" value="surname" disabled> Apellidos</label>
												<label><input type="checkbox" name="per[]" value="addrName" disabled> Direccion</label>
												<label><input type="checkbox" name="per[]" value="phone" disabled> Telefono Fijo</label>
												<label><input type="checkbox" name="per[]" value="mobile" disabled> Telefono Movil</label>
												<label><input type="checkbox" name="per[]" value="mail" disabled> Email</label>
												<label><input type="checkbox" name="per[]" value="drivingType" disabled> Carnet Conducir</label>
												<label><input type="checkbox" name="per[]" value="marital" disabled> Estado Civil</label>
												<label><input type="checkbox" name="per[]" value="sons" disabled> Hijos Civil</label>
												<label><input type="checkbox" name="per[]" value="language" disabled> Idiomas</label>
												<label><input type="checkbox" name="per[]" value="occupation" disabled> Profesion</label>
												<label><input type="checkbox" name="per[]" value="experDesc" disabled> Experiencia Laboral</label>
											</div>
										</div>
									</div>

									<div id="form_submit" class="form-group pull-right" style="margin: 1px;">
										<button type="submit" name="Buscar" class="btn btn-success" >Buscar <span class="glyphicon glyphicon-search"> </span></button>
									</div>

								</form> <!-- id="searchForm" -->

							</div> <!-- class="panel-body" -->
						</div> <!-- class="panel panel-default" -->
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
	<script src="../../common/js/functions.js"></script>
	<script src="../../common/js/application.js"></script>
	<script src="../../common/js/docs.min.js"></script>

	<!-- Page own functions -->
	<script type="text/javascript">
		function test (temp){
			switch (temp){
				case 1    :
				var x = document.getElementById("searchForm");
				var texto = "";
				for (var i=0;i<x.length;i++){
					var pattern=/per/i
					if (pattern.test(x.elements[i].name)){
						x.elements[i].disabled = false ;
						texto = texto + x.elements[i].name + "<br>";
					}
				}
				break;

				case 2    :
				var x = document.getElementById("searchForm");
				var texto = "";
				for (var i=0;i<x.length;i++){
					var pattern=/per/i
					if (pattern.test(x.elements[i].name)){
						x.elements[i].disabled = true ;
						texto = texto + x.elements[i].name + "<br>";
					}
				}
				break;

				default    :
				alert('What to do?');
			}
		}
	</script>
	
	<script type="text/javascript">
		function comprobar(){
			var x = document.getElementById("searchForm");
			var texto = "";
			for (var i=0;i<x.length;i++){
				texto = texto + x.elements[i].name + "<br>";
			}
			//alert(document.getElementById("searchForm"));
			//alert(texto);

		}
	</script>

</body>
</html>

