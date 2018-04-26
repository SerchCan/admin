<?php
    include_once "User.php";
    // Client can only get his own data but cannot set it.
    class Client extends User{
	    private $accountNumber;
        private $cardNumber;
        
        public function createUser($name,$mail,$user,$password,$address,$genre,$rfc,$curp,$idPais){
            $con = new PDORepository;
            $result = $con-> queryList("INSERT INTO Usuario(nombre,correo,username,password,estatus,fecha_alta,direccion,sexo,rfc,curp) 
                                        VALUES(:nombre, :email, :user, :pass, ACTIVO, CURRENT_TIMESTAMP(), :address, :genre, :rfc, :curp, :pais)",
                                        array('nombre'=>$name, 'email'=>$mail, 'user'=>$user, 'pass'=>$password, 'address'=>$address, 'genre'=>$genre, 'rfc'=>$rfc, 'curp'=>$curp, 'pais'=>$idPais));
            if(!$result){
                echo "Ha ocurrido un error al crear usuario.";
            }
        }
        public function fillUser($id){
            $con = new PDORepository;  
            $userData = $con-> queryList("SELECT * FROM Usuario WHERE id_usuario=:id", array('id'=>$id))
                            -> fetch(PDO::FETCH_ASSOC);
            if($userData){
                $id = $userData['id_usuario'];
                $name = $userData['nombre'];
                $email = $userData['correo'];
                $user = $userData['username'];
                $password = $userData['password'];
                $status = $userData['estatus'];
                $alta = $userData['fecha_alta'];
                $address = $userData['direccion'];
                $genre = $userData['sexo'];
                $rfc = $userData['rfc'];
                $curp = $userData['curp'];
                $this-> setUser($id,$name,$email,$user,$password,$status,$alta,$address,$genre,$rfc,$curp);
            }
            else{
                echo "No se encontro el id de usuario en el sistema.";
            }
        }
        public function getAccountNumber(){
            return $this->AccountNumber;
        }
        public function getCard(){
            return $this->CardNumber;
        }
    }
?>