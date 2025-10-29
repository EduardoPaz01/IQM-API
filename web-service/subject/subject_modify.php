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
$name = isset($json['name']) ? $json['name'] : null;
$topic = isset($json['topic']) ? $json['topic'] : null;
$description = isset($json['description']) ? $json['description'] : null;

if (!$id) {
    echo json_encode(['success' => false, 'message' => 'idsubject é obrigatório para modificar.']);
    $con->close();
    exit;
}

// construir SET dinamicamente
$sets = [];
$params = [];
$types = '';

if ($name !== null) {
    $sets[] = 'name = ?';
    $params[] = $name;
    $types .= 's';
}
if ($topic !== null) {
    $sets[] = 'topic = ?';
    $params[] = $topic;
    $types .= 's';
}
if ($description !== null) {
    $sets[] = 'description = ?';
    $params[] = $description;
    $types .= 's';
}

if (!$sets) {
    echo json_encode(['success' => false, 'message' => 'Nenhum campo para atualizar.']);
    $con->close();
    exit;
}

$sql = 'UPDATE subject SET ' . implode(', ', $sets) . ' WHERE idsubject = ?';
$params[] = $id;
$types .= 'i';

$stmt = $con->prepare($sql);
if (!$stmt) {
    echo json_encode(['success' => false, 'message' => 'Erro ao preparar atualização: ' . $con->error]);
    $con->close();
    exit;
}

// bind dinâmico
$refs = [];
foreach ($params as $k => $v) $refs[] = &$params[$k];
array_unshift($refs, $types);
call_user_func_array([$stmt, 'bind_param'], $refs);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Registro atualizado com sucesso', 'affected_rows' => $stmt->affected_rows]);
} else {
    echo json_encode(['success' => false, 'message' => 'Erro ao executar atualização: ' . $stmt->error]);
}

$stmt->close();
$con->close();

?>
