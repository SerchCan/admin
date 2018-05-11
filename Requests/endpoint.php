<?php
include_once '../Includes/Include.php';
    function generateAccount($id){
        $con = new PDORepository;
        $values=$con->queryList("SELECT password,curp,id_pais from usuario WHERE id_usuario=:id",array('id'=>$id))->fetch(PDO::FETCH_ASSOC);
        $password=$values['password'];
        $curp=$values['curp'];
        $idPais=$values['id_pais'];
        //                  country           EP(EdisonPay) CURP      PASSWORD
        $accountNumber = sprintf("%02d", $idPais)."6980".ord($curp).ord($password);
        $account=substr($accountNumber,0,10);
        return $account;
    }
if(isset($_POST['usuario'],$_POST['password'])){
    $e=new Executive($_POST['usuario'],$_POST['password']);
    if($e->isLogged){
        if(isset($_POST['id_usuario'],$_POST['pin'],$_POST['tipo_cuenta'],$_POST['detalles'])){
            $con = new PDORepository;
            $id=$_POST['id_usuario'];
            $pin=$_POST['pin'];
            $tipoCuenta=strtoupper($_POST['tipo_cuenta']);
            $detalles=$_POST['detalles'];
            $account=generateAccount($_POST['id_usuario']);
            $CreateAccount=$con->queryList('INSERT INTO cuenta(numero,balance,detalles,fecha_alta,estatus,tipo,id_usuario)
                            VALUES (:acnumber,0,:details, CURRENT_TIMESTAMP(), "ACTIVA", :tipo, :id)',
                            array('acnumber'=>$account,'details'=>$detalles,'tipo'=>$tipoCuenta,'id'=>$id));       
            $Card= new CreditCard;
            $Card->generate($pin);
            $Card->addToUser($account);

            $all= array('cuenta'=>$account,'tarjeta'=>$Card->getCard());
            echo json_encode($all);
        }
        else{
            echo "Falta el id, pin, tipo de cuenta o detalle del usuario";
        }
    }
    
}else{
    echo "Favor de mandar credenciales de usuario";
}


?>