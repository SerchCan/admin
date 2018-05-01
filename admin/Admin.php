<?php
    include_once "User.php";
    class Admin extends User{
        public function createUser($name, $mail, $user, $password, $address, $genre, $rfc, $curp, $userAlta, $idPais, $balance, $detalles, $tipo){
            $con = new PDORepository;
            
            $Createuser = $con-> queryList('INSERT INTO usuario(nombre, correo, username, password, estatus, tipo, fecha_alta, direccion, sexo, rfc, curp, usuario_alta, id_pais) 
            VALUES(:nombre, :email, :user, :pass, "ACTIVO", "CLIENTE", CURRENT_TIMESTAMP(), :address, :genre, :rfc, :curp, :userAlta, :pais)',
            array('nombre'=>$name, 'email'=>$mail, 'user'=>$user, 'pass'=>$password, 'address'=>$address, 'genre'=>$genre, 'rfc'=>$rfc, 'curp'=>$curp, 'userAlta'=>$userAlta,'pais'=>$idPais));
            
            $id = $this->getLastInsert();
            $accountNumber = $this->generateAccount($password,$curp,$idPais);

            $CreateAccount=$con->queryList('INSERT INTO cuenta(numero,balance,detalles,fecha_alta,estatus,tipo,id_usuario)
                VALUES (:acnumber,:balance,:details, CURRENT_TIMESTAMP(), "ACTIVA", :tipo, :id)',
                array('acnumber'=>$accountNumber,'balance'=>$balance,'details'=>$detalles,'tipo'=>$tipo,'id'=>$id));
            
        }

        public function getLastInsert(){
            $con = new PDORepository;
            $query= $con-> queryList("SELECT id_usuario FROM usuario ORDER BY id_usuario DESC LIMIT 1",null)
                        -> fetch(PDO::FETCH_ASSOC);
            return $query['id_usuario'];
        }
        public function generateAccount($password,$curp,$idPais){
            //                  country           EP(EdisonPay) CURP      PASSWORD
            $accountNumber = sprintf("%02d", $idPais)."6980".ord($curp).ord($password);
            return $accountNumber;
        }
    }
?>