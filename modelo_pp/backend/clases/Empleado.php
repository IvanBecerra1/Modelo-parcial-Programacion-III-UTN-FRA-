<?php 
namespace Empleado;

require_once "Usuario.php";
require_once "AccesoDatos.php";
require_once "ICRUD.php";

use User\Usuario;
use stdClass;
use PDO;
use Poo\AccesoDatos;
use ICRUD;


class Empleado extends Usuario implements ICRUD {
    public string $foto;
    public int $sueldo;

    function __construct
    (/*$nombre="", $correo="", $clave="", $id_perfil="", $perfil="",*/ $foto="", $sueldo="") {
       //super($nombre, $correo, $clave, $id_perfil, $perfil, $foto, $sueldo);

        parent::__construct();
        $this->foto = $foto;
        $this->sueldo = intval($sueldo);
    }


    function setSueldo($sueldo) {
        $this->sueldo = $sueldo;
    }

    
    function setFoto($foto) {
        $this->foto = $foto;
    }

    
    function getSueldo() {
        return $this->sueldo;
    }
    
    function getFoto() {
        return $this->foto;
    }

    static function traerTodos(){
        $objetoAcceso = AccesoDatos::accesoPDO();
    
        $consulta = $objetoAcceso->consultaPDO("SELECT * FROM empleados;");
    
        $consulta->execute();
    
        $usuarios = $consulta->fetchAll(PDO::FETCH_ASSOC);
    
        $lista = array();

        foreach ($usuarios as $usuario) {
            $empleadoObj = new Empleado();
            $empleadoObj->setId($usuario['id']);
            $empleadoObj->setNombre($usuario['nombre']);
            $empleadoObj->setCorreo($usuario['correo']);
            $empleadoObj->setClave($usuario['clave']);
            $empleadoObj->setIdPerfil($usuario['id_perfil']);
            $empleadoObj->setPerfil(Usuario::traerPerfil($usuario['id_perfil']));
            $empleadoObj->setFoto($usuario['foto']);
            $empleadoObj->setSueldo($usuario['sueldo']);


            array_push($lista, $empleadoObj);
        }
        return $lista;
    }

    function agregar(){
        $objetoAccesoDato = AccesoDatos::accesoPDO();
        
        $consulta =$objetoAccesoDato->consultaPDO
        ("INSERT INTO empleados (id, nombre, correo, clave, id_perfil, sueldo, foto)"
        . "VALUES(:id, :nombre, :correo, :clave, :id_perfil, :sueldo, :foto)");
        
        $exito = $this->guardarFoto();

        $consulta->bindValue(':id', $this->id, PDO::PARAM_INT);
        $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':correo', $this->correo, PDO::PARAM_STR);
        $consulta->bindValue(':clave', $this->clave, PDO::PARAM_STR);
        $consulta->bindValue(':id_perfil', $this->id_perfil, PDO::PARAM_INT);
        $consulta->bindValue(':sueldo', $this->sueldo, PDO::PARAM_INT);
        $consulta->bindValue(':foto', $this->foto, PDO::PARAM_STR);


        $consulta->execute();

        $seAgrego = $consulta->rowCount();

        $mensaje = new stdClass();
        $mensaje->notificar=  ($seAgrego >= 1 ? "" : "No ") . "Se agrego al empleado nuevo";
        $mensaje->exito = $seAgrego  == 1 ? true : false;

        return json_encode($mensaje);
    }

    /**
     * Modificar (de instancia): Modifica en la base de datos el registro coincidente
     *  con la instancia actual (comparar 
        por id). Retorna true, si se pudo modificar, false, caso contrario.
        Nota: Si la foto es pasada, guardarla en “./backend/empleados/fotos/”, con el nombre formado por el nombre
        punto tipo punto hora, minutos y segundos del alta (Ejemplo: juan.105905.jpg). Caso contrario, sólo actualizar 
        el campo de la base.

     */
    function modificar(){
        $objetoAccesoDato = AccesoDatos::accesoPDO();
        
        $consulta =$objetoAccesoDato->consultaPDO("UPDATE empleados 
                                                    SET nombre = :nombre,
                                                        correo = :correo, 
                                                        clave = :clave,
                                                        id_perfil = :id_perfil,
                                                        sueldo = :sueldo,
                                                        foto = :foto
                                                    WHERE id = :id");

        $seModifico = $this->guardarFoto();
        $consulta->bindValue(':id', $this->getId(), PDO::PARAM_INT);
        $consulta->bindValue(':nombre', $this->getNombre(), PDO::PARAM_STR);
        $consulta->bindValue(':correo', $this->getCorreo(), PDO::PARAM_STR);
        $consulta->bindValue(':clave', $this->getClave(), PDO::PARAM_STR);
        $consulta->bindValue(':id_perfil', $this->getIdPerfil(), PDO::PARAM_INT);
        $consulta->bindValue(':sueldo', $this->getSueldo(), PDO::PARAM_INT);
        $consulta->bindValue(':foto', $this->getFoto(), PDO::PARAM_STR);

        $consulta->execute();
        
        $seModifico = $consulta->rowCount();

        $objeto = new stdClass();

        $objeto->exito = $seModifico == 1 ? true : false;
        $objeto->mensaje = ($seModifico >= 1 ? "" : "No ") . "Se modifico el empleado";

        return json_encode($objeto);
    }

    /**
     * Eliminar (de clase): elimina de la base de datos 
     * el registro coincidente con el id recibido cómo parámetro. 
        Retorna true, si se pudo eliminar, false, caso contrario
     */
    static function eliminar($id){
        $objetoAccesoDato = AccesoDatos::accesoPDO();

        $consulta =$objetoAccesoDato->consultaPDO("DELETE FROM `empleados` WHERE id = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        
        $stdClass = new stdClass();
        $seBorro = $consulta->rowCount();

        $stdClass->exito = $seBorro >= 1 ? true : false;
        $stdClass->mensaje = ($seBorro >= 1 ? "" : "No ") . "se borro el empleado";
        return json_encode($stdClass);
    }


    // foto

    private function guardarFoto() : bool {

        $foto = $_FILES['foto'];
        $tipoArchivo = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
        $destino = "./empleados/fotos/" . $this->nombre . '.' . date('His') . '.' . $tipoArchivo;
        $this->setFoto($destino);

        // Verificar si el archivo ya existe
        if (file_exists($destino)){
            if (unlink($destino)) {
            } else {
            }
        }
    
        /*// Verificar si el tamaño del archivo es válido
        define('MAX_FOTO', 500000000); // constante
        if ($_FILES["foto"]["size"] > MAX_FOTO){
            echo "La foto es muy grande. ";
            return false;
        }*/
    
        // Verificar si el archivo es una imagen
        $esImagen = getimagesize($_FILES["foto"]["tmp_name"]);
        if (!$esImagen) {
            echo "Solo se permite subir imágenes";
            return false;
        }
    
        // Verificar si la extensión es válida
        /*$extensionesPermitidas = array("jpg", "jpeg", "gif", "png");
        $tipoArchivo = pathinfo($destino, PATHINFO_EXTENSION);
        if (!in_array($tipoArchivo, $extensionesPermitidas)) {
            echo "Solo se permiten imágenes con extensión JPG, JPEG, PNG o GIF.";
            return false;
        }*/
    
        // Mover el archivo al destino
        $archivoSubido = move_uploaded_file($_FILES["foto"]["tmp_name"], $destino);
        if (!$archivoSubido) {
            echo "Ocurrió un error en la subida de archivo.";
            return false;
        }
    
        // Verificar si el archivo se movió correctamente
        if (!file_exists($destino)) {
            echo "Ocurrió un error en la subida de archivo.";
            return false;
        }
    
      //  echo "El archivo se subió con éxito: " . basename($_FILES["foto"]["name"]);
        return true;
    }


}

?>