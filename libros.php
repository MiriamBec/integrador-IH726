<?php

include "db_conn.php";

$accion = $_POST['accion'] ?? $_GET['accion'] ?? '';

switch ($accion) {
  case 'buscar':

    $busqueda = $_GET['busqueda'] ?? '';
    $ordenColumna = $_GET['ordenColumna'] ?? 'id';
    $orden = $_GET['orden'] ?? 'asc';
    $pagina = (int)($_GET['pagina'] ?? 1);
    $resultados = (int)($_GET['resultados'] ?? 5);
    $compensar = ($pagina - 1) * $resultados;

    $condicionBusqueda = "WHERE (titulo LIKE ? OR autor LIKE ?)";
    $sql = "SELECT id, titulo, autor, paginas, leido FROM libros $condicionBusqueda ORDER BY $ordenColumna $orden LIMIT ?, ?";
    $stmt = $conn->prepare($sql);
    $paramBusqueda = "%$busqueda%";

    $stmt->bind_param('ssii', $paramBusqueda, $paramBusqueda, $compensar, $resultados);
    $stmt->execute();
    $result = $stmt->get_result();

    $resultados = [];
    while ($resultado = $result->fetch_assoc()) {
      $resultados[] = $resultado;
    }

    echo json_encode($resultados);
    break;

  case 'buscar-solo':
    $id = $_GET['id'];
    $sql = "SELECT id, titulo, autor, paginas, leido FROM libros WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $libro = $result->fetch_assoc();

    echo json_encode($libro);
    break;

  case 'crear':
    $titulo = $_POST['titulo'];
    $autor = $_POST['autor'];
    $paginas = $_POST['paginas'];

    $sql = "INSERT INTO libros (titulo, autor, paginas) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssi', $titulo, $autor, $paginas);
    $stmt->execute();

    echo json_encode(['success' => $stmt->affected_rows > 0]);
    break;

  case 'alternar-leido':
    $id = $_POST['id'];

    $sqlGet = "SELECT leido FROM libros WHERE id = ?";
    $stmtGet = $conn->prepare($sqlGet);
    $stmtGet->bind_param('i', $id);
    $stmtGet->execute();
    $result = $stmtGet->get_result();
    $libro = $result->fetch_assoc();

    if ($libro == null) {
      echo json_encode(['success' => false, 'error' => 'Libro no existe']);
      break;
    }

    if ($libro['leido'] == null) {
      $fecha = date('Y-m-d');
      $sql = "UPDATE libros SET leido = '$fecha' WHERE id = ?";
    } else {
      $sql = "UPDATE libros SET leido = null WHERE id = ?";
    }

    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id);
    $stmt->execute();

    echo json_encode(['success' => $stmt->affected_rows > 0]);
    break;

  case 'editar':
    $id = $_POST['id'];
    $titulo = $_POST['titulo'];
    $autor = $_POST['autor'];
    $paginas = $_POST['paginas'];

    $sql = "UPDATE libros SET titulo = ?, autor = ?, paginas = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssii', $titulo, $autor, $paginas, $id);
    $stmt->execute();

    echo json_encode(['success' => $stmt->affected_rows > 0]);
    break;

  case 'borrar':
    $id = $_POST['id'];
    $sql = "DELETE FROM libros WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    echo json_encode(['success' => $stmt->affected_rows > 0]);
    break;

  default:
    echo json_encode(['error' => 'accion invalida']);
    break;
}

$conn->close();
