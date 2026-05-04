<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

$db = new SQLite3('El_Faro.db');
$db->busyTimeout(10000);
$db->exec('PRAGMA journal_mode=WAL');

$mensajeEnviado = "";
$articuloMensaje = "";
$registroMensaje = "";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["contacto_submit"])) {
    $nombre = trim($_POST["nombre"] ?? "");
    $mensaje = trim($_POST["mensaje"] ?? "");
    if ($nombre !== "" && $mensaje !== "") {
        $stmt = $db->prepare('INSERT INTO Contacto (Nombre, Mensaje) VALUES (:nombre, :mensaje)');
        $stmt->bindValue(':nombre', $nombre, SQLITE3_TEXT);
        $stmt->bindValue(':mensaje', $mensaje, SQLITE3_TEXT);
        $stmt->execute();
        $mensajeEnviado = "Gracias $nombre, mensaje enviado.";
    } else {
        $mensajeEnviado = "Completa todos los campos del contacto.";
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["registro_submit"])) {
    $nombre = trim($_POST["reg_nombre"] ?? "");
    $email = trim($_POST["reg_email"] ?? "");
    $password = trim($_POST["reg_password"] ?? "");
    $confirm = trim($_POST["reg_confirm"] ?? "");

    if ($nombre === "" || $email === "" || $password === "") {
        $registroMensaje = "Todos los campos son obligatorios.";
    } elseif ($password !== $confirm) {
        $registroMensaje = "Las contraseñas no coinciden.";
    } else {
        $check = $db->prepare('SELECT ID_Usuario FROM Usuario WHERE Email = :email');
        $check->bindValue(':email', $email, SQLITE3_TEXT);
        $existe = $check->execute()->fetchArray();
        if ($existe) {
            $registroMensaje = "El correo ya está registrado.";
        } else {
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $db->prepare('INSERT INTO Usuario (Nombre, Email, Contraseña) VALUES (:nom, :email, :pass)');
            $stmt->bindValue(':nom', $nombre, SQLITE3_TEXT);
            $stmt->bindValue(':email', $email, SQLITE3_TEXT);
            $stmt->bindValue(':pass', $hashed, SQLITE3_TEXT);
            $stmt->execute();
            $registroMensaje = "Registro exitoso. ¡Bienvenido $nombre!";
        }
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["articulo_submit"])) {
    $titulo = trim($_POST["titulo"] ?? "");
    $descripcion = trim($_POST["descripcion"] ?? "");
    $id_categoria = intval($_POST["categoria"] ?? 0);
    $id_usuario = intval($_POST["usuario"] ?? 0);

    if ($titulo !== "" && $descripcion !== "" && $id_categoria > 0 && $id_usuario > 0) {
        $stmt = $db->prepare('INSERT INTO Articulo (Titulo, Descripcion, ID_Usuario, ID_Categoria) VALUES (:tit, :desc, :id_u, :id_c)');
        $stmt->bindValue(':tit', $titulo, SQLITE3_TEXT);
        $stmt->bindValue(':desc', $descripcion, SQLITE3_TEXT);
        $stmt->bindValue(':id_u', $id_usuario, SQLITE3_INTEGER);
        $stmt->bindValue(':id_c', $id_categoria, SQLITE3_INTEGER);
        $stmt->execute();
        $articuloMensaje = "Artículo agregado correctamente.";
    } else {
        $articuloMensaje = "Completa todos los campos del artículo.";
    }
}

$articulos = [];
$resultado = $db->query('
    SELECT a.*, u.Nombre as Autor, c.Nombre as CategoriaNombre 
    FROM Articulo a
    JOIN Usuario u ON a.ID_Usuario = u.ID_Usuario
    JOIN Categoria c ON a.ID_Categoria = c.ID_Categoria
    ORDER BY a.ID_Articulo DESC
');
while ($fila = $resultado->fetchArray(SQLITE3_ASSOC)) {
    $articulos[] = $fila;
}

$categoriasLista = [];
$catRes = $db->query('SELECT ID_Categoria, Nombre FROM Categoria ORDER BY Nombre');
while ($cat = $catRes->fetchArray(SQLITE3_ASSOC)) {
    $categoriasLista[] = $cat;
}

$usuariosLista = [];
$usuRes = $db->query('SELECT ID_Usuario, Nombre FROM Usuario ORDER BY Nombre');
while ($usu = $usuRes->fetchArray(SQLITE3_ASSOC)) {
    $usuariosLista[] = $usu;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>El Faro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="bg-warning text-dark text-center py-2">Aviso: Nuevas noticias disponibles</div>
<header class="container d-flex justify-content-between align-items-center mt-3">
    <h1>El Faro <img src="CM053_faro.jpg" height="50"></h1>
    <div id="reloj"></div>
</header>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark mt-3">
    <div class="container">
        <a class="navbar-brand" href="#">El Faro</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="menu">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="#">Inicio</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Política</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Deportes</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Contacto</a></li>
            </ul>
        </div>
    </div>
</nav>
<div class="container mt-4">
    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-body">
                    <?php if (!empty($articulos)): ?>
                        <h3><?= htmlspecialchars($articulos[0]['Titulo']) ?></h3>
                        <p><strong>Categoría:</strong> <?= htmlspecialchars($articulos[0]['CategoriaNombre']) ?></p>
                        <p><strong>Autor:</strong> <?= htmlspecialchars($articulos[0]['Autor']) ?></p>
                        <p><?= nl2br(htmlspecialchars($articulos[0]['Descripcion'])) ?></p>
                    <?php else: ?>
                        <h3>Artículo destacado</h3>
                        <p>No hay artículos disponibles.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <?php foreach ($articulos as $i => $articulo): ?>
                <?php if ($i > 0 && $i < 3): ?>
                    <div class="card mb-3">
                        <div class="card-body">
                            <h5><?= htmlspecialchars($articulo['Titulo']) ?></h5>
                            <small><?= htmlspecialchars($articulo['CategoriaNombre']) ?></small>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<?php foreach ($categoriasLista as $categoria): ?>
    <h3 class="titulo_seccion"><?= htmlspecialchars($categoria['Nombre']) ?></h3>
    <div class="contenedor">
        <?php foreach ($articulos as $articulo): ?>
            <?php if ($articulo['ID_Categoria'] == $categoria['ID_Categoria']): ?>
                <div class="articulo">
                    <h4><?= htmlspecialchars($articulo['Titulo']) ?></h4>
                    <h5><?= htmlspecialchars($articulo['CategoriaNombre']) ?></h5>
                    <p><em>Por <?= htmlspecialchars($articulo['Autor']) ?></em></p>
                    <p><?= nl2br(htmlspecialchars($articulo['Descripcion'])) ?></p>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
<?php endforeach; ?>

<section class="contacto" style="background-color: #f8f9fa;">
    <h3>Registro de Usuarios</h3>
    <?php if ($registroMensaje): ?>
        <div class="<?= (strpos($registroMensaje, 'exitoso') !== false) ? 'mensaje-exito' : 'mensaje-error' ?>">
            <?= htmlspecialchars($registroMensaje) ?>
        </div>
    <?php endif; ?>
    <form method="post">
        <input type="text" name="reg_nombre" placeholder="Nombre completo" required>
        <input type="email" name="reg_email" placeholder="Correo electrónico" required>
        <input type="password" name="reg_password" placeholder="Contraseña" required>
        <input type="password" name="reg_confirm" placeholder="Confirmar contraseña" required>
        <button type="submit" name="registro_submit">Registrarse</button>
    </form>
</section>

<section class="articulos">
    <h3>Agregar artículo</h3>
    <?php if ($articuloMensaje): ?>
        <div class="mensaje-info"><?= htmlspecialchars($articuloMensaje) ?></div>
    <?php endif; ?>
    <form method="post">
        <input type="text" name="titulo" placeholder="Título" required>
        <textarea name="descripcion" rows="4" placeholder="Descripción" required></textarea>
        <select name="categoria" required>
            <option value="">Selecciona una categoría</option>
            <?php foreach ($categoriasLista as $cat): ?>
                <option value="<?= $cat['ID_Categoria'] ?>"><?= htmlspecialchars($cat['Nombre']) ?></option>
            <?php endforeach; ?>
        </select>
        <select name="usuario" required>
            <option value="">Selecciona el autor (usuario)</option>
            <?php foreach ($usuariosLista as $usu): ?>
                <option value="<?= $usu['ID_Usuario'] ?>"><?= htmlspecialchars($usu['Nombre']) ?></option>
            <?php endforeach; ?>
        </select>
        <button type="submit" name="articulo_submit">Agregar artículo</button>
    </form>
</section>

<section class="contacto">
    <h3>Formulario de Contacto</h3>
    <?php if ($mensajeEnviado): ?>
        <div class="<?= (strpos($mensajeEnviado, 'Gracias') !== false) ? 'mensaje-exito' : 'mensaje-error' ?>">
            <?= htmlspecialchars($mensajeEnviado) ?>
        </div>
    <?php endif; ?>
    <form method="post">
        <input type="text" name="nombre" placeholder="Tu nombre" required>
        <textarea name="mensaje" rows="4" placeholder="Escribe tu mensaje" required></textarea>
        <button type="submit" name="contacto_submit">Enviar mensaje</button>
    </form>
</section>

<footer class="bg-dark text-white mt-5 p-4">
    <div class="container">
        <div class="row">
            <div class="col-md-4"><h5>El Faro</h5><p>Noticias actualizadas todos los días.</p></div>
            <div class="col-md-4"><h5>Secciones</h5><ul class="list-unstyled"><li>Inicio</li><li>Política</li><li>Deportes</li></ul></div>
            <div class="col-md-4"><h5>Contacto</h5><p>correo@elfaro.cl</p></div>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="script.js"></script>
</body>
</html>