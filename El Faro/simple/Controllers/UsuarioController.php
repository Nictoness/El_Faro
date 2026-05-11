<?php
require_once __DIR__ . '/../models/UsuarioModel.php';

class UsuarioController {
    public function registrar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = trim($_POST['reg_nombre'] ?? '');
            $email = trim($_POST['reg_email'] ?? '');
            $password = $_POST['reg_password'] ?? '';
            $confirm = $_POST['reg_confirm'] ?? '';

            if (!$nombre || !$email || !$password) {
                $_SESSION['error_registro'] = "Todos los campos son obligatorios.";
            } elseif ($password !== $confirm) {
                $_SESSION['error_registro'] = "Las contraseñas no coinciden.";
            } else {
                $model = new UsuarioModel();
                if ($model->registrar($nombre, $email, $password)) {
                    $_SESSION['mensaje_registro'] = "Registro exitoso. ¡Bienvenido $nombre!";
                } else {
                    $_SESSION['error_registro'] = "El correo ya está registrado.";
                }
            }
        }
        header("Location: index.php");
        exit;
    }
}