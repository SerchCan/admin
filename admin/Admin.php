<?php
    include_once "User.php";
    class Admin extends User{
        private $canOperate=true;
        public function __construct($user,$pass){
            $res=$this->Login($user,$pass);
            if(!$res || $res['tipo']!="ADMINISTRADOR"){
                echo "No tiene permiso de administrador";
                $this->canOperate=false;
            }
            return;
        }
        
        public function createUser($name, $mail, $user, $password, $address, $genre, $rfc, $curp, $userAlta, $idPais, $balance, $detalles, $tipo){
            if($this->canOperate==true)
            {  
                $con = new PDORepository;
                $tipo=strtoupper($tipo);
                $Createuser = $con-> queryList('INSERT INTO usuario(nombre, correo, username, password, estatus, tipo, fecha_alta, direccion, sexo, rfc, curp, usuario_alta, id_pais) 
                VALUES(:nombre, :email, :user, :pass, "ACTIVO", :tipo, CURRENT_TIMESTAMP(), :address, :genre, :rfc, :curp, :userAlta, :pais)',
                array('nombre'=>$name, 'email'=>$mail, 'user'=>$user, 'pass'=>$password, 'tipo'=>$tipo, 'address'=>$address, 'genre'=>$genre, 'rfc'=>$rfc, 'curp'=>$curp, 'userAlta'=>$userAlta,'pais'=>$idPais));
                
                if($tipo=="CLIENTE"){
                    $id = $this->getLastInsert();
                    $accountNumber = $this->generateAccount($password,$curp,$idPais);
    
                    $CreateAccount=$con->queryList('INSERT INTO cuenta(numero,balance,detalles,fecha_alta,estatus,tipo,id_usuario)
                        VALUES (:acnumber,:balance,:details, CURRENT_TIMESTAMP(), "ACTIVA", :tipo, :id)',
                        array('acnumber'=>$accountNumber,'balance'=>$balance,'details'=>$detalles,'tipo'=>$tipo,'id'=>$id));       
                }
            }
            else{
                echo "No tiene permisos para esta operación";
            }
        }

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