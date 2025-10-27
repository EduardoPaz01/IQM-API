<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json; charset=utf-8');
require_once 'conexao.php';

// define charset da conexão (ajuste se necessário)
$con->set_charset("utf8");

// ler JSON de entrada
$json = json_decode(file_get_contents('php://input'), true);

// se não vier JSON, tenta usar $_POST (opcional)
if (!is_array($json)) {
    $json = $_POST;
}

// extrair campos (sem validações)
$name        = $json['name']        ?? null;
$topic       = $json['topic']       ?? null;
$description = $json['description'] ?? null;

// preparar e executar INSERT
$stmt = $con->prepare("INSERT INTO subject (name, topic, description) VALUES (?, ?, ?)");
if (!$stmt) {
    echo json_encode(['success' => false, 'message' => 'Erro ao preparar consulta: ' . $con->error]);
    exit;
}

$stmt->bind_param("sss", $name, $topic, $description);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Subject inserido com sucesso', 'idsubject' => $con->insert_id]);
} else {
    echo json_encode(['success' => false, 'message' => 'Erro ao inserir: ' . $stmt->error]);
}

$stmt->close();
$con->close();

/*
{
    "name": "subject",
    "topic": "topic",
    "description": "lorem..."
}
*/

?>