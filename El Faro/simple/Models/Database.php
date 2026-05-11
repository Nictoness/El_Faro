<?php
require_once __DIR__ . '/../config/database.php';

class Database {
    private static $instance = null;
    private $pdo;

    private function __construct() {
        $dsn = "sqlite:" . DB_PATH;
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ];
        try {
            $this->pdo = new PDO($dsn, null, null, $options);
            $this->pdo->exec("PRAGMA foreign_keys = ON");
        } catch (PDOException $e) {
            die("Error de conexión: " . $e->getMessage());
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->pdo;
    }

    public function query($sql, $params = []) {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    public function execute($sql, $params = []) {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $this->pdo->lastInsertId();
    }

    // Simulación de procedimientos almacenados para SQLite
    public function callProcedure($procedureName, $params = []) {
        switch ($procedureName) {
            case 'ObtenerArticulosCompletos':
                $sql = "SELECT a.*, u.Nombre as Autor, c.Nombre as CategoriaNombre 
                        FROM Articulo a
                        JOIN Usuario u ON a.ID_Usuario = u.ID_Usuario
                        JOIN Categoria c ON a.ID_Categoria = c.ID_Categoria
                        ORDER BY a.ID_Articulo DESC";
                return $this->query($sql);
            case 'InsertarArticulo':
                $sql = "INSERT INTO Articulo (Titulo, Descripcion, ID_Usuario, ID_Categoria) VALUES (?, ?, ?, ?)";
                $this->execute($sql, $params);
                $lastId = $this->pdo->lastInsertId();
                $stmt = $this->pdo->prepare("SELECT ? as nuevo_id");
                $stmt->execute([$lastId]);
                return $stmt;
            default:
                throw new Exception("Procedimiento no definido: $procedureName");
        }
    }
}