<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<title>Validación un formulario</title>
	<script type="text/javascript">
	
		//Esta es una variable de control para mantener nombres diferentes de cada campo creado dinamicamente. 
		var numero = 0;
		
		//La siguiente funcion nos devuelve el tipo de evento disparado 
		evento = function (evt) {
		   return (!evt) ? event : evt;
		}
		
		//La siguiente funcion crea dinamicamente los nuevos campos file 
		addCampo = function () { 
			//Creamos un nuevo div para que contenga el nuevo campo
			nDiv = document.createElement('div');
			//Con esto se establece la clase de la div 
			nDiv.className = 'archivo';
			//Este es el id de la div, aqui la utilidad de la variable numero nos permite darle un id unico 
			nDiv.id = 'file' + (++numero);
			//Creamos el input para el formulario: 
			nCampo = document.createElement('input');
			//Le damos un nombre, es importante que lo nombren como vector, pues todos los campos compartiran el nombre en un arreglo, asi es mas facil procesar posteriormente con php 
			nCampo.name = 'archivos[]';
			//Establecemos el tipo de campo
			nCampo.type = 'file';
			//Ahora creamos un link para poder eliminar un campo que ya no deseemos 
			a = document.createElement('a');
			//El link debe tener el mismo nombre de la div padre, para efectos de localizarla y eliminarla 
			a.name = nDiv.id;
			//Este link no debe ir a ningun lado
			a.href = '#';
			//Establecemos que dispare esta funcion al pincharse sobre ella 
			a.onclick = elimCamp;
			//Con esto ponemos el texto del link
			//a.innerHTML = 'Eliminar';
			//a.innerHTML = '&minus;';
			a.innerHTML = '&otimes;';
			//Ahora se integra lo que hemos creado al documento, para ello la función appendChild se usa para añadir el campo file nuevo 
			nDiv.appendChild(nCampo);
			//Y justo aquí añadimos el Link 
			nDiv.appendChild(a);
			//Ahora si recuerdan, en el html hay una div cuyo id es 'adjuntos', bien con esta función obtenemos una referencia a ella para usar de nuevo appendChild 
			//y añadir la div que hemos creado, la cual contiene el campo file con su link de eliminación:
			container = document.getElementById('adjuntos');
			container.appendChild(nDiv);
		}
		
		//Con esta función eliminamos el campo cuyo link de eliminación sea presionado 
		elimCamp = function (evt){
			evt = evento(evt);
			nCampo = rObj(evt);
			div = document.getElementById(nCampo.name);
			div.parentNode.removeChild(div);
		}
		
		//Con esta función recuperamos una instancia del objeto que disparo el evento 
		rObj = function (evt) { 
			return evt.srcElement ?  evt.srcElement : evt.target;
		}
	</script>
	
	<link rel="stylesheet" href="//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">
	<script src="//code.jquery.com/jquery-1.9.1.js"></script>
	<script src="//code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
	<link rel="stylesheet" href="/resources/demos/style.css">
	
	<script>
		$(function(){
			$( "#datepicker" ).datepicker();
		});
		
		//Functions used to add/remove realtime Language fields 
		var rowNum = 0;
		function addRow1(frm){
			rowNum ++;
			var row = '<p id="rowNum'+rowNum+'"><input type="text" name="idiomas[]" value="'+frm.add_idiomas.value+'"><input type="text" name="nidiomas[]" size="4" value="'+frm.add_nidiomas.value+'"> <input type="button" value="Eliminar" onclick="removeRow1('+rowNum+');"></p>';
			jQuery('#itemRows').append(row);
			frm.add_idiomas.value = '';
			frm.add_nidiomas.value = '';
		}
		
		function removeRow1(rnum){
			jQuery('#rowNum'+rnum).remove();
		}
		
		//Functions used to add/remove realtime Education fields 
		var rowNum = 0;
		function addRow3(frm){
			rowNum ++;
			var row = '<p id="rowNum'+rowNum+'"><input type="text" name="educ[]" size="30" value="'+frm.add_educ.value+'"><input type="button" value="Eliminar" onclick="removeRow3('+rowNum+');"></p>';
			jQuery('#itemRows3').append(row);
			frm.add_educ.value = '';
		}
		
		function removeRow3(rnum){
			jQuery('#rowNum'+rnum).remove();
		}
		
		function addRow4(frm){
			rowNum ++;
			var row = '<p id="rowNum'+rowNum+'"><input type="text" name="empr[]" value="'+frm.add_empr.value+'"><input type="text" name="categ[]" value="'+frm.add_categ.value+'" ><input type="text" name="expstart[]" value="'+frm.add_expstart.value+'"><input type="text" name="expend[]" value="'+frm.add_expend.value+'"><input type="text" name="desc[]" value="'+frm.add_desc.value+'"><input type="button" value="Eliminar" onclick="removeRow4('+rowNum+');"></p>';
			jQuery('#itemRows4').append(row);
			frm.add_empr.value = '';
			frm.add_categ.value = '';
			frm.add_expstart.value = '';
			frm.add_expend.value = '';
			frm.add_desc.value = '';
		}
		
		function removeRow4(rnum){
			jQuery('#rowNum'+rnum).remove();
		}
		function addRow5(frm){
			rowNum ++;
			var row = '<p id="rowNum'+rowNum+'"><input type="text" name="nat[]" value="'+frm.add_nat.value+'"><input type="button" value="Eliminar" onclick="removeRow5('+rowNum+');"></p>';
			jQuery('#itemRows5').append(row);
			frm.add_nat.value = '';
		}
		
		function removeRow5(rnum){
			jQuery('#rowNum'+rnum).remove();
		}
	</script>
	
	<!-- 

	<script type="text/javascript">
		function checkNIE(dni){
			var numero;
			var let;
			var letra;
			var expresion_regular_dni;
			
			expresion_regular_dni = /^\d{8}[a-zA-Z]$/;
	
			if(expresion_regular_dni.test (dni) == true){
				numero = dni.substr(0,dni.length-1);
				let = dni.substr(dni.length-1,1);
				numero = numero % 23;
				letra='TRWAGMYFPDXBNJZSQVHLCKET';
				letra=letra.substring(numero,numero+1);
				if(letra!=let){
					//alert('Dni erroneo, la letra del NIF no se corresponde');
					return false;
				}
				else{
					//alert('Dni correcto');
					return true;
				}
			}
			else{
				//alert('Dni erroneo, formato no válido');
				return false;
			}
		}
	</script>
	-->
	<!-- 
	<script type="text/javascript">
	function checkFormES(form){
		var result = true;
		var message = "Por favor revise lo siguiente:\n";
		
		if(form.elements["blankname"].value == null || form.elements["blankname"].value.length == 0 || /^\s+$/.test(form.elements["blankname"].value) ){
			message += "El campo nombre no puede estar vacío.\n";
			result = false;
		}
		if(form.elements["blanksurname"].value == null || form.elements["blanksurname"].value.length == 0 || /^\s+$/.test(form.elements["blanksurname"].value) ){
			message += "El campo apellido no puede estar vacío.\n";
			result = false;
		}
		if(form.elements["blankbirthdate"].value == ""){
			message += "El campo fecha no puede estar vacío.\n";
			result = false;
		}
		if(form.elements["blanknie"].value == "" || /^\s+$/.test(form.elements["blanknie"].value) ){
			message += "El campo NIE no puede estar vacío.\n";
			result = false;
		}
		if(!checkNIE(form.elements["blanknie"].value)){
			//message += "El campo NIE no está debidamente escrito\n";
			message += "Revise su NIE (debe incluir la letra en mayúscula)\n";
			result = false;
		}
		if(form.elements["blanknationality"].value == ""){
			message += "El campo Nacionalidad no puede estar vacío.\n";
			result = false;
		}
		if(form.elements["blanksex"].value == ""){
			message += "Debe seleccionar el Sexo.\n";
			result = false;
		}
		if(form.elements["blankaddrtype"].value == ""){
			message += "Debe seleccionar el tipo de dirección.\n";
			result = false;
		}
		if(form.elements["blankaddrname"].value == "" || /^\s+$/.test(form.elements["blankaddrname"].value) ){
			message += "El campo Nombre de la Dirección no puede estar vacío.\n";
			result = false;
		}
		if(form.elements["blankaddrpostalcode"].value == "" || form.elements["blankaddrpostalcode"].value.length != 5 || /^\s+$/.test(form.elements["blankaddrpostalcode"].value) ){
			message += "El campo Código Postal debe estar formado por 5 números.\n";
			result = false;
		}
		if(form.elements["blankaddrcountry"].value == "" || /^\s+$/.test(form.elements["blankaddrcountry"].value) ){
			message += "El campo País no puede estar vacío.\n";
			result = false;
		}
		if(form.elements["blankaddrprovince"].value == "" || /^\s+$/.test(form.elements["blankaddrprovince"].value) ){
			message += "El campo Provincia no puede estar vacío.\n";
			result = false;
		}
		if(form.elements["blankaddrcity"].value == "" || /^\s+$/.test(form.elements["blankaddrcity"].value) ){
			message += "El campo Población no puede estar vacío.\n";
			result = false;
		}
		var phonePattern = new RegExp("^[9][0-9]{8}$");
		if(form.elements["blankphone"].value == "" || !phonePattern.test(form.elements["blankphone"].value)){
			message += "El campo Teléfono debe estar formado por 9 dígitos, comenzando por 9.\n";
			result = false;
		}
		var mobPattern = new RegExp("^[6|7][0-9]{8}$");
		if(form.elements["blankmobile"].value == "" || !mobPattern.test(form.elements["blankmobile"].value)){
			message += "El campo Móvil debe estar formado por 9 dígitos, comenzando por 6 ó 7.\n";
			result = false;
		}
		//var mailPattern = new RegExp(?:[a-z0-9!#$%&'*+/=?^_{|}~-]+(?:.[a-z0-9!#$%&'*+/=?^_{|}~-]+)*|"(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21\x23-\x5b\x5d-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])*")@(?:(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?|\[(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?|[a-z0-9-]*[a-z0-9]:(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21-\x5a\x53-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])+)\]);
		var mailPattern = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
		if(form.elements["blankmail"].value == "" || !mailPattern.test(form.elements["blankmail"].value)){
			message += "El campo Mail no es válido o esta vacío.\n";
			result = false;
		}
		if(form.elements["blankmarital"].value == ""){
			message += "Debe seleccionar su Estado civil.\n";
			result = false;
		}
		if(!document.formu.blanklopd.checked){
			message += "Debe aceptar las condiciones de uso y privacidad para continuar.\n";
			result = false;
		}
		if(result == false){
			alert(message);
		}
		this.form=form;
	}
	</script>
	-->
	
</head>

<body>

<?php
	require_once($_SERVER['DOCUMENT_ROOT'] . '/common/library/functions.php');
	require_once($_SERVER['DOCUMENT_ROOT'] . '/common/library/SimpleImage.php');
	
	if(isset($_POST['push_button'])){
	#echo "entro en el if $_POST[senduser]";
		foreach ($_POST as $key => $entry){
				#echo $key;
			if(is_array($entry)){
				if($key == idiomas){
					//str_idiomas es 'language' en la BD (en addRow1)
					$str_idiomas = implode('|',$entry);
				}
				if($key == nidiomas){
					//str_nidiomas es 'langLevel' en la BD (en addRow1)
					$str_nidiomas = implode('|',$entry);
				}
				if($key == educ){
					//str_educ es 'education' en la BD (en addRow3)
					$str_educ = implode('|', $entry);
				}
				if($key == empr){
					//str_empr es 'experCompany' en la BD (en addRow4)
					$str_empr = implode('|',$entry);
				}
				if($key == categ){
					//str_categ es 'experPos' en la BD (en addRow4)
					$str_categ = implode('|',$entry);
				}
				if($key == expstart){
					//str_expstart es 'experStart' en la BD (en addRow4)
					$str_expstart = implode('|',$entry);
				}
				if($key == expend){
					//str_expend es 'experEnd' en la BD (en addRow4)
					$str_expend = implode('|',$entry);
				}
				if($key == desc){
					//str_desc es 'experDesc' en la BD (en addRow4)
					$str_desc = implode('|',$entry);
				}
				/*
				if($key == nat){
					//str_nat es 'nationalities' en la BD (en addRow5)
					$str_nat = implode('|',$entry);
				}
				*/
				if($key == nat){
					//str_nat es 'nationalities' en la BD (en addRow5)
					if(isset($key)){
						//This is made to avoid as possible SQL Injection
						checkNationality($entry, $outNations);
						$str_nat = $outNations;
						//echo 'str_nat es: '.$str_nat;
					}
					//$str_nat = implode('|',$entry);
				}
				#print $key . ": " . implode(',',$entry) . "<br>";
		     }
		     else {
		       #print $key . ": " . $entry . "<br>";
		     }
		}
		
		/*
		if(!checkFullNameES($_POST['blankname'], $_POST['blanksurname'], $outName, $outSurname, $checkError)){
			?>
			<script type="text/javascript">
				alert('<?php echo $checkError; ?>');
				window.location.href='home.php';
			</script>
			<?php 
		}
		elseif(!checkBirthdate($_POST['blankbirthdate'])){
			?>
			<script type="text/javascript">
				alert('La fecha introducida es incorrecta');
				window.location.href='home.php';
			</script>
			<?php 
		}
		elseif(!checkDNI_NIE($_POST['blanknie'])){
			?>
			<script type="text/javascript">
				alert('El NIE no está correctamente introducido');
				window.location.href='home.php';
			</script>
			<?php 
		}
		elseif(!isset($str_nat)){
			?>
			<script type="text/javascript">
				alert('Incluya al menos 1 nacionalidad, por favor');
				window.location.href='home.php';
			</script>
			<?php 
		}
		*/
		
		
		
		/*
		if(!filter_var($_POST['blankmail'], FILTER_VALIDATE_EMAIL)){
			?>
			<script type="text/javascript">
				alert('Introduzca un email válido, por favor');
				window.location.href='home.php';
			</script>
			<?php 
		}
		*/
		
		$userDir = $_SERVER['DOCUMENT_ROOT'] . "/cvs/".$_SESSION['loglogin']."/";
		
		$tot = count($_FILES["archivos"]["name"]);
		//este for recorre el arreglo
		for ($i = 0; $i < $tot; $i++){
			move_uploaded_file( $_FILES['archivos']['tmp_name'][$i],$userDir.$_FILES['archivos']['name'][$i]);
			//con el indice $i, podemos obtener la propiedad que desemos de cada archivo para trabajar con este
			$tmp_name = $_FILES["archivos"]["tmp_name"][$i];
			$name = $_FILES["archivos"]["name"][$i];
		}
		
		$uploadFile = $userDir . "foto";
		
		echo $uploadFile;
		echo '--> '.$_SESSION['loglogin'].' <--';
		if(move_uploaded_file($_FILES['foto']['tmp_name'], $uploadFile)){
			$image = new SimpleImage(); 
			$image->load($uploadFile); 
			$image->resize(200,250); 
			$image->save($uploadFile."r.jpg"); 
			unlink($uploadFile);
			echo '--> WAY!! <--';
			#echo "El archivo es válido y fue cargado exitosamente.\n";
		}
		else{
			echo '--> Que Mal!! <--';
			#echo "¡Posible ataque de carga de archivos!\n";
		}
		exit();
	/*
	}
	if(isset($_POST['push_button'])){
	*/
		/*
		executeDBquery("INSERT INTO `cvitaes` (`id`, `nie`, `cvStatus`, `name`, `surname`, `birthdate`, `nationalities`, `sex`, `addrType`, `addrName`, `addrNum`, `portal`, `stair`, `addrFloor`, `addrDoor`, 
		`phone`, `postalCode`, `country`, `province`, `city`, `mobile`, `mail`, `drivingType`, `drivingDate`, `marital`, `sons`, `language`, `langLevel`, `education`, 
		`experCompany`, `experStart`, `experEnd`, `experPos`, `experDesc`, `otherDetails`, `skill1`, `skill2`, `skill3`, `skill4`, `skill5`, `skill6`, `skill7`, `skill8`, `skill9`, `skill10`, 
		`checkLOPD`, `cvDate`, `userLogin`, `salary`) VALUES 
		(NULL, '".$_POST['blanknie']."', 'pending', '".utf8_decode($_POST['blankname'])."', '".utf8_decode($_POST['blanksurname'])."', '".$_POST['blankbirthdate']."', '".utf8_decode($_POST['blanknationality'])."', '".$_POST['blanksex']."',
		'".utf8_decode($_POST['blankaddrtype'])."', '".utf8_decode($_POST['blankaddrname'])."', '".$_POST['blankaddrnum']."', '".$_POST['blankaddrportal']."', '".$_POST['blankaddrstair']."', '".$_POST['blankaddrfloor']."',
		'".$_POST['blankaddrdoor']."', '".$_POST['blankphone']."', '".$_POST['blankaddrpostalcode']."', '".utf8_decode($_POST['blankaddrcountry'])."', '".utf8_decode($_POST['blankaddrprovince'])."', '".utf8_decode($_POST['blankaddrcity'])."',
		'".$_POST['blankmobile']."', '".$_POST['blankmail']."', '".$_POST['blankdrivingtype']."', '".$_POST['blankdrivingdate']."', '".$_POST['blankmarital']."', '".$_POST['blanksons']."', '".$str_idiomas."', '".$str_nidiomas."',
		'".$str_educ."', '".$str_empr."', '".$str_expstart."', '".$str_expend."', '".$str_categ."', '".$str_desc."', '".$_POST['blankother']."', 
		'".$_POST['blankskill1']."', '".$_POST['blankskill2']."', '".$_POST['blankskill3']."', '".$_POST['blankskill4']."', '".$_POST['blankskill5']."', '".$_POST['blankskill6']."', '".$_POST['blankskill7']."', 
		'".$_POST['blankskill8']."', '".$_POST['blankskill9']."', '".$_POST['blankskill10']."', '".$_POST['blanklopd']."', CURRENT_TIMESTAMP, '".$_SESSION['loglogin']."', '".$_POST['blanksalary']."')");
		*/
		executeDBquery("INSERT INTO `cvitaes` (`id`, `nie`, `cvStatus`, `name`, `surname`, `birthdate`, `nationalities`, `sex`, `addrType`, `addrName`, `addrNum`, `portal`, `stair`, `addrFloor`, `addrDoor`, 
		`phone`, `postalCode`, `country`, `province`, `city`, `mobile`, `mail`, `drivingType`, `drivingDate`, `marital`, `sons`, `language`, `langLevel`, `education`, 
		`experCompany`, `experStart`, `experEnd`, `experPos`, `experDesc`, `otherDetails`, `skill1`, `skill2`, `skill3`, `skill4`, `skill5`, `skill6`, `skill7`, `skill8`, `skill9`, `skill10`, 
		`checkLOPD`, `cvDate`, `userLogin`, `salary`) VALUES 
		(NULL, '".$_POST['blanknie']."', 'pending', '".$_POST['blankname']."', '".$_POST['blanksurname']."', '".$_POST['blankbirthdate']."', '".$str_nat."', '".$_POST['blanksex']."',
		'".$_POST['blankaddrtype']."', '".$_POST['blankaddrname']."', '".$_POST['blankaddrnum']."', '".$_POST['blankaddrportal']."', '".$_POST['blankaddrstair']."', '".$_POST['blankaddrfloor']."',
		'".$_POST['blankaddrdoor']."', '".$_POST['blankphone']."', '".$_POST['blankaddrpostalcode']."', '".$_POST['blankaddrcountry']."', '".$_POST['blankaddrprovince']."', '".$_POST['blankaddrcity']."',
		'".$_POST['blankmobile']."', '".$_POST['blankmail']."', '".$_POST['blankdrivingtype']."', '".$_POST['blankdrivingdate']."', '".$_POST['blankmarital']."', '".$_POST['blanksons']."', '".$str_idiomas."', '".$str_nidiomas."',
		'".$str_educ."', '".$str_empr."', '".$str_expstart."', '".$str_expend."', '".$str_categ."', '".$str_desc."', '".$_POST['blankother']."', 
		'".$_POST['blankskill1']."', '".$_POST['blankskill2']."', '".$_POST['blankskill3']."', '".$_POST['blankskill4']."', '".$_POST['blankskill5']."', '".$_POST['blankskill6']."', '".$_POST['blankskill7']."', 
		'".$_POST['blankskill8']."', '".$_POST['blankskill9']."', '".$_POST['blankskill10']."', '".$_POST['blanklopd']."', CURRENT_TIMESTAMP, '".$_SESSION['loglogin']."', '".$_POST['blanksalary']."')");
		
		/*
		$userDir = $_SERVER['DOCUMENT_ROOT'] . "/cvs/".$_SESSION['loglogin']."/";
		
		$tot = count($_FILES["archivos"]["name"]);
		//este for recorre el arreglo
		for ($i = 0; $i < $tot; $i++){
			move_uploaded_file( $_FILES['archivos']['tmp_name'][$i],$userDir.$_FILES['archivos']['name'][$i]);
			//con el indice $i, podemos obtener la propiedad que desemos de cada archivo
			//para trabajar con este
			$tmp_name = $_FILES["archivos"]["tmp_name"][$i];
			$name = $_FILES["archivos"]["name"][$i];
		}
		
		$uploadFile = $userDir . "foto";
		
		echo $uploadFile;
		if(move_uploaded_file($_FILES['foto']['tmp_name'], $uploadFile)){
			$image = new SimpleImage(); 
			$image->load($uploadFile); 
			$image->resize(200,250); 
			$image->save($uploadFile."r.jpg"); 
			unlink($uploadFile);
			#echo "El archivo es válido y fue cargado exitosamente.\n";
		}
		else{
			#echo "¡Posible ataque de carga de archivos!\n";
		}
		*/
		//blocks candidate and redirects her/him to index.html
		executeDBquery("UPDATE `users` SET `active`='0' WHERE `login`='".$_SESSION['loglogin']."'");
		?>
		<script type="text/javascript">
			alert('CV insertado con éxito. Gracias!');
			window.location.href='./endsession.php';
		</script>
		<?php
	}//del (isset($_POST[]))
	
/***************  Fin del bloque que valida el contenido enviado en el formulario  ***************/

/***************  Aquí comienza el bloque que permite mostrar el formulario  ***************/
?>

<form id="formu" class="form-horizontal form-upload" name="formu" action=""  method="post" enctype="multipart/form-data">

	<table>
		<tr>
			<td><label class='control-label'>Nombre</label></td>
			<td><input type="text" name="blankname" size="30" maxlength="30"/></td>
		</tr>
		<tr>
			<td><label class='control-label'>Apellidos</label></td>
			<td><input type="text" name="blanksurname" size="30" maxlength="50"/></td>
		</tr>
		<tr>
			<td><label class='control-label'>Fecha de Nacimiento</label></td>
			<td><input type="date" name="blankbirthdate"/></td>
		</tr>
		<tr>
			<td><label class='control-label'>DNI/NIE</label></td>
			<td><input type="text" name="blanknie" size="30" maxlength="12" placeholder="Max. 12 caracteres" onkeyup="this.value=this.value.toUpperCase();" /></td>
		</tr>
		
		<!-- <td><span class="form-sub-label-container"><select class="form-dropdown form-address-country" name="q13_direccion13[country]" id="input_13_country"> -->
		<tr>
			<td><label class='control-label'>Nacionalidad</label></td>
			
			<td>
			<div id="itemRows5">
			<select name="add_nat">
				<option value="" selected> Seleccione </option>
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
			
			<input onclick="addRow5(this.form);" type="button" value="Incluir" />
			</div>
			</td>
		</tr>
		<tr>
			<td><label class='control-label'>Sexo</label></td>
			<td>
				<input type="radio" name="blanksex" value="0"> Hombre
				<input type="radio" name="blanksex" value="1" > Mujer
			</td>
		</tr>
		<tr>
			<td><label class='control-label'>Dirección</label></td>
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
				<input type="text" name="blankaddrnum" size="5" maxlength="10" placeholder="Num" onkeyup="this.value=this.value.toUpperCase();" />
				<input type="text" name="blankaddrportal" size="5" maxlength="10" placeholder="Portal" onkeyup="this.value=this.value.toUpperCase();" />
				<input type="text" name="blankaddrstair" size="5" maxlength="10" placeholder="Escalera" onkeyup="this.value=this.value.toUpperCase();" />
				<input type="text" name="blankaddrfloor" size="5" maxlength="10" placeholder="Piso" />
				<input type="text" name="blankaddrdoor" size="5" maxlength="10" placeholder="Puerta" onkeyup="this.value=this.value.toUpperCase();" /><br>
				<!-- 
				<input type="number" name="blankaddrpostalcode" size="10" maxlength="5" placeholder="Cód. Postal" />
				<input type="text" name="blankaddrcountry" size="20" maxlength="50" placeholder="Pais" />
				<input type="text" name="blankaddrprovince" size="20" maxlength="50" placeholder="Provincia" />
				<input type="text" name="blankaddrcity" size="50" maxlength="50" placeholder="Población" />
				-->
				<!-- 
				<input type="number" name="blankaddrpostalcode" size="10" maxlength="5" placeholder="Cód. Postal" />
				<input type="text" name="blankaddrcity" size="50" maxlength="50" placeholder="Población" />
				<input type="text" name="blankaddrprovince" size="20" maxlength="50" placeholder="Provincia" />
				<input type="text" name="blankaddrcountry" size="20" maxlength="50" placeholder="Pais" />
				-->
				<!-- <input type="number" name="blankaddrpostalcode" size="10" maxlength="5" placeholder="Cód. Postal" onchange="ajaxGetAddres()">  -->
				Cód.Postal <select name="blankcode" onchange="ajaxGetAddress(this.value)">
					<option value "">Elija su CP</option>
					<?php 
					//$cpCol = getDBDistCompleteColID('postalCode', 'postalCitiesES', 'id');
					$cpCol = getDBDistCompleteColID('postalCode', 'postalCitiesES', 'postalCode');
					foreach($cpCol as $i){
						echo "<option value=" . $i . ">" . $i . "</option>";
					}
					?>
					<!-- 
					<option value="01001">01001</option>
					<option value="01002">01002</option>
					<option value="01007">01007</option>
					<option value="01013">01013</option>
					-->
				</select>
				<!-- <div id="txtHint"><b>Info AQUI</b></div> -->
				<div id="txtHint"><b></b></div>
			</td>
		</tr>
		<tr>
			<td><label class='control-label'>Teléfono Fijo</label></td>
			<td><input type="text" name="blankphone" size="30" maxlength="9" placeholder="9XXXXXXXX" /></td>
		</tr>
		<tr>
			<td><label class='control-label'>Teléfono Móvil</label></td>
			<td><input type="text" name="blankmobile" size="30" maxlength="9" /></td>
		</tr>
		<tr>
			<td><label class='control-label'>Correo Electrónico</label></td>
			<td><input type="email" name="blankmail" size="30" placeholder="correo@ejemplo.com" /></td>
		</tr>
		
		<tr>
			<td><label class='control-label'>Carnet de Conducir</label></td>
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
			<td><label class='control-label'>Estado Civil</label></td>
			<td>
			<select name="blankmarital">
				<option selected disabled value="">-- Estado --</option>
				<?php
				$userLang = getDBsinglefield('language', 'users', 'login', $_SESSION['loglogin']);
				$maritStatus = getDBcompletecolumnID($userLang, 'maritalStatus', 'id');
				foreach($maritStatus as $i){
					echo "<option value=" . getDBsinglefield('key', 'maritalStatus', $userLang, $i) . ">" . $i . "</option>";
				}
				?>
			</select>
			</td>
		</tr>
		<tr>
			<td><label class='control-label'>Hijos</label></td>
			<td><input type="number" name="blanksons" maxlength="2" min="0"></td>
		</tr>
		
		<tr>
			<td>
				<div class="tooltip-demo">
					<label class='control-label'>Foto</label>
					<span class="glyphicon glyphicon-info-sign pull-right" data-placement="left" data-toggle="tooltip" data-original-title="Tipos admitidos: JPG, JPEG o PNG. Máx: 1024Kb"></span>
				</div>
			</td>
			<td><input type="file" name="foto" file-accept="jpg, jpeg, png" file-maxsize="1024" /></td>
		</tr>
		
		<tr>
			<td>
				<div class="tooltip-demo">
					<label class='control-label'>Documentos adicionales</label>
					<span class="glyphicon glyphicon-info-sign pull-right" data-placement="left" data-toggle="tooltip" data-original-title="Tipos admitidos: PDF, DOC, DOCX, XLS, XLSX, CSV, TXT, RTF o ZIP. Máx: 1024Kb"></span>
				</div>
			</td>
			<td id="adjuntos"><input type="file" name="archivos[]" file-accept="pdf, doc, docx, xls, xlsx, csv, txt, rtf, zip" file-maxsize="1024" />
			</td><td><input onclick="addCampo();" type="button" value="+" />
			</td>
		</tr>
		
		<tr>
			<td><label class='control-label'>Nivel de idiomas</label></td>
			<td>
				<div id="itemRows">
					<select name="add_idiomas">
						<option selected disabled value="">-- Idioma --</option>
						<?php
						/*
						$langNames = getDBcompletecolumnID(getDBsinglefield('language', 'users', 'login', $_SESSION['loglogin']), 'languages', 'id');
						 foreach($langNames as $i){
						 	echo "<option value=" . $i . ">" . $i . "</option>";
						 }
						 */
						//$userLang = getDBsinglefield('language', 'users', 'login', $_SESSION['loglogin']);
						//$maritStatus = getDBcompletecolumnID($userLang, 'maritalStatus', 'id');
						$langNames = getDBcompletecolumnID($userLang, 'languages', 'id');
						foreach($langNames as $i){
							echo "<option value=" . getDBsinglefield('key', 'languages', $userLang, $i) . ">" . $i . "</option>";
						}
						?>
					</select>
					<select name="add_nidiomas">
						<option selected value="null">-- Sin conocimientos --</option>
						<option value="A1">A1</option>
						<option value="A2">A2</option>
						<option value="B1">B1</option>
						<option value="B2">B2</option>
						<option value="C1">C1</option>
						<option value="C2">C2</option>
						<option value="mothertongue">Lengua Materna</option>
					</select>
					<input onclick="addRow1(this.form);" type="button" value="Incluir" />
				</div>
			</td>
		</tr>
		
		<tr>
			<td>
				<div class="tooltip-demo">
					<label class='control-label'>Educación</label>
					<span class="glyphicon glyphicon-info-sign pull-right" data-placement="left" data-toggle="tooltip" data-original-title="Si su título no aparece en el listado, póngase en contacto con nosotros a través de administracion@perspectiva-alemania.com"></span>
				</div>
			</td>
			<td>
				<div id="itemRows3">
					<!-- <select name="add_nfor"> -->
					<select name="add_educ">
						<option selected value="">Estudié...</option>
						<?php 
						$eduNames = getDBcompleteColumnID(getDBsinglefield('language', 'users', 'login', $_SESSION['loglogin']), 'studies', 'id');
						foreach($eduNames as $i){
							echo "<option value=" . $i . ">" . $i . "</option>";
						}
						?>
					</select>
					<input onclick="addRow3(this.form);" type="button" value="Incluir" />
				</div>
			</td>
		</tr>
		<tr>
			<td><label class='control-label'>Qué has hecho estos últimos años...</label></td>
			<td>
			<div id="itemRows4">
				<input type="text" name="add_empr" size="25" placeholder="Empresa" />
				<input type="text" name="add_categ" size="25" placeholder="Posición" /><br>
				<input type="text" name="add_expstart" size="8" placeholder="Inicio" />
				<input type="text" name="add_expend" size="8" placeholder="Fin(MM-YYYY)" />
				<textarea name="add_desc" rows="3" cols="30" placeholder="Incluya una descripción de su experiencia en este puesto..."></textarea>
				<input onclick="addRow4(this.form);" type="button" value="Incluir" />
			</div>
			</td>
		</tr>
		
		<tr>
			<td><label class='control-label'>Salario deseado</label></td>
			<td><input type="number" name="blanksalary" size="10" placeholder="$" /><br></td>
		</tr>
		
		<tr>
			<td><label class='control-label'>Otros datos de interés</label></td>
			<td><textarea name="blankother" rows="3" cols="40" placeholder="Indique todo aquello que estime oportuno y aparezca en ningún otro campo..."></textarea></td>
		</tr>
		
		<tr>
			<td><label class='control-label'>Las 10 palabras que mejor me definen son...</label></td>
			<td>
			<input type="text" name="blankskill1" size="30" /><br>
			<input type="text" name="blankskill2" size="30" /><br>
			<input type="text" name="blankskill3" size="30" /><br>
			<input type="text" name="blankskill4" size="30" /><br>
			<input type="text" name="blankskill5" size="30" /><br>
			<input type="text" name="blankskill6" size="30" /><br>
			<input type="text" name="blankskill7" size="30" /><br>
			<input type="text" name="blankskill8" size="30" /><br>
			<input type="text" name="blankskill9" size="30" /><br>
			<input type="text" name="blankskill10" size="30" />
			</td>
		</tr>
	</table>

	<input type="checkbox" name="blanklopd"> He leído y acepto las condiciones de uso y política de privacidad<br>
	<input  type="submit" name ="push_button" value="Enviar" />
	<!-- <input type="reset" value="Borrar formulario" /> -->

</form>

</body>
</html>
