<?php

header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Methods: PUT, GET, POST, DELETE, OPTIONS');
header("Access-Control-Allow-Headers: X-Requested-With");
// header('Content-Type: text/html; charset=utf-8');
header('Content-type: application/json; charset=UTF-8');
header('P3P: CP="IDC DSP COR CURa ADMa OUR IND PHY ONL COM STA"'); 

require_once '../include/DbHandler.php';
require_once '../include/PassHash.php';
require '../libs/Slim/Slim.php';
 
\Slim\Slim::registerAutoloader();
 
$app = new \Slim\Slim();

$app->map('/:id/upcard', function($id_usuario) use ($app) {
    $response = array();
    // reading post params
    if($app->request()->isPost()) {
        $numero=$app->request()->pos('numero');
        $pin=$app->request()->pos('pin');
        $codigo_seguridad=$app->request()->pos('codigo_seguridad');
        $fecha_validez_inicio=$app->request()->pos('fecha_validez_inicio');
        $fecha_validez_fin=$app->request()->pos('fecha_validez_fin');
        $estatus=$app->request()->pos('estatus');
        $id_cuenta=$app->request()->pos('id_cuenta');
    }
    $db = new DbHandler();
    $card = $db->updatetarjet($id_usuario,$numero ,$pin ,$codigo_seguridad ,$fecha_validez_inicio ,$fecha_validez_fin ,$estatus ,$id_cuenta);
    if ($card == USER_CREATED_SUCCESSFULLY) {
        $response["error"] = false;
        $response["message"] = "You are successfully registered";
        generateRes(201, $response);
    } else if ($card == USER_CREATE_FAILED) {
        $response["error"] = true;
        $response["message"] = "Oops! An error occurred while registereing";
        generateRes(200, $response);
    } else if ($card == USER_ALREADY_EXISTED) {
        $response["error"] = true;
        $response["message"] = "Sorry, this email already existed";
        generateRes(200, $response);
    }
})->via('POST');


$app->map('/:id/upclient', function($id_usuario) use ($app) {
    $response = array();
    // reading post/get params
    if($app->request()->isPost()) {
        $id_usuario = $app->request()->post('id_usuario');
        $nombre = $app->request()->post('nombre');
        $correo = $app->request()->post('correo');
        $username = $app->request()->post('username');
        $password = $app->request()->post('password');
        $estatus = $app->request()->post('estatus');
        $tipo = $app->request()->post('tipo');
        $fecha_alta = $app->request()->post('fecha_alta');
        $direccion = $app->request()->post('direccion');
        $sexo = $app->request()->post('sexo');
        $rfc = $app->request()->post('rfc');
        $curp = $app->request()->post('curp');
        $usuario_alta = $app->request()->post('usuario_alta');
        $id_pais = $app->request()->post('id_pais');

    }
    $db = new DbHandler();
    $res = $db->updateclient($id_usuario, $nombre, $correo, $username, $password, $estatus, $tipo, $fecha_alta, $direccion, $sexo, $rfc, $curp, $usuario_alta, $id_pais);

    if ($res == USER_CREATED_SUCCESSFULLY) {
        $response["error"] = false;
        $response["message"] = "You are successfully registered";
        generateRes(201, $response);
    } else if ($res == USER_CREATE_FAILED) {
        $response["error"] = true;
        $response["message"] = "Oops! An error occurred while registereing";
        generateRes(200, $response);
    } else if ($res == USER_ALREADY_EXISTED) {
        $response["error"] = true;
        $response["message"] = "Sorry, this email already existed";
        generateRes(200, $response);
    }
})->via('POST');

$app->map('/insertclient', function() use ($app) {

    $response = array();
    // reading post params
    if($app->request()->isPost()) {
        $nombre = $app->request()->post('nombre');
        $correo = $app->request()->post('correo');
        $username = $app->request()->post('username');
        $password = $app->request()->post('password');
        $estatus = $app->request()->post('estatus');
        $tipo = $app->request()->post('tipo');
        $fecha_alta = $app->request()->post('fecha_alta');
        $direccion = $app->request()->post('direccion');
        $sexo = $app->request()->post('sexo');
        $rfc = $app->request()->post('rfc');
        $curp = $app->request()->post('curp');
        $usuario_alta = $app->request()->post('usuario_alta');
        $id_pais = $app->request()->post('id_pais');
    }
    $db = new DbHandler();
    $res = $db->insertclient($nombre,$correo,$username,$password,$estatus,$tipo,$fecha_alta,$direccion,$sexo,$rfc,$curp,$usuario_alta,$id_pais);

    if ($res == USER_CREATED_SUCCESSFULLY) {
        $response["error"] = false;
        $response["message"] = "You are successfully registered";
        generateRes(201, $response);
    } else if ($res == USER_CREATE_FAILED) {
        $response["error"] = true;
        $response["message"] = "Oops! An error occurred while registereing";
        generateRes(200, $response);
    } else if ($res == USER_ALREADY_EXISTED) {
        $response["error"] = true;
        $response["message"] = "Sorry, this email already existed";
        generateRes(200, $response);
    }
})->via('POST');

$app->map('/insertcard', function() use ($app) {
    $response = array();
    // reading post params
    if($app->request()->isPost()) {
        $numero=$app->request()->pos('numero');
        $pin=$app->request()->pos('pin');
        $codigo_seguridad=$app->request()->pos('codigo_seguridad');
        $fecha_validez_inicio=$app->request()->pos('fecha_validez_inicio');
        $fecha_validez_fin=$app->request()->pos('fecha_validez_fin');
        $estatus=$app->request()->pos('estatus');
        $id_cuenta=$app->request()->pos('id_cuenta');
    }
    $db = new DbHandler();
    $res = $db->insertarjet($id_tarjeta,$numero ,$pin ,$codigo_seguridad ,$fecha_validez_inicio ,$fecha_validez_fin ,$estatus ,$id_cuenta);

    if ($res == USER_CREATED_SUCCESSFULLY) {
        $response["error"] = false;
        $response["message"] = "You are successfully registered";
        generateRes(201, $response);
    } else if ($res == USER_CREATE_FAILED) {
        $response["error"] = true;
        $response["message"] = "Oops! An error occurred while registereing";
        generateRes(200, $response);
    } else if ($res == USER_ALREADY_EXISTED) {
        $response["error"] = true;
        $response["message"] = "Sorry, this email already existed";
        generateRes(200, $response);
    }
})->via('POST');

$app->map('/requesclientes', function() use ($app) {
    $response = array();
    // reading post params
    
    $db = new DbHandler();
    $clientes = $db->requesclient();

    if ($clientes != NULL) {
            $response["error"] = false;
            $response["id_usuario"] = $clientes["id_usuario"];
            $response["nombre"] = $clientes["nombre"];
            $response["correo"] = $clientes["correo"];
            $response['username'] = $clientes["username"];
            $response['password'] = $clientes["password"];
            $response['estatus'] = $clientes["estatus"];
            $response["tipo"] = $clientes["tipo"];
            $response["fecha_alta"] = $clientes["fecha_alta"];
            $response["direccion"] = $clientes["direccion"];
            $response['sexo'] = $clientes["sexo"];
            $response['rfc'] = $clientes["rfc"];
            $response['curp'] = $clientes["curp"];
            $response['usuario_alta'] = $clientes["usuario_alta"];
            $response['id_pais'] = $clientes["id_pais"];
            $response['tarjeta_numero'] = $clientes["tarjeta.numero"];
        } else {
            // unknown error occurred
            $response['error'] = true;
            $response['message'] = "An error occurred. Please try again";
        }
    generateRes(200, $response);
})->via('POST');

$app->map('/:id/getclient', function($id_usuario) use ($app) {
    $response = array();
    // reading post/get params
    if($app->request()->isPost()) {
        $username = $app->request()->post('id_usuario');
        
    }
    $db = new DbHandler();
    $clientes = $db->getclientdata($id_usuario);

    if ($clientes != NULL) {
            $response["error"] = false;
            $response["nombre"] = $clientes["nombre"];
            $response["correo"] = $clientes["correo"];
            $response['username'] = $clientes["username"];
            $response['password'] = $clientes["password"];
            $response['estatus'] = $clientes["estatus"];
            $response["tipo"] = $clientes["tipo"];
            $response["direccion"] = $clientes["direccion"];
            $response['sexo'] = $clientes["sexo"];
            $response['rfc'] = $clientes["rfc"];
            $response['curp'] = $clientes["curp"];
            $response['id_paid'] = $clientes["id_paid"];
        } else {
            // unknown error occurred
            $response['error'] = true;
            $response['message'] = "An error occurred. Please try again";
        }
    generateRes(200, $response);
})->via('POST');

$app->map('/:id/getcard', function($id_usuario) use ($app) {
    $response = array();
    // reading post/get params
    if($app->request()->isPost()) {
        $username = $app->request()->post('id_usuario');
        
    }
    $db = new DbHandler();
    $clientes = $db->getcarddata($id_usuario);

    if ($clientes != NULL) {
            $response["error"] = false;
            $response["numero"] = $clientes["numero"];
            $response["pin"] = $clientes["pin"];
            $response['codigo_seguridad'] = $clientes["codigo_seguridad"];
            $response['estatus'] = $clientes["estatus"];
            $response['fecha_validez_fin'] = $clientes["fecha_validez_fin"];
        } else {
            // unknown error occurred
            $response['error'] = true;
            $response['message'] = "An error occurred. Please try again";
        }
    generateRes(200, $response);
})->via('POST');


function generateRes($status_code, $response) {
    $app = \Slim\Slim::getInstance();
    // Http response code
    $app->status($status_code);

    // setting response content type to json
    $app->contentType('application/json');
    //echo json_encode(array_map('utf8_encode', $response),JSON_PRETTY_PRINT);
    $response = utf8_encode_all($response);
    echo json_encode($response,JSON_PRETTY_PRINT);
}


/**
 * Encondig response to UTF-8
 * @param Array $dat data content
 * @return Array $ret encoded data to UTF-8
*/
function utf8_encode_all($dat) // -- It returns $dat encoded to UTF8
{
    if (is_string($dat)) return utf8_encode($dat);
    if (!is_array($dat)) return $dat; 
    $ret = array();
    foreach($dat as $i=>$d) $ret[$i] = utf8_encode_all($d);
    return $ret; 
} 

$app->run();

?>