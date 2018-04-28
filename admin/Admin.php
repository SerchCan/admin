<?php
    class Admin extends User{
         
        public function createUser($name,$mail,$user,$password,$address,$genre,$rfc,$curp,$userAlta,$idPais){
            $con = new PDORepository;
            $result = $con-> queryList("INSERT INTO Usuario(nombre, correo, username, password, estatus, tipo, fecha_alta, direccion, sexo, rfc, curp, usuario_alta, id_pais) 
            VALUES(:nombre, :email, :user, :pass, ACTIVO, CLIENTE, CURRENT_TIMESTAMP(), :address, :genre, :rfc, :curp, :userAlta :pais)",
            array('nombre'=>$name, 'email'=>$mail, 'user'=>$user, 'pass'=>$password, 'address'=>$address, 'genre'=>$genre, 'rfc'=>$rfc, 'curp'=>$curp, 'userAlta'=>$userAlta,'pais'=>$idPais));
            if(!$result){
                echo "Ha ocurrido un error al crear usuario.";
            }
        }
    }
?>