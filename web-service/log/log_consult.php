<?php

// Configurações de Erro
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Inclui o script de conexão existente
require_once __DIR__ . '/../conexao.php';
// Define o charset para garantir que os caracteres especiais sejam tratados corretamente
$con->set_charset("utf8");

// Decodifica a entrada JSON (ignorada na operação SELECT ALL)
json_decode(file_get_contents('php://input'), true);

// Seleciona todos os campos da tabela 'log'
$sql = "SELECT idlog, success, `aswer-selection`, date, idquestion FROM log";

$result = $con->query($sql);

$response = []; // Array que armazenará os resultados

// Processamento dos Resultados
if ($result && $result->num_rows > 0) {
  // Itera sobre os resultados e adiciona cada linha ao array de resposta
  while ($row = $result->fetch_assoc()) {
    $response[] = $row;
  }
} else {
  // Caso não haja resultados, retorna um objeto vazio com a estrutura da tabela
  $response[] = [
    "idlog" => 0,
    "success" => "",
    "aswer-selection" => "",
    "date" => "",
    "idquestion" => 0
  ];
}

// Configura o cabeçalho para JSON e UTF-8
header('Content-Type: application/json; charset=utf-8');
// Envia a resposta como JSON, preservando caracteres UTF-8
echo json_encode($response, JSON_UNESCAPED_UNICODE);

// Fecha a conexão com o banco de dados
$con->close();

?>
