<?php 

require_once "./clases/Empleado.php";
use Empleado\Empleado;

$id = isset($_POST['id']) ? $_POST["id"] : -1;
    echo Empleado::eliminar($id);
?>