<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../conexao.php';
$con->set_charset("utf8");

$json = json_decode(file_get_contents('php://input'), true);
if (!is_array($json)) $json = $_POST;

$statement = isset($json['statement']) ? $json['statement'] : null;
$answer = isset($json['answer']) ? $json['answer'] : null;
$topic = isset($json['topic']) ? $json['topic'] : null;
$tip = isset($json['tip']) ? $json['tip'] : null;
$options = isset($json['options']) ? $json['options'] : null;
$blacklist = isset($json['black-list']) ? $json['black-list'] : (isset($json['black_list']) ? $json['black_list'] : null);
$idsubject = isset($json['idsubject']) ? (int)$json['idsubject'] : null;

// campos obrigatórios: statement, answer, topic, tip, idsubject
if ($statement === null || $answer === null || $topic === null || $tip === null || $idsubject === null) {
    echo json_encode(['success' => false, 'message' => 'Campos obrigatórios: statement, answer, topic, tip, idsubject']);
    $con->close();
    exit;
}

$stmt = $con->prepare('INSERT INTO `question` (statement, answer, topic, tip, `options`, `black-list`, idsubject) VALUES (?, ?, ?, ?, ?, ?, ?)');
if (!$stmt) {
    echo json_encode(['success' => false, 'message' => 'Erro ao preparar INSERT: ' . $con->error]);
    $con->close();
    exit;
}

$stmt->bind_param('ssssssi', $statement, $answer, $topic, $tip, $options, $blacklist, $idsubject);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Question inserida com sucesso', 'idquestion' => $con->insert_id]);
} else {
    echo json_encode(['success' => false, 'message' => 'Erro ao inserir: ' . $stmt->error]);
}

$stmt->close();
$con->close();

?>
