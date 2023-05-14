<?php 

require_once "./clases/Usuario.php";

use User\Usuario;

$usuarioStdClass = json_decode($_POST['usuario_json']);
echo Usuario::traerUno($usuarioStdClass);


/*

VerificarUsuarioJSON.php: (POST) Se recibe el parámetro usuario_json (correo y clave, en formato de cadena 
JSON) y se invoca al método TraerUno. 
Se retornará un JSON que contendrá: éxito(bool) y mensaje(string) indicando lo acontecido

 */
?>