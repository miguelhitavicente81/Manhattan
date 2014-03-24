
/*************************************************************************************************************************************
 * ********************************************************************************************************************************* *
 * ********************************************************************************************************************************* *
 * **********************************                                                             ********************************** *
 * **********************************         GROUP OF FUNCTIONS DEVELOPED IN JAVASCRIPT          ********************************** *
 * **********************************                                                             ********************************** *
 * ********************************************************************************************************************************* *
 * ********************************************************************************************************************************* *
 *************************************************************************************************************************************/



/* Checks whether a file has a proper extension to upload a massive amount of data
 * Called from onchange in "admGenOptions.php"
 */
function checkMassFileExtension(fileId){
	var fileItself = document.getElementById(fileId).value;
	
	var fileArray = fileItself.split(".");
	var fileExt = (fileArray[fileArray.length-1]);
	var acceptedExts = /(csv|txt)$/i.test(fileExt);
	if(!acceptedExts){
		var cleared = document.getElementById(fileId).value = "";
		alert ("\'"+fileExt+"\' no es una extensión válida para subir datos masivos.");
		return false;
	}
}



/* Used to check in realtime a phone number in which there could be included dashes (guiones)
 * Called from "pendingCVs.php" (and also in "upload.php", although in this last file is inherently written)
 */ 
function checkDashedNumbers(e){
	tecla = e.which || e.keyCode;
	patron = /[0-9\\-]/;
	te = String.fromCharCode(tecla);
	return (patron.test(te) || tecla == 9 || tecla == 8);
}



/* Used to ensure that only numbers are written in a field
 * Called from "pendingCVs.php" (also in "upload.php", but in this php is inherently written)
 */
function checkOnlyNumbers(e){
	tecla = e.which || e.keyCode;
	patron = /\d/; // Solo acepta números
	te = String.fromCharCode(tecla);
	return (patron.test(te) || tecla == 9 || tecla == 8);
}



/* Captures 2 strings wished to match themselves to be a new password
 * Called from onsubmit in "validatefront.php"
 */
function checkPasswordES() {
	var formElements = document.getElementById("form");
	var passwd1 = formElements[0];
	var passwd2 = formElements[1];

	if(passwd1.value != passwd2.value) {
		alert("Ambas contraseñas deben coincidir");
		return false;
	}
	if(passwd1.strlen < 6) {
		alert("La clave debe tener al menos 6 caracteres");
		return false;
	}
	if(passwd1.strlen < 6) {
		alert("La clave no puede tener más de 16 caracteres");
		return false;
	}
	if(!passwd1.match(/[a-z]/)) {
		alert("La clave debe tener al menos una letra minúscula");
		return false;
	}
	if(!passwd1.match(/[A-Z]/)) {
		alert("La clave debe tener al menos una letra mayúscula");
		return false;
	}
	if(!passwd1.match(/[0-9]/)) {
		alert("La clave debe tener al menos un carácter numérico");
		return false;
	}
	else{
		alert("Contraseña actualizada con éxito");
		formElements.submit();
		return true;
	}
}



/* Captures 2 passwords sent from a form and tells if they both are equal each other
 * Called from onsubmit in "personalData.php"
 * If wished, it can be controlled here if form is also blanked, under limited characters or over-limited characters and more...
 */
function equalPassword() {
	var formElements = document.getElementById("form");	
	var passwd1 = formElements[0];
	var passwd2 = formElements[1];

	if (passwd1.value==passwd2.value) {
		alert("Contraseña actualizada con éxito");
		formElements.submit();
		return true;
	} else {
		alert("Ambas contraseñas deben coincidir");
		return false;
	}
}



/* Subtract (Restar) an existing date to current date, returning the difference in days
 * Called from onsubmit in "validatefront.php"
 */
function subToCurrentDateYMD() {
	var dat = Date();
	var curDate = Date(dat.getFullYear() + "-" + (dat.getMonth()+1) + "+" + dat.getDate());
	var d = new Date(); //establecemos la fecha de hoy
	
	//solo requerimos el año, mes, día
	//d.getFullYear() extrae el año
	//(d.getMonth() + 1 ) extrae el mes
	//d.getDate() extrae el día.

	//Establecemos la fecha inicio con los parametros anteriores
	var fechaInicio= new Date(d.getFullYear()+ "/" + (d.getMonth() + 1 ) + "/" + d.getDate());

	//Establecemos la fecha final
	var fechaFinal= new Date("2013/07/30");            

	//Restamos la fechaFinal menos fechaInicio, 
	//esto establece la diferencia entre las fechas
	var fechaResta= fechaFinal-fechaInicio;

	//Transformamos el tiempo de diferencia en días.
	fechaResta=(((fechaResta/1000)/60)/60)/24;            

	//imprimir
	document.write("Faltan: " + fechaResta + " días.");

}



// ESTA LA DEJO DE EJEMPLO PARA VER SI DESDE ELLA PUEDO SACAR LA FUNCION ANTERIOR
function DiferenciaFechas (formulario) {  

	//Obtiene los datos del formulario  
	CadenaFecha1 = formulario.fecha1.value; 
	CadenaFecha2 = formulario.fecha2.value;  

	//Obtiene dia, mes y año  
	var fecha1 = new fecha( CadenaFecha1 );     
	var fecha2 = new fecha( CadenaFecha2 ); 

	//Obtiene objetos Date  
	var miFecha1 = new Date( fecha1.anio, fecha1.mes, fecha1.dia );
	var miFecha2 = new Date( fecha2.anio, fecha2.mes, fecha2.dia ); 

	//Resta fechas y redondea  
	var diferencia = miFecha1.getTime() - miFecha2.getTime();
	//var dias = Math.floor(diferencia / (1000 * 60 * 60 * 24));
	var segundos = Math.floor(diferencia / 1000);
	alert ('La diferencia es de ' + dias + ' dias,\no ' + segundos + ' segundos.');

	return false;
}  



function fecha( cadena ) {  

	//Separador para la introduccion de las fechas  
	var separador = "⁄"  

	//Separa por dia, mes y año  
	if ( cadena.indexOf( separador ) != -1 ) {  
		var posi1 = 0  
		var posi2 = cadena.indexOf( separador, posi1 + 1 )  
		var posi3 = cadena.indexOf( separador, posi2 + 1 )  
		this.dia = cadena.substring( posi1, posi2 )  
		this.mes = cadena.substring( posi2 + 1, posi3 )  
		this.anio = cadena.substring( posi3 + 1, cadena.length )  
	} else {  
		this.dia = 0  
		this.mes = 0  
		this.anio = 0     
	}  
} 





/************************************************************************************************************************************
 * ***************   GROUP OF FUNCTIONS USED TO CONFIRM ANY TYPE OF ACTION (activations, creations, deletions...)   *************** *
 ************************************************************************************************************************************/



/* Double-checks deletion of an existing language
 * Called in "admGenOptions.php"
 */
function confirmLangDeletionES(id) {
	return confirm('¿Confirma que desea borrar este idioma?');
}



/* Double-checks deletion of an existing career
 * Called in "admGenOptions.php"
 */
function confirmCareerDeletionES(id) {
	return confirm('¿Confirma que desea borrar esta Profesión?');
}



/* Double-checks deletion of an existing User
 * Called from "onclick" method in "admCurUsers.php"
 * Entry (id): Number/indentifier of user to be deleted if confirmed
 */
function confirmUserDeletionES(id) {
	return confirm('Si se trata de un Candidato también se borrará su CV. ¿Esta seguro?');
}





/************************************************************************************************************
 * **********   GROUP OF FUNCTIONS USED TO MAKE APPEAR/DISAPPEAR NEW FIELDS IN "blankform.php"   ********** *
 ************************************************************************************************************/



var mailcount = 0;

function cerrar(obj) {
	email=document.getElementById("blankdynamiclang"); 
	email.parentNode.removeChild(email.parentNode.childNodes[mailcount+7]);
	mailcount --;
	if (mailcount==0) {
		//retirar el código para borrar la última dirección de mail 
		document.getElementById("addingField").removeChild(document.getElementById("cerrarMail"));
	}
}

function newEntry(inputName,text) {
	newInput = document.createElement("input");
	newInput.type="text";
	newInput.name=inputName;
	newNode = document.createElement("tr");
	newNode.appendChild(document.createElement("td"));
	newNode.appendChild(document.createElement("td"));
	newNode.firstChild.appendChild(document.createTextNode(text));
	newNode.lastChild.appendChild(newInput);

	return newNode;
}

function newLanguage() {
	mailcount ++;
	email=document.getElementById("blankdynamiclang");
	
	//Creo el nuevo campo
	newNode=newEntry("email"+mailcount,"Email alternativo "+mailcount+":");
	//newNode=newNode1+"Email";
	//Muestro el nuevo campo
	email.parentNode.insertBefore(newNode,email);

	//Agregar el código para borrar el último mail
	if (mailcount==1) {
		newClose = document.createElement("a");
		newClose.id="cerrarMail";
		newClose.href="javascript:cerrar(this)";
		newClose.appendChild(document.createTextNode("Borrar último"));
		document.getElementById("addingField").appendChild(newClose);
	}
}








/*******************************************************************************************************************************
 * *************************************************************************************************************************** *
 * *************************************************************************************************************************** *
 * **********************************                                                       ********************************** *
 * **********************************         GROUP OF FUNCTIONS DEVELOPED IN AJAX          ********************************** *
 * **********************************                                                       ********************************** *
 * *************************************************************************************************************************** *
 * *************************************************************************************************************************** *
 *******************************************************************************************************************************/



/*************************************************************************************************************************
 * ****************   GROUP OF FUNCTIONS USED TO CONTROL ADDRESS DEPENDANT BLOCK OF TEXTBOXES/SELECTS   **************** *
 *************************************************************************************************************************/

/* Para las 4 funciones siguientes, la variable de tipo XMLHttpRequest debe ser global para todas ellas.
 * Si la creamos de manera independiente dentro de cada función los SELECT dependientes no funcionarán
 */
function ajaxDelLanguage(str){
	if(str==""){
		document.getElementById("txtHint2").innerHTML="";
		return;
	}
	if(window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	}
	else{// code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.onreadystatechange=function(){
		if(xmlhttp.readyState==4 && xmlhttp.status==200){
			document.getElementById("txtHint2").innerHTML=xmlhttp.responseText;
		}
	}

	//xmlhttp.open("GET","getcd.php?q="+str,true);
	xmlhttp.open("GET","getLanguageS.php?valuedel="+str,true);
	xmlhttp.send();
}




function ajaxGetAddress(str){
	if(str==""){
		document.getElementById("txtHint").innerHTML="";
		return;
	}
	if(window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	}
	else{// code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.onreadystatechange=function(){
		if(xmlhttp.readyState==4 && xmlhttp.status==200){
			document.getElementById("txtHint").innerHTML=xmlhttp.responseText;
		}
	}
	//xmlhttp.open("GET","getcd.php?q="+str,true);
	xmlhttp.open("GET","getPostalData.php?value="+str,true);
	xmlhttp.send();
}



function ajaxGetLanguage(str){
	if(str==""){
		document.getElementById("txtHint2").innerHTML="";
		return;
	}
	if(window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	}
	else{// code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.onreadystatechange=function(){
		if(xmlhttp.readyState==4 && xmlhttp.status==200){
			document.getElementById("txtHint2").innerHTML=xmlhttp.responseText;
		}
	}

	//xmlhttp.open("GET","getcd.php?q="+str,true);
	xmlhttp.open("GET","getLanguageS.php?value="+str,true);
	xmlhttp.send();
}





function ajaxDelLanguage(str){
	if(str==""){
		document.getElementById("txtHint2").innerHTML="";
		return;
	}
	if(window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	}
	else{// code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.onreadystatechange=function(){
		if(xmlhttp.readyState==4 && xmlhttp.status==200){
			document.getElementById("txtHint2").innerHTML=xmlhttp.responseText;
		}
	}

	//xmlhttp.open("GET","getcd.php?q="+str,true);
	xmlhttp.open("GET","getLanguageS.php?valuedel="+str,true);
	xmlhttp.send();
}