<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../conexao.php';
$con->set_charset("utf8");

$json = json_decode(file_get_contents('php://input'), true);
if (!is_array($json)) $json = $_POST;

$success = isset($json['success']) ? $json['success'] : null; // expected 'Y'/'N' or similar
$answerSelection = isset($json['aswer-selection']) ? $json['aswer-selection'] : (isset($json['aswer_selection']) ? $json['aswer_selection'] : null);
$date = isset($json['date']) ? $json['date'] : date('Y-m-d');
$idquestion = isset($json['idquestion']) ? (int)$json['idquestion'] : null;

if ($success === null || $idquestion === null) {
    echo json_encode(['success' => false, 'message' => 'Campos obrigatórios: success e idquestion.']);
    $con->close();
    exit;
}

$stmt = $con->prepare('INSERT INTO `log` (`success`, `aswer-selection`, `date`, `idquestion`) VALUES (?, ?, ?, ?)');
if (!$stmt) {
    echo json_encode(['success' => false, 'message' => 'Erro ao preparar INSERT: ' . $con->error]);
    $con->close();
    exit;
}

$stmt->bind_param('sssi', $success, $answerSelection, $date, $idquestion);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Log inserido com sucesso', 'idlog' => $con->insert_id]);
} else {
    echo json_encode(['success' => false, 'message' => 'Erro ao inserir: ' . $stmt->error]);
}

$stmt->close();
$con->close();

?>
