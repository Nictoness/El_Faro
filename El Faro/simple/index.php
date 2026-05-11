<?php
session_start();
require_once 'controllers/ArticuloController.php';
require_once 'controllers/ContactoController.php';
require_once 'controllers/UsuarioController.php';

$action = $_GET['action'] ?? 'index';

switch ($action) {
    case 'agregar_articulo':
        $controller = new ArticuloController();
        $controller->agregar();
        break;
    case 'enviar_contacto':
        $controller = new ContactoController();
        $controller->enviar();
        break;
    case 'registrar_usuario':
        $controller = new UsuarioController();
        $controller->registrar();
        break;
    default:
        $controller = new ArticuloController();
        $controller->index();
        break;
}