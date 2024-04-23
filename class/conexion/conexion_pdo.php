<?php 

class conexion{ # poner Conexion
    #atributos que son propios del objeto
    private $servidor ="localhost";
    private $usuario ="root";
    private $pass = "";
    private $conexionPdo;#objeto de tipo pdo, de la clase propia de php
    private $base = "movies_cac";
    
    #apenas se instancia un objeto de tipo Conexion(de esta clase que estamos parados aca, se conectara a la base)
    #constructor de los objetos conexion
    public function __construct(){
        // cargar las variables de entorno
        try{ 
            # manejo de excepciones, si el codigo esta adentro del try, esta controlado por el sistema
            #$servidor,$base,$usuario,$pass
            # $this->$servidor = $servidor;
           
            $this->conexionPdo = new PDO("mysql:host=$this->servidor;dbname=$this->base",$this->usuario,$this->pass);
            #ACTIVAMOS LOS ERRORES Y LAS EXCEPTIONES
            $this->conexionPdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

        }catch(PDOException $e){ 
            # si algo del codigo del try falla, le mostramos un msj amigable al usuario del sistema
            return "Falla de Conexión".$e;
        }
    }
  
     #creo un metodo de ejecucion a sql de insert, update, delete   
    public function ejecutar($sql){
        #Execute una consulta de sql
        $this->conexionPdo->exec($sql);
        #esto nos da el valor de id insertado
        return $this->conexionPdo->lastInsertId();
    }
    public function consultar($sql){ # select 
        #ejecuta la consulta y nos devuelve la info de la base
        $sentencia = $this->conexionPdo->prepare($sql);
        $sentencia->execute();
        #retorna todos los registros de la consulta sql
        return $sentencia->fetchAll();
        /*1ro agarra nuestra sentencia de sql y lo mete adentro de un objeto 
         2do agarra el objeto y ejecuta la sentencia de sql que devuelve o no filas de base de datos 
         3ro fetchall() nos devuelve un array con las filas del select  */
    }


} 

?>