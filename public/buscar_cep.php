<?php

header('Content-Type: application/json; charset=utf-8');

// Função que chama a API (fica aqui para não depender de outro arquivo)
if (!function_exists('chamarApi')) {
    function chamarApi(string $url): ?array {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 5,
            CURLOPT_CONNECTTIMEOUT => 3,
        ]);
        $resposta = curl_exec($ch);
        $codigo = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($resposta === false || $codigo !== 200) {
            return null;
        }
        $json = json_decode($resposta, true);
        return is_array($json) ? $json : null;
    }
}

// 1) Limpa e valida o formato (8 números)
$cep = preg_replace('/\D/', '', $_GET['cep'] ?? '');

if (strlen($cep) !== 8) {
    http_response_code(400);
    echo json_encode(['erro' => 'CEP inválido: digite 8 números.']);
    exit;
}

// 2) Consulta a ViaCEP
$dados = chamarApi("https://viacep.com.br/ws/{$cep}/json/");

// 3) Falha de comunicação (timeout, API fora do ar, sem internet)
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