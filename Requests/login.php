<?php
    include_once "../Includes/include.php";
    if(isset($_POST['user'], $_POST['password'],$_POST['type'])){
        if(strtoupper($_POST['type'])=="CLIENTE"){
            $c=new Client($_POST['user'], $_POST['password']);
            if($c->isLogged){
                session_start();
                $_SESSION['logged']=true;
                $_SESSION['user']=$_POST['user'];
                $_SESSION['pass']=$_POST['password'];
                $_SESSION['type']='CLIENTE';
                $_SESSION['start']=time();
                $_SESSION['expire']=$_SESSION['start']+(1);//0*60);
                echo "Sesion iniciada";
            }
            else{
                echo "</br>Error en credenciales del usuario";
            }
        }
        if(strtoupper($_POST['type'])=="EJECUTIVO"){
            $c=new Executive($_POST['user'], $_POST['password']);
            if($c->isLogged){
                session_start();
                $_SESSION['logged']=true;
                $_SESSION['user']=$_POST['user'];
                $_SESSION['pass']=$_POST['password'];
                $_SESSION['type']='EJECUTIVO';
                $_SESSION['start']=time();
                $_SESSION['expire']=$_SESSION['start']+(10*60);
                echo "Sesion iniciada";
            }
            else{
                echo "</br>Error en credenciales del usuario";
            }
        }
        if(strtoupper($_POST['type'])=="ADMINISTRADOR"){
            $c=new Admin($_POST['user'], $_POST['password']);
            if($c->isLogged){
                session_start();
                $_SESSION['logged']=true;
                $_SESSION['user']=$_POST['user'];
                $_SESSION['pass']=$_POST['password'];
                $_SESSION['type']='ADMINISTRADOR';
                $_SESSION['start']=time();
                $_SESSION['expire']=$_SESSION['start']+(10*60);
                echo "Sesion iniciada";
            }
            else{
                echo "</br>Error en credenciales del usuario";
            }
        }        
    }
    else{
        echo "Faltan parámetros";
    }

?>
