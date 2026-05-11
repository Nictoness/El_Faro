<?php
require_once __DIR__ . '/Database.php';

class ArticuloModel {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function obtenerTodos() {
        $stmt = $this->db->callProcedure('ObtenerArticulosCompletos');
        return $stmt->fetchAll();
    }

    public function crear($titulo, $descripcion, $id_usuario, $id_categoria) {
    try {
        $stmt = $this->db->callProcedure('InsertarArticulo', [$titulo, $descripcion, $id_usuario, $id_categoria]);
        $row = $stmt->fetch();
        return $row['nuevo_id'];
    } catch (Exception $e) {
        die("Error al insertar: " . $e->getMessage());
    }
}
}