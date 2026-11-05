<?php
require_once "../infra/seguranca.php";
require_once "../infra/conexao.php";
exigirAdministrador();

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: rotas.php");
    exit;
}

validarTokenCsrf();

$id = filter_var($_POST["id"] ?? null, FILTER_VALIDATE_INT);
$nome = trim($_POST["nome"] ?? "");
$descricao = trim($_POST["descricao"] ?? "");
$distancia = filter_var($_POST["distancia_km"] ?? null, FILTER_VALIDATE_FLOAT);
$tremId = filter_var($_POST["trem_id"] ?? null, FILTER_VALIDATE_INT);
$duracao = filter_var($_POST["duracao_min"] ?? null, FILTER_VALIDATE_INT);
$status = trim($_POST["status"] ?? "");
$statusPermitidos = ["Ativa", "Inativa", "Manutenção"];

if (!$id || $id <= 0 || $nome === "" || mb_strlen($nome) > 200 || $descricao === "" || $distancia === false || $distancia <= 0 || $tremId === false || $tremId <= 0 || $duracao === false || $duracao <= 0 || !in_array($status, $statusPermitidos, true)) {
    header("Location: editar_rota.php?id=" . (int) $id . "&erro=" . urlencode("Confira os campos preenchidos."));
    exit;
}

$verificar = $conexao->prepare("SELECT id FROM TRENS WHERE id = ?");
if (!$verificar) {
    header("Location: rotas.php?erro=" . urlencode("Não foi possível validar o trem."));
    exit;
}
$verificar->bind_param("i", $tremId);
$verificar->execute();
$existeTrem = $verificar->get_result()->num_rows === 1;
$verificar->close();

if (!$existeTrem) {
    header("Location: editar_rota.php?id=" . $id . "&erro=" . urlencode("Selecione um trem válido."));
    exit;
}

$stmt = $conexao->prepare("UPDATE ROTAS SET nome = ?, descricao = ?, distancia_km = ?, trem_id = ?, duracao_min = ?, status = ? WHERE id = ?");
if (!$stmt) {
    header("Location: rotas.php?erro=" . urlencode("Não foi possível preparar a atualização."));
    exit;
}

$stmt->bind_param("ssdiisi", $nome, $descricao, $distancia, $tremId, $duracao, $status, $id);
$ok = $stmt->execute();
$stmt->close();

if ($ok) {
    header("Location: rotas.php?sucesso=1");
    exit;
}

header("Location: editar_rota.php?id=" . $id . "&erro=" . urlencode("Não foi possível atualizar a rota."));
exit;
