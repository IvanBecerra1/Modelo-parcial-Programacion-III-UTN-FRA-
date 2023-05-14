<?php 

/**
 * ModificarEmpleado.php: Se recibirán por POST los siguientes valores: empleado_json (id, nombre, correo, 
clave, id_perfil, sueldo y pathFoto, en formato de cadena JSON) y foto (para modificar un empleado en la base 
de datos. Invocar al método Modificar. 
Nota: El valor del id, será el id del empleado 'original', mientras que el resto de los valores serán los del 
empleado modificado.
Se retornará un JSON que contendrá: éxito(bool) y mensaje(string) indicando lo acontecido.

 */

include_once "./clases/Empleado.php";
use Empleado\Empleado;

//{"id":3, "correo":"javi@mail.com", "clave":123,}
// "nombre":"javier", "id_perfil": 1, "sueldo":33500, 
 //   "foto": "fake.jpg"}

$empleadoJson = json_decode($_POST["empleado_json"]);
$empleado = new Empleado();
$empleado->setId($empleadoJson->id);
$empleado->setCorreo($empleadoJson->correo);
$empleado->setClave($empleadoJson->clave);
$empleado->setNombre($empleadoJson->nombre);
$empleado->setIdPerfil($empleadoJson->id_perfil);
$empleado->setSueldo($empleadoJson->sueldo);
echo $empleado->modificar();
?>