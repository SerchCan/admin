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
        if(isset($_POST['id_usuario'],$_POST['pin'])){
            $pin=$_POST['pin'];
            $account=generateAccount($_POST['id_usuario']);
            $Card= new CreditCard;
            $Card->generate($pin);
        
            $all= array('cuenta'=>$account,'tarjeta'=>$Card->getCard());
            echo json_encode($all);
        }
        else{
            echo "Falta el id o pin de usuario";
        }
    }
    
}else{
    echo "Favor de mandar credenciales de usuario";
}


?>