<?php
require_once __DIR__ . '/../models/ArticuloModel.php';
require_once __DIR__ . '/../models/CategoriaModel.php';
require_once __DIR__ . '/../models/UsuarioModel.php';

class ArticuloController {
    public function index() {
        $articuloModel = new ArticuloModel();
        $categoriaModel = new CategoriaModel();
        $usuarioModel = new UsuarioModel();

        $articulos = $articuloModel->obtenerTodos();
        $categorias = $categoriaModel->listar();
        $usuarios = $usuarioModel->listar();

        include __DIR__ . '/../views/index.php';
    }

    public function agregar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $titulo = trim($_POST['titulo'] ?? '');
            $descripcion = trim($_POST['descripcion'] ?? '');
            $id_categoria = (int)($_POST['categoria'] ?? 0);
            $id_usuario = (int)($_POST['usuario'] ?? 0);
            if ($titulo && $descripcion && $id_categoria > 0 && $id_usuario > 0) {
                $model = new ArticuloModel();
                $model->crear($titulo, $descripcion, $id_usuario, $id_categoria);
                $_SESSION['mensaje'] = "Artículo agregado correctamente.";
            } else {
                $_SESSION['error'] = "Complete todos los campos del artículo.";
            }
        }
        header("Location: index.php");
        exit;
    }
}