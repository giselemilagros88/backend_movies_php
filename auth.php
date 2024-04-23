<?php
require_once 'class/auth.class.php';
require_once 'class/respuestas.class.php';

header("Access-Control-Allow-Origin: *"); // Permite el acceso desde cualquier origen
header("Access-Control-Allow-Methods: POST"); // Permitir solo el método POST
header("Access-Control-Allow-Headers: Content-Type"); // Permitir el encabezado Content-Type

$_auth = new auth();
$_respuestas = new respuestas();

// para el login de user solo se usara method post

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    //recibir datos
    $postBody = file_get_contents("php://input");
   // print_r($postBody);
    
    //enviamos los datos al manejador
    $datosArray = $_auth->login($postBody);# el method login esta en la class de auth

    //devolver una respuesta
    header('Content-Type: application/json');

    if (isset($datosArray["result"]["error_id"])) {
        $responseCode = $datosArray["result"]["error_id"];
        http_response_code($responseCode);
    } else {
        http_response_code(200);
    }

    echo json_encode($datosArray);
} else {
    header('Content-Type: application/json');
    $datosArray = $_respuestas->error_405();
    echo json_encode($datosArray);
}

?>