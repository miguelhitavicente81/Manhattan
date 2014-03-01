/****************************************************************************************************************************
* ************************************************************************************************************************ *
* *********************************  GROUP OF FUNCTIONS CALLED FROM AN "onsubmit" EVENT  ********************************* *
* ************************************************************************************************************************ *
****************************************************************************************************************************/



/* Captures 2 strings wished to match themselves to be a new password
* Called from "validatefront.php"
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
* Called from "personalData.php"
* If wished, it can be controlled here if form is also blanked, under limited characters or over-limited characters and more...
*/
function confirmProfileCreation() {
	if(confirm('¿Realmente desea crear este perfil?')) {
		window.location.href='admCurProfiles.php';
	}
}


/* Captures 2 passwords sent from a form and tells if they both are equal each other
* Called from "personalData.php"
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
* Called from "validatefront.php"
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

/****************************************************************************************************************************
* ************************************************************************************************************************ *
* *********************************  GROUP OF FUNCTIONS CALLED FROM AN "onclick" EVENT  ********************************** *
* ************************************************************************************************************************ *
****************************************************************************************************************************/

/*
function confirmDeactivate(id) {
if(confirm('¿Realmente desea desactivar este perfil?')) {
//location.href='changeProfile.php?hiddenfield=activate&codvalue='+id;
window.location.href='changeProfile.php?hiddenfield=activate&codvalue='+id;
}
}
*/

/*
function confirmActivate(id) {
if(confirm('¿Realmente desea activar este perfil?')) {
//location.href='changeProfile.php?hiddenfield=activate&codvalue='+id;
window.location.href='changeProfile.php?hiddenfield=activate&codvalue='+id;
}
}
*/


/* Unactivates a profile
* Called in "onclick" event from "admCurProfiles.php"
*/
function confirmProfileDeactivation(id) {
	if(confirm('¿Realmente desea desactivar este perfil?')) {
		window.location.href='editProfile.php?hiddenfield=activate&codvalue='+id;
	}
}


/* Lets to SuperAdmin user to delete a profile
* Called in "onclick" event from "admCurProfiles.php"
*/
function confirmProfileActivation(id) {
	if(confirm('¿Realmente desea activar este perfil?')) {
		window.location.href='editProfile.php?hiddenfield=activate&codvalue='+id;
	}
}


/* Lets to SuperAdmin user to delete a profile
* Called in "onclick" event from "admCurProfiles.php"
*/
function confirmProfileDeletion(id) {
	if(confirm('¿Realmente desea borrar este perfil?')) {
		window.location.href='editProfile.php?hiddenfield=delete&codvalue='+id;
	}
}



/* Double-checks deletion of an existing language
 * Called in "admGenOptions.php"
 */
/*
function confirmLangDeletionES(id) {
	if(confirm('¿Realmente desea borrar este idioma?')) {
		window.location.href='admGenOptions.php?hiddenfield=hDelLang&codvalue='+id;
	}
}
*/
function confirmLangDeletionES(id) {
	if(confirm('¿Confirma que desea borrar este idioma?')) {
	}
}






/**********************************************************************************************************
* ****************************************************************************************************** *
* **********  GROUP OF FUNCTIONS USED TO MAKE APPEAR/DISAPPEAR NEW FIELDS IN "blankform.php"  ********** *
* ****************************************************************************************************** *
**********************************************************************************************************/


/*
var mailcount = 0;
function cerrar(obj) {
email=document.getElementById("emailNode"); 
email.parentNode.removeChild(email.parentNode.childNodes[mailcount+7]);
mailcount --;
if (mailcount==0) {
//retirar el c�digo para borrar la �ltima direcci�n de mail 
document.getElementById("mailManagment").removeChild(document.getElementById("cerrarMail"));
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

function newMail() {
mailcount ++;
email=document.getElementById("emailNode");
//Creo el nuevo campo
newNode=newEntry("email"+mailcount,"Email alternativo "+mailcount+":");
//Muestro el nuevo campo
email.parentNode.insertBefore(newNode,email);

//Agregar el c�digo para borrar el �ltimo mail
if (mailcount==1) {
newClose = document.createElement("a");
newClose.id="cerrarMail";
newClose.href="javascript:cerrar(this)";
newClose.appendChild(document.createTextNode("Borrar �ltimo"));
document.getElementById("mailManagment").appendChild(newClose);
}
}
*/


/*
var mailcount = 0;

function cerrar(obj) {
email=document.getElementById("blankdynamiclang"); 
email.parentNode.removeChild(email.parentNode.childNodes[mailcount+7]);
mailcount --;
if (mailcount==0) {
//retirar el c�digo para borrar la �ltima direcci�n de mail 
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
//Muestro el nuevo campo
email.parentNode.insertBefore(newNode,email);

//Agregar el c�digo para borrar el �ltimo mail
if (mailcount==1) {
newClose = document.createElement("a");
newClose.id="cerrarMail";
newClose.href="javascript:cerrar(this)";
newClose.appendChild(document.createTextNode("Borrar �ltimo"));
document.getElementById("addingField").appendChild(newClose);
}
}
*/


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
