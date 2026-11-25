<?php

require_once "../infra/seguranca.php";
require_once "../infra/conexao.php";

exigirAdministrador();

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: usuarios_cadastrados.php");
    exit;
}

$id = filter_input(INPUT_POST, "id", FILTER_VALIDATE_INT);
$nome = trim($_POST["nome"] ?? "");
$cpf = preg_replace('/\D/', '', $_POST["cpf"] ?? "");
$telefone = preg_replace('/\D/', '', $_POST["telefone"] ?? "");
$email = trim($_POST["email"] ?? "");
$tipo = trim($_POST["tipo"] ?? "");
$cep = trim($_POST["cep"] ?? "");
$logradouro = trim($_POST["logradouro"] ?? "");
$bairro = trim($_POST["bairro"] ?? "");
$cidade = trim($_POST["cidade"] ?? "");
$uf = trim($_POST["uf"] ?? "");

if ($id === false || $id <= 0) {
    header("Location: usuarios_cadastrados.php?erro=" . urlencode("Usuário inválido."));
    exit;
}

if ($nome === "" || $cpf === "" || $telefone === "" || $email === "" || $tipo === "") {
    header("Location: usuarios_cadastrados.php?erro=" . urlencode("Preencha todos os campos obrigatórios."));
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header("Location: usuarios_cadastrados.php?erro=" . urlencode("E-mail inválido."));
    exit;
}

if (strlen($cpf) !== 11) {
    header("Location: usuarios_cadastrados.php?erro=" . urlencode("CPF inválido."));
    exit;
}

if ($tipo !== "usuario" && $tipo !== "administrador") {
    header("Location: usuarios_cadastrados.php?erro=" . urlencode("Tipo de acesso inválido."));
    exit;
}

$stmt = $conexao->prepare(
    "UPDATE USUARIO
     SET nome = ?, CPF = ?, telefone = ?, email = ?, tipo = ?,
         cep = ?, logradouro = ?, bairro = ?, cidade = ?, uf = ?
     WHERE id = ?"
);

if (!$stmt) {
    header("Location: usuarios_cadastrados.php?erro=" . urlencode("Não foi possível preparar a atualização."));
    exit;
}

$stmt->bind_param(
    "ssssssssssi",
    $nome,
    $cpf,
    $telefone,
    $email,
    $tipo,
    $cep,
    $logradouro,
    $bairro,
    $cidade,
    $uf,
    $id
);

if (!$stmt->execute()) {
    $stmt->close();
    header("Location: usuarios_cadastrados.php?erro=" . urlencode("Não foi possível atualizar o usuário."));
    exit;
}

$stmt->close();
header("Location: usuarios_cadastrados.php?sucesso=" . urlencode("Usuário atualizado com sucesso."));
exit;
