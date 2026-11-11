<?php

require_once "../infra/seguranca.php";
require_once "../infra/conexao.php";

exigirAdministrador();
validarTokenCsrf();

$id = filter_input(
    INPUT_POST,
    "id",
    FILTER_VALIDATE_INT
);


if ($id === false || $id === null || $id <= 0) {
    header("Location: gerenciar_sensores.php?erro=" . urlencode("Sensor inválido."));
    exit;
}

$stmt = $conexao->prepare(
    "DELETE FROM SENSORES WHERE id = ?"
);

if (!$stmt) {
    header(
        "Location: gerenciar_sensores.php?erro=" .
        urlencode("Erro ao preparar a exclusão: " . $conexao->error)
    );
    exit;
}

$stmt->bind_param("i", $id);

if (!$stmt->execute()) {

    header(
        "Location: gerenciar_sensores.php?erro=" .
        urlencode("Erro ao excluir o sensor: " . $stmt->error)
    );

    exit;
}

if ($stmt->affected_rows === 0) {

    header(
        "Location: gerenciar_sensores.php?erro=" .
        urlencode("Nenhum sensor foi encontrado para excluir.")
    );

    exit;
}

$stmt->close();

header("Location: gerenciar_sensores.php");
exit;