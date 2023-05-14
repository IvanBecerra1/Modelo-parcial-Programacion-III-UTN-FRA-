<?php 

require_once "./clases/Empleado.php";

use Empleado\Empleado;


$empleado = new Empleado();
$arrayUser = ($empleado->traerTodos());


      /**
        * ListadoUsuarios.php: (GET) Se mostrará el listado completo de los usuarios, exepto la clave (obtenidos de la 
        base de datos) en una tabla (HTML con cabecera). Invocar al método TraerTodos. 
        */
        $tabla = "<table><tr><td>ID</td><td>NOMBRE</td><td>CORREO</td><td>PERFIL</td><td>SUELDO</td><td>FOTO</td></tr>";
        foreach ($arrayUser as $empleadoJson) {
            $tabla .= "<tr>";
            $tabla .= "<td>{$empleadoJson->getId()}</td>";
            $tabla .= "<td>{$empleadoJson->getNombre()}</td>";
            $tabla .= "<td>{$empleadoJson->getCorreo()}</td>";
            $tabla .= "<td>{$empleadoJson->getPerfil()}</td>";
            $tabla .= "<td>{$empleadoJson->getSueldo()}</td>";
            $tabla .= "<td><img src='{$empleadoJson->getFoto()}' width='50' height='50' /></td>";
            $tabla .= "</tr>";
        }



$tabla .= "</table>";
echo $tabla;
?>