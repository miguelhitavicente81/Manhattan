<?php
	error_reporting (E_ALL & ~E_NOTICE & ~E_STRICT & ~E_DEPRECATED & ~E_WARNING);
	
	require_once($_SERVER['DOCUMENT_ROOT'] . '/common/library/functions.php');

	session_start();

	$userLang = getDBsinglefield('language', 'users', 'login', $_SESSION['loglogin']);
	$sel= getDBsinglefield($userLang, 'languages', 'key', $_GET[value]);
	$_SESSION['langselected'] = $_SESSION['langselected']." ".$sel;
	if(isset($_GET[valuedel])){
		$sel = $_GET[valuedel];
		$languageSelected = explode(" ",$_SESSION['langselected']);
		$languageSelectedC=array_values(array_diff($languageSelected, array('')));
		unset($_SESSION['langselected']) ;
		$result = count($languageSelectedC);
		
		if($result<=($sel-1)){
			unset($languageSelectedC);
		}
		else{
			unset($languageSelectedC[$sel-1]);
		}
		$languageSelectedC=array_values(array_diff($languageSelectedC, array('')));
		
		foreach($languageSelectedC as $value){
			if(strlen($value)>1){
				$_SESSION['langselected'] = $_SESSION['langselected']." ".$value;
			}
			else
			{
				unset($_SESSION['langselected']) ;
			}
		}
	}
?>

<select class="form-control" name="add_idiomas">
	<option selected disabled value=""> Pulse "+" tras elegir... </option>
	<?php
		$langNames = getDBcompletecolumnID($userLang, 'languages', 'id');
		
		foreach($langNames as $i){
		$resultado = strpos($_SESSION['langselected'], $i);
			if ($resultado == FALSE){
				echo "<option value=" . getDBsinglefield('key', 'languages', $userLang, $i) . ">" . $i ."</option>";
			}
		}
		?>
