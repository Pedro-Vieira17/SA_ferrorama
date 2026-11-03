<?php

require_once "../infra/seguranca.php";
require_once "../infra/conexao.php";

exigirAdministrador();

$mensagem = "";
$erro = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    validarTokenCsrf();

    $nome = trim($_POST["nome"] ?? "");
    $cpf = preg_replace('/\D/', '', $_POST["cpf"] ?? "");
    $telefone = preg_replace('/\D/', '', $_POST["telefone"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $senha = $_POST["senha"] ?? "";
    $tipo = $_POST["tipo"] ?? "";

    if (
        $nome === "" ||
        $cpf === "" ||
        $telefone === "" ||
        $email === "" ||
        $senha === "" ||
        $tipo === ""
    ) {

        $erro = "Preencha todos os campos.";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $erro = "E-mail inválido.";

    } elseif (strlen($cpf) !== 11) {

        $erro = "CPF inválido.";

    } elseif (!in_array($tipo, ["usuario", "administrador"], true)) {

        $erro = "Tipo de acesso inválido.";

    } elseif (strlen($senha) < 8) {

        $erro = "A senha deve possuir pelo menos 8 caracteres.";

    } else {

        $stmt = $conexao->prepare(
            "SELECT id
             FROM USUARIO
             WHERE email = ? OR CPF = ?
             LIMIT 1"
        );

        $stmt->bind_param("ss", $email, $cpf);
        $stmt->execute();

        $resultado = $stmt->get_result();

        if ($resultado->num_rows > 0) {

            $erro = "E-mail ou CPF já cadastrado.";

        } else {

            $senhaHash = password_hash(
                $senha,
                PASSWORD_DEFAULT
            );

            $stmt = $conexao->prepare(
                "INSERT INTO USUARIO
                (nome, CPF, telefone, email, senha, tipo)
                VALUES (?, ?, ?, ?, ?, ?)"
            );

            $stmt->bind_param(
                "ssssss",
                $nome,
                $cpf,
                $telefone,
                $email,
                $senhaHash,
                $tipo
            );

            if ($stmt->execute()) {
                $mensagem = "Cadastro realizado com sucesso.";
            } else {
                $erro = "Não foi possível realizar o cadastro.";
            }
        }
    }
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Cadastrar ADMs e Usuários</title>

    <link rel="stylesheet" href="../assets/style/style.css">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
        rel="stylesheet">

    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <link rel="icon"
        href="../assets/icons/TREM_AZUL.svg">

</head>

<body>

    <header class="cabecalho">

        <h2>
            <img src="../assets/icons/TREM_AZUL.svg" alt="">
            Bem vindo, Administrador
        </h2>

        <a href="../logout.php">
            <img src="../assets/icons/exit.svg" class="item" alt="Sair">
        </a>

    </header>

    <div class="layout">

        <aside class="menu-lateral">

            <a href="tela_inicial.php" class="item">

                <img src="../assets/icons/dashboard_branco.svg" alt="">

                Dashboard

            </a>

            <a href="gerenciar_sensores.php" class="item">

                <img src="../assets/icons/sensor_branco.svg" alt="">

                Sensores

            </a>

            <a href="rotas.php" class="item">

                <img src="../assets/icons/relatorio_branco.svg" alt="">

                Rotas

            </a>

            <a href="cadastro_admin.php" class="item ativo">

                <img src="../assets/icons/cadastrar_preto.svg" alt="">

                Cadastrar ADMs e Usuários

            </a>

            <a href="usuarios_cadastrados.php" class="item">

                <img src="../assets/icons/usuarios_branco.svg" alt="">

                Usuários Cadastrados

            </a>

        </aside>

        <main class="conteudo">

            <div class="planilha_dashboard cadastro_container">

                <h2 class="titulo_cadastro">
                    Cadastrar ADMs e Usuários
                </h2>

                <?php if ($mensagem != ""): ?>

                    <div class="alert alert-success">

                        <?= htmlspecialchars($mensagem) ?>

                    </div>

                <?php endif; ?>

                <?php if ($erro != ""): ?>

                    <div class="alert alert-danger">

                        <?= htmlspecialchars($erro) ?>

                    </div>

                <?php endif; ?>

                <form method="POST">

                    <input
                        type="hidden"
                        name="csrf_token"
                        value="<?= e(gerarTokenCsrf()) ?>"
                    >

                    <div class="row">

                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                Nome completo
                            </label>

                            <input
                                type="text"
                                name="nome"
                                class="form-control"
                                placeholder="Digite o nome completo"
                                value="<?= htmlspecialchars($_POST["nome"] ?? "") ?>"
                                required>

                        </div>

                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                CPF
                            </label>

                            <input
                                type="text"
                                name="cpf"
                                class="form-control"
                                placeholder="Digite o CPF"
                                value="<?= htmlspecialchars($_POST["cpf"] ?? "") ?>"
                                required>

                        </div>

                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                Telefone
                            </label>

                            <input
                                type="text"
                                name="telefone"
                                class="form-control"
                                placeholder="Digite o telefone"
                                value="<?= htmlspecialchars($_POST["telefone"] ?? "") ?>"
                                required>

                        </div>

                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                E-mail
                            </label>

                            <input
                                type="email"
                                name="email"
                                class="form-control"
                                placeholder="Digite o e-mail"
                                value="<?= htmlspecialchars($_POST["email"] ?? "") ?>"
                                required>

                        </div>

                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                Senha
                            </label>

                            <input
                                type="password"
                                name="senha"
                                class="form-control"
                                placeholder="Digite a senha"
                                required>

                        </div>

                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                Tipo de acesso
                            </label>

                            <select
                                name="tipo"
                                class="form-select"
                                required>

                                <option value="">
                                    Selecione o tipo
                                </option>

                                <option
                                    value="usuario"
                                    <?= ($_POST["tipo"] ?? "") == "usuario" ? "selected" : "" ?>>

                                    Usuário

                                </option>

                                <option
                                    value="administrador"
                                    <?= ($_POST["tipo"] ?? "") == "administrador" ? "selected" : "" ?>>

                                    Administrador

                                </option>

                            </select>

                        </div>

                    </div>

                    <div class="d-flex gap-2 mt-3">

                        <button
                            type="submit"
                            class="btn btn-primary">

                            Cadastrar

                        </button>

                        <a
                            href="usuarios_cadastrados.php"
                            class="btn btn-secondary">

                            Ver usuários cadastrados

                        </a>

                    </div>

                </form>

            </div>

        </main>

    </div>

</body>

</html>
