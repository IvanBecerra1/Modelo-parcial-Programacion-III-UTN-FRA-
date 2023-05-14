<?php 

require_once "./clases/Empleado.php";

use Empleado\Empleado;

$nombre = isset($_POST["nombre"]) ? $_POST["nombre"] : "no-recibido";
$correo = isset($_POST["correo"]) ? $_POST["correo"] : "no-recibido";
$clave = isset($_POST["clave"]) ? $_POST["clave"] : "no-recibido";
$id_perfil = isset($_POST["id_perfil"]) ? $_POST["id_perfil"] : "0";
$sueldo = isset($_POST["sueldo"]) ? $_POST["sueldo"] : "0";

/*$foto = $_FILES['foto'];
$tipoArchivo = pathinfo($foto['name'], PATHINFO_EXTENSION);
$foto2 = "./empleados/fotos/" .$nombre. '.' . date('His') . '.' . $tipoArchivo;*/

$empleado = new Empleado();
$empleado->nombre = $nombre;
$empleado->correo = $correo;
$empleado->clave = $clave;
$empleado->id_perfil = intval($id_perfil);
$empleado->sueldo = intval($sueldo);
//$empleado->setFoto($foto2);


echo $empleado->agregar();

?>