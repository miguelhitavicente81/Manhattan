<? session_start(); ?>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<link href='http://fonts.googleapis.com/css?family=Ubuntu+Mono:400,700,400italic,700italic|Ubuntu:300,400,500,700,300italic,400italic,500italic,700italic|Ubuntu+Condensed&
subset=latin,latin-ext' rel='stylesheet' type='text/css'>
	<title>Inicio</title>
	<link href="../../common/css/styles.css" rel="stylesheet" type="text/css">
	<script type="text/javascript" src="../../common/js/functions.js"></script>
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
	$curUpdate = date('Y-n-j H:i:s');
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
	require_once '../library/functions.php';
	?>
	<div id="topbar" class="azul">
		<a style="float:left;" href="#">Opciones</a>
		<a style="float:center">Conectado como: <?php echo $_SESSION['loglogin']; ?></a>
		<a href="../endsession.php" style="float:right">Salir</a>
	</div>
	<?php 
	$myFile = 'administration';
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
		<h3>Crear Nuevo Usuario</h3>
		<?php if(getDBsinglefield('AdmCurUsers', 'administration', 'profile', $_SESSION['logprofile'])) ?>
			<a id="rightlink" href="AdmCurUsers.php" style="position: relative;">Usuarios actuales</a>
		<hr class="long">
		<br>
		Cambiar la forma de mostrar la lista de perfiles, ya que ahora mismo muestra todos los existentes, est√°n activos o no.
		<br><br>
		<div id="stylized" class="myform">
			<form id="form" name="form" action="../library/ValidateForms.php" method="post">
				<h1>Inserte los datos</h1>
				<label>Usuario: * </label><input type="text" name="newuser" size="30"><br>
				<label>Password: * </label><input type="password" name="newpass" size="30"><br>
				<label>Confirmar pwd: * </label><input type="password" name="renewpass" size="30"><br>
				<label>Perfil: * </label><select name="chooseprof">
					<option value="0">-- Elegir --</option>
					<?php 
					$nameColumn = getDBcompletecolumnID('largeName', 'profiles', 'cod_profile');
					//$admin_profile = getDBsinglefield('cod_profile', 'users', 'login', $_SESSION['loglogin']);
					/* Teniendo en cuenta que a este men√∫ solo tendr√°n acceso el Administrador y los Usuarios Gestores, debo permitir que admin pueda crear un usuario 
					 * de cualquier perfil, y que el resto de usuarios con acceso aqu√≠ puedan crear usuarios que no sean Administrador ni de su propio perfil.
					 */
					//if($admin_profile == 1){
					if($userRow['profile'] == 'Administrador'){
						foreach($nameColumn as $i){
							echo "<option>" . utf8_encode($i) . "</option>";
						}
					}
					//En caso de un usuario NO ADMINISTRADOR que puede crear usuarios de un perfil m√°s restringido que el suyo propio
					else{
						foreach($nameColumn as $i){
							//LO SIGUIENTE ESTA MAL, PORQUE ASI NO EVITO MOSTRAR LOS PERFILES CON MAS PRIVILEGIOS QUE NO FUERAN EL PROPIO O EL DE ADMIN -->> YA ESTA BIEN
							//Muestro los perfiles con nivel inferior (level m√°s alto que el del usuario que est√° creando el nuevo usuario) al del usuario creador (con esto ya evito el de Administrador y el propio)
							//if((getDBsinglefield('cod_profile', 'profiles', 'largeName', $i) != 1) && ($i != getDBsinglefield('largeName', 'profiles', 'cod_profile', $_SESSION['logprofile']))){
							if(getDBsinglefield('level', 'profiles', 'largeName', $i) > getDBsinglefield('level', 'profiles', 'largeName', getDBsinglefield('largeName', 'profiles', 'name', $userRow['profile']))){
								echo "<option>" . utf8_encode($i) . "</option>";
							}
						}
					}
					?>
				</select>
				<?php 
				//Indicando manualmente que la variable 'newactive' vale inicialmente 'off' evito que aparezca un error al cargar la p√°gina
				//$_POST['newactive'] = 'off';
				?>
				<label>Nombre: * </label><input type="text" name="newname" size="30"><br>
				<label>Apellidos: * </label><input type="text" name="newsurname" size="30"><br>
				<label>Correo: * </label><input type="text" name="newmail" size="30"><br>
				<label>Tel√©fono: * </label><input type="text" name="newphone" size="30" maxlength="9"><br/>
				<label>Activo: </label><input type="checkbox" name="newactive"><br>
				<input type="hidden" value="h_admnewuser" name="hiddenfield">
				<input type="submit" value="Crear" name="createuser">
			</form>
		</div><!-- Fin del "stylized" -->
		</div><!-- Fin del "rightbox" -->
	</div><!-- Fin del "workspace" -->
	<?php
}//del "else" de $_SESSION.

?>

</body>
</html>
