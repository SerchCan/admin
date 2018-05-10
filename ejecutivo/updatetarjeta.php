<?php
require("connectDB.php");
// Start XML file, create parent node
// Create connection
//$numero = $_POST['numero'];
//$estatus = $_POST['estatus'];

$conn = mysqli_connect($server, $username, $password, $database);
// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}


$numero = $_POST['numero'];
$pin = $_POST['pin'];
$estatus = $_POST['estatus'];
$id_usuario = $_GET['user'];

$sql = "SELECT id_cuenta FROM cuenta WHERE id_usuario = '".$id_usuario."'";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$id_cuenta = $row["id_cuenta"];

$sql1 = "UPDATE tarjeta SET numero = '".$numero."', pin = '".$pin."',estatus = '".$estatus."'  WHERE id_cuenta = '".$id_cuenta."'";

if ($conn->query($sql1) === TRUE) {
    echo "Record updated successfully";
} else {
    echo "Error updating record: " . $conn->error;
}

mysqli_close($conn);
?>