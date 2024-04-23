<?php
    require_once 'conexion/conexion.php';
    require_once 'respuestas.class.php';

    class peliculas extends conexion{
        private $token = ""; #"token":"e55ed4620e111326df92af5806df4989"
        
        // para listar todas las peliculas y en cada pagina vendran 16 peliculas
        public function listarPeliculas($pagina){
            $inicio = 0;
            $cantidad = 16;
            if ($pagina > 1) {
                $inicio = ($cantidad * ($pagina - 1)) + 1;
                $cantidad = $cantidad * $pagina;
            }
            $query = "SELECT * FROM peliculas LIMIT $inicio, $cantidad";
            $datos = $this->obtenerDatos($query);
            return $datos;
        }
         public function listarPeliculasSinPaginar(){
            $query = "SELECT * FROM peliculas";
            $datos = $this->obtenerDatos($query);
            return $datos;
        }
        // para buscar una pelicula por su id
        public function obtenerPelicula($id){
            $query = "SELECT * FROM peliculas WHERE id_pelicula = '$id'";
            $datos = $this->obtenerDatos($query);
            if ($datos) {
                return $datos;
            } else {
                return 0;
            }
        }
        // para buscar una pelicula por una parte de su nombre
        public function buscarPelicula($nombre){
            // pasar a minuscula el nombre y el campo de la base tambien
            $nombre = strtolower($nombre);
            $query = "SELECT * FROM peliculas WHERE LOWER(titulo) LIKE '%$nombre%'";
            $datos = $this->obtenerDatos($query);
            if ($datos) {
                return $datos;
            } else {
                return 0;
            }
        }
        # inserta una pelicula en la base de datos
        public function insertarPelicula($json){
            $_respuestas = new respuestas();
            #convertimos el json a un array asociativo
            $datos = json_decode($json, true);
             if (!isset($datos['token'])) {
                return $_respuestas->error_401();
            }else{
                $this->token = $datos['token'];
                $arrayToken = $this->buscarToken();
                //print_r($arrayToken);
                if ($arrayToken) {
                    if ($this->refreshToken($arrayToken[0]['id_usuario'])) {
                        #actualizamos el token dentro de la funcion
                    } else {
                        return $_respuestas->error_500();
                    }
                } else {
                    return $_respuestas->error_401("El token enviado es invalido o ha caducado");
                }
            }
            
            #si no estan los datos requeridos
            if (!isset($datos['titulo']) || !isset($datos['fecha_lanzamiento']) || !isset($datos['genero']) || !isset($datos['duracion']) || !isset($datos['director']) || !isset($datos['reparto']) || !isset($datos['sinopsis']) || !isset($datos['imagen'])) {
                #devolvemos un bad request
                return $_respuestas->error_400();
            } else {
                $titulo = $datos['titulo'];
                $fecha_lanzamiento = $datos['fecha_lanzamiento'];
                $genero = $datos['genero'];
                $duracion = $datos['duracion'];
                $director = $datos['director'];
                $reparto = $datos['reparto'];
                $sinopsis = $datos['sinopsis'];
                $imagen = $datos['imagen'];
               // codigo para levantar la imagen guardarla en el server y guardar la ruta en la base de datos
               
               // faltan validaciones para la fecha de lanzamiento 
                $query = "INSERT INTO peliculas (id_pelicula, titulo, fecha_lanzamiento, genero, duracion, director, reparto, sinopsis, imagen) VALUES (NULL, '$titulo', '$fecha_lanzamiento', '$genero', '$duracion', '$director', '$reparto', '$sinopsis', '$imagen')";            
                $datos = $this->nonQueryId($query);
                if ($datos) {
                    $respuesta = $_respuestas->response;
                    $respuesta["result"] = array(
                        "id" => $datos
                    );
                    return $respuesta;
                } else {
                    return $_respuestas->error_500();
                }
            }
        }  
        #actualiza una pelicula en la base de datos
        public function actualizarPelicula($json){
            $_respuestas = new respuestas();
            #convertimos el json a un array asociativo
            $datos = json_decode($json, true);

            if (!isset($datos['token'])) {
                return $_respuestas->error_401();
            }else{
                $this->token = $datos['token'];
                $arrayToken = $this->buscarToken();
                if ($arrayToken) {
                    if ($this->refreshToken($arrayToken[0]['id_usuario'])) {
                        #actualizamos el token dentro de la funcion
                    } else {
                        return $_respuestas->error_500();
                    }
                } else {
                    return $_respuestas->error_401("El token enviado es invalido o ha caducado");
                }
            }
            
            #si no estan los datos requeridos
            if (!isset($datos['id_pelicula']) || !isset($datos['titulo']) || !isset($datos['fecha_lanzamiento']) || !isset($datos['genero']) || !isset($datos['duracion']) || !isset($datos['director']) || !isset($datos['reparto']) || !isset($datos['sinopsis']) || !isset($datos['imagen'])) {
                #devolvemos un bad request
                return $_respuestas->error_400();
            } else {
                $id = $datos['id_pelicula'];
                $titulo = $datos['titulo'];
                $fecha_lanzamiento = $datos['fecha_lanzamiento'];
                $genero = $datos['genero'];
                $duracion = $datos['duracion'];
                $director = $datos['director'];
                $reparto = $datos['reparto'];
                $sinopsis = $datos['sinopsis'];
                $imagen = $datos['imagen'];
                // codigo para levantar la imagen guardarla en el server y guardar la ruta en la base de datos
                // faltan validaciones para la fecha de lanzamiento 
                $query = "UPDATE peliculas SET titulo = '$titulo', fecha_lanzamiento = '$fecha_lanzamiento', genero = '$genero', duracion = '$duracion', director = '$director', reparto = '$reparto', sinopsis = '$sinopsis', imagen = '$imagen' WHERE id_pelicula = '$id'";            
                $datos = $this->nonQuery($query);
                if ($datos >= 1) {
                    $respuesta = $_respuestas->response;
                    $respuesta["result"] = array(
                        "mensaje" => "Registro actualizado correctamente"
                    );
                    return $respuesta;
                } else {
                    return $_respuestas->error_500();
                }
            }
        }
        #eliminar una pelicula por su id de la base de datos
        public function eliminarPelicula($json){
            $_respuestas = new respuestas();
             #convertimos el json a un array asociativo
            $datos = json_decode($json, true);
            if (!isset($datos['id_pelicula'])) {
                return $_respuestas->error_400();
            }else{
                $id_pelicula = $datos['id_pelicula'];
                $query = "DELETE FROM peliculas WHERE id_pelicula = '$id_pelicula'";
                $datos = $this->nonQuery($query);
                if ($datos >= 1) {
                    $respuesta = $_respuestas->response;
                    $respuesta["result"] = array(
                        "mensaje" => "Registro eliminado correctamente"
                    );
                    return $respuesta;
                } else {
                    return $_respuestas->error_500();
                }
            }
           
        }
        private function buscarToken(){
            #El unico usuario que pueda crear peliculas, modificar, o eliminar es el admin
            $query = "SELECT * FROM usuarios_token WHERE token = '$this->token' AND estado ='A' and es_admin ='S' ";
            
            $datos = $this->obtenerDatos($query);
            if ($datos >= 1) {
                return $datos;
            } else {
                return 0;
            }   
        }
        private function refreshToken($idusuario){
            $date = date("Y-m-d H:i");
            $query = "UPDATE usuarios_token SET fecha = '$date' WHERE id_usuario = '$idusuario' AND token = '$this->token' AND  estado = 'A'";
            $datos = $this->nonQuery($query);
            if ($datos >= 1) {
                return $datos;
            } else {
                return 0;
            }
        }

    }

?>