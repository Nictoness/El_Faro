<?php
require_once __DIR__ . '/Database.php';

class UsuarioModel {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function registrar($nombre, $email, $password) {
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $checkSql = "SELECT ID_Usuario FROM Usuario WHERE Email = ?";
        $checkStmt = $this->db->query($checkSql, [$email]);
        if ($checkStmt->fetch()) {
            return false; // email ya existe
        }
        $sql = "INSERT INTO Usuario (Nombre, Email, Contraseña) VALUES (?, ?, ?)";
        $this->db->execute($sql, [$nombre, $email, $hashed]);
        return true;
    }

    public function listar() {
        $sql = "SELECT ID_Usuario, Nombre FROM Usuario ORDER BY Nombre";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }
}