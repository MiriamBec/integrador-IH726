<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Hogar</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" />

  <script defer src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <script defer src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <script defer src="script.js"></script>
  <script defer src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

</head>

<body>
  <style>
    <?php include "styles.css" ?>
  </style>
  <div class="container">
    <img src="img/logo.png" alt="logo de biblioteca virtual">

    <div class="mt-5 cabecera">
      <div class="cabecera-buscador">
        <h1>Mis libros</h1>
        <input type="text" id="busqueda" class="form-control buscador" placeholder="Buscar libro">
      </div>


      <div class="informacion-general">
        <div class="acciones-tabla">
          <h4>Libros leidos: <span id="libros-leidos"></span> de <span id="libros-total"></span></h4>
        </div>
        <div class="cambiar-pagina">
          <button class="btn btn-agregar" onclick="formularioDeCreacion()">Agregar Libro</button>
          <button class="btn btn-paginas" onclick="paginaPrevia()">&lt;</button>
          <button class="btn btn-paginas" onclick=" proximaPagina()">&gt;</button>
        </div>
      </div>

    </div>


    <table id="tabla-libros" class="table table-striped">
      <!-- cabecera de table -->
      <thead>
        <tr>
          <th data-column="titulo" onclick="ordenarTabla('titulo')">Título</th>
          <th data-column="autor" onclick="ordenarTabla('autor')">Autor</th>
          <th data-column="paginas" onclick="ordenarTabla('paginas')">Número de páginas</th>
          <th data-column="leido" onclick="ordenarTabla('leido')">Leído</th>
          <th>Acciones</th>
        </tr>
      </thead>

      <!-- cuerpo de tabla -->
      <tbody id="datos-libros">
        <!-- Aqui se cargaran los datos dinamicamente -->
      </tbody>
    </table>


  </div>

  <!-- modal de creacion y editar libros -->
  <div class="modal fade" id="modalLibro" tabindex="-1" role="dialog" aria-labelledby="labelModalLibro" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="labelModalLibro">Libro</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <!-- id -->
          <input type="hidden" id="id-libro">
          <!-- titulo -->
          <div class="form-group">
            <label for="titulo-libro" class="col-form-label">Titulo</label>
            <input type="text" id="titulo-libro" class="form-control" placeholder="Titulo">
          </div>
          <!-- Autor -->
          <div class="form-group">
            <label for="autor-libro" class="col-form-label">Autor</label>
            <input type="text" id="autor-libro" class="form-control" placeholder="Autor">
          </div>
          <!-- paginas -->
          <div class="form-group">
            <label for="paginas-libro" class="col-form-label">Páginas</label>
            <input type="number" id="paginas-libro" class="form-control" placeholder="Número de páginas">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-cancelar" data-dismiss="modal">Cancelar</button>
          <button type="button" class="btn btn-guardar" onclick="guardarLibro()">Guardar</button>
        </div>
      </div>
    </div>
  </div>
</body>

</html>