<?php
    include_once "User.php";
    include_once "CC.php";
    // Client can only get his own data but cannot set it.
    class Client extends User{
	    private $accountNumber;
        private $card;
        public function __construct($user,$pass){
            $this->card=new CreditCard;
            $res = $this->Login($user,$pass);
            if($res){
                $id = $res['id_usuario'];
                
                $this->fillUser($id);
                $this->card->fill($this->getAccountNumber());
            }
            else{
                echo "Verifique sus credenciales";
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
                $this->setUser($id,$name,$email,$user,$password,$status,$alta,$address,$genre,$rfc,$curp);
                $this->setClient();
            }
            else{
                echo "No se encontro el id de usuario en el sistema.";
            }
        }
        public function setClient(){
            $con = new PDORepository;
            $id = $this->getId();
            $data = $con->queryList("SELECT cuenta.numero AS accountNumber FROM cuenta INNER JOIN tarjeta
                                    WHERE tarjeta.id_cuenta = cuenta.id_cuenta AND cuenta.id_usuario = :id",
                                    array('id'=>$id))
                        -> fetch(PDO::FETCH_ASSOC);
            if($data){
                $this->accountNumber=$data['accountNumber'];

            }

        }
        public function getAccountNumber(){
            return $this->accountNumber;
        }
        public function getCard(){
            if ($this->card){
                return $this->card->getCard();
            }
        }
    }
?>