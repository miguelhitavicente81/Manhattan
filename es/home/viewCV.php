<style type="text/css">
.ventana_flotante {
    background: none repeat scroll 0 0 #FFFFFF;
    border: 1px solid #DDDDDD;
    border-radius: 6px 6px 6px 6px;
    bottom: 50px;
    left: auto;
    margin-left: -120px;
    padding: 10px 0 0;
    position: fixed;
    text-align: center;
    width: 90px;
    z-index: 15;
}
</style>
<?php
session_start();
error_reporting (E_ALL & ~E_NOTICE & ~E_STRICT & ~E_DEPRECATED & ~E_WARNING);
$id_ac=$_GET["id_b"];
$id_aco=$_GET["id_bb"];
$id =unserialize($_SESSION["id"]);
$id_o =unserialize($_SESSION["id_o"]);
if(strlen($id_ac)>0){$actual=$id[$id_ac];}
if(strlen($id_aco)>0){$actual=$id_o[$id_aco];}
$i=0;
foreach ($id_o as $valor){
if($valor == $actual){
$ind_a=$i;
$h=$i-1;
$ind_p=$h;
$j=$i+1;
$ind_n=$j;
}
$i++;
}

$enlace = mysqli_connect("localhost", "root", "", "PRJ2014001");

if (mysqli_connect_errno()) {
    printf("Fall la conexin: %s\n", mysqli_connect_error());
    exit();
}

$consulta = "SELECT * from cVitaes where nie like '$id_o[$ind_a]'" ;
if ($resultado = mysqli_query($enlace, $consulta)) {
while ($fila = $resultado->fetch_row()) {
$info_campo = mysqli_fetch_fields($resultado);
$i=0;
foreach ($info_campo as $valor) {
echo "<b>$valor->name:</b> $fila[$i]<br>";
$i++;
}
}
}
echo "<div id=ventana_flotante><textarea name=nota rows=5 cols=40>Descripción</textarea></div>";
if(strlen($id_o[$ind_n])>0)
//echo "<a href=visualizacv.php?id_bb=$ind_n>SIGUIENTE</a><br>";
echo "<a href=viewCV.php?id_bb=$ind_n>SIGUIENTE</a><br>";
if(strlen($id_o[$ind_p])>0)
//echo "<a href=visualizacv.php?id_bb=$ind_p>PREVIO </a><br>";
echo "<a href=viewCV.php?id_bb=$ind_p>PREVIO </a><br>";
$_SESSION["id_o"] = serialize($id_o);
$_SESSION["id"] = serialize($id);


?>
