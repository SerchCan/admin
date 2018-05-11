<?php
/**
 *
 * @About:      Database connection manager class
 * @File:       Database.php
 * @Date:       $Date:$ Nov-2015
 * @Version:    $Rev:$ 1.0
 * @Developer:  Federico Guzman (federicoguzman@gmail.com)
 **/
class DbHandler {
 
    private $conn;
 
    function __construct() {
        require_once dirname(__FILE__) . './DbConnect.php';
        // opening db connection
        $db = new DbConnect();
        $this->conn = $db->connect();
    }
 
    public function insertarjet($id_tarjeta,$numero ,$pin ,$codigo_seguridad ,$fecha_validez_inicio ,$fecha_validez_fin ,$estatus ,$id_cuenta)
    {
        if ($this->iscardExists($id_tarjeta)) {
            $stmt = $this->conn->prepare("INSERT INTO tarjeta (numero, pin, codigo_seguridad, fecha_validez_inicio, fecha_validez_fin, estatus, id_cuenta) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssssd",$numero ,$pin ,$codigo_seguridad ,$fecha_validez_inicio ,$fecha_validez_fin ,$estatus ,$id_cuenta);
            // print_r($stmt);
            $result = $stmt->execute();
            $stmt->close();

            // Check for successful insertion
            if ($result) {
                // User successfully inserted
                return USER_CREATED_SUCCESSFULLY;
            } else {
                // Failed to create user
                return USER_CREATE_FAILED;
            }
        }
        else{
            return USER_ALREADY_EXISTED;
        }
    }
    public function insertclient($id_usuario, $nombre, $correo, $username, $password, $estatus, $tipo, $fecha_alta, $direccion, $sexo, $rfc, $curp, $usuario_alta, $id_pais)
    {
        if ($this->isclientExists($id_usuario)) {
            $stmt = $this->conn->prepare("INSERT INTO usuario ( nombre ,correo ,username ,password ,estatus ,tipo ,fecha_alta ,direccion ,sexo ,rfc ,curp ,usuario_alta ,id_pais) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)");
            $stmt->bind_param("ssssssssssddd", $nombre, $correo, $username, $password, $estatus, $tipo, $fecha_alta, $direccion, $sexo, $rfc, $curp, $usuario_alta, $id_pais, $id_usuario);
            // print_r($stmt);
            $result = $stmt->execute();
            $stmt->close();

            // Check for successful insertion
            if ($result) {
                // User successfully inserted
                return USER_CREATED_SUCCESSFULLY;
            } else {
                // Failed to create user
                return USER_CREATE_FAILED;
            }
        }
        else{
            return USER_ALREADY_EXISTED;
        }
    }
 
    public function updatetarjet($id_usuario,$numero ,$pin ,$codigo_seguridad ,$fecha_validez_inicio ,$fecha_validez_fin ,$estatus ,$id_cuenta)
    {
        if ($this->iscardExists($id_usuario)) {
            $stmt = $this->conn->prepare("UPDATE tarjeta INNER JOIN cuenta ON tarjeta.id_cuenta = cuenta.id_cuenta INNER JOIN usuario ON cuenta.id_usuario = usuario.id_usuario SET tarjeta.numero = ?,tarjeta.pin = ?,tarjeta.codigo_seguridad = ?,tarjeta.fecha_validez_inicio = ?,tarjeta.fecha_validez_fin = ?,tarjeta.estatus = ?,tarjeta.id_cuenta = ? WHERE usuario.id_usuario = ?");
            $stmt->bind_param("ssssssdd",$numero ,$pin ,$codigo_seguridad ,$fecha_validez_inicio ,$fecha_validez_fin ,$estatus ,$id_cuenta,$id_usuario);
            // print_r($stmt);
            $result = $stmt->execute();
            $stmt->close();

            // Check for successful insertion
            if ($result) {
                // User successfully inserted
                return USER_CREATED_SUCCESSFULLY;
            } else {
                // Failed to create user
                return USER_CREATE_FAILED;
            }
        }
        else{
            return USER_ALREADY_EXISTED;
        }
    }
    public function updateclient($id_usuario, $nombre, $correo, $username, $password, $estatus, $tipo, $fecha_alta, $direccion, $sexo, $rfc, $curp, $usuario_alta, $id_pais)
    {
        if ($this->isclientExists($id_usuario)) {
            $stmt = $this->conn->prepare("UPDATE usuario  SET nombre = ?, correo = ?, username = ?, password = ?, estatus = ?, tipo = ?, fecha_alta = ?, direccion = ?, sexo = ?, rfc = ?, curp = ?, usuario_alta = ?, id_pais = ? WHERE usuario.id_usuario = ?");
            $stmt->bind_param("ssssssssssddd", $nombre, $correo, $username, $password, $estatus, $tipo, $fecha_alta, $direccion, $sexo, $rfc, $curp, $usuario_alta, $id_pais, $id_usuario);
            // print_r($stmt);
            $result = $stmt->execute();
            $stmt->close();

            // Check for successful insertion
            if ($result) {
                // User successfully inserted
                return USER_CREATED_SUCCESSFULLY;
            } else {
                // Failed to create user
                return USER_CREATE_FAILED;
            }
        }
        else{
            return USER_ALREADY_EXISTED;
        }
    }
    public function requesclient()
    {
        $stmt = $this->conn->prepare("SELECT usuario.*, tarjeta.numero
                                    FROM usuario
                                    INNER JOIN cuenta ON usuario.id_usuario = cuenta.id_usuario
                                    INNER JOIN tarjeta ON cuenta.id_cuenta = tarjeta.id_cuenta
                                    where usuario.tipo = 'CLIENTE'");
        // print_r($stmt);
        if ($stmt->execute()) {
            $clientes = $this->get_result($stmt);//->fetch_assoc();
            $stmt->close();
            return $clientes;
        } else {
            return NULL;
        }
    }

    public function getcarddata($id_usuario)
    { 

        $stmt = $this->conn->prepare("SELECT numero, pin, codigo_seguridad, estatus, fecha_validez_fin, id_cuenta FROM tarjeta WHERE id_cuenta = SELECT id_cuenta from cuenta where id_usuario = ? ");
        $stmt->bind_param("s", $id_usuario);
        // print_r($stmt);
        if ($stmt->execute()) {
            $movements = $this->get_result($stmt);//->fetch_assoc();
            $stmt->close();
            return $movements;
        } else {
            return NULL;
        }
    }

    public function getclientdata($id_usuario)
    {
        $stmt = $this->conn->prepare("SELECT nombre, correo, username, password, estatus, tipo, direccion, sexo, rfc, curp, id_pais FROM usuario WHERE username = ?");
        $stmt->bind_param("s", $id_usuario);
        if ($stmt->execute()) {
            $movements = $this->get_result($stmt);//->fetch_assoc();
            $stmt->close();
            return $movements;
        } else {
            return NULL;
        }
    }
    private function isclientExists($id_usuario) {
        $stmt = $this->conn->prepare("SELECT id_usuario from usuario WHERE id_usuario = ?");
        $stmt->bind_param("s", $id_usuario);
        $stmt->execute();
        $stmt->store_result();
        $num_rows = $stmt->num_rows;
        $stmt->close();
        return $num_rows > 0;
    }
    private function iscardExists($id_usuario) {
        $stmt = $this->conn->prepare("SELECT id_tarjeta from tarjeta INNER JOIN cuenta ON tarjeta.id_cuenta = cuenta.id_cuenta INNER JOIN usuario ON cuenta.id_usuario = usuario.id_usuario WHERE usuario.id_usuario = ?");
        $stmt->bind_param("s", $id_usuario);
        $stmt->execute();
        $stmt->store_result();
        $num_rows = $stmt->num_rows;
        $stmt->close();
        return $num_rows > 0;
    }
   
}
 
?>