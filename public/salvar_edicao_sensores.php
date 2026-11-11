<?php
require_once "../infra/seguranca.php";
require_once "../infra/conexao.php";

exigirAdministrador();

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: gerenciar_sensores.php");
    exit;
}

validarTokenCsrf();

$id = filter_input(INPUT_POST, "id", FILTER_VALIDATE_INT);
$localizacao = trim($_POST["localizacao"] ?? "");
$tipoDado = trim($_POST["tipoDado"] ?? "");
$tremVinculado = filter_input(INPUT_POST, "tremVinculado", FILTER_VALIDATE_INT);
$status = trim($_POST["status"] ?? "");

$tiposPermitidos = ["Velocidade", "Temperatura", "Pressão", "GPS"];
$statusPermitidos = ["Ativo", "Inativo", "Manutenção", "Alerta"];

if (
    !$id ||
    $id < 1 ||
    $localizacao === "" ||
    mb_strlen($localizacao) > 150 ||
    !in_array($tipoDado, $tiposPermitidos, true) ||
    !$tremVinculado ||
    $tremVinculado < 1 ||
    !in_array($status, $statusPermitidos, true)
) {
    header("Location: editar_sensores.php?id=" . (int)$id . "&erro=" . urlencode("Preencha todos os campos corretamente."));
    exit;
}

$stmtSensor = $conexao->prepare("SELECT id FROM SENSORES WHERE id = ?");
if (!$stmtSensor) {
    error_log("Erro ao preparar consulta do sensor: " . $conexao->error);
    header("Location: editar_sensores.php?id=" . (int)$id . "&erro=" . urlencode("Não foi possível verificar o sensor."));
    exit;
}
$stmtSensor->bind_param("i", $id);
$stmtSensor->execute();
$sensorExiste = $stmtSensor->get_result()->num_rows === 1;
$stmtSensor->close();

if (!$sensorExiste) {
    header("Location: gerenciar_sensores.php?erro=" . urlencode("Sensor não encontrado."));
    exit;
}

$stmtTrem = $conexao->prepare("SELECT id FROM TRENS WHERE id = ?");
if (!$stmtTrem) {
    error_log("Erro ao preparar consulta do trem: " . $conexao->error);
    header("Location: editar_sensores.php?id=" . (int)$id . "&erro=" . urlencode("Não foi possível verificar o trem."));
    exit;
}
$stmtTrem->bind_param("i", $tremVinculado);
$stmtTrem->execute();
$tremExiste = $stmtTrem->get_result()->num_rows === 1;
$stmtTrem->close();

if (!$tremExiste) {
    header("Location: editar_sensores.php?id=" . (int)$id . "&erro=" . urlencode("O trem selecionado não existe."));
    exit;
}

$sql = "UPDATE SENSORES
        SET localizacao = ?, tipo_de_dado = ?, trens_id = ?, status = ?
        WHERE id = ?";
$stmt = $conexao->prepare($sql);

if (!$stmt) {
    error_log("Erro ao preparar atualização do sensor: " . $conexao->error);
    header("Location: editar_sensores.php?id=" . (int)$id . "&erro=" . urlencode("Não foi possível preparar a atualização."));
    exit;
}

$stmt->bind_param("ssisi", $localizacao, $tipoDado, $tremVinculado, $status, $id);

if (!$stmt->execute()) {
    error_log("Erro ao atualizar sensor: " . $stmt->error);
    $stmt->close();
    header("Location: editar_sensores.php?id=" . (int)$id . "&erro=" . urlencode("O banco de dados não conseguiu atualizar o sensor."));
    exit;
}

$stmt->close();

$stmtVerificacao = $conexao->prepare(
    "SELECT localizacao, tipo_de_dado, trens_id, status FROM SENSORES WHERE id = ?"
);

if (!$stmtVerificacao) {
    error_log("Erro ao verificar atualização do sensor: " . $conexao->error);
    header("Location: editar_sensores.php?id=" . (int)$id . "&erro=" . urlencode("Não foi possível confirmar a atualização."));
    exit;
}

$stmtVerificacao->bind_param("i", $id);
$stmtVerificacao->execute();
$sensorSalvo = $stmtVerificacao->get_result()->fetch_assoc();
$stmtVerificacao->close();

$atualizacaoConfirmada = $sensorSalvo &&
    $sensorSalvo["localizacao"] === $localizacao &&
    $sensorSalvo["tipo_de_dado"] === $tipoDado &&
    (int)$sensorSalvo["trens_id"] === (int)$tremVinculado &&
    $sensorSalvo["status"] === $status;

if (!$atualizacaoConfirmada) {
    header("Location: editar_sensores.php?id=" . (int)$id . "&erro=" . urlencode("A atualização não foi confirmada no banco de dados."));
    exit;
}

header("Location: gerenciar_sensores.php?sucesso=" . urlencode("Sensor atualizado com sucesso."));
exit;
