<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../conexao.php';

$con->set_charset("utf8");

$json = json_decode(file_get_contents('php://input'), true);
if (!is_array($json)) {
  $json = $_POST;
}

$id = isset($json['idsubject']) ? (int)$json['idsubject'] : null;
$ids = isset($json['ids']) && is_array($json['ids']) ? $json['ids'] : null; // array de ids

try {
  if ($id) {
    $stmt = $con->prepare('DELETE FROM subject WHERE idsubject = ?');
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $affected = $stmt->affected_rows;
    echo json_encode(['success' => true, 'message' => 'Registro removido', 'affected_rows' => $affected]);
    $stmt->close();
    $con->close();
    exit;
  }

  if ($ids) {
    // deletar múltiplos ids
    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    $types = str_repeat('i', count($ids));
    $sql = "DELETE FROM subject WHERE idsubject IN ($placeholders)";
    $stmt = $con->prepare($sql);
    if (!$stmt) throw new Exception('Erro ao preparar exclusão: ' . $con->error);
    $refs = [];
    foreach ($ids as $k => $v) {
      $ids[$k] = (int)$v;
      $refs[] = &$ids[$k];
    }
    array_unshift($refs, $types);
    call_user_func_array([$stmt, 'bind_param'], $refs);
    $stmt->execute();
    $affected = $stmt->affected_rows;
    echo json_encode(['success' => true, 'message' => 'Registros removidos', 'affected_rows' => $affected]);
    $stmt->close();
    $con->close();
    exit;
  }

  echo json_encode(['success' => false, 'message' => 'Informe idsubject (int) ou ids (array) para exclusão.']);
  $con->close();
} catch (Exception $e) {
  echo json_encode(['success' => false, 'message' => $e->getMessage()]);
  if (isset($stmt) && $stmt) $stmt->close();
  $con->close();
}

?>
