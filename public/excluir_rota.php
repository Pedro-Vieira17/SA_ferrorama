<?php

require_once "../infra/seguranca.php";
require_once "../infra/conexao.php";

exigirAdministrador();

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: rotas.php");
    exit;
}

validarTokenCsrf();

$id = filter_input(INPUT_POST, "id", FILTER_VALIDATE_INT);

if (!$id || $id <= 0) {
    header("Location: rotas.php?erro=" . urlencode("ID da rota inválido."));
    exit;
}

$stmt = $conexao->prepare(
    "DELETE FROM ROTAS WHERE id = ?"
);

if (!$stmt) {
    header("Location: rotas.php?erro=" . urlencode("Erro ao preparar a exclusão."));
    exit;
}

$stmt->bind_param("i", $id);

if (!$stmt->execute()) {
    error_log("Erro ao excluir rota: " . $stmt->error);

    $stmt->close();

    header(
        "Location: rotas.php?erro=" .
        urlencode("Não foi possível excluir a rota.")
    );

    exit;
}

$excluida = $stmt->affected_rows > 0;

$stmt->close();

if ($excluida) {
    header(
        "Location: rotas.php?sucesso=" .
        urlencode("Rota excluída com sucesso.")
    );
    exit;
}

header(
    "Location: rotas.php?erro=" .
    urlencode("Rota não encontrada.")
);

exit;