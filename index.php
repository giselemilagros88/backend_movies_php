<?php
   require_once 'class/conexion/conexion.php';
   $conexion = new conexion();
   $query = "SELECT * FROM peliculas";
   $datos = $conexion->obtenerDatos($query);
   print_r($datos);

   /* $query = "INSERT INTO `peliculas` (`id_pelicula`, `titulo`, `fecha_lanzamiento`, `genero`, `duracion`, `director`, `reparto`, `sinopsis`, `imagen`) VALUES (NULL, 'Volver al futuro', '1985-12-26', 'Ciencia ficción/Comedia', '1h 56m', 'Robert Zemeckis', 'Michael J. Fox, Christopher Lloyd, Lea Thompson, Crispin Glover, Thomas F. Wilson, Claudia Wells', 'Una máquina del tiempo transporta a un adolescente a los años 50, cuando sus padres todavía estudiaban en la secundaria.', 'volver_al_futuro.jpg');";
   $datos = $conexion->nonQueryId($query);
   print_r($datos);*/
?>