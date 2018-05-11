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
    if ( isset($_SESSION['logged'],$_SESSION['type']) && $_SESSION['logged']==true && $_SESSION['type']=="ADMINISTRADOR") {
        if(isset($_POST['op'])){
            $a=new Admin($_SESSION['user'],$_SESSION['pass']);
            if($a->isLogged){
                $op=$_POST['op'];
                switch($op){
                    case 1: //create new client
                    if(isset($_POST['name'],$_POST['mail'],$_POST['user'],$_POST['password'],$_POST['address'],$_POST['genre'],$_POST['rfc'],$_POST['curp'],$_POST['id_pais'])){
                        if(isset($_POST['balance'],$_POST['details'],$_POST['tipoCuenta'],$_POST['pin'])){
                            $a->createClient($_POST['name'],$_POST['mail'],$_POST['user'],$_POST['password'],$_POST['address'],$_POST['genre'],$_POST['rfc'],$_POST['curp'],$_POST['id_pais'],$_POST['balance'],$_POST['details'],$_POST['tipoCuenta'],$_POST['pin']);
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
                        if(isset($_POST['user'])){
                            $con=new PDORepository;
                            $res=$con->queryList("SELECT password,tipo FROM usuario WHERE username=:user",array('user'=>$_POST['user']))->fetch(PDO::FETCH_ASSOC);
                            $pass=$res['password'];
                            $type=$res['tipo'];
                            if($type=='CLIENTE'){
                                $c=new Client($_POST['user'],$pass);
                            }else{
                                if($type=='EJECUTIVO'){
                                    $c=new Executive($_POST['user'],$pass);
                                }else{
                                    if($type=='ADMINISTRADOR'){
                                        $c=new Admin($_POST['user'],$pass);
                                    }
                                }
                            }
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
                                    echo $a->editUser($_POST['user'],$_POST['mail'],$_POST['address'],$type,$_POST['new_password']);
                                }
                                else{
                                    // edit usual data
                                    echo $a->editUser($_POST['user'],$_POST['mail'],$_POST['address']);
                                }
                            }
                        }
                        else{
                            echo "Falta información de credenciales para cambio de información";
                        }  
                    break;
                    case 3:
                        if(isset($_POST['user'])){
                            echo $a->desactivateUser($_POST['user']);
                        }
                        else{
                            echo "Faltan credenciales de cliente";
                        }
                    break;
                    case 4:
                    // change card
                    if(isset($_POST['user'],$_POST['newpin'])){
                        echo $a->changeCard($_POST['user'],$_POST['newpin']);
                    }
                    else{
                        echo "Faltan parámetros para cambio de tarjeta";
                    }
                    break;
                    case 5:
                        echo $a->listOfExecutives();
                    break;
                    case 6:
                    //create Executive
                    if(isset($_POST['name'],$_POST['mail'],$_POST['user'],$_POST['password'],$_POST['address'],$_POST['genre'],$_POST['rfc'],$_POST['curp'],$_POST['id_pais'])){
                        if(isset($_POST['fecha_fin'],$_POST['sueldo'])){
                            $a->createExecutive($_POST['name'],$_POST['mail'],$_POST['user'],$_POST['password'],$_POST['address'],$_POST['genre'],$_POST['rfc'],$_POST['curp'],$_POST['id_pais'],$_POST['fecha_fin'],$_POST['sueldo']);
                        }
                        else{
                            echo "Falta información para creación del ejecutivo";
                        }
                    }
                    else{
                        echo "Falta información para creación del usuario";
                    }
                    break;
                    case 7:
                    if(isset($_POST['name'],$_POST['mail'],$_POST['user'],$_POST['password'],$_POST['address'],$_POST['genre'],$_POST['rfc'],$_POST['curp'],$_POST['id_pais'])){
                        if(isset($_POST['fecha_fin'],$_POST['sueldo'])){
                            $a->createAdmin($_POST['name'],$_POST['mail'],$_POST['user'],$_POST['password'],$_POST['address'],$_POST['genre'],$_POST['rfc'],$_POST['curp'],$_POST['id_pais'],$_POST['fecha_fin'],$_POST['sueldo']);
                        }
                        else{
                            echo "Falta información para creación del administrador";
                        }
                    }
                    else{
                        echo "Falta información para creación del usuario";
                    }
                    break;
                    case 8:
                        if(isset($_POST['id'])){
                            $a->CreateCashier($_POST['id']);
                        }
                        else{
                            echo "Falta id de cajero";
                        }
                    break;
                    case 9: unset($_SESSION['user']);
                            unset($_SESSION['password']);
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
        echo "Debe de iniciar sesión como Administrador";
    }

?>