<? session_start(); ?>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<link href='http://fonts.googleapis.com/css?family=Ubuntu+Mono:400,700,400italic,700italic|Ubuntu:300,400,500,700,300italic,400italic,500italic,700italic|Ubuntu+Condensed&
subset=latin,latin-ext' rel='stylesheet' type='text/css'>
	<title>Inicio</title>
	<link href="../../common/css/styles.css" rel="stylesheet" type="text/css">
	<script src="../../common/js/functions.js" type="text/javascript"></script>
	
	<script type="text/javascript">
	function test (temp){
		switch (temp){
			case 1    :
				var x = document.getElementById("form1");
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
				var x = document.getElementById("form1");
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
		var x = document.getElementById("form1");
		var texto = "";
		for (var i=0;i<x.length;i++){
			texto = texto + x.elements[i].name + "<br>";
		}
		alert(texto);
	}
	</script>
	
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
		<!-- Podemos poner un asteristo para los campos que sean obligatorios y un comentario en algún lado indicándolo -->
		<!-- <div id="stylized" class="myform"> -->
		<div id="auto0" class="myform">
			<h1>Formulario de Búsqueda</h1>
			<!-- <form id="form1" name="form1" method="post" action="searchResult.php" autocomplete="off" enctype="multipart/form-data" onsubmit="return comprobar()";> -->
			<form id="form1" name="form1" method="post" action="searchResult.php" autocomplete="off" enctype="multipart/form-data" onsubmit="return comprobar()";>
				<table>
					<!--
					<tr>
						<td>Nombre</td>
						<td><input type="text" name="blankname" size="30" maxlength="20" /></td>
					</tr>
					<tr>
						<td>Apellidos</td>
						<td><input type="text" name="blanksurname" size="30" maxlength="30" /></td>
					</tr>
					<tr>
						<td>Fecha de Nacimiento</td>
						<td><input type="date" name="blankbirthdate" /></td>
					</tr>
					
					<tr>
						<td>Dirección</td>
						<td>
							<select name="blankaddrtype">
								<option value="" selected>-- Tipo --</option>
								<option value="Acceso">Acceso</option>
								<option value="Acera">Acera</option>
								<option value="Alameda">Alameda</option>
								<option value="Autopista">Autopista</option>
								<option value="Autovía">Autovía</option>
								<option value="Avenida">Avenida</option>
								<option value="C. Comercial">C. Comercial</option>
								<option value="Calle">Calle</option>
								<option value="Callejón">Callejón</option>
								<option value="Camino">Camino</option>
								<option value="Cañada">Cañada</option>
								<option value="Carrer">Carrer</option>
								<option value="Carrera">Carrera</option>
								<option value="Carretera">Carretera</option>
								<option value="Cuesta">Cuesta</option>
								<option value="Glorieta">Glorieta</option>
								<option value="Pasadizo">Pasadizo</option>
								<option value="Pasaje">Pasaje</option>
								<option value="Paseo">Paseo</option>
								<option value="Plaza">Plaza</option>
								<option value="Rambla">Rambla</option>
								<option value="Ronda">Ronda</option>
								<option value="Sendero">Sendero</option>
								<option value="Travesía">Travesía</option>
								<option value="Urbanización">Urbanización</option>
								<option value="Vía">Vía</option>
							</select>
							<input type="text" name="blankaddrname" size="50" maxlength="50" placeholder="Nombre" />
							<input type="text" name="blankaddrnum" size="5" maxlength="10" placeholder="Num" />
							<input type="text" name="blankaddrportal" size="5" maxlength="10" placeholder="Portal" />
							<input type="text" name="blankaddrstair" size="5" maxlength="10" placeholder="Escalera" />
							<input type="text" name="blankaddrfloor" size="5" maxlength="10" placeholder="Piso" />
							<input type="text" name="blankaddrdoor" size="5" maxlength="10" placeholder="Puerta" /><br>
							<input type="text" name="blankaddrpostalcode" size="10" maxlength="10" placeholder="Código Postal" />
							<input type="text" name="blankaddrcountry" size="10" maxlength="10" placeholder="Pais" />				
							<input type="text" name="blankaddrprovince" size="10" maxlength="10" placeholder="Provincia" />
							<input type="text" name="blankaddrcity" size="50" maxlength="50" placeholder="Población" />
						</td>
					</tr>
					<tr>
						<td>Teléfono Fijo</td>
						<td><input type="text" name="blankphone" size="30" maxlength="9" /></td>
					</tr>
					<tr>
						<td>Teléfono Móvil</td>
						<td><input type="text" name="blankmobile" size="30" maxlength="12" /></td>
					</tr>
					<tr>
						<td>Correo Electrónico</td>
						<td><input type="email" name="blankmail" size="30" 	placeholder="correo@ejemplo.com" /></td>
					</tr>
					
					<tr>
						<td>Carné de Conducir</td>
						<td>
						<select name="blankdrivingtype">
							<option value=""> Tipo </option>
							<option value="AM">AM</option>
							<option value="A">A</option>
							<option value="A1">A1</option>
							<option value="A2">A2</option>
							<option value="B">B</option>
							<option value="C">C</option>
							<option value="C1">C1</option>
							<option value="D">D</option>
							<option value="D1">D1</option>
							<option value="E">E</option>
							<option value="BTP">BTP</option>
						</select>
						<input type="date" name="blankdrivingdate" />
						</td>
					</tr>
					<tr>
						<td>Estado Civil</td>
						<td>
						<select name="blankmarital">
							<option selected value="">-- Estado --</option>
							<option value="single">Soltero/a</option>
							<option value="married">Casado/a</option>
							<option value="divorced">Divorciado/a</option>
							<option value="widow">Viudo/a</option>
							<option value="separated">Separado/a</option>
						</select>
						</td>
					</tr>
					
					<tr>
						<td>Documentos adicionales</td>
						<td id="adjuntos"><input type="file" name="archivos[]" file-accept="pdf, doc, docx, xls, xlsx, csv, txt, rtf, html, zip, mp3, wma, mpg, flv, avi, jpg, jpeg, png, gif" file-maxsize="1024" />
						<a href="#" onClick="addCampo()">&oplus;</a>
						</td>
					</tr>
					
					
					<tr>
						<td>Experiencia Laboral</td>
						<td>
						<input type="text" name="blankcompany" size="30" placeholder="Empresa" />
						<select name="blankcategory">
							<option value="intern">Becario</option>
							<option value="employee">Empleado</option>
							<option value="middle">Mando intermedio</option>
							<option value="director">Director</option>
						</select>
						<input type="text" name="blankexptime" size="10" placeholder="Duración" />
						<textarea name="blankexpdesc" rows="5" cols="40">Descripción</textarea><a href="#">&oplus;</a>
						</td>
					</tr>
					
					<tr>
						<td>Otros Detalles de Interés</td>
						<td><textarea name="blankother" rows="5" cols="40">...</textarea></td>
					</tr>
					
					<tr>
						<td>Las 10 palabras que mejor me definen son...</td>
						<td>
						<input type="text" name="blankword1" size="30" /><br>
						<input type="text" name="blankword2" size="30" /><br>
						<input type="text" name="blankword3" size="30" /><br>
						<input type="text" name="blankword4" size="30" /><br>
						<input type="text" name="blankword5" size="30" /><br>
						<input type="text" name="blankword6" size="30" /><br>
						<input type="text" name="blankword7" size="30" /><br>
						<input type="text" name="blankword8" size="30" /><br>
						<input type="text" name="blankword9" size="30" /><br>
						<input type="text" name="blankword10" size="30" />
						</td>
					</tr>
					-->
				
				
					<tr>
						<td>NIE</td>
						<td><input type="text" name="blanknie" size="30" maxlength="12" placeholder="Max. 12 caracteres"/></td>
					</tr>
                                
					<tr>
						<td>Carné de Conducir</td>
						<td>
							<select name="drivingtype">
								<option value="0"> Tipo </option>
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
							<input type="date" name="drivingdate" />
						</td>
					</tr>
					
					
					<tr>
						<td>Nacionalidad</td>
						<td>
							<select name="blanknationality">
								<option selected disabled hidden value=''></option>"
								
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
                        </td>
					</tr>
					
					<tr>
						<td>Estado Civil</td>
						<td>
							<select name="civil">
								<option value="0">-- Estado --</option>
								<option value="1">Soltero/a</option>
								<option value="2">Casado/a</option>
								<option value="3">Divorciado/a</option>
								<option value="4">Viudo/a</option>
								<option value="5">Separado/a</option>
							</select>
						</td>
					</tr>
					
					<tr>
						<td>Sexo</td>
						<td>
							<input type="radio" name="blanksex" value="0">Hombre
							<input type="radio" name="blanksex" value="1">Mujer
						</td>
					</tr>
					
                                <tr>
                                        <td>Hijos</td>
                                        <td><input type="number" name="blanksons" maxlength="2"></td>
                                </tr>



                                <tr>
                                        <td>Nivel de Idiomas</td>
                                        <td>
                                        <select name="languages">
                                                <option value="0">Alemán</option>
                                                <option value="1">Inglés</option>
                                                <option value="2">Español</option>
                                                <option value="3">Francés</option>
                                                <option value="4">Portugués</option>
                                                <option value="5">Italiano</option>
                                        </select>
                                        <select name="languagelevel">
                                                <option value="0">Sin conocimientos</option>
                                                <option value="1">Básico hablado y escrito</option>
                                                <option value="2">Medio hablado y escrito</option>
                                                <option value="3">Alto hablado y escrito</option>
                                                <option value="4">Bilingüe</option>
                                        </select>
                                        </td>


                                <tr>
                                        <td>Profesión</td>
                                        <td><input type="text" name="job" size="50" placeholder="Profesión actual" /></td>
                                </tr>

                                <tr>
                                        <td>Formación</td>
                                        <td>
                                        <select name="tittletype">
                                                <option value="0">Sin estudios</option>
                                                <option value="1">Educación obligatoria</option>
                                                <option value="2">Bachillerato</option>
                                                <option value="3">Formación profesional</option>
                                                <option value="4">Diplomatura</option>
                                                <option value="5">Licenciatura</option>
                                        </select>
                                        <input type="text" name="tittles" size="30" placeholder="Estudios" />
                                        </td>
                                </tr>
                                <tr><td><b> TIPO DE INFORME </b></td></tr>
                                <tr>
                                <td>completo<input type="radio" name="radiobutton" value="completo" onclick="test(2);" checked></td>
                                <td>ciego<input type="radio" name="radiobutton" value="completo" onclick="test(2);"></td>
                                <td>personalizado <input type="radio" name="radiobutton" value="personalizado" onclick="test(1);"></td>
                                </tr>
                        </table>
                        <table>
                                <input type="checkbox" name="per1" value="Nombre" disabled> Nombre
                                <input type="checkbox" name="per2" value="Apellidos" disabled> Apellidos
                                <input type="checkbox" name="per8" value="Direccion" disabled> Direccion
                                <input type="checkbox" name="per9" value="Tfijo" disabled> Telefono Fijo
                                <input type="checkbox" name="per10" value="Tmovil" disabled> Telefono Movil
                                <input type="checkbox" name="per11" value="Email" disabled> Email
                                <input type="checkbox" name="per3" value="Cconducir" disabled> Carnet Conducir
                                <input type="checkbox" name="per4" value="Ecivil" disabled> Estado Civil
                                <input type="checkbox" name="per5" value="Hijos" disabled> Hijos Civil
                                <input type="checkbox" name="per6" value="Nidiomas" disabled> Idiomas
                                <input type="checkbox" name="per7" value="Profesion" disabled> Profesion
                                <input type="checkbox" name="per12" value="Formacion" disabled> Formacion
                                <input type="checkbox" name="per13" value="Experiencia" disabled> Experiencia Laboral
                                </tr>
                        </table>
                        <button name="Buscar" type="submit">Busqueda</button>

                </form>

        </div>
		
		
		
		
		
		
			<?php
			/* 
			echo "<p><span id='leftmsg'>Búsqueda de CVs</span></p><hr>";
			if((getDBrowsnumber('cVitaes') == 0) || (count($cvIDs = getDBcolumnvalue('id', 'cVitaes', 'cvStatus', 'checked')) == 0)){
				echo 'No hay CVs clasificados';
			}
			else{
				?>
				<table class="tabla1">
				<tr>
					<th>NIE</th>
					<th>Nombre</th>
					<th>Apellidos</th>
					<th>Acción (Eliminar)</th>
				</tr>
				<?php 
				foreach($cvIDs as $i){
					$cvRow = getDBrow('cVitaes', 'id', $i);
					echo "<tr>";
					echo "<td><a href='editCurCV.php?codvalue=" . $cvRow['nie'] . "'>" . $cvRow['nie'] . "</a></td>";
					echo "<td>" . utf8_encode($cvRow['name']) . "</td>";
					echo "<td>" . utf8_encode($cvRow['surname']) . "</td>";
					echo "<td><a href='delCurCV.php?codvalue=" . $cvRow['nie'] . "'>Borrar</a></td>";
					echo "</tr>";
				}
				?>
				</table>
				<?php 
			}
			*/
			
			
			
			
			
			
			?>
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
		</div><!-- Fin del "rightbox" -->
	</div><!-- Fin del "workspace" -->
	<?php
}//del "else" de $_SESSION.

?>

</body>
</html>
