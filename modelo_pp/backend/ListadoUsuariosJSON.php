<?php 

require_once "./clases/Usuario.php";

use User\Usuario;

$usuario = new Usuario();
$arrayUser = ($usuario->traerTodoLosDatosJSON());

//var_dump($arrayUser);

foreach ($arrayUser as $usuariosJson) {

    echo json_encode($usuariosJson) . "<br>";
}

?>