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
		echo '<label id="uploadFormLabel" class="control-label col-sm-2" for="blankaddrcity" style="padding-right: 10px;">Localidad: </label><select class="form-control" name="blankaddrcity" id="blankaddrcity" style="margin-top:5px; width:60%">';
			echo'<option>Su localidad...</option>';
			foreach($result as $j){
				$auxi = $j;
				echo 'aux es'.$auxi.' - ';
				echo "<option value='$auxi'>".$j."</option>";
			}
		echo '</select><br>';
	}
	else{
		echo '<label id="uploadFormLabel" class="control-label col-sm-2" for="blankaddrcity" style="padding-right: 10px;">Localidad: </label><input class="form-control" type="text" name="blankaddrcity" size="50" value="' . $result[0] . '" readonly style="margin-top:5px;"><br>';
	}
	echo '<label id="uploadFormLabel" class="control-label col-sm-2" for="blankaddrprovince" style="padding-right: 10px;">Provincia: </label><input class="form-control" type="text" name="blankaddrprovince" size="20" value="' . getDBsinglefield('provinceName', 'postalProvincesES', 'id', getDBsinglefield('provCod', 'postalCitiesES', 'postalCode', $value)) . '" readonly style="margin-top:5px;"><br>';
	echo '<label id="uploadFormLabel" class="control-label col-sm-2" for="blankaddrcountry" style="padding-right: 10px;">País: </label><input class="form-control" type="text" name="blankaddrcountry" size="20" value="España" readonly style="margin-top:5px;"><br>';
	
}

?>