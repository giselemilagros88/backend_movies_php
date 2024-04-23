<?php
    require_once 'class/conexion/conexion.php';
    require_once 'class/respuestas.class.php';
    require_once 'class/peliculas.class.php';
    // evitar error de cors
    header("Access-Control-Allow-Origin: *"); // Permite el acceso desde cualquier origen
    header("Access-Control-Allow-Methods: *"); // Permitir cualquier método
    header("Access-Control-Allow-Headers: Content-Type"); // Permitir el encabezado Content-Type
   

    if($_SERVER['REQUEST_METHOD'] == "GET"){
        #peliculas por pagina
        if(isset($_GET['page'])){
            $pagina = $_GET['page'];
            $peliculas = new peliculas();
            $datos = $peliculas->listarPeliculas($pagina);
            //print_r($datos);
            header('Content-Type: application/json');
            echo json_encode($datos);
            http_response_code(200);
        #pelicula por id
        }else if(isset($_GET['id'])){
            $id = $_GET['id'];
            $peliculas = new peliculas();
            $datos = $peliculas->obtenerPelicula($id);
            //print_r($datos);
            header('Content-Type: application/json');
            echo json_encode($datos);
            http_response_code(200);
        #pelicula por una parte del nombre 
        }else if(isset($_GET['buscar'])){
            $nombre = $_GET['buscar'];
            $peliculas = new peliculas();
            $datos = $peliculas->buscarPelicula($nombre);
            //print_r($datos);
            header('Content-Type: application/json');
            echo json_encode($datos);
            http_response_code(200);
       
        }else{
            //quiere todas las peliculas sin paginar
            $peliculas = new peliculas();
            $datos = $peliculas->listarPeliculasSinPaginar(1);
            //print_r($datos);
            header('Content-Type: application/json');
            echo json_encode($datos);
            http_response_code(200);
        }
     #insertar una pelicula
    }else if($_SERVER['REQUEST_METHOD'] == "POST"){
            $peliculas = new peliculas();
            #recibimos los datos enviados
            $postBody = file_get_contents("php://input");
            #enviamos los datos al manejador
            $datosArray = $peliculas->insertarPelicula($postBody);
            #devolvemos una respuesta
            header('Content-Type: application/json');
            if(isset($datosArray["result"]["error_id"])){
                $responseCode = $datosArray["result"]["error_id"];
                http_response_code($responseCode);
            }else{
                http_response_code(200);
            }
            echo json_encode($datosArray);
            /*Ejemplo para pegarle por postman para crear una pelicula o desde json*/
            /* {
                "id_pelicula": null,
                "titulo": "Mision Imposible",
                "fecha_lanzamiento": "1996-07-04",
                "genero": "Acción/Suspenso",
                "duracion": "1h 50m",
                "director": "Brian De Palma",
                "reparto": "Tom Cruise, Jean Reno",
                "sinopsis": "El espía Ethan Hunt debe llevar a cabo una misión imposible: evitar la venta de un disco robado que contiene información confidencial y, al mismo tiempo, limpiar su nombre tras haber sido acusado del asesinato de su mentor.",
                "imagen": "mision_imposible_1.jpg"
                }*/
    }else if($_SERVER['REQUEST_METHOD'] == "PUT"){
             #actualizar pelicula
            $peliculas = new peliculas();
            #recibimos los datos enviados
            $postBody = file_get_contents("php://input");
            #enviamos datos al manejador
            $datosArray = $peliculas->actualizarPelicula($postBody);
            #devolvemos una respuesta
            header('Content-Type: application/json');
            if(isset($datosArray["result"]["error_id"])){
                $responseCode = $datosArray["result"]["error_id"];
                http_response_code($responseCode);
            }else{
                http_response_code(200);
            }
             echo json_encode($datosArray);
    }else if($_SERVER['REQUEST_METHOD'] == "DELETE"){
             #borrar pelicula por su id
            $peliculas = new peliculas();
            #recibimos los datos enviados
            $postBody = file_get_contents("php://input");
            $datosArray = $peliculas->eliminarPelicula($postBody);
            #devolvemos una respuesta
            header('Content-Type: application/json');
            if(isset($datosArray["result"]["error_id"])){
                $responseCode = $datosArray["result"]["error_id"];
                http_response_code($responseCode);
            }else{
                http_response_code(200);
            }
            echo json_encode($datosArray);
            
    }else{
            header('Content-Type: application/json');
            $datosArray = $_respuestas->error_405();
            echo json_encode($datosArray);
    }
    

?>