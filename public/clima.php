<?php

// Função que chama a API (fica aqui mesmo para não depender de outro arquivo)
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

function obterClima(string $cidade): ?array {
    // 1) Cidade -> coordenadas
    $geo = chamarApi(
        "https://geocoding-api.open-meteo.com/v1/search?name=" . urlencode($cidade) .
        "&count=1&language=pt&countryCode=BR"
    );
    if (!$geo || empty($geo['results'])) return null;
    $local = $geo['results'][0];

    // 2) Coordenadas -> clima atual
    $clima = chamarApi(
        "https://api.open-meteo.com/v1/forecast?latitude={$local['latitude']}" .
        "&longitude={$local['longitude']}&current=temperature_2m,weather_code" .
        "&timezone=America%2FSao_Paulo"
    );
    if (!$clima || empty($clima['current'])) return null;

    $condicoes = [
        0 => "Céu limpo", 1 => "Predominantemente limpo", 2 => "Parcialmente nublado",
        3 => "Nublado", 45 => "Neblina", 48 => "Neblina",
        51 => "Garoa leve", 53 => "Garoa", 55 => "Garoa forte",
        61 => "Chuva fraca", 63 => "Chuva moderada", 65 => "Chuva forte",
        80 => "Pancadas de chuva", 81 => "Pancadas de chuva", 82 => "Pancadas fortes",
        95 => "Tempestade", 96 => "Tempestade com granizo", 99 => "Tempestade com granizo",
    ];

    $c = $clima['current'];
    return [
        'cidade'      => $local['name'] . (isset($local['admin1']) ? ' - ' . $local['admin1'] : ''),
        'temperatura' => round($c['temperature_2m']),
        'condicao'    => $condicoes[$c['weather_code']] ?? 'Condição indisponível',
        'atualizado'  => date('d/m/Y H:i', strtotime($c['time'])),
        'consultado'  => date('d/m/Y H:i:s'),
    ];
}