<?php 

require_once "./clases/Usuario.php";
use User\Usuario;

$clase = json_decode($_POST["usuario_json"]);
$usuario = new Usuario();
$usuario->setId($clase->id);
$usuario->setCorreo($clase->correo);
$usuario->setClave($clase->clave);
$usuario->setNombre($clase->nombre);
$usuario->setIdPerfil($clase->id_perfil);

echo $usuario->modificar();

?>
