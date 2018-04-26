<?php
    include_once "User.php";
    // Client can only get his own data but cannot set it.
    class Client extends User{
	    private $accountNumber;
        private $cardNumber;
        public function __construct($id){
            $con = new PDORepository;  
            //Example use: $con->(QUERY, ARGS)-> 'fetchAll()' or 'fetch()' single record.  you decide
            $userData = $con-> queryList("SELECT * FROM Usuario where id_usuario=:id", array("id"=>$id))
                            -> fetch(PDO::FETCH_ASSOC);
            if($userData){
                $id=$userData['id_usuario'];
                $name=$userData['nombre'];
                $email=$userData['correo'];
                $user=$userData['username'];
                $password=$userData['password'];
                $status=$userData['estatus'];
                $alta=$userData['fecha_alta'];
                $address=$userData['direccion'];
                $genre=$userData['sexo'];
                $rfc=$userData['rfc'];
                $curp=$userData['curp'];
                $this->setUser($id,$name,$email,$user,$password,$status,$alta,$address,$genre,$rfc,$curp);
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