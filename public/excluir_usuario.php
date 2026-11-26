<?php

require_once "../infra/seguranca.php";
require_once "../infra/conexao.php";

exigirAdministrador();

$id = filter_input(
    INPUT_POST,
    "id",
    FILTER_VALIDATE_INT
);

if ($id === false || $id === null || $id <= 0) {
    header("Location: usuarios_cadastrados.php");
    exit;
}

// Impede que o administrador exclua a própria conta.
if ($id ===  $_SESSION["usuario_id"]) {
    header("Location: usuarios_cadastrados.php");
    exit;
}

$stmt = $conexao->prepare(
    "DELETE FROM USUARIO WHERE id = ?"
);

$stmt->bind_param("i", $id);

if (!$stmt->execute()) {
    http_response_code(500);
    exit("Não foi possível excluir o usuário.");
}

header("Location: usuarios_cadastrados.php");
exit;