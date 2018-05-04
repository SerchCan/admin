<?php
    class Admin extends User{
        //if user is not admin this variable changes.
        private $canOperate=true;
        //At creation of admin we should Login

        public function __construct($user,$pass){
            $res=$this->Login($user,$pass);
            if(!$res || $res['tipo']!="ADMINISTRADOR"){
                echo "No tiene permiso de administrador";
                $this->canOperate=false;
            }else{
                $id = $res['id_usuario'];
                $this->fillUser($id);
            }
            return;
        }
        public function editUser($username,$password){
            
        }
        //Create a new user (Client, executive or Admin)
        public function createClient($name, $mail, $user, $password, $address, $genre, $rfc, $curp, $idPais, $balance, $detalles,$tipoCuenta,$pin){
            if($this->canOperate==true)
            {  
                $con = new PDORepository;
                $tipo=strtoupper($tipo);
                $genre=strtoupper($genre);
                if($pin!=null || ($pin>=1000 && $pin<=9999))
                {
                    $Createuser = $con-> queryList('INSERT INTO usuario(nombre, correo, username, password, estatus, tipo, fecha_alta, direccion, sexo, rfc, curp, usuario_alta, id_pais) 
                        VALUES(:nombre, :email, :user, :pass, "ACTIVO", "CLIENTE", CURRENT_TIMESTAMP(), :address, :genre, :rfc, :curp, :userAlta, :pais)',
                        array('nombre'=>$name, 'email'=>$mail, 'user'=>$user, 'pass'=>$password, 'address'=>$address, 'genre'=>$genre, 'rfc'=>$rfc, 'curp'=>$curp, 'userAlta'=>$this->id,'pais'=>$idPais));
                
                    $id = $this->getLastInsert();
                    $accountNumber = $this->generateAccount($password,$curp,$idPais);
                    $CreateAccount=$con->queryList('INSERT INTO cuenta(numero,balance,detalles,fecha_alta,estatus,tipo,id_usuario)
                        VALUES (:acnumber,:balance,:details, CURRENT_TIMESTAMP(), "ACTIVA", :tipo, :id)',
                        array('acnumber'=>$accountNumber,'balance'=>$balance,'details'=>$detalles,'tipo'=>$tipoCuenta,'id'=>$id));       
                    $this->assignCard($pin,$accountNumber);
                }
                else{
                    echo "New client requires a 4-digit pin";
                }
            }
            else{
                echo "No tiene permisos para esta operación";
            }
        }
        // Assign card to a user
        public function assignCard($pin,$account){
            if($this->canOperate){
                $card= new CreditCard;
                $card->generate($pin);
                $card->addToUser($account);
            }
        }
        // Get last inserted user
        public function getLastInsert(){
            if($this->canOperate){
                $con = new PDORepository;
                $query= $con-> queryList("SELECT id_usuario FROM usuario ORDER BY id_usuario DESC LIMIT 1",null)
                            -> fetch(PDO::FETCH_ASSOC);
                return $query['id_usuario'];
            }
            else{
                return "No tiene permisos para esta operación";
            }
        }
        // Generate account number
        public function generateAccount($password,$curp,$idPais){
            if($this->canOperate){
                //                  country           EP(EdisonPay) CURP      PASSWORD
                $accountNumber = sprintf("%02d", $idPais)."6980".ord($curp).ord($password);
                return $accountNumber;
            }
            else{
                echo "No tiene permisos para esta operación";
            }
        }
    }
?>