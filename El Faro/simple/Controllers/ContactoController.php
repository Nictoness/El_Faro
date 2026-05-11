<?php
require_once __DIR__ . '/../models/ContactoModel.php';

class ContactoController {
    public function enviar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = trim($_POST['nombre'] ?? '');
            $mensaje = trim($_POST['mensaje'] ?? '');
            if ($nombre && $mensaje) {
                $model = new ContactoModel();
                $model->guardar($nombre, $mensaje);
                $_SESSION['mensaje_contacto'] = "Gracias $nombre, mensaje enviado.";
            } else {
                $_SESSION['error_contacto'] = "Complete todos los campos del contacto.";
            }
        }
        header("Location: index.php");
        exit;
    }
}