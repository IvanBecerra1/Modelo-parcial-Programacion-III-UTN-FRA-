<?php 

namespace User;

interface IMB {
    function modificar();
    static function eliminar(int $idEliminar);
}

?>