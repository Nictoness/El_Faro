
<?php
// Asegurar que las variables existan
$articulos = $articulos ?? [];
$categorias = $categorias ?? [];
$usuarios = $usuarios ?? [];

// Resto del código de mensajes...
$mensajeExito = $_SESSION['mensaje'] ?? $_SESSION['mensaje_contacto'] ?? $_SESSION['mensaje_registro'] ?? '';
$errorMsg = $_SESSION['error'] ?? $_SESSION['error_contacto'] ?? $_SESSION['error_registro'] ?? '';
unset($_SESSION['mensaje'], $_SESSION['error'], $_SESSION['mensaje_contacto'], $_SESSION['error_contacto'], $_SESSION['mensaje_registro'], $_SESSION['error_registro']);
?>

<?php
// views/index.php
// Recibe $articulos, $categorias, $usuarios desde el controlador
$mensajeExito = $_SESSION['mensaje'] ?? $_SESSION['mensaje_contacto'] ?? $_SESSION['mensaje_registro'] ?? '';
$errorMsg = $_SESSION['error'] ?? $_SESSION['error_contacto'] ?? $_SESSION['error_registro'] ?? '';
// Limpiar mensajes después de mostrarlos
unset($_SESSION['mensaje'], $_SESSION['error'], $_SESSION['mensaje_contacto'], $_SESSION['error_contacto'], $_SESSION['mensaje_registro'], $_SESSION['error_registro']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>El Faro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
<div class="bg-warning text-dark text-center py-2">Aviso: Nuevas noticias disponibles</div>

<header class="container d-flex justify-content-between align-items-center mt-3 header-centrado">
    <h1>El Faro <img src="assets/img/CM053_faro.jpg" height="50"></h1>
    <div id="reloj"></div>
</header>

<!-- Navbar -->

<!-- Contenido dinámico -->
<div class="container mt-4">
    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-body text-center">
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
            <?php foreach (array_slice($articulos, 1, 2) as $articulo): ?>
                <div class="card mb-3">
                    <div class="card-body">
                        <h5><?= htmlspecialchars($articulo['Titulo']) ?></h5>
                        <small><?= htmlspecialchars($articulo['CategoriaNombre']) ?></small>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<!-- Secciones por categoría -->
<?php foreach ($categorias as $categoria): ?>
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

<!-- Formularios -->
<section class="articulos">
    <h3>Agregar artículo</h3>
    <?php if ($errorMsg && strpos($errorMsg, 'artículo') !== false) echo "<div class='mensaje-error'>$errorMsg</div>"; ?>
    <form action="index.php?action=agregar_articulo" method="post">
        <input type="text" name="titulo" placeholder="Título" required>
        <textarea name="descripcion" rows="4" placeholder="Descripción" required></textarea>
        <select name="categoria" required>
            <option value="">Selecciona una categoría</option>
            <?php foreach ($categorias as $cat): ?>
                <option value="<?= $cat['ID_Categoria'] ?>"><?= htmlspecialchars($cat['Nombre']) ?></option>
            <?php endforeach; ?>
        </select>
        <select name="usuario" required>
            <option value="">Selecciona el autor (usuario)</option>
            <?php foreach ($usuarios as $usu): ?>
                <option value="<?= $usu['ID_Usuario'] ?>"><?= htmlspecialchars($usu['Nombre']) ?></option>
            <?php endforeach; ?>
        </select>
        <button type="submit">Agregar artículo</button>
    </form>
</section>

<!-- Formulario Contacto -->
<section class="contacto">
    <h3>Formulario de Contacto</h3>
    <?php if ($mensajeExito && strpos($mensajeExito, 'mensaje enviado') !== false) echo "<div class='mensaje-exito'>$mensajeExito</div>"; ?>
    <?php if ($errorMsg && strpos($errorMsg, 'contacto') !== false) echo "<div class='mensaje-error'>$errorMsg</div>"; ?>
    <form action="index.php?action=enviar_contacto" method="post">
        <input type="text" name="nombre" placeholder="Tu nombre" required>
        <textarea name="mensaje" rows="4" placeholder="Escribe tu mensaje" required></textarea>
        <button type="submit">Enviar mensaje</button>
    </form>
</section>

<!-- Formulario Registro -->
<section class="contacto" style="background-color: #f8f9fa;">
    <h3>Registro de Usuarios</h3>
    <?php if ($mensajeExito && strpos($mensajeExito, 'Registro exitoso') !== false) echo "<div class='mensaje-exito'>$mensajeExito</div>"; ?>
    <?php if ($errorMsg && strpos($errorMsg, 'registro') !== false) echo "<div class='mensaje-error'>$errorMsg</div>"; ?>
    <form action="index.php?action=registrar_usuario" method="post">
        <input type="text" name="reg_nombre" placeholder="Nombre completo" required>
        <input type="email" name="reg_email" placeholder="Correo electrónico" required>
        <input type="password" name="reg_password" placeholder="Contraseña" required>
        <input type="password" name="reg_confirm" placeholder="Confirmar contraseña" required>
        <button type="submit">Registrarse</button>
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
<script src="assets/js/script.js"></script>
</body>
</html>