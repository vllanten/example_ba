<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once('class/DB.php');
require_once('class/CheckAccess.php');
//TODO verificar/login login
$check = New CheckAccess();

if($check->checkToken()){
    //  true

    $method = $_SERVER['REQUEST_METHOD'];

    switch ($method) {
    case 'DELETE':
        echo _del(); 
        break;
    case 'PUT':
        echo _put();  
        break;
    case 'POST':
        echo _post();   
        break;
    case 'GET':
    default:
        echo _get();    
        break;
    }

}else{
    echo json_encode(array("success"=> false, "msg"=>"sin acceso"));
}



function  _del(){
    $db = New DB();
    //verifico que venga ID
    if(isset($_GET['id']) && $_GET['id'] != null){
        return json_encode($db->delete("usuario", $_GET['id']));
    }
    return json_encode(array("success"=> false));
}

function  _put(){
    $db = New DB();
    //obtengo la data del body
    $data = json_decode(file_get_contents('php://input'), true);
    return json_encode($db->update("usuario", $data));
}

function  _post(){
    $db = New DB();
    //obtengo la data del body
    $data = json_decode(file_get_contents('php://input'), true);
    return json_encode($db->insert("usuario", $data));
}

function _get(){
    $db = New DB();
    $resp = null;
    
    //verifico que venga ID
    if(isset($_GET['id']) && $_GET['id'] != null){
        //respondo con la data del usuario
        $resp= $db->get("select id, nombres, apellidos, correo from usuario where id = '".$_GET['id']."' ");
    }else{
        //respondo con toda la data
        $resp= $db->get("select id, nombres, apellidos, correo from usuario ");
    }

    return json_encode($resp);
}
