<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once('class/CheckAccess.php');

$check = New CheckAccess();

//verifico que el metodo sea post
if($_SERVER['REQUEST_METHOD'] == "POST"){
    //obtengo la data del body
    $data = json_decode(file_get_contents('php://input'), true);
    $resp = json_encode( $check->logout());
}else{
    $resp = json_encode(array("success"=> false, "msg"=>"metodo no soportado"));
}

echo $resp;