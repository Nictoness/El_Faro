<?php
class Mensaje {
    private $id;
    private $nombre;        
    private $mensaje;

    public function __construct($id, $nombre,$mensaje) {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->mensaje = $mensaje;
    }

    public function getId() {
        return $this->id;
    }

    public function getNombre() {
        return $this->nombre;
    }

    public function getMensaje() {
        return $this->mensaje;
    }
}
?>