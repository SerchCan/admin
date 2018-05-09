<?php
require("connectDB.php");
// Start XML file, create parent node
// Create connection
$numero = $_POST['numero'];
$estatus = $_POST['estatus'];
$id_tarjeta = $_GET['id_tarjeta'];
$conn = mysqli_connect($server, $username, $password, $database);
// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$sql = "UPDATE usuario " .
     "SET numero = '" . $numero . "', estatus = '" . $estatus . "' WHERE `id_tarjeta` = '" . $id_tarjeta. "'";

if (mysqli_query($conn, $sql)) {
    echo "Record updated successfully";
} else {
    echo "Error updating record: " . mysqli_error($conn);
}

mysqli_close($conn);
?>