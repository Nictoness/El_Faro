<?php
class Articulo {
    private $id;
    private $titulo;
    private $descripcion;
    private $fecha;
    private $categoria;

    public function __construct($id, $titulo, $descripcion, $fecha, $categoria) {
        $this->id = $id;
        $this->titulo = $titulo;
        $this->descripcion = $descripcion;
        $this->fecha = $fecha;
        $this->categoria = $categoria;
    }

    public function getId() {
        return $this->id;
    }

    public function getCategoria() {
        return $this->categoria;
    }
    
    public function getTitulo() {
        return $this->titulo;
    }

    public function getDescripcion() {
        return $this->descripcion;
    }

    public function getFecha() {
        return $this->fecha;
    }
}
?>