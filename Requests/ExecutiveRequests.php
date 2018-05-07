<?php
include_once "../Includes/Include.php";
session_start();
$now = time();
if( isset($_SESSION['expire']) && $now > $_SESSION['expire']) {
    session_destroy();
    echo "Su sesión ha terminado";
    exit;
}
//send * data via POST
if ( isset($_SESSION['logged'],$_SESSION['type']) && $_SESSION['logged']==true && $_SESSION['type']=="EJECUTIVO") {
    if(isset($_POST['op'])){
        $e=new Executive($_SESSION['user'],$_SESSION['pass']);
        if($e->isLogged){     
            $op=$_POST['op'];
            switch($op){
                case 1:
                // Create a new client
                // name: , mail: , user: , password: ,address: , genre: ,rfc: , curp: , id_pais: , balance: ,details: ,tipoCuenta: , pin:
                if(isset($_POST['name'],$_POST['mail'],$_POST['user'],$_POST['password'],$_POST['address'],$_POST['genre'],$_POST['rfc'],$_POST['curp'],$_POST['id_pais'])){
                    if(isset($_POST['balance'],$_POST['details'],$_POST['tipoCuenta'],$_POST['pin'])){
                        $e->createClient($_POST['name'],$_POST['mail'],$_POST['user'],$_POST['password'],$_POST['address'],$_POST['genre'],$_POST['rfc'],$_POST['curp'],$_POST['id_pais'],$_POST['balance'],$_POST['details'],$_POST['tipoCuenta'],$_POST['pin']);
                    }
                    else{
                        echo "Falta información para creación de cuenta.";
                    }
                }
                else{
                    echo "Falta información para creación de usuario.";
                }            
                break;
                case 2: 
                // Edit the client data
                if(isset($_POST['user'],$_POST['password'])){
                    $c=new Client($_POST['user'],$_POST['password']);
                    if($c->isLogged){
                        // getting default if not send
                        if(!isset($_POST['mail'])){
                            $_POST['mail']= $c->getMail();
                        }
                        if(!isset($_POST['address'])){
                            $_POST['address']= $c->getAddress();
                        }
                    }
                    if(isset($_POST['mail'],$_POST['address'])){
    
                        if(isset($_POST['new_password'])){
                            // new password
                            echo $e->editUser($_POST['user'],$_POST['password'],$_POST['mail'],$_POST['address'],$_POST['new_password']);
                        }
                        else{
                            // edit usual data
                            echo $e->editUser($_POST['user'],$_POST['password'],$_POST['mail'],$_POST['address']);
                        }
                    }
                }
                else{
                    echo "Falta información de credenciales para cambio de información";
                }
                break;
                case 3: 
                // Desactivate client
                    if(isset($_POST['user'],$_POST['password'])){
                        $e->desactivateUser($_POST['user'],$_POST['password']);
                    }
                    else{
                        echo "Faltan credenciales de cliente";
                    }
                break;
                case 4:
                // change card
                    if(isset($_POST['user'],$_POST['password'],$_POST['newpin'])){
                        $e->changeCard($_POST['user'],$_POST['password'],$_POST['newpin']);
                    }
                    else{
                        echo "Faltan parámetros para cambio de tarjeta";
                    }
                break;
                case 5:
                    unset($_SESSION['user']);
                    session_destroy();
                    echo "Sesión cerrada exitosamente";
                break; 
                default:
                    echo "Operación no permitida";
                break;
            }
        }   
    }
    else{
        echo "Falta operación [op]";
    }
}
else{
    echo "Debe de iniciar sesión como Ejecutivo";
}
?>