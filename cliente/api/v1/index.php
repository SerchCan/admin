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

/**
 * User Login
 * url - /login
 * method - POST
 * params - username, password
 */
$app->map('/login', function() use ($app) {
    // check for required params
    verifyParams(array('username', 'password'));
    $response = array();
    // reading post params
    if($app->request()->isPost()) {
        $username = $app->request()->post('username');
        $password = $app->request()->post('password');
    }

    $db = new DbHandler();
    // check for correct email and password
    if ($db->checkLogin($username, $password)) {
        // get the user by username
        $user = $db->getUserByUsername($username);
        $user = array_shift($user);
        if ($user != NULL) {
            $response["error"] = false;
            $response["id_usuario"] = $user["id_usuario"];
            $response["nombre"] = $user["nombre"];
            $response["correo"] = $user["correo"];
            $response['fecha_alta'] = $user["fecha_alta"];
            $response['direccion'] = $user["direccion"];
            $response['rfc'] = $user["rfc"];
            $response['sexo'] = $user["sexo"];
        } else {
            // unknown error occurred
            $response['error'] = true;
            $response['message'] = "An error occurred. Please try again";
        }
    } else {
        // user credentials are wrong
        $response['error'] = true;
        $response['message'] = 'Login failed. Incorrect credentials';
    }
    generateRes(200, $response);
})->via('POST');

/**
 * User Movements
 * url - /id/movimientos
 * method - POST
 * params - id
 */
$app->map('/:id/movimientos', function($id_usuario) use ($app) {
    $response = array();
    // reading post params
    if($app->request()->isPost()) {
        $username = $app->request()->post('id_usuario');
    }
    $db = new DbHandler();
    $movimientos = $db->getMovements($username);

    if($movimientos != NULL){
        $response["error"] = false;
        $response["movimientos"] = $movimientos;
    }
    generateRes(200, $response);
})->via('POST');

/**
 * User Balance
 * url - /id/saldo
 * method - POST
 * params - id
 */
$app->map('/:id/saldo', function($id_usuario) use ($app) {
    $response = array();
    // reading post/get params
    if($app->request()->isPost()) {
        $username = $app->request()->post('id_usuario');
    }
    $db = new DbHandler();
    $saldo = $db->getBalance($username);

    if($saldo != NULL){
        $response["error"] = false;
        $response["saldo"] = $saldo;
    }
    generateRes(200, $response);
})->via('POST');

/**
 * User's Account Status
 * url - /id/estadodecuenta/mes
 * method - POST
 * params - id, mes
 */
$app->map('/:id/estadodecuenta/:mes', function($id_usuario, $mes) use ($app) {
    $response = array();
    // reading post/get params
    if($app->request()->isPost()) {
        $username = $app->request()->post('id_usuario');
        $date = $app->request()->post('mes');
    }
    $db = new DbHandler();
    $detalles = $db->getAccountStatus($username,$date);

    if($detalles != NULL){
        $response["error"] = false;
        $response["detalles"] = $detalles;
    }
    generateRes(200, $response);
})->via('POST');

/**
 * Verifying required params posted or not
 */
function verifyParams($required_fields) {
    $error = false;
    $error_fields = "";
    $request_params = array();
    $request_params = $_REQUEST;
    // Handling PUT request params
    if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
        $app = \Slim\Slim::getInstance();
        parse_str($app->request()->getBody(), $request_params);
    }
    foreach ($required_fields as $field) {
        if (!isset($request_params[$field]) || strlen(trim($request_params[$field])) <= 0) {
            $error = true;
            $error_fields .= $field . ', ';
        }
    }
    if ($error) {
        // Required field(s) are missing or empty
        // echo error json and stop the app
        $response = array();
        $app = \Slim\Slim::getInstance();
        $response["error"] = true;
        $response["message"] = 'Required field(s) ' . substr($error_fields, 0, -2) . ' is missing or empty';
        generateRes(400, $response);
        $app->stop();
    }
}

/**
 * Echoing json response to client
 * @param Int $status_code HTTP response code
 * @param Array $response JSON response
 */
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