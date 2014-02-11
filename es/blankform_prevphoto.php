<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<!-- Utilizamos fuentes desde Google Fonts API, sin necesidad de descargarlas -->
	<link href='http://fonts.googleapis.com/css?family=Ubuntu+Mono:400,700,400italic,700italic|Ubuntu:300,400,500,700,300italic,400italic,500italic,700italic|Ubuntu+Condensed&
subset=latin,latin-ext' rel='stylesheet' type='text/css'>
	<title>Formulario</title>
	<link href="./teststyle.css" rel="stylesheet" type="text/css">
	<!-- <script src="./Functions.js" type="text/javascript"></script> -->
</head>

<body>

	<?php
	
	//require_once ('./library/Functions.php');
	
	?>
	<!-- Podemos poner un asteristo para los campos que sean obligatorios y un comentario en algún lado indicándolo -->
	<div id="stylized" class="myform">
		<form id="form" name="form" method="post" action="" autocomplete="off">
			<h1>Formulario</h1>

			<label>Nombre</label>
			<input type="text" name="blankname" size="20" maxlength="20" /><br>

			<label>Apellidos</label>
			<input type="text" name="blanksurname" size="20" maxlength="30" /><br>
			
			<label>DNI/Pasaporte</label>
			<input type="text" name="blankdni" size="10" maxlength="10" /><br>
			
			<label>Dirección</label>
			<select name="blankstreettype">
				<option value="0">-- Tipo --</option>
				<option value="1">Acceso</option>
				<option value="2">Acera</option>
				<option value="3">Alameda</option>
				<option value="4">Autopista</option>
				<option value="5">Autovía</option>
				<option value="6">Avenida</option>
				<option value="7">C. Comercial</option>
				<option value="8">Calle</option>
				<option value="9">Callejón</option>
				<option value="10">Camino</option>
				<option value="11">Cañada</option>
				<option value="12">Carrer</option>
				<option value="13">Carrera</option>
				<option value="14">Carretera</option>
				<option value="15">Cuesta</option>
				<option value="16">Glorieta</option>
				<option value="17">Pasadizo</option>
				<option value="18">Pasaje</option>
				<option value="19">Paseo</option>
				<option value="20">Plaza</option>
				<option value="21">Rambla</option>
				<option value="22">Ronda</option>
				<option value="23">Sendero</option>
				<option value="24">Travesía</option>
				<option value="25">Urbanización</option>
				<option value="26">Vía</option>
			</select>
			<input type="text" name="blankstreetname" size="30" maxlength="50" placeholder="Nombre" />
			<input type="text" name="blankstreetnum" size="5" maxlength="10" placeholder="Número" />
			<input type="text" name="blankstreetfloor" size="5" maxlength="10" placeholder="Piso" /><br>
			
			<label>Teléfono Fijo</label>
			<input type="text" name="blankphone" size="15" maxlength="12" /><br>
			
			<label>Teléfono Móvil</label>
			<input type="text" name="blankmobile" size="15" maxlength="12" /><br>
			
			<label>Correo Electrónico</label>
			<input type="email" name="blankmail" size="30" 	placeholder="correo@ejemplo.com" /><br>
			
			<label>Carné de Conducir</label>
			<select name="blankdrivingtype">
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
			<input type="date" name="blankdrivingdate" /><br>
			
			<label>Estado Civil</label>
			<select name="blankcivil">
				<option value="0">-- Estado --</option>
				<option value="1">Soltero/a</option>
				<option value="2">Casado/a</option>
				<option value="3">Divorciado/a</option>
				<option value="4">Viudo/a</option>
				<option value="5">Separado/a</option>
			</select><br>
			
			<label>Hijos</label>
			0<input type="range" name="blanksons" min="0" max="10" />10<br>
			<!-- Hemos interpretado que no es posible tener más de 10 hijos, aunque es modificable -->
			<!-- Este tipo no está soportado por IE -->
			
			<label>Foto</label>
			<input type="text" name="blankphoto" size="10" placeholder="desplegable" /><br>
			
			<label>Curriculum en Word</label>
			<input type="text" name="blankcv" size="10" placeholder="desplegable" /><br>
			
			<label>Nivel de Idiomas</label>
			<input type="text" name="blanklanguage" size="10" placeholder="desplegable" />PONER AQUI UN + EN JS<br>
			
			<label>Profesión</label>
			<input type="text" name="blankjob" size="10" placeholder="desplegable" />PIDEN QUE SEA COMO EL ANTERIOR PERO YO ENTIENDO QUE SOLO SE PUEDE SER UNA COSA A LA VEZ...<br>
			
			<label>Formación</label>
			<input type="text" name="blanktittles" size="10" placeholder="desplegable" />PONER AQUI UN + EN JS<br>
			
			<label>Experiencia Laboral</label>
			<input type="text" name="blankexptime" size="10" placeholder="desplegable" />
			<input type="text" name="blankexppos" size="30" placeholder="desplegable" />
			<input type="text" name="blankexpdesc" size="50" placeholder="desplegable" />PONER AQUI UN + EN JS<br>
			
			<label>Otros Detalles de Interés</label>
			<input type="text" name="blankother" size="50" placeholder="desplegable" /><br>
			
			<label>Las 10 palabras que mejor me definen son...</label><br>
			<input type="text" name="blankword1" size="20" /><br>
			<input type="text" name="blankword2" size="20" /><br>
			<input type="text" name="blankword3" size="20" /><br>
			<input type="text" name="blankword4" size="20" /><br>
			<input type="text" name="blankword5" size="20" /><br>
			<input type="text" name="blankword6" size="20" /><br>
			<input type="text" name="blankword7" size="20" /><br>
			<input type="text" name="blankword8" size="20" /><br>
			<input type="text" name="blankword9" size="20" /><br>
			<input type="text" name="blankword10" size="20" /><br>
			
			<input type="checkbox" name="blanklopd" /> He leído y acepto las condiciones de uso y política de privacidad<br>
			
			<button name="senduser" type="submit">Enviar solicitud</button>
			
		</form>
		
	</div>
	
	</body>
</html>
