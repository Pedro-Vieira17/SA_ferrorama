<?php

require_once "../infra/seguranca.php";
require_once "../infra/conexao.php";

exigirAdministrador();

$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

if ($id === false || $id === null || $id <= 0) {
    header("Location: usuarios_cadastrados.php");
    exit;
}

$stmt = $conexao->prepare(
    "SELECT id, nome, CPF, telefone, email, tipo
     FROM USUARIO
     WHERE id = ?"
);

$stmt->bind_param("i", $id);
$stmt->execute();

$resultado = $stmt->get_result();
$usuario = $resultado->fetch_assoc();

if (!$usuario) {
    header("Location: usuarios_cadastrados.php");
    exit;
}

$erro = "";
$sucesso = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    validarTokenCsrf();

    $nome = trim($_POST["nome"] ?? "");
    $cpf = preg_replace('/\D/', '', $_POST["cpf"] ?? "");
    $telefone = preg_replace('/\D/', '', $_POST["telefone"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $tipo = $_POST["tipo"] ?? "";

    if (
        $nome === "" ||
        $cpf === "" ||
        $telefone === "" ||
        $email === ""
    ) {
        $erro = "Preencha todos os campos.";

    } elseif (!in_array($tipo, ["usuario", "administrador"], true)) {
        $erro = "Tipo de acesso inválido.";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erro = "Informe um e-mail válido.";

    } elseif (strlen($cpf) !== 11) {
        $erro = "CPF inválido.";

    } else {

        $stmt = $conexao->prepare(
            "SELECT id
             FROM USUARIO
             WHERE (email = ? OR CPF = ?)
             AND id <> ?
             LIMIT 1"
        );

        $stmt->bind_param("ssi", $email, $cpf, $id);
        $stmt->execute();

        $resultado = $stmt->get_result();

        if ($resultado->num_rows > 0) {
            $erro = "E-mail ou CPF já está cadastrado.";

        } else {

            $stmt = $conexao->prepare(
                "UPDATE USUARIO
                 SET nome = ?, CPF = ?, telefone = ?, email = ?, tipo = ?
                 WHERE id = ?"
            );

            $stmt->bind_param(
                "sssssi",
                $nome,
                $cpf,
                $telefone,
                $email,
                $tipo,
                $id
            );

            if ($stmt->execute()) {

                if (
                    (int) $id === (int) ($_SESSION["usuario_id"] ?? 0) &&
                    $tipo !== "administrador"
                ) {
                    session_unset();
                    session_destroy();

                    header("Location: ../index.php");
                    exit;
                }

                $sucesso = "Usuário atualizado com sucesso.";

                $usuario["nome"] = $nome;
                $usuario["CPF"] = $cpf;
                $usuario["telefone"] = $telefone;
                $usuario["email"] = $email;
                $usuario["tipo"] = $tipo;

            } else {
                $erro = "Não foi possível atualizar o usuário.";
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

    <title>Editar Usuário</title>

    <link rel="stylesheet" href="../assets/style/style.css">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <link rel="icon" href="../assets/icons/TREM_AZUL.svg" type="image/svg+xml">

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

        <a href="cadastro_admin.php" class="item">
            <img src="../assets/icons/cadastrar_branco.svg" alt="">
            Cadastrar ADMs e Usuários
        </a>

        <a href="usuarios_cadastrados.php" class="item ativo">
            <img src="../assets/icons/usuarios_preto.svg" alt="">
            Usuários Cadastrados
        </a>

    </aside>

    <main class="conteudo">

        <div class="container-sensor">

            <?php if ($erro !== ""): ?>
                <div class="alert alert-danger">
                    <?= e($erro) ?>
                </div>
            <?php endif; ?>

            <?php if ($sucesso !== ""): ?>
                <div class="alert alert-success">
                    <?= e($sucesso) ?>
                </div>
            <?php endif; ?>

            <form class="form-sensor" method="POST">

                <input
                    type="hidden"
                    name="csrf_token"
                    value="<?= e(gerarTokenCsrf()) ?>"
                >

                <h3 style="color: white;">
                    <i class="bi bi-person-gear"></i>
                    Editar Usuário
                </h3>

                <div class="linha-formulario">

                    <div class="grupo-input">
                        <label for="nome" style="color: white;">Nome completo</label>
                        <input
                            type="text"
                            id="nome"
                            name="nome"
                            value="<?= e($usuario["nome"]) ?>"
                            maxlength="200"
                            required
                        >
                    </div>

                    <div class="grupo-input">
                        <label for="cpf" style="color: white;">CPF</label>
                        <input
                            type="text"
                            id="cpf"
                            name="cpf"
                            value="<?= e($usuario["CPF"]) ?>"
                            maxlength="11"
                            inputmode="numeric"
                            required
                        >
                    </div>

                </div>

                <div class="linha-formulario">

                    <div class="grupo-input">
                        <label for="telefone" style="color: white;">Telefone</label>
                        <input
                            type="text"
                            id="telefone"
                            name="telefone"
                            value="<?= e($usuario["telefone"]) ?>"
                            maxlength="15"
                            inputmode="numeric"
                            required
                        >
                    </div>

                    <div class="grupo-input">
                        <label for="email" style="color: white;">E-mail</label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="<?= e($usuario["email"]) ?>"
                            maxlength="200"
                            required
                        >
                    </div>

                </div>

                <div class="linha-formulario">

                    <div class="grupo-input">
                        <label for="tipo" style="color: white;">Tipo de acesso</label>
                        <select id="tipo" name="tipo" required>
                            <option
                                value="usuario"
                                <?= $usuario["tipo"] === "usuario" ? "selected" : "" ?>
                            >
                                Usuário
                            </option>
                            <option
                                value="administrador"
                                <?= $usuario["tipo"] === "administrador" ? "selected" : "" ?>
                            >
                                Administrador
                            </option>
                        </select>
                    </div>

                    <div class="grupo-input">
                        <label for="cep" style="color: white;">CEP</label>
                        <input
                            type="text"
                            id="cep"
                            name="cep"
                            placeholder="00000-000"
                            maxlength="9"
                            autocomplete="off"
                            value="<?= e($_POST["cep"] ?? "") ?>"
                        >
                        <small id="cep-msg" class="d-block mt-1"></small>
                    </div>

                </div>

                <!-- ============ ENDEREÇO (API ViaCEP) ============ -->

                <div class="linha-formulario">

                    <div class="grupo-input">
                        <label for="logradouro" style="color: white;">Logradouro</label>
                        <input
                            type="text"
                            id="logradouro"
                            name="logradouro"
                            placeholder="Rua, avenida..."
                            value="<?= e($_POST["logradouro"] ?? "") ?>"
                        >
                    </div>

                    <div class="grupo-input">
                        <label for="bairro" style="color: white;">Bairro</label>
                        <input
                            type="text"
                            id="bairro"
                            name="bairro"
                            placeholder="Bairro"
                            value="<?= e($_POST["bairro"] ?? "") ?>"
                        >
                    </div>

                </div>

                <div class="linha-formulario">

                    <div class="grupo-input">
                        <label for="cidade" style="color: white;">Cidade</label>
                        <input
                            type="text"
                            id="cidade"
                            name="cidade"
                            placeholder="Cidade"
                            value="<?= e($_POST["cidade"] ?? "") ?>"
                        >
                    </div>

                    <div class="grupo-input">
                        <label for="uf" style="color: white;">UF</label>
                        <input
                            type="text"
                            id="uf"
                            name="uf"
                            placeholder="UF"
                            maxlength="2"
                            value="<?= e($_POST["uf"] ?? "") ?>"
                        >
                    </div>

                </div>

                <!-- =============================================== -->

                <div class="botoes-formulario">

                    <button
                        type="button"
                        class="botao_cancelar"
                        onclick="window.location.href='usuarios_cadastrados.php'"
                    >
                        Cancelar
                    </button>

                    <button type="submit" class="btn-salvar">
                        <i class="bi bi-check-circle"></i>
                        Salvar alterações
                    </button>

                </div>

            </form>

        </div>

    </main>

</div>

<script>
    const cepInput = document.getElementById("cep");
    const cepMsg = document.getElementById("cep-msg");
    const camposEndereco = ["logradouro", "bairro", "cidade", "uf"];

    function limparEndereco() {
        camposEndereco.forEach(function (id) {
            document.getElementById(id).value = "";
        });
    }

    // Máscara: 12345678 -> 12345-678
    cepInput.addEventListener("input", function () {
        let v = cepInput.value.replace(/\D/g, "").slice(0, 8);
        if (v.length > 5) {
            v = v.slice(0, 5) + "-" + v.slice(5);
        }
        cepInput.value = v;
    });

    async function buscarCep() {

        if (cepInput.value.trim() === "") {
            cepMsg.textContent = "";
            return;
        }

        cepMsg.textContent = "Consultando...";
        cepMsg.style.color = "#d9d9d9";

        try {

            const res = await fetch(
                "buscar_cep.php?cep=" + encodeURIComponent(cepInput.value)
            );

            const dados = await res.json();

            if (!res.ok) {
                cepMsg.textContent = dados.erro || "Erro ao consultar o CEP.";
                cepMsg.style.color = "#ff6b6b";
                limparEndereco();
                return;
            }

            cepInput.value = dados.cep;

            camposEndereco.forEach(function (id) {
                document.getElementById(id).value = dados[id];
            });

            cepMsg.textContent = "CEP válido ✔";
            cepMsg.style.color = "#2ecc71";

        } catch (e) {

            cepMsg.textContent = "Erro de conexão com o servidor. Tente novamente.";
            cepMsg.style.color = "#ff6b6b";
            limparEndereco();
        }
    }

    cepInput.addEventListener("blur", buscarCep);
</script>

</body>

</html>