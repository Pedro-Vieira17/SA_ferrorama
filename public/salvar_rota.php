<?php

require_once "../infra/seguranca.php";
require_once "../infra/conexao.php";

exigirAdministrador();

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: rotas.php");
    exit;
}

validarTokenCsrf();

$nome = trim($_POST["nome"] ?? "");
$descricao = trim($_POST["descricao"] ?? "");
$distancia = filter_input(INPUT_POST, "distancia_km", FILTER_VALIDATE_FLOAT);
$tremId = filter_input(INPUT_POST, "trem_id", FILTER_VALIDATE_INT);
$duracao = filter_input(INPUT_POST, "duracao_min", FILTER_VALIDATE_INT);
$status = trim($_POST["status"] ?? "");

$statusPermitidos = ["Ativa", "Inativa", "Manutenção"];

if (
    $nome === "" ||
    $descricao === "" ||
    $distancia === false ||
    $distancia === null ||
    $distancia <= 0 ||
    !$tremId ||
    $tremId <= 0 ||
    !$duracao ||
    $duracao <= 0 ||
    !in_array($status, $statusPermitidos, true)
) {
    header(
        "Location: cadastrar_rota.php?erro=" .
        urlencode("Preencha os campos corretamente.")
    );
    exit;
}

$verificar = $conexao->prepare(
    "SELECT id FROM TRENS WHERE id = ?"
);

$verificar->bind_param("i", $tremId);
$verificar->execute();

$tremExiste = $verificar->get_result()->num_rows > 0;

$verificar->close();

if (!$tremExiste) {
    header(
        "Location: cadastrar_rota.php?erro=" .
        urlencode("O trem selecionado não existe.")
    );
    exit;
}

$stmt = $conexao->prepare(
    "INSERT INTO ROTAS (
        nome,
        descricao,
        distancia_km,
        trem_id,
        duracao_min,
        status
    ) VALUES (?, ?, ?, ?, ?, ?)"
);

if (!$stmt) {
    error_log("Erro ao preparar cadastro da rota: " . $conexao->error);

    header(
        "Location: cadastrar_rota.php?erro=" .
        urlencode("Erro ao preparar o cadastro.")
    );
    exit;
}

$stmt->bind_param(
    "ssdiis",
    $nome,
    $descricao,
    $distancia,
    $tremId,
    $duracao,
    $status
);

if ($stmt->execute()) {
    $stmt->close();

    header(
        "Location: rotas.php?sucesso=" .
        urlencode("Rota cadastrada com sucesso.")
    );
    exit;
}

error_log("Erro ao cadastrar rota: " . $stmt->error);

$stmt->close();

header(
    "Location: cadastrar_rota.php?erro=" .
    urlencode("Não foi possível cadastrar a rota.")
);

exit;