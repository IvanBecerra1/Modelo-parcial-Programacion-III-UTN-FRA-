<?php

interface ICRUD {
    
    static function traerTodos();
    function agregar();
    function modificar();
    static function eliminar($id);
}
?>