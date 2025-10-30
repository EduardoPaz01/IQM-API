<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../conexao.php';
$con->set_charset("utf8");

$json = json_decode(file_get_contents('php://input'), true);
if (!is_array($json)) $json = $_POST;

$id = isset($json['idquestion']) ? (int)$json['idquestion'] : null;
$statement = isset($json['statement']) ? $json['statement'] : null;
$answer = isset($json['answer']) ? $json['answer'] : null;
$topic = isset($json['topic']) ? $json['topic'] : null;
$tip = isset($json['tip']) ? $json['tip'] : null;
$options = isset($json['options']) ? $json['options'] : null;
$blacklist = isset($json['black-list']) ? $json['black-list'] : (isset($json['black_list']) ? $json['black_list'] : null);
$idsubject = isset($json['idsubject']) ? (int)$json['idsubject'] : null;

if (!$id) {
    echo json_encode(['success' => false, 'message' => 'idquestion é obrigatório para modificar.']);
    $con->close();
    exit;
}

$sets = [];
$params = [];
$types = '';

if ($statement !== null) { $sets[] = 'statement = ?'; $params[] = $statement; $types .= 's'; }
if ($answer !== null) { $sets[] = 'answer = ?'; $params[] = $answer; $types .= 's'; }
if ($topic !== null) { $sets[] = 'topic = ?'; $params[] = $topic; $types .= 's'; }
if ($tip !== null) { $sets[] = 'tip = ?'; $params[] = $tip; $types .= 's'; }
if ($options !== null) { $sets[] = '`options` = ?'; $params[] = $options; $types .= 's'; }
if ($blacklist !== null) { $sets[] = '`black-list` = ?'; $params[] = $blacklist; $types .= 's'; }
if ($idsubject !== null) { $sets[] = 'idsubject = ?'; $params[] = $idsubject; $types .= 'i'; }

if (!$sets) {
    echo json_encode(['success' => false, 'message' => 'Nenhum campo para atualizar.']);
    $con->close();
    exit;
}

$sql = 'UPDATE question SET ' . implode(', ', $sets) . ' WHERE idquestion = ?';
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

$stmt->close();
$con->close();

?>
