document.addEventListener("DOMContentLoaded", function () {

  /* Reloj en tiempo real */

  function actualizarReloj() {
    const ahora = new Date();

    // Opciones para formato de fecha
    const opciones = {
      weekday: 'long',
      year: 'numeric',
      month: 'long',
      day: 'numeric'
    };

    const fecha = ahora.toLocaleDateString('es-CL', opciones);
    const hora = ahora.toLocaleTimeString('es-CL');

    // Insertar en el HTML
    document.getElementById('reloj').textContent = `${fecha} - ${hora}`;
  }

  // Ejecutar cada segundo
  setInterval(actualizarReloj, 1000);

  // Ejecutar inmediatamente al cargar
  actualizarReloj();

});


/* 🔹 variables de articulos globales */

const formArticulo = document.getElementById('formArticulo');
const listaArticulos = document.getElementById('listaArticulos');
const contador = document.getElementById('contador');

// Array donde se guardan los artículos
let articulos = [];



/* Agregar articulos */

formArticulo.addEventListener('submit', function (e) {
  e.preventDefault(); // evita recargar la página

  const titulo = document.getElementById('titulo').value;
  const descripcion = document.getElementById('descripcion').value;

  // Crear objeto artículo
  const nuevoArticulo = { titulo, descripcion };

  // Guardar en array
  articulos.push(nuevoArticulo);

  // Actualizar vista
  renderArticulos();

  // Limpiar formulario
  formArticulo.reset();
});


/* 🔹 Mostrar articulos */
function renderArticulos() {
  // Limpiar lista antes de volver a dibujar
  listaArticulos.innerHTML = '';

  // Recorrer artículos
  articulos.forEach(a => {
    const div = document.createElement('div');

    // Insertar contenido
    div.innerHTML = `
      <h3>${a.titulo}</h3>
      <p>${a.descripcion}</p>
    `;

    listaArticulos.appendChild(div);
  });

  // Actualizar contador
  contador.textContent = articulos.length;
}


/* Formulario de contacto */

const formContacto = document.getElementById('formContacto');
const respuestaDiv = document.getElementById('respuestaContacto');

// Verificar que el formulario exista
if (formContacto) {

  formContacto.addEventListener('submit', function (e) {
    e.preventDefault();

    const nombre = document.getElementById('nombreContacto').value.trim();
    const mensaje = document.getElementById('mensajeContacto').value.trim();

    /* Validacion */
    if (nombre === '' || mensaje === '') {
      respuestaDiv.innerHTML = `
        <div style="background:#ffebee;color:#c62828;padding:10px;">
          Completa todos los campos
        </div>
      `;
      return;
    }

    /* mensaje de exito */
    respuestaDiv.innerHTML = `
      <div style="background:#e8f5e9;color:#2e7d32;padding:10px;">
        Gracias ${nombre}, mensaje enviado.
      </div>
    `;

    // Limpiar formulario
    formContacto.reset();

    // Borrar mensaje después de 3 segundos
    setTimeout(() => {
      respuestaDiv.innerHTML = '';
    }, 3000);

  });

}