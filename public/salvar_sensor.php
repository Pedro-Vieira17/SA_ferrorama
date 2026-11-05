<?php
require_once "../infra/seguranca.php";
require_once "../infra/conexao.php";

exigirAdministrador();

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: gerenciar_sensores.php");
    exit;
}

validarTokenCsrf();

$localizacao = trim($_POST["localizacao"] ?? "");
$tipoDado = trim($_POST["tipoDado"] ?? "");
$tremVinculado = filter_input(INPUT_POST, "tremVinculado", FILTER_VALIDATE_INT);
$status = "Ativo";

$tiposPermitidos = ["Velocidade", "Temperatura", "Pressão", "GPS"];

if (
    $localizacao === "" ||
    mb_strlen($localizacao) > 150 ||
    !in_array($tipoDado, $tiposPermitidos, true) ||
    !$tremVinculado ||
    $tremVinculado < 1
) {
    header("Location: cadastrar_sensores.php?erro=" . urlencode("Preencha todos os campos corretamente."));
    exit;
}

$stmtTrem = $conexao->prepare("SELECT id FROM TRENS WHERE id = ?");
$stmtTrem->bind_param("i", $tremVinculado);
$stmtTrem->execute();
$tremExiste = $stmtTrem->get_result()->num_rows === 1;
$stmtTrem->close();

if (!$tremExiste) {
    header("Location: cadastrar_sensores.php?erro=" . urlencode("O trem selecionado não existe."));
    exit;
}

$stmt = $conexao->prepare(
    "INSERT INTO SENSORES (localizacao, tipo_de_dado, trens_id, status) VALUES (?, ?, ?, ?)"
);

if (!$stmt) {
    error_log("Erro ao preparar cadastro do sensor: " . $conexao->error);
    header("Location: cadastrar_sensores.php?erro=" . urlencode("Não foi possível preparar o cadastro."));
    exit;
}

$stmt->bind_param("ssis", $localizacao, $tipoDado, $tremVinculado, $status);

if (!$stmt->execute()) {
    error_log("Erro ao cadastrar sensor: " . $stmt->error);
    $stmt->close();
    header("Location: cadastrar_sensores.php?erro=" . urlencode("O banco de dados não conseguiu cadastrar o sensor."));
    exit;
}

$stmt->close();

header("Location: gerenciar_sensores.php?sucesso=" . urlencode("Sensor cadastrado com sucesso."));
exit;