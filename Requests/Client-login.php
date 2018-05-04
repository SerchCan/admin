<?php
// USE THIS ONLY TO AUTHENTICATE CLIENTS
    include_once "../Includes/include.php";
    if(isset($_POST['user'], $_POST['password'])){
        $c=new Client($_POST['user'], $_POST['password']);
        if($c->isLogged){
            session_start();
            $_SESSION['logged']=true;
            $_SESSION['user']=$_POST['user'];
            $_SESSION['pass']=$_POST['password'];
            $_SESSION['type']='CLIENTE';
            $_SESSION['start']=time();
            $_SESSION['expire']=$_SESSION['start']+(10*60);
            echo "Sesion iniciada";
        }
        else{
            echo "Error en credenciales del usuario, probablemente usted no es usuario del tipo cliente";
        }
    }

?>
