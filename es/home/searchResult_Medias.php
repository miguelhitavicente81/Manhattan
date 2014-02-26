<? 
session_start();
set_time_limit(1800);
set_include_path('../../common/0.12-rc12/src/' . PATH_SEPARATOR . get_include_path());
?>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<link href='http://fonts.googleapis.com/css?family=Ubuntu+Mono:400,700,400italic,700italic|Ubuntu:300,400,500,700,300italic,400italic,500italic,700italic|Ubuntu+Condensed&
subset=latin,latin-ext' rel='stylesheet' type='text/css'>
	<title>Inicio</title>
	<link href="../../common/css/styles.css" rel="stylesheet" type="text/css">
	<link href="../../common/css/searchStyle.css" rel="stylesheet" type="text/css">
	<script src="../../common/js/functions.js" type="text/javascript"></script>
</head>

<body>
<?php
if (!$_SESSION['loglogin']){
	 ?>
	<script type="text/javascript">
		window.location.href='../index.html';
	</script>
	<?php
}
else{
	$lastUpdate = $_SESSION['lastupdate'];
	$curUpdate = date('Y-m-j H:i:s');
	$elapsedTime = (strtotime($curUpdate)-strtotime($lastUpdate));
	if($elapsedTime > $_SESSION['sessionexpiration']){
		?>
		<script type="text/javascript">
			window.location.href='../endsession.php';
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
	?>
	<div id="topbar" class="azul">
		<a style="float:left;" href="#">Opciones</a>
		<a style="float:center">Conectado como: <?php echo $_SESSION['loglogin']; ?></a>
		<a href="../endsession.php" style="float:right">Salir</a>
	</div>
	<?php 
	$myFile = 'home';
	$userRow = getDBrow('users', 'login', $_SESSION['loglogin']);
	?>
	<div id="mainmenu">
	<ul class="navbar1">
		<?php 
		$mainKeysRow = getDBcompletecolumnID('key', 'mainNames', 'id');
		$mainNamesRow = getDBcompletecolumnID('esName', 'mainNames', 'id');
		$j = 0;
		foreach($mainKeysRow as $i){
			if(getDBsinglefield('active', $i, 'profile', $userRow['profile'])){
				if($myFile == $i){
					echo "<li><a href=../$i.php id='onlink'>" . utf8_encode($mainNamesRow[$j]) . "</a></li>";
					$j++;
				}
				else{
					echo "<li><a href=../$i.php>" . utf8_encode($mainNamesRow[$j]) . "</a></li>";
					$j++;
				}
			}
		}
		?>
	</ul>
	</div>

	<div class="workspace">
		<div class="leftbox">
			<!-- Este 'class' sirve para mostrar los submenús alineados a la izquierda en el nivel 2 -->
			<ul>
			<?php
			$namesTable = $myFile.'Names';
			$numCols = getDBnumcolumns($myFile);
			$myFileProfileRow = getDBrow($myFile, 'profile', $userRow['profile']);
			for($j=3;$j<$numCols;$j++){
				$colNamej = getDBcolumnname($myFile, $j);
				if(($myFileProfileRow[$j] == 1) && ($subLevelMenu = getDBsinglefield2('esName', $namesTable, 'key', $colNamej, 'level', '2'))){
					if(!getDBsinglefield2('esName', $namesTable, 'fatherKey', $colNamej, 'level', '3')){
						$level2File = getDBsinglefield('key', $namesTable, 'esName', $subLevelMenu);
						echo "<li><a href=./$level2File.php>" . $subLevelMenu . "</a></li>";
					}
					else{
						$arrayKeys = array();
						$arrayKeys = getDBcolumnvalue('key', $namesTable, 'fatherKey', $colNamej);
						$checkFinished = 0;
						$l = 1;
						foreach($arrayKeys as $k){
							if($checkFinished == 0){
								if(($myFileProfileRow[$j+$l] == 1) && (getDBsinglefield($k, $myFile, 'profile', $userRow['profile']))){
									$level3File = $k;
									$checkFinished = 1;
								}
								else{
									$l++;
								}
							}
						}
						echo "<li><a href=./$level3File.php>" . $subLevelMenu . "</a></li>";
					}
				}
			}
			?>
			</ul>
		</div>

		<div class="rightbox">
			
			
<?php
	include 'Cezpdf.php';
	
	class Creport extends Cezpdf{
		function Creport($p,$o){
			$this->__construct($p, $o,'none',array());
		}
	}
/*
$enlace = mysqli_connect("localhost", "root", "", "PRJ2014001");

if (mysqli_connect_errno()) {
    printf("Fall la conexin: %s\n", mysqli_connect_error());
    exit();
}
*/
	$consulta = "SELECT * from cVitaes";
	
//if ($resultado = mysqli_query($enlace, $consulta)) {
	if($queryResult = executeDBquery($consulta)){
		$j = 0;
		echo "<table id=tabla3>";
			echo "<tr class=alt>";
				echo "<th colspan='6'>Nivel de Idiomas</th>";
			echo "</tr>";
			echo "<tr class=alt>";
				echo "<th>Id</th>";
				echo "<th>NIE</th>";
				echo "<th>Name</th>";
				echo "<th>Surname</th>";
				echo "<th>Occupation</th>";
			echo "</tr>";
			
			while ($fila = $queryResult->fetch_row()) {
				$pdf = new Cezpdf('A4'); //seleccionamos tipo de hoja
				$pdf->selectFont('fonts/Helvetica.afm'); //seleccionamos fuente a utilizar
				$info_campo = mysqli_fetch_fields($queryResult);
				$i=0;
				//$npdf=$npdf."_".$fila[4]."_".$fila[5];
				$npdf=$npdf."_".$fila[3]."_".$fila[4];
				foreach ($info_campo as $valor){
					chop($valor->name);
					if ($valor->name == id){$id[$fila[$i]]=$fila[++$i];}
					$pdf->ezText("<b>$valor->name</b> $fila[$i]");
					$i++;
				}
				//echo "<table id=tabla3>";
				if ($j%2==0){
					echo "<tr><td>";
				}
				else{
					echo "<tr class=alt><td>";
				}
				//echo "$fila[0]</td><td><a href=visualizacv.php?id_b=$fila[0] target=_blank>$fila[1]</a></td><td>$fila[4]</td><td>$fila[5]</td><td>$fila[21]</td></tr>";
				echo "$fila[0]</td><td><a href=viewCV.php?id_b=$fila[0] target=_blank>$fila[1]</a></td><td>$fila[4]</td><td>$fila[5]</td><td>$fila[21]</td></tr>";
				
				//echo "</table>";
				#$pdf->ezStream();
				
				$documento_pdf = $pdf->ezOutput();
				$nf="/Applications/XAMPP/xamppfiles/temp/cvs/cv_$npdf.pdf";
				$fichero = fopen("$nf",'wb');
				fwrite ($fichero, $documento_pdf);
				fclose ($fichero);
				$nf="";
				$npdf="";
				$j++;
			}
		echo "</table>";
			/*
				$langLevelNumRows = getDBrowsnumber('languageLevel');
				for($i=1;$i<=$langLevelNumRows;$i++){
					$langLevelRow = getDBrow('languageLevel', 'id', $i);
					echo "<tr>";
					echo "<td>" . $langLevelRow['id'] . "</td>";
					echo "<td>" . utf8_encode($langLevelRow['key']) . "</td>";
					echo "<td>" . utf8_encode($langLevelRow['enName']) . "</td>";
					echo "<td>" . utf8_encode($langLevelRow['esName']) . "</td>";
					echo "<td>" . utf8_encode($langLevelRow['deName']) . "</td>";
					echo "<td>Borrar</td>";
					echo "</tr>";
					*/
	    mysqli_free_result($resultado);
	}
$numero=rand();
`cd /Applications/XAMPP/xamppfiles/temp/cvs/ && tar cf cvs$numero.tar *.pdf`;
`rm -rf /Applications/XAMPP/xamppfiles/temp/cvs/*.pdf`;
$i=0;
foreach ($id as $valor) {
$id_o[$i]=$valor;
$i++;
}
echo "<a href=downloadFile.php?doc=cvs$numero.tar>descargar</a>";

$_SESSION["id_o"] = serialize($id_o);
$_SESSION["id"] = serialize($id);
?>			
			
			
			
			
			
			
			
			
			
			
			
		</div><!-- Fin del "rightbox" -->
	</div><!-- Fin del "workspace" -->
	<?php
}//del "else" de $_SESSION.

?>

</body>
</html>
