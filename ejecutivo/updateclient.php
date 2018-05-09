<?php
require("connectDB.php");
// Start XML file, create parent node
// Create connection
$nombre = $_POST['nombre'];
$correo = $_POST['correo'];
$username = $_POST['username'];
$estatus = $_POST['estatus'];
$tipo = $_POST['tipo'];
$direccion = $_POST['direccion'];
$sexo = $_POST['sexo'];
$rfc = $_POST['rfc'];
$curp = $_POST['curp'];
$id_pais = $_POST['id_pais'];
$id_usuario = $_GET['id_usuario'];
$conn = mysqli_connect($server, $username, $password, $database);
// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$sql = "UPDATE usuario " .
     "SET nombre = '" . $nombre . "', correo = '" . $correo . "', username = '" . $username . "', estatus = '" . $estatus . "', tipo = '" . $tipo . "', direccion = '" . $direccion . "', sexo = '" . $sexo . "', rfc = '" . $rfc . "',curp = '" . $curp . "', id_pais = '" . $id_pais . "' WHERE `id_usuario` = '" . $id_usuario. "'";

if (mysqli_query($conn, $sql)) {
    echo "Record updated successfully";
} else {
    echo "Error updating record: " . mysqli_error($conn);
}

mysqli_close($conn);
?>