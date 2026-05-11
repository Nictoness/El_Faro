<?php
require_once __DIR__ . '/Database.php';

class ContactoModel {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function guardar($nombre, $mensaje) {
        $sql = "INSERT INTO Contacto (Nombre, Mensaje, Fecha) VALUES (?, ?, datetime('now'))";
        return $this->db->execute($sql, [$nombre, $mensaje]);
    }
}