<?php

class conexion{
    private $server;
    private $user;
    private $password;
    private $database;
    private $port;
    private $conexion;

    function __construct(){
        $listadatos = $this->datosConexion();

        #recorremos los datos de la conexion para poder configurarlos en los atributos
        foreach($listadatos as $key => $value){
            $this->server = $value["server"];
            $this->user = $value["user"];
            $this->password = $value["password"];
            $this->database = $value["database"];
            $this->port = $value["port"];
        }
        $this->conexion = new mysqli($this->server, $this->user, $this->password, $this->database, $this->port);
        if($this->conexion->connect_errno){
            echo "algo va mal con la conexion";
            die();
        }
    }

    #obtener del archivo config los datos de la conexion
    private function datosConexion(){
        #obtener los datos de la conexion
        $direccion = dirname(__FILE__);
        $jsondata = file_get_contents($direccion . "/" . "config");

        #convertir el json a un array asociativo con el true
        return json_decode($jsondata, true);
    }

    #convertir caracteres a utf8
    private function convertirUTF8($array){
        array_walk_recursive($array,function(&$item,$key){
            if(!mb_detect_encoding($item,'utf-8',true)){
                $item = utf8_encode($item);
            }
        });
        return $array;
    }

    #obtener los datos de la base de datos
    public function obtenerDatos($sqlstr){
        $results = $this->conexion->query($sqlstr);
       // print_r($results);
        $resultArray = array();
        #si results tiene algo entonces recorrerlo
        if($results){
            foreach($results as $key){
                    $resultArray[] = $key;
            }
            return $this->convertirUTF8($resultArray);
        }else{
            return 0;
        }  
    }
    #para guardar datos en la base de datos
    public function nonQuery($sqlstr){
        $results = $this->conexion->query($sqlstr);
        return $this->conexion->affected_rows;
    }
    #para obtener el id de un registro
    public function nonQueryId($sqlstr){
        $results = $this->conexion->query($sqlstr);
        $filas = $this->conexion->affected_rows;
        if($filas >= 1){
            return $this->conexion->insert_id;
        }else{
            return 0;
        }
    }
}

?>

