<?php
    class Admin extends Executive{
        //if user is not admin this variable changes.
        private $isAdmin=true;
        //At creation of admin we should Login
        public function __construct($user,$pass){
            $res=$this->Login($user,$pass);
            if(!$res || $res['tipo']!="ADMINISTRADOR"){
                $this->isAdmin=false;
                $this->isExecutive=false;
                return "No tiene permiso de administrador";
            }else{
                $id = $res['id_usuario'];
                $this->fillUser($id);
            }
            return;
        }
        //Create a new Executive
        public function createExecutive($name, $mail, $user, $password, $address, $genre, $rfc, $curp, $idPais,$fin,$sueldo){
            if($this->isAdmin){
                $con = new PDORepository;
                $Createuser = $con
                    -> queryList('INSERT INTO usuario(nombre, correo, username, password, estatus, tipo, fecha_alta, direccion, sexo, rfc, curp, usuario_alta, id_pais) 
                                VALUES(:nombre, :email, :user, :pass, "ACTIVO", "EJECUTIVO", CURRENT_TIMESTAMP(), :address, :genre, :rfc, :curp, :userAlta, :pais)',
                        array('nombre'=>$name, 'email'=>$mail, 'user'=>$user, 'pass'=>$password, 'address'=>$address, 'genre'=>$genre, 'rfc'=>$rfc, 'curp'=>$curp, 'userAlta'=>$this->id,'pais'=>$idPais));
                $id = $this->getLastInsert();
                $CreateEmployee = $con
                    -> queryList('INSERT INTO empleado(contrato_fecha_inicio,contrato_fecha_fin,sueldo,id_usuario)
                                    VALUES(CURRENT_TIMESTAMP(),:Final,:Sueldo,:id)',
                        array('Final'=>$fin,'Sueldo'=>$sueldo,'id'=>$id));
                    
            }
            else{
                return "No tiene permisos para esta operación";
            }
        }
        // Create new Admin
        public function createAdmin($name, $mail, $user, $password, $address, $genre, $rfc, $curp, $idPais,$fin,$sueldo){
            if($this->isAdmin){
                $con = new PDORepository;
                $Createuser = $con
                    -> queryList('INSERT INTO usuario(nombre, correo, username, password, estatus, tipo, fecha_alta, direccion, sexo, rfc, curp, usuario_alta, id_pais) 
                                VALUES(:nombre, :email, :user, :pass, "ACTIVO", "ADMINISTRADOR", CURRENT_TIMESTAMP(), :address, :genre, :rfc, :curp, :userAlta, :pais)',
                        array('nombre'=>$name, 'email'=>$mail, 'user'=>$user, 'pass'=>$password, 'address'=>$address, 'genre'=>$genre, 'rfc'=>$rfc, 'curp'=>$curp, 'userAlta'=>$this->id,'pais'=>$idPais));
                $id = $this->getLastInsert();
                $CreateEmployee = $con
                    -> queryList('INSERT INTO empleado(contrato_fecha_inicio,contrato_fecha_fin,sueldo,id_usuario)
                                    VALUES(CURRENT_TIMESTAMP(),:Final,:Sueldo,:id)',
                        array('Final'=>$fin,'Sueldo'=>$sueldo,'id'=>$id));
            }
            else{
                return "No tiene permisos para esta operación";
            }
        }
       
    }
?>