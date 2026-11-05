<?php

require_once "../infra/seguranca.php";
require_once "../infra/conexao.php";

exigirAdministrador();

$id = filter_var(
    $_GET["id"] ?? null,
    FILTER_VALIDATE_INT
);

if ($id === false || $id === null || $id <= 0) {
    header("Location: tela_inicial.php?erro=" . urlencode("Trem não informado."));
    exit;
}

$stmt = $conexao->prepare("
    SELECT *
    FROM TRENS
    WHERE id = ?
");

if (!$stmt) {
    header("Location: tela_inicial.php?erro=" . urlencode("Erro ao consultar o trem."));
    exit;
}

$stmt->bind_param("i", $id);
$stmt->execute();

$resultado = $stmt->get_result();
$trem = $resultado->fetch_assoc();

$stmt->close();

if (!$trem) {
    header("Location: tela_inicial.php?erro=" . urlencode("Trem não encontrado."));
    exit;
}

$consultaUsuarios = $conexao->query("
    SELECT id, nome, tipo
    FROM USUARIO
    ORDER BY nome ASC
");

$usuarios = $consultaUsuarios
    ? $consultaUsuarios->fetch_all(MYSQLI_ASSOC)
    : [];

$erro = trim($_GET["erro"] ?? "");

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

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Editar Trem</title>

    <link rel="stylesheet" href="../assets/style/style.css">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
        rel="stylesheet">

    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <link rel="icon" href="../assets/icons/TREM_AZUL.svg">

</head>

<body>

<header class="cabecalho">
 
    <h2>
        <img src="../assets/icons/TREM_AZUL.svg" alt="">
        Bem vindo, <?= e($_SESSION["usuario_nome"] ?? "Administrador") ?>
    </h2>
 
    <span class="badge bg-primary ms-auto me-3">admin</span>
 
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

        <div class="container-sensor">

           
            <?php if ($erro !== ""): ?>

                <div class="alert alert-danger">
                    <?= e($erro) ?>
                </div>

            <?php endif; ?>

            <?php if (empty($usuarios)): ?>

                <div class="alert alert-warning">
                    Não existem usuários cadastrados para selecionar como responsáveis.
                </div>

            <?php endif; ?>

            <form
                class="form-sensor"
                method="POST"
                action="atualizar_trem.php"
            >

                <input
                    type="hidden"
                    name="id"
                    value="<?= (int) $trem["id"] ?>"
                >

                <input
                    type="hidden"
                    name="csrf_token"
                    value="<?= e(gerarTokenCsrf()) ?>"
                >

               <h3 style="color: white;">
    <i class="bi bi-train-front"></i>
    Editar Trem
</h3>

                <div class="linha-formulario">

                    <div class="grupo-input">

                        <label for="localizacao" style="color: white;">
                            Localização
                        </label>

                        <input
                            type="text"
                            id="localizacao"
                            name="localizacao"
                            maxlength="200"
                            value="<?= e($trem["localizacao"]) ?>"
                            placeholder="Ex.: Estação Central"
                            required
                        >

                    </div>

                    <div class="grupo-input">

                        <label for="tipo_de_dado" style="color: white;">
                            Tipo de Dado
                        </label>

                        <select
                            id="tipo_de_dado"
                            name="tipo_de_dado"
                            required
                        >

                            <option value="">Selecione o tipo de dado</option>

                            <?php foreach ($tiposPermitidos as $opcao): ?>

                                <option
                                    value="<?= e($opcao) ?>"
                                    <?= $trem["tipo_de_dado"] === $opcao ? "selected" : "" ?>
                                >
                                    <?= e($opcao) ?>
                                </option>

                            <?php endforeach; ?>

                        </select>

                    </div>

                </div>

                <div class="linha-formulario">

                    <div class="grupo-input">

                        <label for="horario" style="color: white;">
                            Horário
                        </label>

                        <input
                            type="time"
                            id="horario"
                            name="horario"
                            value="<?= e(substr($trem["horario"], 0, 5)) ?>"
                            required
                        >

                    </div>

                    <div class="grupo-input">

                        <label for="status" style="color: white;">
                            Status
                        </label>

                        <select id="status" name="status" required>

                            <option value="">Selecione um status</option>

                            <?php foreach ($statusPermitidos as $opcao): ?>

                                <option
                                    value="<?= e($opcao) ?>"
                                    <?= $trem["status"] === $opcao ? "selected" : "" ?>
                                >
                                    <?= e($opcao) ?>
                                </option>

                            <?php endforeach; ?>

                        </select>

                    </div>

                </div>

                <div class="linha-formulario">

                    <div class="grupo-input">

                        <label for="usuario_id" style="color: white;">
                            Usuário responsável
                        </label>

                        <select
                            id="usuario_id"
                            name="usuario_id"
                            required
                        >

                            <option value="">Selecione um responsável</option>

                            <?php foreach ($usuarios as $usuario): ?>

                                <option
                                    value="<?= (int) $usuario["id"] ?>"
                                    <?= (int) ($trem["usuario_id"] ?? 0) === (int) $usuario["id"] ? "selected" : "" ?>
                                >
                                    <?= e($usuario["nome"]) ?>
                                    (<?= $usuario["tipo"] === "administrador" ? "Administrador" : "Usuário" ?>)
                                </option>

                            <?php endforeach; ?>

                        </select>

                    </div>

                </div>

                <div class="botoes-formulario">

                    <button
                        type="button"
                        class="botao_cancelar"
                        onclick="window.location.href='tela_inicial.php'"
                    >
                        Cancelar
                    </button>

                    <button
                        type="submit"
                        class="btn-salvar"
                        <?= empty($usuarios) ? "disabled" : "" ?>
                    >
                        <i class="bi bi-check-circle"></i>
                        Salvar alterações
                    </button>

                </div>

            </form>

        </div>

    </main>

</div>

</body>
</html>