<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
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
		$(function() {
		$( "#datepicker" ).datepicker();
		});
		var rowNum = 0;
		function addRow1(frm){
			rowNum ++;
			var row = '<p id="rowNum'+rowNum+'"><input type="text" name="nidiomas[]" size="4" value="'+frm.add_nidiomas.value+'"><input type="text" name="idiomas[]" value="'+frm.add_idiomas.value+'"> <input type="button" value="-" onclick="removeRow1('+rowNum+');"></p>';
			jQuery('#itemRows').append(row);
			frm.add_idiomas.value = '';
			frm.add_nidiomas.value = '';
		}
		
		function removeRow1(rnum){
			jQuery('#rowNum'+rnum).remove();
		}

		var rowNum = 0;
		function addRow2(frm){
			rowNum ++;
			var row = '<p id="rowNum'+rowNum+'"><input type="text" name="prof[]" value="'+frm.add_prof.value+'"> <input type="button" value="-" onclick="removeRow2('+rowNum+');"></p>';
			jQuery('#itemRows2').append(row);
			frm.add_prof.value = '';
		}
		
		function removeRow2(rnum){
			jQuery('#rowNum'+rnum).remove();
		}
		
		var rowNum = 0;
		function addRow3(frm){
			rowNum ++;
			var row = '<p id="rowNum'+rowNum+'"><input type="text" name="nfor[]" size="4" value="'+frm.add_nfor.value+'"><input type="text" name="forma[]" value="'+frm.add_for.value+'"> <input type="button" value="-" onclick="removeRow3('+rowNum+');"></p>';
			jQuery('#itemRows3').append(row);
			frm.add_for.value = '';
			frm.add_nfor.value = '';
		}
		
		function removeRow3(rnum){
			jQuery('#rowNum'+rnum).remove();
		}
		
		function addRow4(frm){
			rowNum ++;
			var row = '<p id="rowNum'+rowNum+'"><input type="text" name="empr[]" value="'+frm.add_empr.value+'"><input type="text" name="categ[]" value="'+frm.add_categ.value+'" ><input type="text" name="dur[]" value="'+frm.add_dur.value+'"><input type="text" name="desc[]" value="'+frm.add_desc.value+'"><input type="button" value="-" onclick="removeRow4('+rowNum+');"></p>';
			jQuery('#itemRows4').append(row);
			frm.add_empr.value = '';
			frm.add_categ.value = '';
			frm.add_dur.value = '';
			frm.add_desc.value = '';
		}
		
		function removeRow4(rnum){
			jQuery('#rowNum'+rnum).remove();
		}
  </script>
	
</head>

<body>

<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/library/functions.php');
if(isset($_POST['senduser'])){

	foreach ($_POST as $key => $entry){
		if(is_array($entry)){
			if($key == categ){
				//str_cated es 'occupation'
				$str_categ = implode(',',$entry);
			}
			if($key == nfor){
				//str_nfor es 'studyType'
				$str_nfor = implode(',',$entry);
			}
			if($key == forma){
				//str_forma es 'studyName'
				$str_forma = implode(',',$entry);
			}
			if($key == idiomas){
				//str_idiomas es 'language'
				$str_idiomas = implode(',',$entry);
			}
			if($key == nidiomas){
				//str_nidiomas es 'langLevel'
				$str_nidiomas = implode(',',$entry);
			}
			if($key == dur){
				//esto deberían ser 2 campos, que se correspondiesen con 'experStart' y 'experEnd'
				$str_dur = implode(',',$entry);
			}
			if($key == prof){
				//str_prof es 'experPos'
				$str_prof = implode(',',$entry);
			}
			if($key == empr){
				//str_empr es 'experCompany'
				$str_empr = implode(',',$entry);
			}
			if($key == desc){
				//str_desc es 'experDesc'
				$str_desc = implode(',',$entry);
			}
			#print $key . ": " . implode(',',$entry) . "<br>";
	     }
	     else {
	       #print $key . ": " . $entry . "<br>";
	     }
	}
//echo "---> $str_empr $str_categ $str_dur $str_nfor $str_forma $str_idiomas  $str_nidiomas  $str_prof ";
//exit();
	/*
	executeDBquery("INSERT INTO `cVitaes` (`id`, `nie`, `cvStatus`, `name`, `surname`, `birthdate`, `nationalities`, `sex`, `addrType`, `addrName`, `addrNum`, `portal`, `stair`, `addrFloor`, `addrDoor`, 
	`postalCode`, `country`, `province`, `city`, `phone`, `mobile`, `mail`, `marital`, `sons`, `language`, `langLevel`, `occupation`, `studyType`, `studyName`, `cvDate`) VALUES 
	(NULL, '".$_POST['blanknie']."', 'pending', '".utf8_decode($_POST['blankname'])."', '".utf8_decode($_POST['blanksurname'])."', '".$_POST['blankbirthdate']."', '".utf8_decode($_POST['blanknationality'])."', '".$_POST['blanksex']."',
	'".utf8_decode($_POST['blankaddrtype'])."', '".utf8_decode($_POST['blankaddrname'])."', '".$_POST['blankaddrnum']."', '".$_POST['blankaddrportal']."', '".$_POST['blankaddrstair']."', '".$_POST['blankaddrfloor']."',
	'".$_POST['blankaddrdoor']."', '".$_POST['blankaddrpostalcode']."', '".utf8_decode($_POST['blankaddrcountry'])."', '".utf8_decode($_POST['blankaddrprovince'])."', '".utf8_decode($_POST['blankaddrcity'])."',
	'".$_POST['blankphone']."', '".$_POST['blankmobile']."', '".$_POST['blankmail']."', '".$_POST['blankmarital']."', '".$_POST['blanksons']."', '".$str_idiomas."', '".$str_nidiomas."',
	'".$str_prof."', '".$str_nfor."', '".$str_forma."', CURRENT_TIMESTAMP)");
	*/
	/* CHUTA
	executeDBquery("INSERT INTO `cVitaes` (`id`, `nie`, `cvStatus`, `name`, `surname`, `birthdate`, `nationalities`, `sex`, `addrType`, `addrName`, `addrNum`, `portal`, `stair`, `addrFloor`, `addrDoor`, 
	`postalCode`, `country`, `province`, `city`, `phone`, `mobile`, `mail`, `drivingType`, `drivingDate`, `marital`, `sons`, `language`, `langLevel`, `occupation`, `studyType`, `studyName`, `experCompany`, `experPos`, `experStart`, `experEnd`, `experDesc`, `cvDate`) VALUES 
	(NULL, '".$_POST['blanknie']."', 'pending', '".utf8_decode($_POST['blankname'])."', '".utf8_decode($_POST['blanksurname'])."', '".$_POST['blankbirthdate']."', '".utf8_decode($_POST['blanknationality'])."', '".$_POST['blanksex']."',
	'".utf8_decode($_POST['blankaddrtype'])."', '".utf8_decode($_POST['blankaddrname'])."', '".$_POST['blankaddrnum']."', '".$_POST['blankaddrportal']."', '".$_POST['blankaddrstair']."', '".$_POST['blankaddrfloor']."',
	'".$_POST['blankaddrdoor']."', '".$_POST['blankaddrpostalcode']."', '".utf8_decode($_POST['blankaddrcountry'])."', '".utf8_decode($_POST['blankaddrprovince'])."', '".utf8_decode($_POST['blankaddrcity'])."',
	'".$_POST['blankphone']."', '".$_POST['blankmobile']."', '".$_POST['blankmail']."', '".$_POST['blankdrivingtype']."', '".$_POST['blankdrivingdate']."', '".$_POST['blankmarital']."', '".$_POST['blanksons']."', 
	'".$str_idiomas."', '".$str_nidiomas."', '".$str_prof."', '".$str_nfor."', '".$str_forma."', '".$str_empr."', '".$str_prof."', '".$str_dur."', '".$str_dur."', '".$str_desc."', CURRENT_TIMESTAMP)");
	*/
	/* NO CHUTA
	executeDBquery("INSERT INTO `cVitaes` (`id`, `nie`, `cvStatus`, `name`, `surname`, `birthdate`, `nationalities`, `sex`, `addrType`, `addrName`, `addrNum`, `portal`, `stair`, `addrFloor`, `addrDoor`, 
	`phone`, `postalCode`, `country`, `province`, `city`, `mobile`, `mail`, `drivingType`, `drivingDate`, `marital`, `sons`, `language`, `langLevel`, `occupation`, `studyType`, `studyName`, 
	`experCompany`, `experPos`, `experStart`, `experEnd`, `experDesc`, `otherDetails`, `skill1`, `skill2`, `skill3`, `skill4`, `skill5`, `skill6`, `skill7`, `skill8`, `skill9`, `skill10`, `checkLOPD`, `cvDate`) VALUES 
	(NULL, '".$_POST['blanknie']."', 'pending', '".utf8_decode($_POST['blankname'])."', '".utf8_decode($_POST['blanksurname'])."', '".$_POST['blankbirthdate']."', '".utf8_decode($_POST['blanknationality'])."', '".$_POST['blanksex']."',
	'".utf8_decode($_POST['blankaddrtype'])."', '".utf8_decode($_POST['blankaddrname'])."', '".$_POST['blankaddrnum']."', '".$_POST['blankaddrportal']."', '".$_POST['blankaddrstair']."', '".$_POST['blankaddrfloor']."',
	'".$_POST['blankaddrdoor']."', '".$_POST['blankaddrpostalcode']."', '".utf8_decode($_POST['blankaddrcountry'])."', '".utf8_decode($_POST['blankaddrprovince'])."', '".utf8_decode($_POST['blankaddrcity'])."',
	'".$_POST['blankphone']."', '".$_POST['blankmobile']."', '".$_POST['blankmail']."', '".$_POST['blankdrivingtype']."', '".$_POST['blankdrivingdate']."', '".$_POST['blankmarital']."', '".$_POST['blanksons']."', 
	'".$str_idiomas."', '".$str_nidiomas."', '".$str_prof."', '".$str_nfor."', '".$str_forma."', '".$str_empr."', '".$str_prof."', '".$str_dur."', '".$str_dur."', '".$str_desc."', CURRENT_TIMESTAMP)");
	*/
	executeDBquery("INSERT INTO `cVitaes` (`id`, `nie`, `cvStatus`, `name`, `surname`, `birthdate`, `nationalities`, `sex`, `addrType`, `addrName`, `addrNum`, `portal`, `stair`, `addrFloor`, `addrDoor`, 
	`phone`, `postalCode`, `country`, `province`, `city`, `mobile`, `mail`, `drivingType`, `drivingDate`, `marital`, `sons`, `language`, `langLevel`, `occupation`, `studyType`, `studyName`, 
	`experCompany`, `experPos`, `experStart`, `experEnd`, `experDesc`, `otherDetails`, `skill1`, `skill2`, `skill3`, `skill4`, `skill5`, `skill6`, `skill7`, `skill8`, `skill9`, `skill10`, `checkLOPD`, `cvDate`, `userLogin`) VALUES 
	(NULL, '".$_POST['blanknie']."', 'pending', '".utf8_decode($_POST['blankname'])."', '".utf8_decode($_POST['blanksurname'])."', '".$_POST['blankbirthdate']."', '".utf8_decode($_POST['blanknationality'])."', '".$_POST['blanksex']."',
	'".utf8_decode($_POST['blankaddrtype'])."', '".utf8_decode($_POST['blankaddrname'])."', '".$_POST['blankaddrnum']."', '".$_POST['blankaddrportal']."', '".$_POST['blankaddrstair']."', '".$_POST['blankaddrfloor']."',
	'".$_POST['blankaddrdoor']."', '".$_POST['blankaddrpostalcode']."', '".utf8_decode($_POST['blankaddrcountry'])."', '".utf8_decode($_POST['blankaddrprovince'])."', '".utf8_decode($_POST['blankaddrcity'])."',
	'".$_POST['blankphone']."', '".$_POST['blankmobile']."', '".$_POST['blankmail']."', '".$_POST['blankdrivingtype']."', '".$_POST['blankdrivingdate']."', '".$_POST['blankmarital']."', '".$_POST['blanksons']."', 
	'".$str_idiomas."', '".$str_nidiomas."', '".$str_prof."', '".$str_nfor."', '".$str_forma."', '".$str_empr."', '".$str_prof."', '".$str_dur."', '".$str_dur."', '".$str_desc."', '".$_POST['blankother']."', 
	'".$_POST['blankskill1']."', '".$_POST['blankskill2']."', '".$_POST['blankskill3']."', '".$_POST['blankskill4']."', '".$_POST['blankskill5']."', '".$_POST['blankskill6']."', '".$_POST['blankskill7']."', 
	'".$_POST['blankskill8']."', '".$_POST['blankskill9']."', '".$_POST['blankskill10']."', '".$_POST['blanklopd']."', CURRENT_TIMESTAMP, '".$_SESSION['loglogin']."')");
		
	
	
	//Preguntamos si nuetro arreglo 'archivos' fue definido
	if (isset ($_FILES["archivos"])) {
		/* CUANDO TENGA CLAROS LOS ERRORES DESCOMENTARE EL CODIGO
		if($_FILES['archivos']['error']){
			switch ($_FILES['nom_du_fichier']['error']){
				case 1: // UPLOAD_ERR_INI_SIZE
					echo"El archivo sobrepasa el limite autorizado por el servidor(archivo php.ini) !";
				break;
				
				case 2: // UPLOAD_ERR_FORM_SIZE
					echo "El archivo sobrepasa el limite autorizado en el formulario HTML !";
				break;
				
				case 3: // UPLOAD_ERR_PARTIAL
					echo "El envio del archivo ha sido suspendido durante la transferencia!";
				break;
				
				case 4: // UPLOAD_ERR_NO_FILE
					echo "El archivo que ha enviado tiene un tamaño nulo !";
				break;
	          }
		}
		else{
			*/
		//$destinyPath = '/Users/Burlock/Desktop/';
		$destinyPath = '/Applications/XAMPP/xamppfiles/temp/';
		
		//de ser asi, para procesar los archivos subidos al servidor solo debemos recorrerlo
		//obtenemos la cantidad de elementos que tiene el arreglo archivos
		$tot = count($_FILES["archivos"]["name"]);
		//este for recorre el arreglo
		for ($i = 0; $i < $tot; $i++){
			move_uploaded_file($_FILES['archivos']['tmp_name'][$i], $destinyPath.$_FILES['archivos']['name'][$i]);
			//con el indice $i, podemos obtener la propiedad que desemos de cada archivo
			//para trabajar con este
			$tmp_name = $_FILES["archivos"]["tmp_name"][$i];
			$name = $_FILES["archivos"]["name"][$i];
			/*
			echo("<b>Archivo </b> $key ");
			echo("<br />");
			echo("<b>el nombre original:</b> ");
			echo($name);
			echo("<br />");
			echo("<b>el nombre temporal:</b> \n");
			echo($tmp_name);
			echo("<br />");
			*/            
		}
		//} FIN DEL SWITCH
	}
}
/***************  Fin del bloque que valida el contenido enviado en el formulario  ***************/

/***************  Aquí comienza el bloque que permite mostrar el formulario  ***************/
?>
<h1>Formulario</h1>
<form name="formu" id="formu" action="upload.php" method="post" enctype="multipart/form-data">
	<table>
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
			<td>NIE</td>
			<td><input type="text" name="blanknie" size="30" maxlength="12" placeholder="Max. 12 caracteres"/></td>
		</tr>
		
		<!-- <td><span class="form-sub-label-container"><select class="form-dropdown form-address-country" name="q13_direccion13[country]" id="input_13_country"> -->
		<tr>
			<td>Nacionalidad</td>
			<td>
			<select name="blanknationality">
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
				<input type="text" name="blankaddrcountry" size="20" maxlength="50" placeholder="Pais" />				
				<input type="text" name="blankaddrprovince" size="20" maxlength="50" placeholder="Provincia" />
				<input type="text" name="blankaddrcity" size="50" maxlength="50" placeholder="Población" />
			</td>
		</tr>
		<tr>
			<td>Teléfono Fijo</td>
			<td><input type="text" name="blankphone" size="30" maxlength="9" /></td>
			<!-- <td><input type="tel" name="blankphone" size="30"/></td> -->
		</tr>
		<tr>
			<td>Teléfono Móvil</td>
			<td><input type="text" name="blankmobile" size="30" maxlength="12" /></td>
			<!-- <td><input type="tel" name="blankmobile" size="30"/></td> -->
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
		
		<!-- 
		<tr>
			<td>Carnet de Conducir</td>
			<td><input type="text" id="datepicker" name="blankdrivingdate"></td>
		</tr>
		-->

 <!--
<p>Date: <input type="text" id="datepicker"></p>
-->
		
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
			<td>Hijos</td>
			<td><input type="number" name="blanksons" maxlength="2" min="0"></td>
		</tr>
		
		<!-- AQUI TENGO QUE CARGAR DINAMICAMENTE LAS FOTOS -->
		<tr>
			<td>Foto</td>
			<td><input type="number" name="blankphoto" maxlength="2"></td>
		</tr>
		
		
		<tr>
			<td>Documentos adicionales</td>
			<!-- <td id="adjuntos"><input type="file" name="archivos[]" /> ORIGINAL -->
			<td id="adjuntos"><input type="file" name="archivos[]" file-accept="pdf, doc, docx, xls, xlsx, csv, txt, rtf, html, zip, mp3, wma, mpg, flv, avi, jpg, jpeg, png, gif" file-maxsize="1024" />
			<!-- <a href="#" onClick="addCampo()">Subir otro archivo</a> -->
			<input onclick="addCampo();" type="button" value="+" />
			
			</td>
		</tr>
		
		<tr>
			<td>Nivel de Idiomas</td>
			<td>
			<div id="itemRows">
			<select name="add_idiomas">
				<option selected value="">-- Idioma --</option>
				<option value="german">Alemán</option>
				<option value="english">Inglés</option>
				<option value="spanish">Español</option>
				<option value="french">Francés</option>
				<option value="portuguese">Portugués</option>
				<option value="italian">Italiano</option>
				<option value="greek">Griego</option>
				<option value="ruse">Ruso</option>
				<option value="chinese">Chino</option>
				<option value="japanese">Japonés</option>
			</select>
			<select name="add_nidiomas">
				<option selected value="null">Sin conocimientos</option>
				<option value="basic">Básico hablado y escrito</option>
				<option value="medium">Medio hablado y escrito</option>
				<option value="high">Alto hablado y escrito</option>
				<option value="bilingual">Bilingüe</option>
			</select>
	
	<input onclick="addRow1(this.form);" type="button" value="+" />
</div>
			</td>
		</tr>
		
		<tr>
			<td>Profesión</td>
			
			<td><div id="itemRows2"><input type="text" name="add_prof" size="50" placeholder="Profesión actual" /><input onclick="addRow2(this.form);" type="button" value="+" /></td>
		</tr>
		</div>
		
		<tr>
			<td>Formación</td>
			<td>
			<div id="itemRows3">
			<select name="add_nfor">
				<option selected value="">Sin estudios</option>
				<option value="basic">Educación obligatoria</option>
				<option value="secondary">Bachillerato</option>
				<option value="vocational">Formación profesional</option>
				<option value="bfa">Diplomatura</option>
				<option value="bachelor">Licenciatura</option>
			</select>
			<input type="text" name="add_for" placeholder="Formacion" /><input onclick="addRow3(this.form);" type="button" value="+" /></td></div>
		</tr>
		
		<tr>
			<td>Experiencia Laboral</td>
			<td>
			<div id="itemRows4">
			<input type="text" name="add_empr" size="30" placeholder="Empresa" />
			<select name="add_categ">
				<option value="intern">Becario</option>
				<option value="employee">Empleado</option>
				<option value="middle">Mando intermedio</option>
				<option value="director">Director</option>
			</select>
			<input type="text" name="add_dur" size="10" placeholder="Duración" />
			<textarea name="add_desc" rows="5" cols="40">Descripción</textarea>
			<input onclick="addRow4(this.form);" type="button" value="+" /></td></div>
			</td>
		</tr>
		
		<tr>
			<td>Otros Detalles de Interés</td>
			<td><textarea name="blankother" rows="5" cols="40">...</textarea></td>
		</tr>
		
		<tr>
			<td>Las 10 palabras que mejor me definen son...</td>
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
	<input type="checkbox" name="blanklopd" /> He leído y acepto las condiciones de uso y política de privacidad<br>
	<input type="submit" name="senduser" value="Enviar solicitud">
</form>

</body>

</html>