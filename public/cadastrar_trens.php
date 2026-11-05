<?php

require_once "../infra/seguranca.php";
require_once "../infra/conexao.php";

exigirAdministrador();

$erro = "";
$sucesso = "";

$localizacao = "";
$tipo_de_dado = "";
$horario = "";
$status = "";
$usuario_id_form = "";

$tiposPermitidos = [
    "Temperatura",
    "Velocidade",
    "Localização",
    "Pressão"
];

$statusPermitidos = [
    "Ativo",
    "Inativo",
    "Manutenção"
];

$consultaUsuarios = $conexao->query("
    SELECT id, nome, tipo
    FROM USUARIO
    ORDER BY nome ASC
");

$usuarios = $consultaUsuarios
    ? $consultaUsuarios->fetch_all(MYSQLI_ASSOC)
    : [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    validarTokenCsrf();

    $localizacao = trim($_POST["localizacao"] ?? "");
    $tipo_de_dado = trim($_POST["tipo_de_dado"] ?? "");
    $horario = trim($_POST["horario"] ?? "");
    $status = trim($_POST["status"] ?? "");
    $usuario_id_form = $_POST["usuario_id"] ?? "";

    $usuario_id = filter_var(
        $usuario_id_form,
        FILTER_VALIDATE_INT
    );

    if (
        $localizacao === "" ||
        $tipo_de_dado === "" ||
        $horario === "" ||
        $status === "" ||
        $usuario_id === false ||
        $usuario_id <= 0
    ) {
        $erro = "Preencha todos os campos corretamente.";
    } elseif (
        !in_array($tipo_de_dado, $tiposPermitidos, true) ||
        !in_array($status, $statusPermitidos, true)
    ) {
        $erro = "Selecione um tipo de dado e um status válidos.";
    } elseif (
        strlen($localizacao) > 800 ||
        !preg_match('/^(?:[01]\d|2[0-3]):[0-5]\d$/', $horario)
    ) {
        $erro = "Verifique a localização e o horário informados.";
    } else {

        $stmtUsuario = $conexao->prepare("
            SELECT id
            FROM USUARIO
            WHERE id = ?
        ");

        if (!$stmtUsuario) {

            $erro = "Erro ao verificar o usuário responsável.";

        } else {

            $stmtUsuario->bind_param("i", $usuario_id);
            $stmtUsuario->execute();

            $resultadoUsuario = $stmtUsuario->get_result();
            $usuarioExiste = $resultadoUsuario->num_rows > 0;

            $stmtUsuario->close();

            if (!$usuarioExiste) {

                $erro = "O usuário responsável selecionado não existe.";

            } else {

                $sql = "
                    INSERT INTO TRENS (
                        localizacao,
                        tipo_de_dado,
                        horario,
                        status,
                        usuario_id
                    )
                    VALUES (?, ?, ?, ?, ?)
                ";

                $stmt = $conexao->prepare($sql);

                if (!$stmt) {

                    $erro = "Erro ao preparar o cadastro do trem.";

                } else {

                    $stmt->bind_param(
                        "ssssi",
                        $localizacao,
                        $tipo_de_dado,
                        $horario,
                        $status,
                        $usuario_id
                    );

                    if ($stmt->execute()) {

                        $sucesso = "Trem cadastrado com sucesso!";

                        $localizacao = "";
                        $tipo_de_dado = "";
                        $horario = "";
                        $status = "";
                        $usuario_id_form = "";

                    } else {

                        $erro = "Erro ao cadastrar o trem.";

                    }

                    $stmt->close();

                }
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

    <title>Cadastrar Trem</title>

    <link rel="stylesheet" href="../assets/style/style.css">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
        rel="stylesheet">

    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <link rel="icon" href="../assets/icons/TREM_AZUL.svg">

    <style>
        .formulario-trem {
            background: #0f172a;
            padding: 30px;
            border-radius: 15px;
            max-width: 700px;
        }

        .formulario-trem h3 {
            color: white;
            margin-bottom: 25px;
        }

        .campo {
            margin-bottom: 18px;
        }

        .campo label {
            color: white;
            display: block;
            margin-bottom: 6px;
        }

        .campo input,
        .campo select {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 7px;
        }

        .botoes {
            margin-top: 20px;
            display: flex;
            gap: 10px;
        }
    </style>

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

        <a href="tela_inicial.php" class="item ativo">
            <img src="../assets/icons/dashboard_preto.svg" alt="">
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

        <a href="usuarios_cadastrados.php" class="item">
            <img src="../assets/icons/usuarios_branco.svg" alt="">
            Usuários Cadastrados
        </a>

    </aside>

    <main class="conteudo">

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

        <?php if (empty($usuarios)): ?>

            <div class="alert alert-warning">
                Não existem usuários cadastrados para selecionar como responsáveis.
            </div>

        <?php endif; ?>

        <div class="formulario-trem">

            <h3>
                <i class="bi bi-train-front"></i>
                Cadastrar Trem
            </h3>

            <form method="POST">

                <input
                    type="hidden"
                    name="csrf_token"
                    value="<?= e(gerarTokenCsrf()) ?>"
                >

                <div class="campo">

                    <label for="localizacao">Localização</label>

                    <input
                        type="text"
                        id="localizacao"
                        name="localizacao"
                        maxlength="200"
                        placeholder="Ex.: Estação Central"
                        value="<?= e($localizacao) ?>"
                        required
                    >

                </div>

                <div class="campo">

                    <label for="tipo_de_dado">Tipo de Dado</label>

                    <select
                        id="tipo_de_dado"
                        name="tipo_de_dado"
                        required
                    >

                        <option value="">Selecione</option>

                        <?php foreach ($tiposPermitidos as $opcao): ?>

                            <option
                                value="<?= e($opcao) ?>"
                                <?= $tipo_de_dado === $opcao ? "selected" : "" ?>
                            >
                                <?= e($opcao) ?>
                            </option>

                        <?php endforeach; ?>

                    </select>

                </div>

                <div class="campo">

                    <label for="horario">Horário</label>

                    <input
                        type="time"
                        id="horario"
                        name="horario"
                        value="<?= e($horario) ?>"
                        required
                    >

                </div>

                <div class="campo">

                    <label for="status">Status</label>

                    <select id="status" name="status" required>

                        <option value="">Selecione</option>

                        <?php foreach ($statusPermitidos as $opcao): ?>

                            <option
                                value="<?= e($opcao) ?>"
                                <?= $status === $opcao ? "selected" : "" ?>
                            >
                                <?= e($opcao) ?>
                            </option>

                        <?php endforeach; ?>

                    </select>

                </div>

                <div class="campo">

                    <label for="usuario_id">Usuário responsável</label>

                    <select
                        id="usuario_id"
                        name="usuario_id"
                        required
                    >

                        <option value="">Selecione um responsável</option>

                        <?php foreach ($usuarios as $usuario): ?>

                            <option
                                value="<?= (int) $usuario["id"] ?>"
                                <?= (string) $usuario_id_form === (string) $usuario["id"] ? "selected" : "" ?>
                            >
                                <?= e($usuario["nome"]) ?>
                                (<?= $usuario["tipo"] === "administrador" ? "Administrador" : "Usuário" ?>)
                            </option>

                        <?php endforeach; ?>

                    </select>

                </div>

                <div class="botoes">

                    <a href="tela_inicial.php" class="btn btn-secondary">
                        Cancelar
                    </a>

                    <button
                        type="submit"
                        class="btn btn-primary"
                        <?= empty($usuarios) ? "disabled" : "" ?>
                    >
                        <i class="bi bi-check-circle"></i>
                        Salvar
                    </button>

                </div>

            </form>

        </div>

    </main>

</div>

</body>
</html>