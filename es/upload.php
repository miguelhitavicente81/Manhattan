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
			//nDiv.className = 'archivo col-sm-11 form-inline';
			nDiv.className = 'archivo col-sm-11 form-inline';
			nDiv.setAttribute("style", "display: inline-flex;"); 
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
			a.className = 'pull-right form-inline';
			//El link debe tener el mismo nombre de la div padre, para efectos de localizarla y eliminarla 
			a.name = nDiv.id;
			//Este link no debe ir a ningun lado
			a.href = '#';
			//Establecemos que dispare esta funcion al pincharse sobre ella 
			a.onclick = elimCamp;
			//Con esto ponemos el texto del link
			//a.innerHTML = 'Eliminar';
			//a.innerHTML = '&minus;';
			//a.innerHTML = '&otimes;';
			a.innerHTML = '<span class="glyphicon glyphicon-remove" style="margin-bottom: 10px; margin-left: 10px; color: #FF0000"></span>';
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
	
	<script>
		//Functions used to add/remove in realtime Language fields 
		var rowNum = 0;
		function addLanguage(frm){
			rowNum ++;
			var row = '<div class="form-group uploadFormChild" style="margin-left: 0px; margin-right: 0px; margin-bottom: 0px;" id="rowLanguage'+rowNum+'"><div class="col-sm-5"><input class="form-control" type="text" name="idiomas[]" value="'+frm.add_idiomas.value+'" disabled></div><div class="col-sm-5"><input class="form-control" type="text" name="nidiomas[]" value="'+frm.add_nidiomas.value+'" disabled></div><div class="btn-toolbar col-sm-1"><div class="btn-group btn-group-sm"><button type="button" class="btn btn-default" onclick="removeLanguage('+rowNum+');"><span class="glyphicon glyphicon-remove" style="color: #FF0000;"></span></button></div></div></div>';
			jQuery('#uploadFormLanguage').append(row);
			frm.add_idiomas.value = '';
			frm.add_nidiomas.value = '';
		}
		
		function removeLanguage(rnum){
			jQuery('#rowLanguage'+rnum).remove();
		}
		
		//Functions used to add/remove in realtime Education fields 
		var rowNum = 0;
		function addDegree(frm){
			rowNum ++;
			var row = '<div class="form-group uploadFormChild" style="margin-left: 0px; margin-right: 0px; margin-bottom: 0px;" id="rowDegree'+rowNum+'"><div class="col-sm-11"><input class="form-control" type="text" name="educ[]" value="'+frm.add_educ.value+'" disabled></div><div class="btn-toolbar col-sm-1"><div class="btn-group btn-group-sm"><button type="button" class="btn btn-default" onclick="removeDegree('+rowNum+');"><span class="glyphicon glyphicon-remove" style="color: #FF0000;"></span></button></div></div></div>';
			jQuery('#uploadFormDegree').append(row);
			frm.add_educ.value = '';
		}
		
		function removeDegree(rnum){
			jQuery('#rowDegree'+rnum).remove();
		}

		//Functions to add/remove in realtime Experience fields 
		function addCareer(frm){
			rowNum ++;
			var row ='<div class="row" style="padding-left: 0px; margin-bottom: 10px;" id="rowCareer'+rowNum+'"> \
				<div class="col-sm-5"> \
					<div class="row"> \
						<div class="col-sm-6"> \
							<input class="form-control" type="text" name="empr[]" value="'+frm.add_empr.value+'"> \
						</div> \
						<div class="col-sm-6"> \
							<input class="form-control" type="text" name="categ[]" value="'+frm.add_categ.value+'" > \
						</div> \
					</div> \
					<div class="row"> \
						<div class="col-sm-6"> \
							<input class="form-control" type="text" name="expstart[]" value="'+frm.add_expstart.value+'"> \
						</div>			 \
						<div class="col-sm-6"> \
							<input class="form-control" type="text" name="expend[]" value="'+frm.add_expend.value+'"> \
						</div> \
					</div> \
				</div> \
				<div class=" row col-sm-4"> \
					<div class="col-sm-10"> \
						<textarea class="form-control" name="desc[]">'+frm.add_desc.value+'</textarea> \
					</div>	 \
					<div class="btn-toolbar col-sm-1"> \
						<div class="btn-group btn-group-sm"><button class="btn btn-default" onclick="removeCareer('+rowNum+');" type="button"><span class="glyphicon glyphicon-remove" style="color: #FF0000;"></span></button></div> \
					</div> \
				</div>					 \
			</div>';	
			jQuery('#uploadFormCareer').append(row);
			frm.add_empr.value = '';
			frm.add_categ.value = '';
			frm.add_expstart.value = '';
			frm.add_expend.value = '';
			frm.add_desc.value = '';
		}
		
		function removeCareer(rnum){
			jQuery('#rowCareer'+rnum).remove();
		}

		//Functions to add/remove in realtime Nationalities fields 
		function addNationality(frm){
			rowNum ++;
			var row = '<div class="form-group uploadFormChild" style="margin-left: 0px; margin-right: 0px; margin-bottom: 0px;" id="rowNationality'+rowNum+'"><div class="col-sm-11"><input class="form-control" type="text" name="nat[]" value="'+frm.add_nat.value+'" disabled></div><div class="btn-toolbar col-sm-1"><div class="btn-group btn-group-sm"><button type="button" class="btn btn-default" onclick="removeNationality('+rowNum+');"><span class="glyphicon glyphicon-remove" style="color: #FF0000;"></span></button></div></div></div>';
			jQuery('#uploadFormNationality').append(row);
			frm.add_nat.value = '';
		}
		
		function removeNationality(rnum){
			jQuery('#rowNationality'+rnum).remove();
		}

		//Function to realtime check characters written in Salary field 
		function checkMoney(e){
			tecla = e.which || e.keyCode;
			patron = /\d/; // Solo acepta números
			te = String.fromCharCode(tecla);
			return (patron.test(te) || tecla == 9 || tecla == 8);
		}

		//Function to check in realtime photo's extensions 
		function checkJSPhotoExtension(fileId){
			var fileItself = document.getElementById(fileId).value;
			
			var fileArray = fileItself.split(".");
			var fileExt = (fileArray[fileArray.length-1]);
			var acceptedExts = /(jpg|png|jpeg)$/i.test(fileExt);
			if(!acceptedExts){
				//var cleared = document.getElementById('foto').value = "";
				var cleared = document.getElementById(fileId).value = "";
				//alert ("El fichero "+fileItself+" no posee una extensión válida");
				alert ("\'"+fileExt+"\' no es una extensión válida para su fotografía");
				return false;
			}
		}

		//Function to check in realtime doc's extensions 
		function checkJSDocsExtension(fileId){
			var fileItself = document.getElementById(fileId).value;
			
			var fileArray = fileItself.split(".");
			var fileExt = (fileArray[fileArray.length-1]);
			var acceptedExts = /(pdf|doc|docx|xls|xlsx|csv|txt|rtf)$/i.test(fileExt);
			if(!acceptedExts){
				//var cleared = document.getElementById('foto').value = "";
				var cleared = document.getElementById(fileId).value = "";
				//alert ("El fichero "+fileItself+" no posee una extensión válida");
				alert ("\'"+fileExt+"\' no es una extensión válida para su documento");
				return false;
			}
		}
	</script>
	
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
					//str_idiomas es 'language' en la BD (en addLanguage)
					$str_idiomas = implode('|',$entry);
				}
				if($key == nidiomas){
					//str_nidiomas es 'langLevel' en la BD (en addLanguage)
					$str_nidiomas = implode('|',$entry);
				}
				if($key == educ){
					//str_educ es 'education' en la BD (en addDegree)
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
		
		if(!checkFullNameES($_POST['blankname'], $_POST['blanksurname'], $outName, $outSurname, $checkError)){
			?>
			<script type="text/javascript">
				alert('<?php echo $checkError; ?>');
				window.location.href='home.php';
			</script>
			<?php 
		}
		/*
		elseif(!isPreviousDate($_POST['blankbirthdate'])){
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
				alert('Incluya al menos 1 nacionalidad');
				window.location.href='home.php';
			</script>
			<?php 
		}
		//Sex and Type of address are automatically detected as restricted fields
		elseif(!checkFullAddress($_POST['blankaddrname'], $_POST['blankaddrnum'], $outAddrName, $outAddrNumber, $checkError)){
			?>
			<script type="text/javascript">
				alert('<?php echo $checkError; ?>');
				window.location.href='home.php';
			</script>
			<?php 
		}
		elseif(!checkPhone($_POST['blankphone'])){
			?>
			<script type="text/javascript">
				alert('Indique un número de teléfono válido');
				window.location.href='home.php';
			</script>
			<?php 
		}
		elseif(!checkMobile($_POST['blankmobile'])){
			?>
			<script type="text/javascript">
				alert('Indique un número de móvil válido');
				window.location.href='home.php';
			</script>
			<?php 
		}
		if(!filter_var($_POST['blankmail'], FILTER_VALIDATE_EMAIL)){
			?>
			<script type="text/javascript">
				alert('Introduzca un email válido, por favor');
				window.location.href='home.php';
			</script>
			<?php 
		}
		if((strlen($_POST['blankdrivingtype']) > 0) || (strlen($_POST['blankdrivingdate']) > 0)){
			if(!checkDrivingLicense($_POST['blankdrivingtype'], $_POST['blankdrivingdate'], $checkError)){
				?>
				<script type="text/javascript">
					alert('<?php echo $checkError; ?>');
					window.location.href='home.php';
				</script>
				<?php 
			}
		}
		*/
		
		
		//Field 'maritalStatus' is automatically checked in the own form with field "required"
		/*
		elseif(){
		}
		*/
		
		//echo 'Hijos '.$_POST['blanksons'];
		/*
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
			$image->resize(250,250); 
			$image->save($uploadFile."r.jpg"); 
			unlink($uploadFile);
			echo '--> WAY!! <--';
			#echo "El archivo es válido y fue cargado exitosamente.\n";
		}
		else{
			echo '--> Que Mal!! <--';
			#echo "¡Posible ataque de carga de archivos!\n";
		}
		 */
		
		$insertCVQuery = "INSERT INTO `cvitaes` (`id`, `nie`, `cvStatus`, `name`, `surname`, `birthdate`, `nationalities`, `sex`, `addrType`, `addrName`, `addrNum`, `portal`, `stair`, `addrFloor`, `addrDoor`, 
		`phone`, `postalCode`, `country`, `province`, `city`, `mobile`, `mail`, `drivingType`, `drivingDate`, `marital`, `sons`, `language`, `langLevel`, `education`, 
		`experCompany`, `experStart`, `experEnd`, `experPos`, `experDesc`, `otherDetails`, `skill1`, `skill2`, `skill3`, `skill4`, `skill5`, `skill6`, `skill7`, `skill8`, `skill9`, `skill10`, 
		`checkLOPD`, `cvDate`, `userLogin`, `salary`) VALUES 
		(NULL, '".$_POST['blanknie']."', 'pending', '".$outName."', '".$outSurname."', '".$_POST['blankbirthdate']."', '".$str_nat."', '".$_POST['blanksex']."',
		'".$_POST['blankaddrtype']."', '".$outAddrName."', '".$outAddrNumber."', '".$_POST['blankaddrportal']."', '".$_POST['blankaddrstair']."', '".$_POST['blankaddrfloor']."',
		'".$_POST['blankaddrdoor']."', '".$_POST['blankphone']."', '".$_POST['blankaddrpostalcode']."', '".$_POST['blankaddrcountry']."', '".$_POST['blankaddrprovince']."', '".$_POST['blankaddrcity']."',
		'".$_POST['blankmobile']."', '".$_POST['blankmail']."', '".$_POST['blankdrivingtype']."', '".$_POST['blankdrivingdate']."', '".$_POST['blankmarital']."', '".$_POST['blanksons']."', 
		'".$str_idiomas."', '".$str_nidiomas."', '".$str_educ."', '".$str_empr."', '".$str_expstart."', '".$str_expend."', '".$str_categ."', '".$str_desc."', '".$_POST['blankother']."', 
		'".$_POST['blankskill1']."', '".$_POST['blankskill2']."', '".$_POST['blankskill3']."', '".$_POST['blankskill4']."', '".$_POST['blankskill5']."', '".$_POST['blankskill6']."', '".$_POST['blankskill7']."', 
		'".$_POST['blankskill8']."', '".$_POST['blankskill9']."', '".$_POST['blankskill10']."', '".$_POST['blanklopd']."', CURRENT_TIMESTAMP, '".$_SESSION['loglogin']."', '".$_POST['blanksalary']."')";
		
		executeDBquery($insertCVQuery);
		
		
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
			$image->resize(250,250); 
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

	/*****************************     End of FORM validations     *****************************/
	
	/*************************     Start of WebPage code as showed     *************************/
?>

<form id="uploadForm" class="form-horizontal" name="formu" action=""  method="post" enctype="multipart/form-data">
	<div class="panel panel-default">
		<div class="panel-body">
			<div class="form-group"> <!-- Nombre -->
				<label id="uploadFormLabel" class="control-label col-sm-2" for="blankname">Nombre: </label> 
				<div class="col-sm-10">
					<input class="form-control" type='text' name='blankname' autocomplete="off" required/>
				</div>
			</div>

			<div class="form-group"> <!-- Apellidos -->
				<label id="uploadFormLabel" class="control-label col-sm-2" for="blanksurname">Apellidos: </label> 
				<div class="col-sm-10">
					<input class="form-control" type='text' name='blanksurname' autocomplete="off" required/>
				</div>
			</div>

			<div class="form-group"> <!-- Fecha de Nacimiento -->
				<label id="uploadFormLabel" class="control-label col-sm-2" for="blankbirthdate">Fecha de Nacimiento: </label> 
				<div class="col-sm-10">
					<input class="form-control" type='date' name='blankbirthdate' autocomplete="off"/>
				</div>
			</div>		

			<div class="form-group"> <!-- DNI/NIE -->
				<label id="uploadFormLabel" class="control-label col-sm-2" for="blanknie">DNI/NIE: </label> 
				<div class="col-sm-10">
					<input class="form-control" type='text' name='blanknie' autocomplete="off" maxlength="9" placeholder="12345678X" onkeyup="this.value=this.value.toUpperCase();" />
				</div>
			</div>		

			<div class="form-group"> <!-- Nacionalidad -->
				<label id="uploadFormLabel" class="control-label col-sm-2" for="add_nat">Nacionalidad: </label> 
				<div class="col-sm-9" id="uploadFormNationality">
					<select class="form-control" name="add_nat" >
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
						<option value="British Virgin Islands"> British Virgin Islands </option>
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
						<option value="Gambia"> Gambia </option>
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
						<option value="North Korea"> North Korea </option>
						<option value="Northern Mariana"> Northern Mariana </option>
						<option value="Norway"> Norway </option>
						<option value="Oman"> Oman </option>
						<option value="Pakistan"> Pakistan </option>
						<option value="Palau"> Palau </option>
						<option value="Palestine"> Palestine </option>
						<option value="Panama"> Panama </option>
						<option value="Papua New Guinea"> Papua New Guinea </option>
						<option value="Paraguay"> Paraguay </option>
						<option value="People's Republic of China"> People's Republic of China </option>
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
						<option value="South Korea"> South Korea </option>
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
						<option value="Turkish Republic of Northern Cyprus"> Turkish Republic of Northern Cyprus </option>
						<option value="Turkmenistan"> Turkmenistan </option>
						<option value="Turks and Caicos Islands"> Turks and Caicos Islands </option>
						<option value="Tuvalu"> Tuvalu </option>
						<option value="Uganda"> Uganda </option>
						<option value="Ukraine"> Ukraine </option>
						<option value="United Arab Emirates"> United Arab Emirates </option>
						<option value="United Kingdom"> United Kingdom </option>
						<option value="United States of America"> United States of America </option>
						<option value="Uruguay"> Uruguay </option>
						<option value="Uzbekistan"> Uzbekistan </option>
						<option value="Vanuatu"> Vanuatu </option>
						<option value="Vatican City"> Vatican City </option>
						<option value="Venezuela"> Venezuela </option>
						<option value="Vietnam"> Vietnam </option>
						<option value="US Virgin Islands"> US Virgin Islands </option>
						<option value="Wallis and Futuna"> Wallis and Futuna </option>
						<option value="Western Sahara"> Western Sahara </option>
						<option value="Yemen"> Yemen </option>
						<option value="Zambia"> Zambia </option>
						<option value="Zimbabwe"> Zimbabwe </option>
					</select>
				</div>
				<div class="btn-toolbar col-sm-1">
					  <div class="btn-group btn-group-sm"><button type="button" class="btn btn-default" onclick="addNationality(this.form);"><span class="glyphicon glyphicon-plus"></span></button></div>	
				</div>
			</div>	

			<div class="form-group"> <!-- Sexo -->
				<label id="uploadFormLabel" class="control-label col-sm-2" for="blanksex">Sexo: </label>
				<div class="col-sm-10">
					<div class='radio-inline'>
						<label id='noPadding' class='radio-inline'><input class='radio-inline' type='radio' name='blanksex' value='0'>Hombre</label>
						<label id='noPadding' class='radio-inline'><input class='radio-inline' type='radio' name='blanksex' value='1'>Mujer</label>
					</div>
				</div>
			</div>							

			<div class="form-group">  <!-- Dirección -->
				<label id="uploadFormLabel" class="control-label col-sm-2" for="blankaddrtype">Dirección: </label>
				<div class="col-sm-10 form-inline">
					<select class="form-control form-inline" name="blankaddrtype" >
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
					<input class="form-control form-inline" type="text" name="blankaddrname" size="24" maxlength="50" placeholder="Nombre">
					<input class="form-control form-inline" type="text" name="blankaddrnum" size="1" maxlength="10" placeholder="Num" onkeyup="this.value=this.value.toUpperCase();">
					<input class="form-control form-inline" type="text" name="blankaddrportal" size="2" maxlength="10" placeholder="Portal" onkeyup="this.value=this.value.toUpperCase();">
					<input class="form-control form-inline" type="text" name="blankaddrstair" size="1" maxlength="10" placeholder="Esc" onkeyup="this.value=this.value.toUpperCase();">
					<input class="form-control form-inline" type="text" name="blankaddrfloor" size="1" maxlength="10" placeholder="Piso">
					<input class="form-control form-inline" type="text" name="blankaddrdoor" size="3" maxlength="10" placeholder="Puerta" onkeyup="this.value=this.value.toUpperCase();">
					<br><br>
					<select class="form-control form-inline pull-right" name="blankcode" onchange="ajaxGetAddress(this.value)" style="margin-top:5px;">
						<option value="" selected>-- Código Postal --</option>
						<?php 
							$cpCol = getDBDistCompleteColID('postalCode', 'postalCitiesES', 'postalCode');
							foreach($cpCol as $i){
								echo "<option value=" . $i . ">" . $i . "</option>";
							}
						?>
					</select>
					<div id="txtHint"><b></b></div>
				</div>
			</div>	

			<div class="form-group"> <!-- Teléfono Fijo -->
				<label id="uploadFormLabel" class="control-label col-sm-2" for="blankphone">Teléfono Fijo: </label> 
				<div class="col-sm-10">
					<input class="form-control" type="text" name="blankphone" autocomplete="off" maxlength="9" placeholder="9XXXXXXXX">
				</div>
			</div>

			<div class="form-group"> <!-- Teléfono Móvil -->
				<label id="uploadFormLabel" class="control-label col-sm-2" for="blankmobile">Teléfono Móvil: </label> 
				<div class="col-sm-10">
					<input class="form-control" type="text" name="blankmobile" autocomplete="off" maxlength="9">
				</div>
			</div>

			<div class="form-group"> <!-- Correo Electrónico -->
				<label id="uploadFormLabel" class="control-label col-sm-2" for="blankmail">eMail: </label> 
				<div class="col-sm-10">
					<input class="form-control" type="email" name="blankmail" autocomplete="off" placeholder="correo@ejemplo.com">
				</div>
			</div>		

			<div class="form-group">  <!-- Carnet de Conducir -->
				<label id="uploadFormLabel" class="control-label col-sm-2" for="blankdrivingtype">Carnet Conducir: </label>
				<div class="col-sm-10 form-inline">
					<select class="form-control form-inline" name="blankdrivingtype">
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
				<input class="form-control form-inline" type="date" name="blankdrivingdate" >	
				</div>				
			</div>
			
			<div class="form-group"> <!-- Estado Civil -->
				<label id="uploadFormLabel" class="control-label col-sm-2" for="blankmarital">Estado Civil: </label> 
				<div class="col-sm-10">
					<select class="form-control" name="blankmarital">
					<?php
						$userLang = getDBsinglefield('language', 'users', 'login', $_SESSION['loglogin']);
						$maritStatus = getDBcompletecolumnID($userLang, 'maritalStatus', 'id');

						foreach($maritStatus as $i){
							echo "<option value=" . getDBsinglefield('key', 'maritalStatus', $userLang, $i) . ">" . $i . "</option>";
						}
					?>
					</select>
				</div>
			</div>

			<div class="form-group"> <!-- Hijos -->
				<label id="uploadFormLabel" class="control-label col-sm-2" for="blanksons">Hijos: </label> 
				<div class="col-sm-10">
					<input class="form-control" type="number" name="blanksons" maxlength="2" min="0">
				</div>
			</div>		

			<div class="form-group"> <!-- Foto -->
				<label id="uploadFormLabel" class="control-label col-sm-2" for="foto">Foto: </label>
				<div class="col-sm-10">
					<input class="form-control" type="file" name="foto" id="foto" onchange="checkJSPhotoExtension(this.id)">
					<p class="help-block">Tipos admitidos: JPG, JPEG o PNG. Máx: 1024Kb</p>
				</div>
			</div>

			<div class="form-group"> <!-- Documentos -->
				<label id="uploadFormLabel" class="control-label col-sm-2" for="archivos[]">Documentos adicionales </label>
				<div class="col-sm-10" style="padding-left: 0px;">
					<div id="adjuntos" class="col-sm-11">
						<input class="form-control" type="file" name="archivos[]" file-accept="pdf, doc, docx, xls, xlsx, csv, txt, rtf, zip" file-maxsize="1024" required>
						<p class="help-block">Tipos admitidos: PDF, DOC, DOCX, XLS, XLSX, CSV, TXT o RTF. Máx: 1024Kb</p>
					</div>
					<div class="btn-toolbar col-sm-1">
						<div class="btn-group btn-group-sm"><button type="button" class="btn btn-default" onclick="addCampo();"><span class="glyphicon glyphicon-plus"></span></button></div>
					</div>
				</div>
			</div>

			<div class="form-group"> <!-- Nivel de Idiomas -->
				<label id="uploadFormLabel" class="control-label col-sm-2" for="add_idiomas">Nivel de idiomas: </label> 
				<div class="col-sm-10" style="padding-left: 0px;">
					<div class="col-sm-6" id="uploadFormLanguage">
						<select class="form-control" name="add_idiomas">
							<option selected disabled value="">-- Idioma --</option>
							<?php
							$langNames = getDBcompletecolumnID($userLang, 'languages', 'id');
							foreach($langNames as $i){
								echo "<option value=" . getDBsinglefield('key', 'languages', $userLang, $i) . ">" . $i . "</option>";
							}
							?>
						</select>
					</div>
					<div class="col-sm-5">
						<select class="form-control" name="add_nidiomas">
							<option selected value="null">-- Sin conocimientos --</option>
							<option value="A1">A1</option>
							<option value="A2">A2</option>
							<option value="B1">B1</option>
							<option value="B2">B2</option>
							<option value="C1">C1</option>
							<option value="C2">C2</option>
							<option value="mothertongue">Lengua Materna</option>
						</select>
					</div>
					<div class="btn-toolbar col-sm-1">
						<div class="btn-group btn-group-sm"><button class="btn btn-default" onclick="addLanguage(this.form);" type="button"><span class="glyphicon glyphicon-plus"></span></button></div>
					</div>
				</div>
			</div>			

			<div class="form-group tooltip-demo"> <!-- Educación -->
				<label id="uploadFormLabel" class="control-label col-sm-2" for="add_educ"><span class="glyphicon glyphicon-info-sign" data-toggle="tooltip" data-original-title="Si su título no aparece en el listado, póngase en contacto con nosotros a través de administracion@perspectiva-alemania.com"></span> Educación: </label> 
				<div id="uploadFormDegree" class="col-sm-9">
					<select class="form-control" name="add_educ">
						<option selected value="">Estudié...</option>
						<?php 
							$eduNames = getDBcompleteColumnID(getDBsinglefield('language', 'users', 'login', $_SESSION['loglogin']), 'studies', 'id');
							foreach($eduNames as $i){
								echo "<option value=" . $i . ">" . $i . "</option>";
							}
						?>
					</select>						
				</div>
				<div class="btn-toolbar col-sm-1">
					<div class="btn-group btn-group-sm"><button class="btn btn-default" onclick="addDegree(this.form);" type="button"><span class="glyphicon glyphicon-plus"></span></button></div>
				</div>
			</div>	

			<div class="form-group"> <!-- Trayectoria -->
				<label id="uploadFormLabel" class="control-label col-sm-2" for="add_career">¿Qué has hecho estos últimos años? </label> 
				<div class="col-sm-10" id="uploadFormCareer">
					<div class="row" style="padding-left: 0px; margin-bottom: 10px;">
						<div class="col-sm-5">
							<div class="row">
								<div class="col-sm-6">
									<input class="form-control" type="text" name="add_empr" placeholder="Empresa" />
								</div>
								<div class="col-sm-6">
									<input class="form-control" type="text" name="add_categ" placeholder="Posición" />
								</div>
							</div>
							<div class="row">
								<div class="col-sm-6">
									<input class="form-control" type="date" name="add_expstart" placeholder="Inicio" />
								</div>			
								<div class="col-sm-6">
									<input class="form-control" type="date" name="add_expend" placeholder="Fin" />
								</div>
							</div>
						</div>
						<div class=" row col-sm-4">
							<div class="col-sm-10">
								<textarea class="form-control" name="add_desc" placeholder="Descripción del puesto"></textarea>
							</div>	
							<div class="btn-toolbar col-sm-1">
								<div class="btn-group btn-group-sm"><button class="btn btn-default" onclick="addCareer(this.form);" type="button"><span class="glyphicon glyphicon-plus"></span></button></div>
							</div>
						</div>					
					</div>
				</div>
			</div>		

			<div class="form-group"> <!-- Salario -->
				<label id="uploadFormLabel" class="control-label col-sm-2" for="blanksalary">Salario deseado: </label> 
				<div class="col-sm-10">
					<input class="form-control" type="text" name="blanksalary" maxlength="7" placeholder="€uros/año" onkeypress="return checkMoney(event)">
				</div>
			</div>

			<div class="form-group"> <!-- Otros datos de Interés -->
				<label id="uploadFormLabel" class="control-label col-sm-2" for="blankother">Otros datos de interés: </label> 
				<div class="col-sm-10">
					<textarea class="form-control" type="number" name="blankother" placeholder="Exponga aquí cualquier dato que estime oportuno y no aparezca en ningún otro campo..."></textarea>	
				</div>
			</div>		

			<div class="form-group"> <!-- 10 Tags -->
				<label id="uploadFormLabel" class="control-label col-sm-2" for="blankother">Las 10 palabras que mejor me definen son... </label> 
				<div class="col-sm-10">
					<?php
					for ($i=1; $i <= 10 ; $i++) { 
						echo "	<div class='col-sm-5' style='margin-bottom: 10px;'>";
						echo "		<input class='form-control' type='text' name='blankskill$i'>";
						echo "	</div>";
					}
					?>
				</div>
			</div>			
		</div> <!-- Panel Body -->

		<div class="panel-footer">
					<label class "control-label" style="margin-bottom: 10px; margin-top: 5px;"><input type="checkbox" name="blanklopd" required> He leído y acepto las condiciones de uso y política de privacidad</label>
				<div class="btn-group pull-right">
					<button type="submit" name ="push_button" class="btn btn-primary">Enviar</button>
				</div>
		</div> <!-- Panel Footer-->
	</div> <!-- Panel -->
</form>

</body>
</html>
