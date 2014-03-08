<?php session_start(); 

if (!$_SESSION['loglogin']){
	?>
	<script type="text/javascript">
		window.location.href='index.html';
	</script>
	<?php
}
else {
	$lastUpdate = $_SESSION['lastupdate'];
	$curUpdate = date('Y-m-d H:i:s');
	$elapsedTime = (strtotime($curUpdate)-strtotime($lastUpdate));
	if($elapsedTime > $_SESSION['sessionexpiration']){
		?>
		<script type="text/javascript">
			window.location.href='endsession.php';
		</script>
		<?php
	}
	else{
		$_SESSION['lastupdate'] = $curUpdate;
		unset($lastUpdate);
		unset($curUpdate);
		unset($elapsedTime);
	}
	require_once($_SERVER['DOCUMENT_ROOT'] . '/common/library/functions.php');
	
	$value = intval($_GET['value']);
	$result = getDBcolumnvalue('cityName', 'postalCitiesES', 'postalCode', $value);
	if(count($result) > 1){
		echo 'Localidad: <select name="blankaddrcity" id="blankaddrcity" style="width:60%">';
			echo'<option>Su localidad...</option>';
			foreach($result as $j){
				$auxi = $j;
				echo 'aux es'.$auxi.' - ';
				echo "<option value='$auxi'>".$j."</option>";
			}
		echo '</select><br>';
	}
	else{
		echo 'Localidad: <input type="text" name="blankaddrcity" size="50" value="' . $result[0] . '" disabled><br>';
	}
	echo 'Provincia: <input type="text" name="blankaddrprovince" size="20" value="' . getDBsinglefield('provinceName', 'postalProvincesES', 'id', getDBsinglefield('provCod', 'postalCitiesES', 'postalCode', $value)) . '" disabled><br>';
	echo 'País: <input type="text" name="blankaddrcountry" size="20" value="España" disabled><br>';
	
}

?>