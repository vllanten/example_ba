<?php

Class DB{

    private $mi;

    //estabesco conexio con el mysql
    private function conexion(){
        $this->mi = New MySQLi("127.0.0.1", "root", "123456", "gestion");
        if($this->mi->connect_errno){
            die("Error : ". $this->mi->connect_errno);
        }
    }

    //desconecto de mysql
    private function desconexion(){
        $this->mi->close();
    }

    //recibo un select
    function get($sql){
        $rows = [];
        //levanto la conexion
        $this->conexion();
        //ejecuto el selec
        $result = $this->mi->query($sql);
        //recorro los resultados
        while($row = $result->fetch_array(MYSQLI_ASSOC)){
            $rows[] = $row;
        }
        //desconecto de la BD
        $this->desconexion();
        return $rows;
    }

    //elimina un registro
    function delete($tabla, $id){
        //respuesta por defecto
        $success = array("success"=>false);
        //levanto la bd
        $this->conexion();
        //ejectu el delete al registro de la tabla
        $result = $this->mi->query("DELETE FROM $tabla where id=$id");
        if($this->mi->error){
            $success = array("success"=>false, "msg"=>$this->mi->error );
        }else{
            $success = array("success"=>true);
        }
        $this->desconexion();
        return $success;
    }

    //actualiza un registro
    function update($tabla, $data){
        $success = array("success"=>false);

        try {
            //verifico que venga ID
            if(isset($data['id'])){
                $id = $data['id'];
                //elimino el id del arreglo
                unset($data['id']);
            
                $valores = [];
                //preparo la data del los campos y valores a actualizar
                foreach ($data as $columa => $valor) {
                    $valores[] = "$columa='$valor'";
                }
                $this->conexion();
                //ejecuto el update
                $sql = "UPDATE $tabla SET ". implode(",",$valores)." WHERE id = $id";
                $result = $this->mi->query($sql);
                
                if($this->mi->error){
                    $success = array("success"=>false, "msg"=>$this->mi->error );
                }else{
                    $success = array("success"=>true);
                }
                $this->desconexion();
            }
    
        } catch (Exception $e) {
            //throw $th;
        }
        return $success;

    }

    function insert($tabla, $data){
        $success = array("success"=>false);
        
        try {
            
            $valores = [];
            $campos = [];
            //preparo la data del los campos y valores a insertar
            foreach ($data as $columa => $valor) {
                $valores[] = "'$valor'";
                $campos[] = $columa;
            }

            $this->conexion();
            //ejecuto el insert
            $sql = "INSERT INTO $tabla (". implode(",",$campos).") VALUES (". implode(",",$valores).");";
            $result = $this->mi->query($sql);

            if($this->mi->error){
                $success = array("success"=>false, "msg"=>$this->mi->error );
            }else{
                $success = array("success"=>true);
            }
            $this->desconexion();
    
        } catch (Exception $e) {
            //throw $th;
        }
        return $success;

    }


}