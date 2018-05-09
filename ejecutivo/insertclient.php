 <?php
require("connectDB.php");
$id_usuario = $_POST['id_usuario'];
$nombre = $_POST['nombre'];
$correo = $_POST['correo'];
$username = $_POST['username'];
$password = $_POST['password'];
$estatus = $_POST['estatus'];
$tipo = $_POST['tipo'];
$fecha_alta = $_POST['fecha_alta'];
$direccion = $_POST['direccion'];
$sexo = $_POST['sexo'];
$rfc = $_POST['rfc'];
$curp = $_POST['curp'];
$usuario_alta = $_POST['usuario_alta'];
$id_pais = $_POST['id_pais'];
// Create connection
$conn = mysqli_connect($server, $username, $password, $database);
// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$sql = sprintf("INSERT INTO usuario (id_usuario,nombre,correo,username,password,estatus,tipo,fecha_alta,direccion,sexo,rfc,curp,usuario_alta,id_pais)
VALUES ('".$id_usuario."','".$nombre."','".$correo."','".$username."','".$estatus."','".$tipo."',CAST('". $fecha_validez_fin ."' AS DATE),".$direccion."','".$sexo."','".$rfc."','".$curp."','".$usuario_alta."','".$id_pais."')";

if ($conn->query($sql) === TRUE) {
    echo "New record created successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

mysqli_close($conn);
?> 