<?php

class respuestas{
 
    public $response = [
        'status' => "ok",
        "result" => array()
    ];
    
    #envia el usuario una solicitud por un metodo no permitido
    public function error_405(){
        $this->response['status'] = "error"; #cambiamos de ok a error 
        $this->response['result'] = array(
            "error_id" => "405",
            "error_msg" => "Metodo no permitido"
        );
        return $this->response;
    }
    #el error 200 no existe, pero lo llamamos asi cuando envia datos incorrectos 
    public function error_200($valor = "Datos incorrectos"){
        $this->response['status'] = "error";
        $this->response['result'] = array(
            "error_id" => "200",
            "error_msg" => $valor
        );
        return $this->response;
    }
    
    #bad request
    public function error_400(){
        $this->response['status'] = "error";
        $this->response['result'] = array(
            "error_id" => "400",
            "error_msg" => "Datos enviados incompletos o con formato incorrecto"
        );
        return $this->response;
    }

    public function error_500($valor = "Error interno del servidor"){
        $this->response['status'] = "error";
        $this->response['result'] = array(
            "error_id" => "500",
            "error_msg" => $valor
        );
        return $this->response;
    }

    public function error_401($valor = "No autorizado"){
        $this->response['status'] = "error";
        $this->response['result'] = array(
            "error_id" => "401",
            "error_msg" => $valor
        );
        return $this->response;
    }

    public function error_404(){
        $this->response['status'] = "error";
        $this->response['result'] = array(
            "error_id" => "404",
            "error_msg" => "Recurso no encontrado"
        );
        return $this->response;
    }

    public function error_403(){
        $this->response['status'] = "error";
        $this->response['result'] = array(
            "error_id" => "403",
            "error_msg" => "No tienes permisos para acceder al recurso"
        );
        return $this->response;
    }

    public function error_204($valor = "No se encontraron resultados"){
        $this->response['status'] = "error";
        $this->response['result'] = array(
            "error_id" => "204",
            "error_msg" => $valor
        );
        return $this->response;
    }   
    
}


?>