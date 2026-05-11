<?php
require_once __DIR__ . '/Database.php';

class CategoriaModel {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function listar() {
        $sql = "SELECT ID_Categoria, Nombre FROM Categoria ORDER BY Nombre";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }
}