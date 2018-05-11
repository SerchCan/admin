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
                $this->isLogged=true;
            }
            return;
        }
        //Overload
        public function editUser($user,$mail,$address,$type='CLIENTE',$newPass=null){
            if($this->isAdmin){
                $type= strtoupper($type);
                $con= new PDORepository;
                $con->queryList("UPDATE usuario set correo=:mail, tipo=:typeu, direccion=:address WHERE username=:user",
                array('mail'=>$mail,'typeu'=>$type,'address'=>$address,'user'=>$user));
                if($newPass!=null){
                    $con->queryList("UPDATE usuario SET password=:pass WHERE username=:user",array('pass'=>$newPass, 'user'=>$user));
                }
            }
            else{
                return "No tiene permisos para esta operación";
            }
        }
        //Overload
        public function desactivateUser($user,$password=null){
            if($this->isAdmin){
                $con= new PDORepository;
                $tipo=$con ->queryList("SELECT tipo from usuario where username=:user",
                array('user'=>$user))->fetch(PDO::FETCH_ASSOC);
                $tipo=strtoupper($tipo['tipo']);
                if($tipo=='CLIENTE'){
                    $con ->queryList("UPDATE usuario,tarjeta,cuenta 
                    SET usuario.estatus='INACTIVO', tarjeta.estatus='INACTIVO', cuenta.estatus='INACTIVA'
                    WHERE tarjeta.estatus='ACTIVO' AND tarjeta.id_cuenta=cuenta.id_cuenta AND cuenta.id_usuario=usuario.id_usuario AND usuario.username=:user",array('user'=>$user));
                }
                if($tipo=='EJECUTIVO' || $tipo=='ADMINISTRADOR'){
                    $con ->queryList("UPDATE usuario SET estatus='INACTIVO' WHERE username=:user",array('user'=>$user));
                }
            }
            else{
                return "No tiene permisos para esta operación";
            }
        }
        //Overload
        public function changeCard($user,$newpin,$password=null){
            if($this->isAdmin){
                $con=new PDORepository;
                $cuenta=$con->queryList("SELECT cuenta.numero AS numero from cuenta INNER JOIN tarjeta INNER JOIN usuario WHERE tarjeta.id_cuenta=cuenta.id_cuenta AND cuenta.id_usuario=usuario.id_usuario AND usuario.username=:user",array('user'=>$user))->fetch(PDO::FETCH_ASSOC);
                $con->queryList("UPDATE tarjeta,cuenta,usuario SET tarjeta.estatus='INACTIVO' WHERE tarjeta.estatus='ACTIVO' AND tarjeta.id_cuenta=cuenta.id_cuenta AND cuenta.id_usuario=usuario.id_usuario AND usuario.username=:user",array('user'=>$user));
                $accountNumber=$cuenta['numero'];
                $this->assignCard($newpin,$accountNumber);
            }
            else{
                return "No tiene permisos para esta operación";
            }
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
        public function listOfExecutives(){
            if($this->isAdmin){
                $con= new PDORepository;
                $List= $con 
                    -> queryList("SELECT * FROM usuario WHERE tipo='EJECUTIVO' and estatus='ACTIVO'",null)
                    -> fetchAll(PDO::FETCH_ASSOC);
                return json_encode($List);
            }
        }
        public function CreateCashier($id){
            if($this->isAdmin){
                $con=new PDORepository;
                $tipo=$con
                ->queryList("SELECT tipo FROM usuario WHERE id_usuario=:id",array('id'=>$id))
                ->fetch(PDO::FETCH_ASSOC);
                $tipo=strtoupper($tipo['tipo']);
                if($tipo=="ADMINISTRADOR" || $tipo == "EJECUTIVO"){
                    $con-> 
                        queryList('INSERT INTO caja (estatus, fecha_alta, id_usuario_asignado) VALUES("ACTIVO", CURRENT_TIMESTAMP(), :id)',array('id'=>$id));
                }else{
                    echo "El usuario no puede ser cajero";
                }
            }
            else{
                return "No tiene permisos para esta operación";
            }
        }
    }
?>