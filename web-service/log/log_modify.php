<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../conexao.php';
$con->set_charset("utf8");

$json = json_decode(file_get_contents('php://input'), true);
if (!is_array($json)) $json = $_POST;

$id = isset($json['idlog']) ? (int)$json['idlog'] : null;
$success = isset($json['success']) ? $json['success'] : null;
$answerSelection = isset($json['aswer-selection']) ? $json['aswer-selection'] : (isset($json['aswer_selection']) ? $json['aswer_selection'] : null);
$date = isset($json['date']) ? $json['date'] : null;
$idquestion = isset($json['idquestion']) ? (int)$json['idquestion'] : null;

if (!$id) { echo json_encode(['success' => false, 'message' => 'idlog é obrigatório para modificar.']); $con->close(); exit; }

$sets = [];
$params = [];
$types = '';
if ($success !== null) { $sets[] = '`success` = ?'; $params[] = $success; $types .= 's'; }
if ($answerSelection !== null) { $sets[] = '`aswer-selection` = ?'; $params[] = $answerSelection; $types .= 's'; }
if ($date !== null) { $sets[] = '`date` = ?'; $params[] = $date; $types .= 's'; }
if ($idquestion !== null) { $sets[] = 'idquestion = ?'; $params[] = $idquestion; $types .= 'i'; }

if (!$sets) { echo json_encode(['success' => false, 'message' => 'Nenhum campo para atualizar.']); $con->close(); exit; }

$sql = 'UPDATE `log` SET ' . implode(', ', $sets) . ' WHERE idlog = ?';
$params[] = $id; $types .= 'i';

$stmt = $con->prepare($sql);
if (!$stmt) { echo json_encode(['success' => false, 'message' => 'Erro ao preparar atualização: ' . $con->error]); $con->close(); exit; }

$refs = [];
foreach ($params as $k => $v) $refs[] = &$params[$k];
array_unshift($refs, $types);
call_user_func_array([$stmt, 'bind_param'], $refs);

if ($stmt->execute()) {
  echo json_encode(['success' => true, 'message' => 'Registro atualizado', 'affected_rows' => $stmt->affected_rows]);
} else {
  echo json_encode(['success' => false, 'message' => 'Erro ao executar atualização: ' . $stmt->error]);
}

$stmt->close(); $con->close();

?>
