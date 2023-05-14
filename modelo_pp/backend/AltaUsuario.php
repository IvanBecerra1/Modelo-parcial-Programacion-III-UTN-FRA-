<?php 

require_once "./clases/Usuario.php";

use User\Usuario;

$nombre = isset($_POST["nombre"]) ? $_POST["nombre"] : "no-recibido";
$correo = isset($_POST["correo"]) ? $_POST["correo"] : "no-recibido";
$clave = isset($_POST["clave"]) ? $_POST["clave"] : "no-recibido";
$id_perfil = isset($_POST["id_perfil"]) ? $_POST["id_perfil"] : "0";

$usuario = new Usuario();
$usuario->nombre = $nombre;
$usuario->correo = $correo;
$usuario->clave = $clave;
$usuario->id_perfil = intval($id_perfil);

echo $usuario->Agregar();

?>