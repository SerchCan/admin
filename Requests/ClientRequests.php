<?php
    // REQUEST FOR CLIENTS

    // send op VIA GET
    // rest of data is sended automatically if user is logged on server;
    include_once "../Includes/Include.php";
    session_start();
    if ( isset($_SESSION['logged'],$_SESSION['type'], $_GET['op']) && $_SESSION['logged']==true && $_SESSION['type']=="CLIENTE") {
        $c=new Client($_SESSION['user'],$_SESSION['pass']);
        if($c->isLogged){
            $op=$_GET['op'];
            switch($op){
                case 1: echo $c->getName();
                break;
                case 2: echo $c->getAddress();
                break;
                case 3: echo $c->getRFC();
                break;
                case 4: echo $c->getCURP();
                break;
                case 5: echo $c->getAccountNumber();
                break;
                case 6: echo json_encode($c->getCard());
                break;
            }
        }
    }else{
        echo "Favor de iniciar sesión como cliente";
    }

?>