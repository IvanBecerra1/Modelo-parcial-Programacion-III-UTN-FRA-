<?php 

namespace User;

require_once "AccesoDatos.php";
require_once "IMB.php";
use stdClass;
use PDO;
use Poo\AccesoDatos;

class Usuario implements IMB {
    public int $id;
    public string $nombre;
    public string $correo;
    public string $clave;
    public int $id_perfil;
    public string $perfil;


    function __construct($nombre="", $correo="", $clave="", $id_perfil="", $perfil="")
    {
        $this->id=0;
        $this->nombre = $nombre;
        $this->correo = $correo;
        $this->clave = $clave;
        $this->id_perfil = intval($id_perfil);
        $this->perfil = $perfil;
    }


    function getId() : int {
        return $this->id;
    }
    function getNombre() : string {
        return $this->nombre;
    }

    function getClave() : string {
        return $this->clave;
    }

    function getIdPerfil() : int {
        return $this->id_perfil;
    }

    function getPerfil() : string {
        return $this->perfil;
    }
    function getCorreo() : string {
        return $this->correo;
    }
    function setId($id) {
        $this->id = $id;
    }

    function setIdPerfil($idPerfil) {
        $this->id_perfil = $idPerfil;
    }

    function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    function setPerfil($perfil) {
        $this->perfil = $perfil;
    }

    function setClave($clave) {
        $this->clave = $clave;
    }

    
    function setCorreo($correo) {
        $this->correo = $correo;
    }
    function toJSON() : string {
        return json_encode(get_object_vars($this));
    }

    /*Funciones de crud */

    static string $directorio = "./archivos/usuarios.json";

    function guardarEnArchivo() : string {;
        $abrirArchivo = fopen(Usuario::$directorio, "a");
        
        $seGuardo = fwrite($abrirArchivo, $this->toJSON() . "\r\n");
        
        fclose($abrirArchivo);
    
        $objeto = new stdClass();

        $objeto->exito = $seGuardo == 1 ? true : false;
        $objeto->mensaje = "Se guardo el usuario";

        return json_encode($objeto);
    }

    static function traerTodoLosDatosJSON() : array {
        $listaAlumnos = Usuario::obtenerDatos();

        return $listaAlumnos;
    }
    static function obtenerDatos(): ?array
    {
        $archivoAbierto = fopen(Usuario::$directorio, "r"); // abro modo lectura
        $listaUsuarios = array(); // creo la array

        while (!feof($archivoAbierto)) {
            $linea = fgets($archivoAbierto); // leo por linea completa
            $linea = trim($linea);

            if ($linea != "") {
                $datos = explode(",", $linea); // separo por las comas

                $usuario = new Usuario($datos[0], $datos[1], $datos[2]);
                array_push($listaUsuarios, $usuario);
            }
        }

        fclose($archivoAbierto);
        return $listaUsuarios;
    }

    public static function traerTodos()
    {
        $objetoAcceso = AccesoDatos::accesoPDO();
    
        $consulta = $objetoAcceso->consultaPDO("SELECT * FROM usuarios;");
    
        $consulta->execute();
    
        $usuarios = $consulta->fetchAll(PDO::FETCH_ASSOC);
    
        $lista = array();

        foreach ($usuarios as $usuario) {
            $usuarioObj = new Usuario();
            $usuarioObj->setId($usuario['id']);
            $usuarioObj->setNombre($usuario['nombre']);
            $usuarioObj->setCorreo($usuario['correo']);
            $usuarioObj->setClave($usuario['clave']);
            $usuarioObj->setIdPerfil($usuario['id_perfil']);
            $usuarioObj->setPerfil(Usuario::traerPerfil($usuario['id_perfil']));

            array_push($lista, $usuarioObj);
        }
        return $lista;
    }

    // MYSQL
    public function Agregar() {
        $objetoAccesoDato = AccesoDatos::accesoPDO();
        
        // (id,nombre, correo, clave e id_perfil),
        $consulta =$objetoAccesoDato->consultaPDO
        ("INSERT INTO usuarios (id, nombre, correo, clave, id_perfil)"
        . "VALUES(:id, :nombre, :correo, :clave, :id_perfil)");
        
        $consulta->bindValue(':id', $this->id, PDO::PARAM_INT);
        $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':correo', $this->correo, PDO::PARAM_STR);
        $consulta->bindValue(':clave', $this->clave, PDO::PARAM_STR);
        $consulta->bindValue(':id_perfil', $this->id_perfil, PDO::PARAM_INT);
        $exito = $consulta->execute();
        
        $mensaje = new stdClass();
        $mensaje->notificar= "Se agrego al usuario";
        $mensaje->exito = $exito  == 1 ? true : false;
        return json_encode($mensaje);
    }

    public static function traerUno(StdClass $claseSTD) {
        $accesoDatos = AccesoDatos::accesoPDO();
        $consulta = $accesoDatos->consultaPDO("SELECT * FROM usuarios WHERE correo = :correo AND clave = :clave");
        $consulta->bindValue(":correo", $claseSTD->correo);
        $consulta->bindValue(":clave", $claseSTD->clave);
        $consulta->execute();

        $objetoUsuario = new Usuario();
        $exito = false;
        while($fila = $consulta->fetch()){
            $objetoUsuario->id = $fila[0]; 
            $objetoUsuario->correo = $fila[1]; 
            $objetoUsuario->clave = $fila[2]; 
            $objetoUsuario->nombre = $fila[3]; 
            $objetoUsuario->id_perfil = $fila[4]; 
            $objetoUsuario->perfil = Usuario::traerPerfil($fila[4]);
            $exito = true;
        }

        $mensaje = new stdClass();
        $mensaje->notificar= ($exito == 1 ? "" : "No ") . "Se encontro el usuario";
        $mensaje->exito = $exito  == 1 ? true : false;

        return json_encode($mensaje);
    }

    static function traerPerfil($idPerfil){
        $accesoDatos = AccesoDatos::accesoPDO();
        $consulta = $accesoDatos->consultaPDO("SELECT descripcion FROM perfiles WHERE id = :idPerfil");
        $consulta->bindValue(":idPerfil", $idPerfil);
        $consulta->execute();

        $descripcion = "ninguno";

        while($fila = $consulta->fetch()){
            $descripcion = $fila[0]; 
        }
        return $descripcion;
    }



    // INTERFAZ IBM

    function modificar(){
        $objetoAccesoDato = AccesoDatos::accesoPDO();
        
        $consulta =$objetoAccesoDato->consultaPDO("UPDATE usuarios SET nombre = :nombre, correo = :correo, 
                                                        clave = :clave, id_perfil = :id_perfil WHERE id = :id");
        $consulta->bindValue(':id', $this->getId(), PDO::PARAM_INT);
        $consulta->bindValue(':nombre', $this->getNombre(), PDO::PARAM_STR);
        $consulta->bindValue(':correo', $this->getCorreo(), PDO::PARAM_STR);
        $consulta->bindValue(':clave', $this->getClave(), PDO::PARAM_STR);
        $consulta->bindValue(':id_perfil', $this->getIdPerfil(), PDO::PARAM_INT);

        $seModifico = $consulta->execute();
        $objeto = new stdClass();

        $objeto->exito = $seModifico == 1 ? true : false;
        $objeto->mensaje = "Se modifico el usuario";

        return json_encode($objeto);
    }
    
    static function eliminar(int $idEliminar){
        $objetoAccesoDato = AccesoDatos::accesoPDO();

        $consulta =$objetoAccesoDato->consultaPDO("DELETE FROM `usuarios` WHERE id = :id");
        $seBorro = $consulta->bindValue(':id', $idEliminar, PDO::PARAM_INT);

        $stdClass = new stdClass();

        $stdClass->exito = $seBorro == 1 ? true : false;
        $stdClass->mensaje = "se borro el usuario";

        return json_encode($stdClass);
    }
    /*
    public static function modificarPDO(Usuario $usuario)
    {
        $objetoAccesoDato = AccesoDatos::accesoPDO();
        
        $consulta =$objetoAccesoDato->consultaPDO("UPDATE alumnos SET nombre = :nombre, apellido = :apellido, 
                                                        foto = :foto WHERE legajo = :legajo");
        
        $consulta->bindValue(':legajo', $alumno->getLegajo(), PDO::PARAM_INT);
        $consulta->bindValue(':nombre', $alumno->getNombre(), PDO::PARAM_STR);
        $consulta->bindValue(':apellido', $alumno->getApellido(), PDO::PARAM_STR);
        $consulta->bindValue(':foto', $alumno->getfoto(), PDO::PARAM_STR);

        return $consulta->execute();
    }

   */
    
}

?>