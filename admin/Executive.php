<?php
    class Executive extends User{
        protected $isExecutive=True;
        public function __construct($user,$pass){
            $res=$this->Login($user,$pass);
            if(!$res || $res['tipo']!="EJECUTIVO"){
                echo "No tiene permiso de Ejecutivo";
                $this->isExecutive=false;
            }else{
                $id = $res['id_usuario'];
                $this->fillUser($id);
                $this->isLogged=true;
            }
            return;
        }
        //Create a new Client
        public function createClient($name, $mail, $user, $password, $address, $genre, $rfc, $curp, $idPais, $balance, $detalles,$tipoCuenta,$pin){
            if($this->isExecutive)
            {  
                if($pin!=null || ($pin>=1000 && $pin<=9999))
                {
                    $con = new PDORepository;
                    $tipoCuenta=strtoupper($tipoCuenta);
                    $genre=strtoupper($genre);
                    $Createuser = $con
                        -> queryList('INSERT INTO usuario(nombre, correo, username, password, estatus, tipo, fecha_alta, direccion, sexo, rfc, curp, usuario_alta, id_pais) 
                                    VALUES(:nombre, :email, :user, :pass, "ACTIVO", "CLIENTE", CURRENT_TIMESTAMP(), :address, :genre, :rfc, :curp, :userAlta, :pais)',
                            array('nombre'=>$name, 'email'=>$mail, 'user'=>$user, 'pass'=>$password, 'address'=>$address, 'genre'=>$genre, 'rfc'=>$rfc, 'curp'=>$curp, 'userAlta'=>$this->id,'pais'=>$idPais));
                
                    $id = $this->getLastInsert();
                    $accountNumber = $this->generateAccount($password,$curp,$idPais);
                    $CreateAccount=$con
                        ->queryList('INSERT INTO cuenta(numero,balance,detalles,fecha_alta,estatus,tipo,id_usuario)
                                        VALUES (:acnumber,:balance,:details, CURRENT_TIMESTAMP(), "ACTIVA", :tipo, :id)',
                        array('acnumber'=>$accountNumber,'balance'=>$balance,'details'=>$detalles,'tipo'=>$tipoCuenta,'id'=>$id));       
                    $this->assignCard($pin,$accountNumber);
                }
                else{
                    return "El cliente requiere de un pin de 4 digitos.";
                }
            }
            else{
                return "No tiene permisos para esta operación";
            }
        }
        // Edit user Send the Field in DB and the Value.
        public function editUser($user,$password,$mail,$address,$newPass=null){
            if($this->isExecutive){    
                $c=new Client($user,$password);
                if($c->isLogged){
                    $id=$c->getId();
                    $con= new PDORepository;
                    $con->queryList("UPDATE usuario SET correo= :mail, direccion= :address WHERE id_usuario=:id and tipo='CLIENTE'",
                    array('mail'=>$mail,'address'=>$address,'id'=>$id));
                    if($newPass!=null){
                        $con->queryList("UPDATE usuario SET password=:pass WHERE id_usuario=:id",array('pass'=>$newPass, 'id'=>$id));
                    }
                }
                else{
                    return "Información de usuario no encontrada";
                }
            }
            else{
                return "No tiene permisos para esta operación";
            }
        }
        // user and password of the client
        public function desactivateUser($user,$password){
            if ($this->isExecutive)
            {
                $con=new PDORepository;
                $c= new Client($user,$password);
                if($c->isLogged){
                    $id= $c->getId();
                    $account=$c->getAccountNumber();
                    $card=$c->getCard()['Number'];
                    $con ->queryList("UPDATE usuario SET estatus='INACTIVO' WHERE id_usuario=:id and TIPO='CLIENTE'",array('id'=>$id));
                    $this->desactivateCard($card);
                    $this->desactivateAccount($account);
                }
                else{
                    return "Información de usuario no encontrada";
                }
            }
            else{
                return "No tiene permisos para esta operación";
            }
        }
        // user and password of the client
        public function changeCard($user,$password,$newpin){
            if($this->isExecutive){
                $con=new PDORepository;
                $c= new Client($user,$password);
                if($c->isLogged)
                {
                    $cardNumber=$c->getCard()['Number'];
                    $accountNumber=$c->getAccountNumber();
                    
                    $con-> queryList("UPDATE tarjeta SET estatus='INACTIVO' WHERE numero=:card",
                        array('card'=>$cardNumber));
    
                    $this->assignCard($newpin,$accountNumber);
                }
                else{
                    return "Información de usuario no encontrada";
                }
            }
            else{
                return "No tiene permisos para esta operación";
            }

        }

        public function desactivateAccount($accountNumber){
            if($this->isExecutive){
                $con = new PDORepository;
                $con-> queryList("UPDATE cuenta SET estatus='INACTIVA' WHERE numero=:cuenta",
                    array('cuenta'=>$accountNumber));
            }
        }
        public function desactivateCard($cardNumber){
            if($this->isExecutive){
                $con= new PDORepository;
                $con-> queryList("UPDATE tarjeta SET estatus='INACTIVO' WHERE numero=:card",
                    array('card'=>$cardNumber));
            }
        }
        // Assign card to a user
        public function assignCard($pin,$account){
            if($this->isExecutive){
                $card= new CreditCard;
                $card->generate($pin);
                $card->addToUser($account);
            }
        }
        // Get last inserted user
        public function getLastInsert(){
            if($this->isExecutive){
                $con = new PDORepository;
                $query= $con
                    -> queryList("SELECT id_usuario FROM usuario ORDER BY id_usuario DESC LIMIT 1",null)
                    -> fetch(PDO::FETCH_ASSOC);
                return $query['id_usuario'];
            }
            else{
                return "No tiene permisos para esta operación";
            }
        }
        // Generate account number
        public function generateAccount($password,$curp,$idPais){
            if($this->isExecutive){
                //                  country           EP(EdisonPay) CURP      PASSWORD
                $accountNumber = sprintf("%02d", $idPais)."6980".ord($curp).ord($password);
                return $accountNumber;
            }
            else{
                return "No tiene permisos para esta operación";
            }
        }
    }
?>