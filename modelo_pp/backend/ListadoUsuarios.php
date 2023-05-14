<?php 

require_once "./clases/Usuario.php";

use User\Usuario;


$usuario = new Usuario();
$arrayUser = ($usuario->traerTodos());


      /**
        * ListadoUsuarios.php: (GET) Se mostrará el listado completo de los usuarios, exepto la clave (obtenidos de la 
        base de datos) en una tabla (HTML con cabecera). Invocar al método TraerTodos. 
        */
$tabla = "<table><tr><td>ID</td><td>NOMBRE</td><td>CORREO</td><td>PERFIL</td></tr>";
foreach ($arrayUser as $usuariosJson) {

  //  echo json_encode($usuariosJson) . "<br>";

    $tabla .= "<tr><td>{$usuariosJson->getId()}</td><td>{$usuariosJson->getNombre()}</td><td>{$usuariosJson->getCorreo()}</td><td>{$usuariosJson->getIdPerfil()}</td></tr>";
}

$tabla .= "</table>";
echo $tabla;
?>