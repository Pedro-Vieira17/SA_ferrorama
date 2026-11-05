<?php

require_once "../infra/seguranca.php";
require_once "../infra/conexao.php";

exigirAdministrador();

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: tela_inicial.php");
    exit;
}

validarTokenCsrf();

$id = filter_var(
    $_POST["id"] ?? null,
    FILTER_VALIDATE_INT
);

if ($id === false || $id === null || $id <= 0) {
    header("Location: tela_inicial.php?erro=" . urlencode("Trem inválido."));
    exit;
}

$localizacao = trim($_POST["localizacao"] ?? "");
$tipo_de_dado = trim($_POST["tipo_de_dado"] ?? "");
$horario = trim($_POST["horario"] ?? "");
$status = trim($_POST["status"] ?? "");

$usuario_id = filter_var(
    $_POST["usuario_id"] ?? null,
    FILTER_VALIDATE_INT
);

$tiposPermitidos = [
    "Velocidade",
    "Temperatura",
    "Pressão",
    "Localização"
];

$statusPermitidos = [
    "Ativo",
    "Inativo",
    "Manutenção"
];

function voltarEdicao(int $id, string $mensagem): void
{
    header(
        "Location: editar_trens.php?id=" .
        $id .
        "&erro=" .
        urlencode($mensagem)
    );

    exit;
}

if (
    $localizacao === "" ||
    $tipo_de_dado === "" ||
    $horario === "" ||
    $status === "" ||
    $usuario_id === false ||
    $usuario_id === null ||
    $usuario_id <= 0
) {
    voltarEdicao($id, "Preencha todos os campos corretamente.");
}

if (
    strlen($localizacao) > 800 ||
    !preg_match('/^(?:[01]\d|2[0-3]):[0-5]\d$/', $horario) ||
    !in_array($tipo_de_dado, $tiposPermitidos, true) ||
    !in_array($status, $statusPermitidos, true)
) {
    voltarEdicao($id, "Verifique os dados informados.");
}

$stmtTrem = $conexao->prepare("
    SELECT id
    FROM TRENS
    WHERE id = ?
");

if (!$stmtTrem) {
    voltarEdicao($id, "Erro ao verificar o trem.");
}

$stmtTrem->bind_param("i", $id);
$stmtTrem->execute();

$tremExiste = $stmtTrem->get_result()->num_rows > 0;

$stmtTrem->close();

if (!$tremExiste) {
    header("Location: tela_inicial.php?erro=" . urlencode("Trem não encontrado."));
    exit;
}

$stmtUsuario = $conexao->prepare("
    SELECT id
    FROM USUARIO
    WHERE id = ?
");

if (!$stmtUsuario) {
    voltarEdicao($id, "Erro ao verificar o usuário responsável.");
}

$stmtUsuario->bind_param("i", $usuario_id);
$stmtUsuario->execute();

$usuarioExiste = $stmtUsuario->get_result()->num_rows > 0;

$stmtUsuario->close();

if (!$usuarioExiste) {
    voltarEdicao($id, "O usuário responsável selecionado não existe.");
}

$sql = "
    UPDATE TRENS
    SET
        localizacao = ?,
        tipo_de_dado = ?,
        horario = ?,
        status = ?,
        usuario_id = ?
    WHERE id = ?
";

$stmt = $conexao->prepare($sql);

if (!$stmt) {
    voltarEdicao($id, "Erro ao preparar a atualização do trem.");
}

$stmt->bind_param(
    "ssssii",
    $localizacao,
    $tipo_de_dado,
    $horario,
    $status,
    $usuario_id,
    $id
);

if ($stmt->execute()) {

    $stmt->close();

    header(
        "Location: tela_inicial.php?sucesso=" .
        urlencode("Trem atualizado com sucesso!")
    );

    exit;

}

$stmt->close();

voltarEdicao($id, "Não foi possível atualizar o trem.");