<HEAD>
<SCRIPT LANGUAGE="JavaScript">
<!--
var mailcount = 0;
function cerrar(obj){
	email=document.getElementById("emailNode"); 
	email.parentNode.removeChild(email.parentNode.childNodes[mailcount+7]);
	mailcount --;
	if (mailcount==0){
		//retirar el c—digo para borrar la œltima direcci—n de mail 
		document.getElementById("mailManagment").removeChild(document.getElementById("cerrarMail"));
	}
}

function newEntry(inputName,text){
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

function newMail(){
	mailcount ++;
	email=document.getElementById("emailNode");
	//Creo el nuevo campo
	newNode=newEntry("email"+mailcount,"Email alternativo "+mailcount+":");
	//Muestro el nuevo campo
	email.parentNode.insertBefore(newNode,email);

	//Agregar el c—digo para borrar el œltimo mail
	if (mailcount==1){
		newClose = document.createElement("a");
		newClose.id="cerrarMail";
		newClose.href="javascript:cerrar(this)";
		newClose.appendChild(document.createTextNode("Borrar œltimo"));
		document.getElementById("mailManagment").appendChild(newClose);
	}
}
//-->
</SCRIPT>
</HEAD>
<BODY>
<FORM METHOD=POST ACTION="nuevo.php">
<table>
<tr>
<td>Nombre:</td>
<td> <INPUT TYPE="text" NAME="nombre"></td>
</tr>
<tr>
<td>Apellido:</td>
<td><INPUT TYPE="text" NAME="apellido"></td>
</tr>
<tr>
<td>Contrase–a:</td>
<td><INPUT TYPE="password" NAME="password"></td>
</tr>
<tr>
<td>Email principal:</td>
<td><INPUT TYPE="text" NAME="email0"></td>
</tr>
<tr id="emailNode">
<td colspan="2"><CENTER id="mailManagment"><A HREF="javascript:newMail();">Agregar otro mail</A>&nbsp;</CENTER></td></tr>
<tr><td><INPUT TYPE="submit"></td><td><INPUT TYPE="reset"></td></tr>
</table>
</form>
</body>