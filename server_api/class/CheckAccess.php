<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once('class/DB.php');

Class CheckAccess{

    private $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyz';

    //verifica que el usuario exista y crea el token
    function login($correo, $password){
        $response = array("success"=>false);
        $db = New DB();
        $resp= $db->get("select id, nombres, apellidos, correo from usuario where correo = '$correo' and password='$password' ");
        if(count($resp) > 0){
            $token = array(
                "id_user" => $resp[0]["id"],
                "fcreacion" => date("Y-m-d H:i:s"),
                "fuso" => date("Y-m-d H:i:s"),
                "token"=> substr(str_shuffle($this->permitted_chars), 0, 20) 
            );

            $db->insert("token", $token);
            $response = array("success"=>true, "data"=> $resp[0], "token"=>$token["token"]);
        }
        return $response;
    }
    //destruye el token
    function logout(){
        $response = array("success"=>false);
        $headers = getallheaders();
        $db = New DB();

        $token = $db->get("select * from token where token = '".$headers['Token']."' ");

        if(count($token) > 0){
            $tokenArr = $token[0];
            $tokenArr['token'] = $tokenArr['token']."_c";
            $tokenArr["fuso"] = date("Y-m-d H:i:s");

            return $db->update("token", $tokenArr);
        }
        

        return $response;
    }
    //valida el token
    function checkToken(){
        $response = false;

        $tiempo = 300;
        $headers = getallheaders();

        if(isset($headers['Token'])){
            $db = New DB();

            $duracion = date('Y-m-d H:i:s', time() - $tiempo);
    
            $q = "select * from token where fuso >='$duracion' and  token = '".$headers['Token']."' ";
            //print_r($q);
            $token = $db->get($q);
    
           if(count($token) > 0 ){
                $tokenArr = $token[0];
                $tokenArr["fuso"] = date("Y-m-d H:i:s");
                $db->update("token", $tokenArr);
            return true;
           }
        }
  

       http_response_code(401);
       return $response;

    }


}