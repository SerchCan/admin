<?php
require("connectDB.php");
// Start XML file, create parent node

$return_arr = array();
$connection=mysql_connect ('localhost', $username, $password);
if (!$connection) {
  die('Not connected : ' . mysql_error());
}

$db_selected = mysql_select_db($database, $connection);
if (!$db_selected) {
  die ('Can\'t use db : ' . mysql_error());
}

$fetch = mysql_query("SELECT * FROM usuario WHERE tipo = 'CLIENTE'"); 

while ($row = mysql_fetch_array($fetch, MYSQL_ASSOC)) {
	$row_array['id_usuario'] =  $row['id_usuario'];
	$row_array['nombre'] =  $row['nombre'];///
	$row_array['correo'] =  $row['correo'];///
	$row_array['username'] =  $row['username'];///
	$row_array['password'] =  $row['password'];
	$row_array['estatus'] =  $row['estatus'];//
	$row_array['tipo'] =  $row['tipo'];///
	$row_array['fecha_alta'] =  $row['fecha_alta'];
	$row_array['direccion'] =  $row['direccion'];///
	$row_array['sexo'] =  $row['sexo'];///
	$row_array['rfc'] =  $row['rfc'];///
	$row_array['curp'] =  $row['curp'];///
	$row_array['usuario_alta'] =  $row['usuario_alta'];
	$row_array['id_pais'] =  $row['id_pais'];///
    array_push($return_arr,$row_array);
}
echo json_encode($return_arr);

?>