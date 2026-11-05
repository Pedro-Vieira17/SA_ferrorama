<?php

require_once "infra/seguranca.php";
require_once "infra/conexao.php";

$erro = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = trim($_POST["email"] ?? "");
    $senha = $_POST["senha"] ?? "";

    if ($email === "" || $senha === "") {

        $erro = "Preencha todos os campos.";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $erro = "E-mail ou senha incorretos.";

    } else {

        $stmt = $conexao->prepare(
            "SELECT id, nome, email, senha, tipo
             FROM USUARIO
             WHERE email = ?
             LIMIT 1"
        );

        if (!$stmt) {

            $erro = "Não foi possível realizar o login.";

        } else {

            $stmt->bind_param("s", $email);
            $stmt->execute();

            $resultado = $stmt->get_result();
            $usuario = $resultado->fetch_assoc();

            if (
                $usuario &&
                password_verify($senha, $usuario["senha"])
            ) {

                session_regenerate_id(true);

                $_SESSION["usuario_id"] = (int) $usuario["id"];
                $_SESSION["usuario_nome"] = $usuario["nome"];
                $_SESSION["usuario_tipo"] = $usuario["tipo"];

                if ($usuario["tipo"] === "administrador") {

                    header("Location: public/tela_inicial.php");
                    exit;

                }

                if ($usuario["tipo"] === "usuario") {

                    header("Location: public/tela_inicial_usuario.php");
                    exit;

                }

                $erro = "Tipo de usuário inválido.";

            } else {

                $erro = "E-mail ou senha incorretos.";
            }

            $stmt->close();
        }
    }
}

?>

<!doctype html>
<html lang="pt-br">

<head>

    <meta charset="utf-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1">

    <title>Tela de Login</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
        rel="stylesheet">

    <link
        rel="stylesheet"
        href="assets/style/style.css">

    <link
        rel="icon"
        href="assets/icons/TREM_AZUL.svg"
        type="image/x-icon">

</head>

<body>

    <div class="container vh-100 d-flex align-items-center">

        <div
            class="p-3"
            style="max-width: 400px; width: 100%;">

           <div class="titulo">

    <h1 class="text-nowrap d-flex align-items-center gap-2">
        <img src="assets/icons/TREM_AZUL.svg" alt="Ícone Trem" style="width: 32px; height: 32px;">
        Sistema Ferroviário
    </h1>

    <p>
        Monitoramento em tempo real
    </p>

</div>

            <?php if ($erro !== ""): ?>

                <div class="alert alert-danger">

                    <?= htmlspecialchars(
                        $erro,
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>

                </div>

            <?php endif; ?>

            <form method="POST">

                <div class="formulario">

                    <div class="mb-3">

                        <label class="form-label">
                            E-mail
                        </label>

                        <input
                            type="email"
                            name="email"
                            class="form-control"
                            placeholder="operador@ferrovia.com.br"
                            required>

                    </div>

                    <div class="mb-3">

                        <label class="form-label">
                            Senha
                        </label>

                        <input
                            type="password"
                            name="senha"
                            class="form-control"
                            placeholder="Digite sua senha"
                            required>

                    </div>

                </div>

                <div class="botao">

                    <div class="d-grid gap-2">

                        <button
                            class="btn btn-primary"
                            type="submit">

                            Entrar

                        </button>

                    </div>

                </div>

            </form>

        </div>

        <img
            src="assets/img/trem-png novo.webp"
            alt="Trem"
            style="margin-left:auto">

    </div>

</body>

</html>