let paginaActual = 1;
const resultadosPorPagina = 15;
const ordenActual = { columna: "id", orden: "asc" };

function cargarDatos() {
  $.ajax({
    url: "libros.php?accion=buscar",
    type: "GET",
    data: {
      pagina: paginaActual,
      resultados: resultadosPorPagina,
      ordenColumna: ordenActual.columna,
      orden: ordenActual.orden,
      busqueda: $("#busqueda").val(),
    },
    success: function (datos) {
      let libros = JSON.parse(datos);
      let html = "";
      libros.forEach((libro) => {
        html += `<tr>
                    <td id="td">${libro.titulo}</td>
                    <td>${libro.autor}</td>
                    <td>${libro.paginas}</td>
                    <td>${libro.leido ?? "No"}</td>
                    <td class="acciones">
                      <button class="btn btn-leido" onclick="alternarLeido(${
                        libro.id
                      })">Leído</button>
                      <button class="btn btn-editar" onclick="formularioDeEditar(${
                        libro.id
                      })">Editar</button>
                      <button class="btn btn-borrar" onclick="borrarLibro(${
                        libro.id
                      })">Borrar</button>
                    </td>
                  </tr>
                  `;
      });
      $("#datos-libros").html(html);

      $("#libros-leidos").html(
        libros.filter((libro) => libro.leido !== null).length
      );
      $("#libros-total").html(libros.length);
    },
  });
}

function ordenarTabla(columna) {
  if (ordenActual.columna === columna) {
    ordenActual.orden = ordenActual.orden === "asc" ? "desc" : "asc";
  } else {
    ordenActual.columna = columna;
    ordenActual.orden = "asc";
  }
  // Animación al ordenar la tabla
  $("#datos-libros").fadeOut(20);
  cargarDatos();
  $("#datos-libros").fadeIn(2000);
}

function proximaPagina() {
  paginaActual++;
  cargarDatos();
}

function paginaPrevia() {
  if (paginaActual > 1) {
    paginaActual--;
    cargarDatos();
  }
}

function formularioDeCreacion() {
  $("#id-libro").val("");
  $("#titulo-libro").val("");
  $("#autor-libro").val("");
  $("#paginas-libro").val("");

  $("#labelModalLibro").text("Agregar libro");

  $("#modalLibro").modal("show");
}

function formularioDeEditar(idLibro) {
  $.ajax({
    url: "libros.php?accion=buscar-solo",
    type: "GET",
    data: { id: idLibro },
    success: function (data) {
      const libro = JSON.parse(data);

      $("#id-libro").val(libro.id);
      $("#titulo-libro").val(libro.titulo);
      $("#paginas-libro").val(libro.paginas);
      $("#autor-libro").val(libro.autor);

      $("#labelModalLibro").text("Editar Libro");

      $("#modalLibro").modal("show");
    },
  });
}

function alternarLeido(idLibro) {
  $.ajax({
    url: "libros.php?accion=alternar-leido",
    type: "POST",
    data: { id: idLibro },
    success: function (res) {
      cargarDatos();
    },
  });
}

function guardarLibro() {
  const idLibro = $("#id-libro").val();
  const titulo = $("#titulo-libro").val();
  const autor = $("#autor-libro").val();
  const paginas = $("#paginas-libro").val();

  const accion = idLibro ? "editar" : "crear";

  $.ajax({
    url: `libros.php?accion=${accion}`,
    type: "POST",
    data: {
      id: idLibro,
      titulo,
      autor,
      paginas,
    },
    success: function (res) {
      const resultado = JSON.parse(res);

      if (resultado.success) {
        $("#modalLibro").modal("hide");
        cargarDatos();
        alertaLibroAgregado();
      } else {
        alert("Error al guardar los datos: " + resultado.message);
      }
    },
    error: function () {
      alert("Occurio un error al intentar guardar los datos.");
    },
  });
}

function borrarLibro(idLibro) {
  $.ajax({
    url: "libros.php?accion=borrar",
    type: "POST",
    data: { id: idLibro },
    success: function (data) {
      cargarDatos();
      $("#datos-libros").fadeOut(500, function () {
        alertaMensajeBorrado();
      });
      $("#datos-libros").fadeIn(1000);
    },
  });
}

$(document).ready(function () {
  cargarDatos();
  $("#busqueda").keyup(cargarDatos);
});

// Alertas
function alertaLibroAgregado() {
  Swal.fire({
    title: "Libro guardado!",
    text: "El libro ha sido guardado correctamente",
    icon: "success",
  });
}

function alertaMensajeBorrado() {
  Swal.fire({
    title: "Libro Borrado!",
    text: "El libro ha sido borrado de la tabla de libros",
    icon: "success",
  });
}
