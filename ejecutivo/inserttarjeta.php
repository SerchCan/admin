 <?php
require("connectDB.php");
$id_tarjeta = $_POST['id_tarjeta'];
$nombre = $_POST['nombre'];
$pin = $_POST['pin'];
$codigo_seguridad = $_POST['codigo_seguridad'];
$fecha_validez_inicio = $_POST['fecha_validez_inicio'];
$fecha_validez_fin = $_POST['fecha_validez_fin'];
$estatus = $_POST['estatus'];
$id_cuenta = $_POST['id_cuenta'];
// Create connection
$conn = mysqli_connect($server, $username, $password, $database);
// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$sql = "INSERT INTO tarjeta (id_tarjeta, nombre, pin, codigo_seguridad, fecha_validez_inicio, fecha_validez_fin, estatus, id_cuenta) VALUES ('".$id_tarjeta."','".$nombre."','".$pin."','".$codigo_seguridad."',CAST('". $fecha_validez_inicio ."' AS DATE),CAST('". $fecha_validez_fin ."' AS DATE),".$estatus."','".$id_cuenta."')";

if ($conn->query($sql) === TRUE) {
    echo "New record created successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

mysqli_close($conn);
?> 