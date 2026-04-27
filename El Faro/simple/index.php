<?php
// Datos simulados
$articulos = [
    ["titulo" => "Conductor evita encerrona en Quinta Normal", "categoria" => "Noticias generales", "descripcion" => "Maniobra arriesgada..."],
    ["titulo" => "Alza de combustibles afecta panaderías", "categoria" => "Noticias generales", "descripcion" => "Subir el pan no es fácil..."],
    ["titulo" => "Fondo Comunidad Activa 2026", "categoria" => "Noticias generales", "descripcion" => "Proyectos sociales..."],

    ["titulo" => "Colo Colo gana de visita", "categoria" => "Deportes", "descripcion" => "Victoria importante..."],
    ["titulo" => "Tabla de posiciones", "categoria" => "Deportes", "descripcion" => "Resultados de la fecha..."],
    ["titulo" => "Derrota de la U", "categoria" => "Deportes", "descripcion" => "Análisis del partido..."],

    ["titulo" => "Banco Central mantiene tasa", "categoria" => "Negocios", "descripcion" => "Impacto en inflación..."],
    ["titulo" => "Proyecto de mitigación", "categoria" => "Negocios", "descripcion" => "Medidas por combustibles..."],
    ["titulo" => "Acuerdo Mercosur - UE", "categoria" => "Negocios", "descripcion" => "Impacto económico..."]
];

//  Procesar formulario de contacto
$mensajeEnviado = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = trim($_POST["nombre"] ?? "");
    $mensaje = trim($_POST["mensaje"] ?? "");

    if ($nombre !== "" && $mensaje !== "") {
        $mensajeEnviado = "Gracias $nombre, mensaje enviado.";
    } else {
        $mensajeEnviado = "Completa todos los campos.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>El Faro</title>

    <!--bootstrap-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>

<body>

<!--Aviso-->
<div class="bg-warning text-dark text-center py-2">
   Aviso: Nuevas noticias disponibles
</div>

<!--Header-->
<header class="container d-flex justify-content-between align-items-center mt-3">
  <h1>El Faro <img src="CM053_faro.jpg" height="50"></h1>
  <div id="reloj"></div>
</header>

<!--Navbar-->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark mt-3">
  <div class="container">

    <a class="navbar-brand" href="#">El Faro</a>

    <button 
      class="navbar-toggler" 
      type="button" 
      data-bs-toggle="collapse" 
      data-bs-target="#menu"
      aria-controls="menu"
      aria-expanded="false">
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

<!--Contenido-->
<div class="container mt-4">
  <div class="row">

    <!--Destacado-->
    <div class="col-md-8">
      <div class="card mb-4">
        <div class="card-body">
          <h3><?= $articulos[0]['titulo']; ?></h3>
          <p><?= $articulos[0]['descripcion']; ?></p>
        </div>
      </div>
    </div>

    <!--Secundarios-->
    <div class="col-md-4">
      <?php foreach($articulos as $i => $a): ?>
        <?php if($i > 0 && $i < 3): ?>
        <div class="card mb-3">
          <div class="card-body">
            <h5><?= $a['titulo']; ?></h5>
          </div>
        </div>
        <?php endif; ?>
      <?php endforeach; ?>
    </div>

  </div>
</div>

<!--Secciones por categoría-->
<?php 
$categorias = ["Noticias generales", "Deportes", "Negocios"];
foreach($categorias as $cat): 
?>
<h3 class="titulo_seccion"><?= $cat ?></h3>

<div class="contenedor">
  <?php foreach($articulos as $a): ?>
    <?php if($a['categoria'] === $cat): ?>
    <div class="articulo">
        <h4><?= $a['titulo']; ?></h4>
        <h5><?= $a['categoria']; ?></h5>
        <p><?= $a['descripcion']; ?></p>
    </div>
    <?php endif; ?>
  <?php endforeach; ?>
</div>
<?php endforeach; ?>

<!--Artículos dinámicos(JS)-->
<section class="articulos">
  <h3>Artículos (<span id="contador">0</span>)</h3>

  <form id="formArticulo">
    <input type="text" id="titulo" placeholder="Título" required>
    <textarea id="descripcion" rows="4" placeholder="Descripción" required></textarea>
    <button type="submit">Agregar artículo</button>
  </form>

  <div id="listaArticulos"></div>
</section>

<!--Contacto-->
<section class="contacto">
    <h3>Formulario de Contacto</h3>

   <form id="formContacto">
    <input type="text" id="nombreContacto" name="nombre" placeholder="Tu nombre" required>
    <textarea id="mensajeContacto" name="mensaje" rows="4" placeholder="Escribe tu mensaje" required></textarea>
    <button type="submit">Enviar mensaje</button>
</form>

   <div id="respuestaContacto">
    <?php if ($mensajeEnviado): ?>
        <?= $mensajeEnviado ?>
    <?php endif; ?>
</div>
</section>

<!--Footer-->
<footer class="bg-dark text-white mt-5 p-4">
  <div class="container">
    <div class="row">

      <div class="col-md-4">
        <h5>El Faro</h5>
        <p>Noticias actualizadas todos los días.</p>
      </div>

      <div class="col-md-4">
        <h5>Secciones</h5>
        <ul class="list-unstyled">
          <li>Inicio</li>
          <li>Política</li>
          <li>Deportes</li>
        </ul>
      </div>

      <div class="col-md-4">
        <h5>Contacto</h5>
        <p>correo@elfaro.cl</p>
      </div>

    </div>
  </div>
</footer>

<!--Scripts-->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="script.js"></script>

</body>
</html>