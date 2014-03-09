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
	$connection = mysqli_connect('localhost','root','', 'PRJ2014001') or die('MySQL connection error. Please contact administrator');
	//mysqli_select_db($conexion, 'PRJ2014001') or die('There was a problem connecting to DDBB. Please contact administrator');

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

	if(mysqli_query($conexion, $query) or die("Error al borrar registro de BD: ".mysqli_error($conexion))){
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

	if(mysqli_query($conexion, $query) or die("Error en la llamada de BD: ".mysqli_error($conexion))){
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
	//$connection = mysqli_connect("localhost", "root", "", "PRJ2014001") or die("Error " .mysqli_error($connection));
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
	$result = mysqli_query($conexion, "SELECT `$fieldrequested` FROM `$dbtable` WHERE `$fieldsupported`='$infosupported'") or die("Error al extraer array coincidente: ".mysqli_error($conexion));
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
 * Entry (columnrequested): Name of the column which values want to be extracted
 * Entry (dbtable): Table where info is
 * Entry (id): Unique identificator used to get array ordered
 * Exit (row): Array with complete column ordered
 */
function getDBcompletecolumnID($columnrequested, $dbtable, $id){
	$conexion = connectDB();

	$result = mysqli_query($conexion, "SELECT `$columnrequested` FROM `$dbtable` ORDER BY `$id`") or die("Error en getDBcompletecolumnID: ".mysqli_error($conexion));

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



/* Returns all DISTINCT values in one column, ordered by especified ID
 * Entry (colRequested): Name of the column which distinct values want to be extracted
 * Entry (dbTable): Table where info is
 * Entry (id): Unique identificator used to get array ordered
 * Exit (row): Array with complete column ordered
 */
function getDBDistCompleteColID($colRequested, $dbTable, $id){
	$connection = connectDB();

	$result = mysqli_query($connection, "SELECT DISTINCT `$colRequested` FROM `$dbTable` ORDER BY `$id`") or die("Error en getDBcompletecolumnID: ".mysqli_error($connection));

	$i = 0;
	if(mysqli_num_rows($result) > 0){
		while($column = mysqli_fetch_row($result)){
			$row[$i] = $column[0];
			$i++;
		}
		mysqli_free_result($result);
		mysqli_close($connection);
		return $row;
	}
	else{
		mysqli_free_result($result);
		mysqli_close($connection);
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
	$result = mysqli_query($conexion, "SELECT * FROM `$dbtable` WHERE `$fieldsupported`='$infosupported'") or die("Error buscando el registro: ".mysqli_error($conexion));
	if(mysqli_num_rows($result) <= 0 ){
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

	$result = mysqli_query($conexion, "SELECT COUNT(*) FROM `$dbtable`") or die("Error en getDBrowsnumber: ".mysqli_error($conexion));

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

	$result = mysqli_query($conexion, "SELECT `$fieldrequested` FROM `$dbtable` WHERE `$fieldsupported`='$infosupported'") or die("Error buscando el valor: ".mysqli_error($conexion));

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
	$result = mysqli_query($conexion, "SELECT `$fieldreq` FROM `$dbtable` WHERE `$fieldsup1`='$infosup1' AND `$fieldsup2`='$infosup2'") or die("Error buscando el valor: ".mysqli_error($conexion));
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





/**************************************************************************
 * ********************  PASSWORD RELATED FUNCTIONS  ******************** *
 **************************************************************************/


/* Generates a Hash key using Blowfish Algorithm to create after it a password
 * Entry (password): String wanted to be hashed
 * Exit (crypt): Hash key
 */
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
 * Entry (key1): String where passed 1st password attempt
 * Entry (key2): String where passed 2nd password attempt
 * Exit (keyError): String with the error when needed (or void)
 */
function checkPassChange($key1, $key2, &$keyError){
	if($key1 != $key2){
		$keyError = "Ambas contraseñas deben ser iguales";
		return false;
	}
	if(strlen($key1) < 6){
		$keyError = "La contraseña debe tener al menos 6 caracteres";
		return false;
	}
	if(strlen($key1) > 16){
		$keyError = "La contraseña no puede tener más de 16 caracteres";
		return false;
	}
	if (!preg_match('`[a-z]`',$key1)){
		$keyError = "La contraseña debe tener al menos una letra minúscula";
		return false;
	}
	if (!preg_match('`[A-Z]`',$key1)){
		$keyError = "La contraseña debe tener al menos una letra mayúscula";
		return false;
	}
	if (!preg_match('`[0-9]`',$key1)){
		$keyError = "La contraseña debe tener al menos un caracter numérico";
		return false;
	}
	$keyError = "";
	return true;
}



function getRandomPass(){
	$str = "_-$%&/()=?!ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890";
	$cad = "";
	$passLen = 8;
	for($i=0;$i<$passLen;$i++){
		$cad .= substr($str,rand(0,62),1);
	}
	return $cad;
}



/* Checks whether current password is about to expire
 * Entry (curDate): String with current date in YYYY-MM-DD format
 * Entry (curExpirate): String with current expiration date, which should a future date
 * Exit (true/false): Boolean that tells if password is about to expire or not
 */
function suggestPassword($curDate, $curExpirate, &$days){
	$datetime1 = date_create($curDate);
	$datetime2 = date_create($curExpirate);
	$interval = date_diff($datetime1, $datetime2);
	$days = $interval->format('%a');
	if($days > getDBsinglefield('value', 'otherOptions', 'key', 'passExpiryAdvise')){
		//It won't be necessary to remind user about changing password
		return false;
	}
	else{
		return true;
	}
}





/*******************************************************************************
 * ********************  PERSONAL DATA RELATED FUNCTIONS  ******************** *
 *******************************************************************************/


/* Checks whether a complete address (Name and Number for this Form) are well-formatted, avoiding as possible security breachs
 * Entry (inName): String which contains name of the address
 * Entry (inNumber): String which contains number/letter for given address name
 */
function checkFullAddress($inName, $inNumber, &$outName, &$outNumber, &$checkError){
	$connection = connectDB();
	
	$outName = trim(htmlentities(mysqli_real_escape_string($connection, $inName)));
	$outNumber = trim(htmlentities(mysqli_real_escape_string($connection, $inNumber)));
	
	if(strlen($outName) < 2){
		$checkError = "Introduzca una dirección válida, por favor.";
		return false;
	}
	elseif(strlen($outNumber) < 1){
		$checkError = "Introduzca el número de su dirección, por favor";
		return false;
	}
	return true;
}



/* Checks whether a DNI (native) or NIE (abroad people with national document) is well-formatted and written or not
 * Entry (nie): String
 * Exit: Boolean
 */
function checkDNI_NIE($nie){
	$connection = connectDB();
	
	$outNie = trim(htmlentities(mysqli_real_escape_string($connection, $nie)));
	
	if(strlen($outNie) != 9){
		return false;      
	}
	//Possible values for end letter
	$letterValues = array(0 => 'T', 1 => 'R', 2 => 'W', 3 => 'A', 4 => 'G', 5 => 'M', 6 => 'Y', 7 => 'F', 8 => 'P', 9 => 'D', 10 => 'X', 11 => 'B',
	12 => 'N', 13 => 'J', 14 => 'Z', 15 => 'S', 16 => 'Q', 17 => 'V', 18 => 'H', 19 => 'L', 20 => 'C', 21 => 'K', 22 => 'E');
	
	//Checks if matches with an original DNI
	if(preg_match('/^[0-9]{8}[A-Z]$/i', $outNie)){
		//Checking letter match
		if(strtoupper($outNie[strlen($outNie) - 1]) != $letterValues[((int) substr($outNie, 0, strlen($outNie) - 1)) % 23]){
			return false;
		}
		else{
			return true; 
		}
	}
	//Checks if matches with an original NIE
	elseif(preg_match('/^[XYZ][0-9]{7}[A-Z]$/i', $outNie)){
		//Checking letter match
		if(strtoupper($outNie[strlen($outNie) - 1]) != $letterValues[((int) substr($outNie, 1, strlen($outNie) - 2)) % 23]){
			return false;
		}
		else{
			return true;
		}
	}
	//If function arrives here is because entry string is not valid
	return false; 
}



/* Checks whether both Driving License fields are properly fullfilled
 * Entry (type): String
 * Entry (date): Date in format YYYY-MM-DD
 * Exit: Boolean
 */
/*
function checkDrivingLicense($type, $licDate, &$checkError){
	if(isset($type) && !isset($licDate)){
		$keyError = "Ha olvidado indicar la fecha en que obtuvo su permiso de conducir";
		return false;
	}
	if(!isset($type) && isset($licDate)){
		$keyError = "Ha olvidado indicar qué tipo de permiso de conducir posee";
		return false;
	}
	$keyError = "";
	return true;
}
*/
function checkDrivingLicense($type, $licDate, &$checkError){
	if((strlen($type) > 0) && (strlen($licDate) == 0)){
		$checkError = "Ha olvidado indicar la fecha en que obtuvo su permiso de conducir";
		return false;
	}
	elseif((strlen($type) == 0) && (strlen($licDate) > 0)){
		$checkError = "Ha olvidado indicar qué tipo de permiso de conducir posee";
		return false;
	}
	elseif(!isPreviousDate($licDate)){
		//$checkError = "La fecha de permiso de conducir no puede ser futura";
		$checkError = "Ha indicado una fecha de permiso de conducir futura";
		return false;
	}
	$checkError = "";
	return true;
}




/* Checks whether a Name & Surname are both correct to be saved in a DB
 * Entry (inName): Input string for Name
 * Entry (inSurname): Input string for Surname
 * Exit (outName): Returned string for Name
 * Exit (outSurname): Returned string for Surname
 * Exit (checkError): String with text that includes a description of the error
 */
function checkFullNameES($inName, $inSurname, &$outName, &$outSurname, &$checkError){
	$connection = connectDB();
	
	$outName = trim(htmlentities(mysqli_real_escape_string($connection, $inName)));
	$outSurname = trim(htmlentities(mysqli_real_escape_string($connection, $inSurname)));
	if((strlen($outName) < 2) || (strlen($outSurname) < 2)){
		$checkError = "Nombre y Apellidos deben tener al menos 2 caracteres cada uno.";
		return false;
	}
	return true;
}



/* Checks whether a MOBILE phone number is valid or not
 * Entry (mobile): Integer which contains a number
 * Exit: Boolean
 */
function checkMobile($mobile){
	$connection = connectDB();
	
	$outMobile = trim(htmlentities(mysqli_real_escape_string($connection, $mobile)));
	
	if(strlen($outMobile) != 9){
		return false;
	}
	elseif(!preg_match('/^[6-7][0-9]{8}$/', $outMobile)){
		return false;
	}
	return true;
}



/* Checks whether field Nationality in form is properly fulfilled
 * Entry (inNations): Input string acting as an array with 1 or more nationalities
 * Exit (outNations): Output string acting as an array for nationalities
 * Exit: Boolean
 */
function checkNationality($inNations, &$outNations){
	$connection = connectDB();
	
	$outArray = array();
	$numNats = count($inNations);
	for($i=0; $i<$numNats; $i++){
		$outSingleNation = trim(htmlentities(mysqli_real_escape_string($connection, $inNations[$i])));
		$outArray[$i] = $outSingleNation;
	}
	$outNations = implode("|",$outArray);
}



/* Checks whether a phone number is valid or not
 * Entry (phone): Integer which contains a number
 * Exit: Boolean
 */
function checkPhone($phone){
	$connection = connectDB();
	
	$outPhone = trim(htmlentities(mysqli_real_escape_string($connection, $phone)));
	
	if(strlen($outPhone) != 9){
		return false;
	}
	elseif(!preg_match('/^[9][0-9]{8}$/', $outPhone)){
		return false;
	}
	return true;
}






/*************************************************************************
 * ********************  STRINGS RELATED FUNCTIONS  ******************** *
 *************************************************************************/


/* Erases/Strips/Removes any character in a string which contains any non-supported type of accent (if necessary)
 * Entry (incoming_string): String with accents
 * Exit: String without accents
 */
function dropAccents($incoming_string){
	$tofind = "ÀÁÂÄÅÃàáâäãÒÓÔÖÕòóôöõÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñ";
	$replac = "AAAAAAaaaaaOOOOOoooooEEEEeeeeCcIIIIiiiiUUUUuuuuyNn";
	return utf8_encode(strtr(utf8_decode($incoming_string), utf8_decode($tofind), $replac));
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





/**************************************************************************
 * ********************  TIMEDATE RELATED FUNCTIONS  ******************** *
 **************************************************************************/


/* Generates a future date from current date
 * Entry (monthsNumber): Integer which indicates the number of months to be added
 * Exit (endDate): Date in format "YYYY-MM-DD"
 */
function addMonthsToDate($monthsNumber){
	$endDate = date('Y-m-d', strtotime('+'.$monthsNumber.' month'));
	return $endDate;
}



/* Checks whether a Birthdate is well-formatted and under current date
 * Entry (birth): Date in format YYYY-MM-DD
 * Exit: Boolean that confirms if date is correct or not
 */
function checkBirthdate($birth){
	$auxDateArray = explode('-', $birth);
	$auxDateMonth = $auxDateArray[1];
	$auxDateYear = $auxDateArray[0];
	$auxDateDay = $auxDateArray[2];
	/*
	echo 'Tal como viene es... '.$birth;
	echo 'Es el día '.$auxDateDay.' del mes '.$auxDateMonth.' del Año: '.$auxDateYear.'.';
	*/
	$current = strtotime(date('Y-m-d'));
	$birthdate = strtotime($birth);
	
	//if(checkdate($auxDateMonth, $auxDateDay, $auxDateYear)){
	//if((!checkdate($auxDateMonth, $auxDateDay, $auxDateYear) || ($birthdate > $current)){
	if((!checkdate($auxDateMonth, $auxDateDay, $auxDateYear)) || ($birthdate > $current)){
		return false;
	}
	else{
		return true;
	}
}



/* Checks whether a given input date is well-formatted and is if it is also older than current date
 * Entry (prevDate): Date in format YYYY-MM-DD
 * Exit: Boolean that confirms if date is correct and older than current or not
 */
function isPreviousDate($prevDate){
	$auxDateArray = explode('-', $prevDate);
	$auxDateMonth = $auxDateArray[1];
	$auxDateYear = $auxDateArray[0];
	$auxDateDay = $auxDateArray[2];
	
	//Converting common dates to UNIX date to be compared each other
	$current = strtotime(date('Y-m-d'));
	$initDate = strtotime($prevDate);
	
	if((!checkdate($auxDateMonth, $auxDateDay, $auxDateYear)) || ($initDate > $current)){
		return false;
	}
	else{
		return true;
	}
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





/**************************************************************************
 * ********************  OTHER FUNCTIONS  ******************** *
 **************************************************************************/



/* Gets the pending CVs number
 * Exit (singleDBfield): amount of pending CVs
 */
function getPendingCVs(){

	$connection = connectDB();

	$result = mysqli_query($connection, "SELECT COUNT( * ) FROM cvitaes WHERE cvStatus = 'pending'") or die ("Error calculando el número de CVs pendientes: ".mysqli_error($connection));
	
	if (mysqli_num_rows($result)>0){
		$fila = mysqli_fetch_array($result);
		$singleDBfield = $fila[0]; //Getting count (*) value
		mysqli_free_result($result);
		mysqli_close($connection);
		return $singleDBfield;
	}
	else{
		mysqli_free_result($result);
		mysqli_close($connection);
	}
}



/* Gets the translation for language
 * Entry (keyLanguage): Language key on DB
 * Entry (languageToBeTranslated): Desired language to $keyLanguage be translated
 * Exit (translation): Term into desired language
 */
function getLanguageTranslation($keyLanguage, $languageToBeTranslated){
	$connection = connectDB();

	$result = mysqli_query($connection, "SELECT `$languageToBeTranslated` FROM `siteLanguages` WHERE `key` = '$keyLanguage'") or die ("Error translating the language: ".mysqli_error($connection));
	$translation = mysqli_fetch_array($result);
	return $translation[0];
}



/* Gets key for a language
 * Entry (languageToBeTranslated): Desired language to be translated
 * Entry (languageWritten): In wich language are written $languageToBeTranslated
 * Exit (translation): Term into desired language
 */
function getKeyLanguage($languageToBeTranslated, $languageWritten){
	$connection = connectDB();

	$result = mysqli_query($connection, "SELECT `key` FROM `siteLanguages` WHERE `$languageWritten` = '$languageToBeTranslated'") or die ("Error translating the language: ".mysqli_error($connection));
	$translation = mysqli_fetch_array($result);
	return $translation[0];
}



?>
