<?php
  require_once 'conexion/conexion.php';
  require_once 'respuestas.class.php';

  class auth extends conexion{
    public function login($json){ #metodo para loguear
      $_respuestas = new respuestas(); #instancia de la clase respuestas
      $datos = json_decode($json, true); #convertir json a array asociativo

      if (!isset($datos['usuario']) || !isset($datos['password'])) { #si no estan seteados los datos
        //error en la peticion
        return $_respuestas->error_400(); #bad request, se enviaron erroneamente los datos
      } else {
        //todo esta bien
        $usuario = $datos['usuario'];
        $password = $datos['password'];
        //$password = parent::encriptar($password);

        $datos = $this->obtenerDatosUsuario($usuario);
        if ($datos) {
          //verificar si la contraseña es igual
          if ($password == $datos[0]['password']) {
            if ($datos[0]['estado'] == 'A') {
              //crear el token
              $verificar = $this->insertarToken($datos[0]['id_usuario'],$datos[0]['es_admin']);
              if ($verificar) {
                //si todo es correcto, devolver token
                $result = $_respuestas->response;
                $result['result'] = array(
                  "token" => $verificar,
                  "es_admin" => $datos[0]['es_admin']
                );
                return $result;
              } else {
                return $_respuestas->error_500();
              }
            } else {
              return $_respuestas->error_200("El usuario no esta activo");
            }
          } else {
            return $_respuestas->error_200("La contraseña es incorrecta");
          }
        } else {
          return $_respuestas->error_200("El usuario $usuario no existe");
        }
      }
    }

    private function obtenerDatosUsuario($correo){
      $query = "SELECT id_usuario, password, es_admin, estado FROM usuarios WHERE usuario = '$correo'";
      $datos = parent::obtenerDatos($query);
      if (isset($datos[0]['id_usuario'])) {
        return $datos;
      } else {
        return 0;
      }
    }

    private function insertarToken($id_usuario,$admin){
      $val = true;
      #generar token: bin2hedx devuelve un hexadecimal, y openssl_random_pseudo_bytes genera un string aleatorio, generamos un token unico
      $token = bin2hex(openssl_random_pseudo_bytes(16, $val));
      $date = date("Y-m-d H:i");
      $estado = "A";
      $es_admin = $admin;
      //primero verifico si ya existe el usuario en la tabla token, si existe solo actualizo el token por uno nuevo token
      $query = "SELECT * FROM usuarios_token WHERE id_usuario = '$id_usuario' AND es_admin = '$es_admin'";
      $verificar = parent::obtenerDatos($query);
      if ($verificar) {
        $query = "UPDATE usuarios_token SET token = '$token'";
        $verificar = $this->nonQuery($query);
        if ($verificar) {
          return $token;
        } else {
          return 0;
        }
      }else{
        $query = "INSERT INTO usuarios_token (id_usuario, token, estado, fecha,es_admin) VALUES ('$id_usuario', '$token', '$estado', '$date','$es_admin')";
        $verificar = $this->nonQuery($query);
        if ($verificar) {
          return $token;
        } else {
          return 0;
        }  
      }
  }
}
?>