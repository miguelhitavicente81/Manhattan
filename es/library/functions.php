<?php

/*******************************************************************************
 * *************************************************************************** *
 ******************  HERE BEGINS DDBB STANDARD PHP FUNCTIONS  ******************
 * *************************************************************************** *
 *******************************************************************************/



/* Lets the program to establish connection against DDBB. We will only need to enter manually name of the DDBB.
 * Entry: N/A
 * Exit: Connection instance
 */
function connectDB(){
	
	// Set connection to remote Database
	//$connection = mysqli_connect('mysql.hostinger.es','u548400664_root','fOr3v3r', 'u548400664_alema') or die('MySQL connection error. Please contact administrator');

	// Set connection to local Database
	$connection = mysqli_connect('localhost','root','') or die('MySQL connection error. Please contact administrator');
	mysqli_select_db($connection, 'PRJ2014001') or die('There was a problem connecting to DDBB. Please contact administrator');

	$connection->query("SET NAMES 'utf8'");

	return $connection;
}


/* Delete 1 registry from a given table
 * Entry (dbtable): Name for DB table
 * Entry (primaryname): Field used to identify uniquely registry to be erased
 * Entry (primaryvalue): Value that identifies uniquely the registry to be erased
 * Exit: Boolean that indicates if it was OK or KO
 */
function deleteDBrow($dbtable, $primaryname, $primaryvalue){
	$conexion = connectDB();

	$query = "DELETE FROM `$dbtable` WHERE `$primaryname`='$primaryvalue'";

	//if(mysqli_query($query, $conexion) or die("Error al borrar registro de BD: ".mysqli_error())){
	if(mysqli_query($conexion, $query) or die("Error al borrar registro de BD: ".mysqli_error())){
		mysqli_close($conexion);
		return 1;
	}
	else{
		mysqli_close($conexion);
	}
}



/* Executes a complete DB query sent by PHP code, whatever it would be
 * Entry ($query): Complete query sent from original code
 * Exit: Returns 1 if succesfully executed
 */
function executeDBquery($query){
	$conexion = connectDB();

	//if(mysqli_query($query, $conexion) or die("Error en la llamada de BD: ".mysqli_error())){
	if(mysqli_query($conexion, $query) or die("Error en la llamada de BD: ".mysqli_error())){
		mysqli_close($conexion);
		return 1;
	}
	else {
		mysqli_close($conexion);
	}
}


/* Extract name of the column whose position is passed in parameter
 * Entry (dbtable): Name of the table
 * Entry (column): Number of column in which it is desired to know its name
 * Exit (cname): Array with name
 */
function getDBcolumnname($dbtable, $column){
	$connection = connectDB();
	$query = "SELECT * FROM `$dbtable` LIMIT 1";
	if($result = mysqli_query($connection, $query)){
		$i = 0;
		while($colObject = mysqli_fetch_field($result)){
			if($i == $column){
				$colName = ($colObject->name);
			}
			$i++;
		}
		mysqli_free_result($result);
	}
	mysqli_close($connection);
	return $colName;
}


/* Returns Array (if succeded) with all matched values in one given column
 * Entry (fieldrequested): Field where possible matches will be searched
 * Entry (dbtable): Name of table
 * Entry (fieldsupported): Field used by SELECT query to identify matches in "fieldrequested"
 * Entry (infosupported): Value that indicates match
 * Exit (row): Array with matched values
 */
function getDBcolumnvalue($fieldrequested, $dbtable, $fieldsupported, $infosupported){
	$conexion = connectDB();
	$result = mysqli_query($conexion, "SELECT `$fieldrequested` FROM `$dbtable` WHERE `$fieldsupported`='$infosupported'") or die("Error al extraer array coincidente: ".mysqli_error());
	$i = 0;
	if(mysqli_num_rows($result) > 0){
		while($column = mysqli_fetch_row($result)){
			$row[$i] = $column[0];
			$i++;
		}
		mysqli_free_result($result);
		mysqli_close($conexion);
		return $row;
	}
	else{
		mysqli_free_result($result);
		mysqli_close($conexion);
	}
}


/* Returns all values in one column, ordered by especified ID
 * Entry (columnrequested): Name which values want to extracted
 * Entry (dbtable): Table where info is
 * Entry (id): Unique identificator used to get array ordered
 * Exit (row): Array with complete column ordered
 */
function getDBcompletecolumnID($columnrequested, $dbtable, $id){
	$conexion = connectDB();

	$result = mysqli_query($conexion, "SELECT `$columnrequested` FROM `$dbtable` ORDER BY `$id`") or die("Error en getDBcompletecolumnID: ".mysqli_error());

	$i = 0;
	if(mysqli_num_rows($result) > 0){
		while($column = mysqli_fetch_row($result)){
			$row[$i] = $column[0];
			$i++;
		}
		mysqli_free_result($result);
		mysqli_close($conexion);
		return $row;
	}
	else{
		mysqli_free_result($result);
		mysqli_close($conexion);
	}
}


/* Counts number of columns in a table
 * Entry (dbtable): Name for the table in which will be counted number of columns
 * Exit (num_columns): Integer with total number of columns in table
 */
function getDBnumcolumns($dbtable){
	$connection = connectDB();

	$result = mysqli_query($connection, "SELECT * FROM `$dbtable` LIMIT 1");
	$numColumns = mysqli_field_count($connection);
	mysqli_free_result($result);
	mysqli_close($connection);
	return $numColumns;
}


/* Gets a complete row from DB for supported data
 * Entry (dbtable): Name for the table where row must be get
 * Entry (fieldsupported): Name for column used to select uniquely the row
 * Entry (infosupported): Unique value used to identify uniquely the row
 * Exit (fila): Complete and unique row
 */
function getDBrow($dbtable, $fieldsupported, $infosupported){
	$conexion = connectDB();
	//$result = mysqli_query($conexion, "SELECT * FROM `$dbtable` WHERE `$fieldsupported`='$infosupported'") or die("Error en getDBrow: ".mysqli_error());
	//$result = mysqli_query($conexion, "SELECT * FROM `$dbtable` WHERE `$fieldsupported`='$infosupported'") or die("Error buscando el registro: ".mysqli_error());
	$result = mysqli_query($conexion, "SELECT * FROM `$dbtable` WHERE `$fieldsupported`='$infosupported'") or die("Error buscando el registro: ".mysqli_error());
	//if(mysqli_num_rows($result) <= 0 ){
	if(mysqli_num_rows($result) <= 0){
		mysqli_free_result($result);
		mysqli_close($conexion);
		return 0;
	}
	else{
		$fila = mysqli_fetch_array($result);
		mysqli_free_result($result);
		mysqli_close($conexion);
		return $fila;
	}
}


/* Counts total number of rows in a table
 * Entry (dbtable): DB where wanted to know total number of registries
 * Exit (num_rows): Integer with number of rows
 */
function getDBrowsnumber($dbtable){
	$conexion = connectDB();

	$result = mysqli_query($conexion, "SELECT COUNT(*) FROM `$dbtable`") or die("Error en getDBrowsnumber: ".mysqli_error());

	$num_rows = mysqli_fetch_array($result);
	mysqli_free_result($result);
	mysqli_close($conexion);
	return $num_rows[0];
}


/* Extracts a unique value from 1 single row
 * Entry (fieldrequested): Field in which is needed value
 * Entry (dbtable): Table where to exectue SELECT query
 * Entry (fieldsupported): Field used to execute SELECT query
 * Entry (infosupported): Unique value used to execute SELECT query
 * Exit (singleDBfield): Array stored in "fieldrequested" var
 */
function getDBsinglefield($fieldrequested, $dbtable, $fieldsupported, $infosupported){
	$conexion = connectDB();

	$result = mysqli_query($conexion, "SELECT `$fieldrequested` FROM `$dbtable` WHERE `$fieldsupported`='$infosupported'") or die("Error buscando el valor: ".mysqli_error());

	if (mysqli_num_rows($result)>0){
		$fila = mysqli_fetch_array($result);
		$singleDBfield = $fila[$fieldrequested];
		mysqli_free_result($result);
		mysqli_close($conexion);
		return $singleDBfield;
	}
	else{
		mysqli_free_result($result);
		mysqli_close($conexion);
	}
}


/* Extracts a unique value from 1 single row searching it using 2 different fields
 * Entry (fieldreq): Field in which is needed value
 * Entry (dbtable): Table where to exectue SELECT query
 * Entry (fieldsup1): Name for 1st field used to execute SELECT query
 * Entry (infosup1): Unique value for 1st field used to execute SELECT query
 * Entry (fieldsup2): Name for 2nd field used to execute SELECT query
 * Entry (infosup2): Unique value for 2nd field used to execute SELECT query
 * Exit (singleDBfield): Array stored in "fieldreq" var
 */
function getDBsinglefield2($fieldreq, $dbtable, $fieldsup1, $infosup1, $fieldsup2, $infosup2){
	$conexion = connectDB();
	$result = mysqli_query($conexion, "SELECT `$fieldreq` FROM `$dbtable` WHERE `$fieldsup1`='$infosup1' AND `$fieldsup2`='$infosup2'") or die("Error buscando el valor: ".mysqli_error());
	if(mysqli_num_rows($result)>0){
		$fila = mysqli_fetch_array($result);
		$singleDBfield = $fila[$fieldreq];
		mysqli_free_result($result);
		mysqli_close($conexion);
		return $singleDBfield; //Devuelve un string
	}
	else{
		//Es necesario liberar la memoria de la variable "result"
		mysqli_free_result($result);
		mysqli_close($conexion);
	}
}





/***************************************************************************************************************************
 * *********************************************************************************************************************** *
 * ****************************  HERE BEGINS NON STANDARD PHP FUNCTIONS (NOT RELATED TO DDBB) **************************** *
 * *********************************************************************************************************************** *
 ***************************************************************************************************************************/



/* Generates a future date from current date
 * Entry (monthsNumber): Integer which indicates the number of months to be added
 * Exit (endDate): Date in format "YYYY-MM-DD"
 */
function addMonthsToDate($monthsNumber){
	$endDate = date('Y-m-d', strtotime('+'.$monthsNumber.' month'));
	return $endDate;
}


/* Generates a Hash key using Blowfish Algorithm to create after it a password
 * Entry (password): String wanted to be hashed
 * Exit (crypt): Hash key
 *//*
function crypt_blowfish_bydinvader($password, $digito = 7) {
$set_salt = './1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
$salt = sprintf('$2a$%02d$', $digito);
for($i = 0; $i < 22; $i++)
{
    $salt .= $set_salt[mt_rand(0, 63)];
}
return crypt($password, $salt);
}*/
function blowfishCrypt($password, $rounds = 7){
	$saltChars = './1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
	$salt = sprintf('$2a$%02d$', $rounds);
	for($i=0; $i<22; $i++){
		$salt .= $saltChars[mt_rand(0, 63)];
	}
	return crypt($password, $salt);
	
}



//HAY UNA VERSION JAVASCRIPT DE ESTA FUNCION YA
/* Checks whether a given password is strong enough (and properly written) when changed for a new one
 * Entry (keypass): 
 */
//function validar_clave($clave,&$error_clave){
function checkPassword($clave,&$error_clave){
   if(strlen($clave) < 6){
      $error_clave = "La clave debe tener al menos 6 caracteres";
      return false;
   }
   if(strlen($clave) > 16){
      $error_clave = "La clave no puede tener más de 16 caracteres";
      return false;
   }
   if (!preg_match('`[a-z]`',$clave)){
      $error_clave = "La clave debe tener al menos una letra minúscula";
      return false;
   }
   if (!preg_match('`[A-Z]`',$clave)){
      $error_clave = "La clave debe tener al menos una letra mayúscula";
      return false;
   }
   if (!preg_match('`[0-9]`',$clave)){
      $error_clave = "La clave debe tener al menos un caracter numérico";
      return false;
   }
   $error_clave = "";
   return true;
}



/* Changes a given date to format "Y-m-d" (YYYY-MM-DD)
 * Entry (oldDate): String that includes the old format date
 * Exit (endDate): Date in format "YYYY-MM-DD"
 */
function dateFormatToDB($oldDate){
 $endDate = date('Y-m-d', strtotime($oldDate));
 return $endDate;
}



/* Changes a given date (usually in DB) to format "d-m-Y" (DD-MM-YYYY). A common one to spanish people
 * Entry (oldDate): String that includes the old format date
 * Exit (endDate): Date in format "DD-MM-YYYY"
 */
function dateToSpanishFormat($oldDate){
 $endDate = date('d-m-Y', strtotime($oldDate));
 return $endDate;
}


/*
function normalizeString($string){
	$charset = 'UTF-8';
	$str = iconv($charset, 'ASCII//TRANSLIT', $str);
	$final = preg_replace("/[^A-Za-z0-9 ]/", '', $str);
	return $final;
}


/ * Erases/Strips/Removes any character in a string which contains any non-supported type of accent (if necessary)
 * Entry (string): String with accents
 * Exit: String without accents
 * /
function stripAccents($string){
	return strtr($string,'àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ', 'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');
}


//function limpiar_caracteres_especiales($s) {
function stripSpecialChars($string){
	$s = ereg_replace("[áàâãªä@]","a",$s);
	$s = ereg_replace("[ÁÀÂÃÄ]","A",$s);
	$s = ereg_replace("[éèêë]","e",$s);
	$s = ereg_replace("[ÉÈÊË]","E",$s);
	$s = ereg_replace("[íìîï]","i",$s);
	$s = ereg_replace("[ÍÌÎÏ]","I",$s);
	$s = ereg_replace("[óòôõºö]","o",$s);
	$s = ereg_replace("[ÓÒÔÕÖ]","O",$s);
	$s = ereg_replace("[úùûü]","u",$s);
	$s = ereg_replace("[ÚÙÛÜ]","U",$s);
	$s = str_replace("[¿?\]","_",$s);
	$s = str_replace(" ","-",$s);
	$s = str_replace("ñ","n",$s);
	$s = str_replace("Ñ","N",$s);
//para ampliar los caracteres a reemplazar agregar lineas de este tipo:
//$s = str_replace("caracter-que-queremos-cambiar","caracter-por-el-cual-lo-vamos-a-cambiar",$s);
return $s;
}
*/

/* Erases/Strips/Removes any character in a string which contains any non-supported type of accent (if necessary)
 * Entry (incoming_string): String with accents
 * Exit: String without accents
 */
function dropAccents($incoming_string){
	$tofind = "ÀÁÂÄÅàáâäÒÓÔÖòóôöÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñ";
	$replac = "AAAAAaaaaOOOOooooEEEEeeeeCcIIIIiiiiUUUUuuuuyNn";
	return utf8_encode(strtr(utf8_decode($incoming_string), utf8_decode($tofind), $replac));
}




function getRandomPass(){
	//$str = "_-$%&/()=?¿¡!ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890";
	//$str = "_-$%&/()=¿¡!ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890";
	//$str = "_-$%&/()=¡!ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890";
	//$str = "_-$%&/()=¿!ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890";
	//$str = "_-$%&/()=!ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890";
	$str = "_-$%&/()=?!ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890";
	//se ven bien: %_!/$-(=)&
	//mal: ¿¡
	$cad = "";
	$passLen = 8;
	for($i=0;$i<$passLen;$i++){
		$cad .= substr($str,rand(0,62),1);
		/* EN LUGAR DE LA UNICA LINEA DE ARRIBA, PUEDO USAR ESTAS 2:
		$pos = rand(0, $passLen-1);
		$cad .= substr($str,rand(0,62),1);
		*/
	}
	return $cad;
}



/* Prepares a new login to be registered in DB, checking if it meets requirements
 * Entry (incomingLogin): Varchar that can includes non-supported characters in DB
 * Exit (): Varchar with no non-supported characters
 */
function normalizeLogin($incomingLogin){
	$aux = dropAccents($incomingLogin);
	if((strlen($aux) < 4) || (strlen($aux) > 16)){
		return 0;
	}
	else return $aux;
}



?>