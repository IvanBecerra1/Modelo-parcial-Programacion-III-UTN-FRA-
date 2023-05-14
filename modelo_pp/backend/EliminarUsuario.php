<?php 

require_once "./clases/Usuario.php";
use User\Usuario;

$id = isset($_POST['id']) ? $_POST["id"] : -1;
$accion = isset($_POST["accion"]) ? $_POST["accion"] : "";

if ($accion == "borrar"){
    echo Usuario::eliminar($id);
}
?>