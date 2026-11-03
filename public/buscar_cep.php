<?php
require_once 'api_clima.php'; // usa a função chamarApi(); se renomear para api.php, troque aqui

header('Content-Type: application/json; charset=utf-8');

// 1) Limpa e valida o formato
$cep = preg_replace('/\D/', '', $_GET['cep'] ?? '');

if (strlen($cep) !== 8) {
    http_response_code(400);
    echo json_encode(['erro' => 'CEP inválido: digite 8 números.']);
    exit;
}

// 2) Consulta a API
$dados = chamarApi("https://viacep.com.br/ws/{$cep}/json/");

// 3) Falha de comunicação (timeout, API fora do ar, resposta inválida)
if ($dados === null) {
    http_response_code(502);
    echo json_encode(['erro' => 'Não foi possível consultar o serviço de CEP agora. Tente novamente.']);
    exit;
}

// 4) CEP com formato válido, mas inexistente
if (isset($dados['erro'])) {
    http_response_code(404);
    echo json_encode(['erro' => 'CEP não encontrado.']);
    exit;
}

// 5) Sucesso
echo json_encode([
    'cep'        => $dados['cep'] ?? '',
    'logradouro' => $dados['logradouro'] ?? '',
    'bairro'     => $dados['bairro'] ?? '',
    'cidade'     => $dados['localidade'] ?? '',
    'uf'         => $dados['uf'] ?? '',
]);