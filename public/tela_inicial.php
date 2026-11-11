<?php

require_once "../infra/seguranca.php";
require_once "../infra/conexao.php";
require_once __DIR__ . '/clima.php';

exigirAdministrador();

$erroCadastro = "";
$abrirModalCadastro = false;

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

if ($_SERVER["REQUEST_METHOD"] === "POST" && ($_POST["acao"] ?? "") === "cadastrar_trem") {

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

        $erroCadastro = "Preencha todos os campos corretamente.";

    } elseif (
        !in_array($tipo_de_dado, $tiposPermitidos, true) ||
        !in_array($status, $statusPermitidos, true)
    ) {

        $erroCadastro = "Selecione um tipo de dado e um status válidos.";

    } elseif (
        strlen($localizacao) > 800 ||
        !preg_match('/^(?:[01]\d|2[0-3]):[0-5]\d$/', $horario)
    ) {

        $erroCadastro = "Verifique a localização e o horário informados.";

    } else {

        $stmtUsuario = $conexao->prepare("
            SELECT id
            FROM USUARIO
            WHERE id = ?
        ");

        if (!$stmtUsuario) {

            $erroCadastro = "Erro ao verificar o usuário responsável.";

        } else {

            $stmtUsuario->bind_param("i", $usuario_id);
            $stmtUsuario->execute();

            $resultadoUsuario = $stmtUsuario->get_result();
            $usuarioExiste = $resultadoUsuario->num_rows > 0;

            $stmtUsuario->close();

            if (!$usuarioExiste) {

                $erroCadastro = "O usuário responsável selecionado não existe.";

            } else {

                $sqlCadastro = "
                    INSERT INTO TRENS (
                        localizacao,
                        tipo_de_dado,
                        horario,
                        status,
                        usuario_id
                    )
                    VALUES (?, ?, ?, ?, ?)
                ";

                $stmtCadastro = $conexao->prepare($sqlCadastro);

                if (!$stmtCadastro) {

                    $erroCadastro = "Erro ao preparar o cadastro do trem.";

                } else {

                    $stmtCadastro->bind_param(
                        "ssssi",
                        $localizacao,
                        $tipo_de_dado,
                        $horario,
                        $status,
                        $usuario_id
                    );

                    if ($stmtCadastro->execute()) {

                        $stmtCadastro->close();

                        header("Location: tela_inicial.php?sucesso=" . urlencode("Trem cadastrado com sucesso!"));
                        exit;

                    } else {

                        $erroCadastro = "Erro ao cadastrar o trem.";

                    }

                    $stmtCadastro->close();

                }

            }

        }

    }

    if ($erroCadastro !== "") {
        $abrirModalCadastro = true;
    }

}

$cidade = trim($_GET['cidade'] ?? '') ?: 'Joinville';
$clima = obterClima($cidade);

$sensores = $conexao->query(
    "SELECT COUNT(*) AS total FROM SENSORES"
)->fetch_assoc()["total"];

$trens = $conexao->query(
    "SELECT COUNT(*) AS total FROM TRENS"
)->fetch_assoc()["total"];

$alertas = $conexao->query(
    "SELECT COUNT(*) AS total FROM TRENS WHERE status = 'Manutenção'"
)->fetch_assoc()["total"];

$sensores_ativos = $sensores - $alertas;

$sql = "
    SELECT
        TRENS.id,
        TRENS.localizacao,
        TRENS.tipo_de_dado,
        TRENS.horario,
        TRENS.status,
        TRENS.usuario_id,
        USUARIO.nome AS responsavel
    FROM TRENS
    LEFT JOIN USUARIO ON TRENS.usuario_id = USUARIO.id
    ORDER BY TRENS.id DESC
";

$trens_cadastrados = $conexao->query($sql);

$consultaUsuarios = $conexao->query("
    SELECT id, nome, tipo
    FROM USUARIO
    ORDER BY nome ASC
");

$usuarios = $consultaUsuarios
    ? $consultaUsuarios->fetch_all(MYSQLI_ASSOC)
    : [];

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dashboard</title>

    <link rel="stylesheet" href="../assets/style/style.css">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
        rel="stylesheet">

    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <link rel="icon" href="../assets/icons/TREM_AZUL.svg">

    <style>

        .painel_topo {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            align-items: stretch;
            margin-top: 20px;
            margin-bottom: 24px;
        }

        .painel_topo .informacoes_dashboard {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            grid-auto-rows: 1fr;
            gap: 20px;
            width: 100%;
            margin: 0;
        }

        .painel_topo .informacoes {
            width: 100%;
            max-width: none;
            min-width: 0;
            margin: 0;
        }

        .painel_topo .informacoes h4 {
            white-space: normal;
        }

        .painel_topo .clima_card {
            margin: 0;
            padding: 28px 30px;
            color: #d9d9d9;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            gap: 28px;
        }

        .painel_topo .clima_corpo {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            flex-wrap: wrap;
            gap: 24px;
        }

        .painel_topo .clima_cidade {
            margin-bottom: 14px;
        }

        .painel_topo .clima_temp {
            margin-bottom: 6px;
        }

        .painel_topo .clima_horarios {
            line-height: 1.9;
        }

        .painel_trens {
            margin-top: 0;
        }

        #modalEditarTrem .modal-content,
        #modalCadastrarTrem .modal-content {
            background-color: #2e3747;
            color: white;
            border: 1px solid #475569;
        }

        #modalEditarTrem .modal-header,
        #modalEditarTrem .modal-footer,
        #modalCadastrarTrem .modal-header,
        #modalCadastrarTrem .modal-footer {
            border-color: #475569;
        }

        #modalEditarTrem .form-label,
        #modalCadastrarTrem .form-label {
            color: white;
        }

        #modalEditarTrem .form-control,
        #modalEditarTrem .form-select,
        #modalCadastrarTrem .form-control,
        #modalCadastrarTrem .form-select {
            background-color: #ffffff;
            color: #212529;
        }

        @media (max-width: 1100px) {
            .painel_topo {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 576px) {
            .painel_topo .informacoes_dashboard {
                grid-template-columns: 1fr;
            }
        }

    </style>

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

        <?php if ($erroCadastro !== ""): ?>

            <div class="alert alert-danger">
                <?= e($erroCadastro) ?>
            </div>

        <?php endif; ?>

        <?php if (!empty($_GET["erro"])): ?>

            <div class="alert alert-danger">
                <?= e($_GET["erro"]) ?>
            </div>

        <?php endif; ?>

        <?php if (!empty($_GET["sucesso"])): ?>

            <div class="alert alert-success">
                <?= e($_GET["sucesso"]) ?>
            </div>

        <?php endif; ?>

        <div class="painel_topo">

            <div class="informacoes_dashboard">

                <div class="informacoes">
                    <h4>
                        <img src="../assets/icons/ENGRENAGEM.svg" alt="">
                        Sensores Cadastrados
                    </h4>

                    <h3><?= $sensores ?></h3>
                </div>

                <div class="informacoes">
                    <h4>
                        <img src="../assets/icons/TREM_AZUL.svg" alt="">
                        Trens Cadastrados
                    </h4>

                    <h3><?= $trens ?></h3>
                </div>

                <div class="informacoes">
                    <h4>
                        <img src="../assets/icons/ALERTA.svg" alt="">
                        Alertas
                    </h4>

                    <h3><?= $alertas ?></h3>
                </div>

                <div class="informacoes">
                    <h4>
                        <img src="../assets/icons/OK_VERDE.svg" alt="">
                        Sensores Funcionando
                    </h4>

                    <h3><?= $sensores_ativos ?></h3>
                </div>

            </div>

            <div class="planilha_dashboard clima_card">

                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

                    <h3 class="fs-5 mb-0" style="color: #d9d9d9 !important;">
                        <i class="bi bi-cloud-sun"></i>
                        Clima
                    </h3>

                    <form method="get" class="d-flex gap-2">

                        <input
                            type="text"
                            name="cidade"
                            class="form-control form-control-sm"
                            placeholder="Digite a cidade"
                            value="<?= e($cidade) ?>"
                        >

                        <button type="submit" class="btn btn-sm btn-primary">
                            Ver clima
                        </button>

                    </form>

                </div>

                <?php if ($clima): ?>

                    <div class="clima_corpo">

                        <div>

                            <h4 class="fs-6 clima_cidade" style="color: #d9d9d9;">
                                <i class="bi bi-geo-alt"></i>
                                <?= e($clima['cidade']) ?>
                            </h4>

                            <div class="clima_temp" style="font-size: 2.2rem; font-weight: bold; line-height: 1.1;">
                                <?= (int) $clima['temperatura'] ?>°C
                            </div>

                            <div><?= e($clima['condicao']) ?></div>

                        </div>

                        <small class="clima_horarios">
                            Última atualização da API: <?= e($clima['atualizado']) ?><br>
                            Consulta feita em: <?= e($clima['consultado']) ?><br>
                            Dados: <a href="https://open-meteo.com" target="_blank" rel="noopener">Open-Meteo.com</a>
                        </small>

                    </div>

                <?php else: ?>

                    <p class="mb-0">Cidade não encontrada ou clima indisponível no momento.</p>

                <?php endif; ?>

            </div>

        </div>

        <div class="planilha_dashboard painel_trens">

            <div class="cabecalho_planilha">

                <h3 style="color: white;">Trens Cadastrados</h3>

                <button
                    type="button"
                    class="btn btn-primary"
                    data-bs-toggle="modal"
                    data-bs-target="#modalCadastrarTrem"
                >
                    <i class="bi bi-plus-circle"></i>
                    Cadastrar Trens
                </button>

            </div>

            <div class="table-responsive">

                <table class="table table-borderless">

                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>LOCALIZAÇÃO</th>
                            <th>TIPO DE DADO</th>
                            <th>RESPONSÁVEL</th>
                            <th>STATUS</th>
                            <th>AÇÕES</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php if ($trens_cadastrados && $trens_cadastrados->num_rows > 0): ?>

                            <?php while ($trem = $trens_cadastrados->fetch_assoc()): ?>

                                <tr>

                                    <th>
                                        #<?= str_pad($trem["id"], 3, "0", STR_PAD_LEFT) ?>
                                    </th>

                                    <td>
                                        <?= e($trem["localizacao"]) ?>
                                    </td>

                                    <td>
                                        <?= e($trem["tipo_de_dado"]) ?>
                                    </td>

                                    <td>

                                        <?php if (!empty($trem["responsavel"])): ?>

                                            <?= e($trem["responsavel"]) ?>

                                        <?php else: ?>

                                            <span class="badge bg-secondary">
                                                Não definido
                                            </span>

                                        <?php endif; ?>

                                    </td>

                                    <td>

                                        <?php if ($trem["status"] === "Ativo" || $trem["status"] === "Ativa"): ?>

                                            <span class="badge bg-success">
                                                <?= e($trem["status"]) ?>
                                            </span>

                                        <?php elseif ($trem["status"] === "Manutenção"): ?>

                                            <span class="badge bg-warning text-dark">
                                                Manutenção
                                            </span>

                                        <?php else: ?>

                                            <span class="badge bg-secondary">
                                                <?= e($trem["status"]) ?>
                                            </span>

                                        <?php endif; ?>

                                    </td>

                                    <td>

                                        <button
                                            type="button"
                                            class="btn btn-sm btn-warning"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalEditarTrem"
                                            data-id="<?= (int) $trem['id'] ?>"
                                            data-localizacao="<?= e($trem['localizacao']) ?>"
                                            data-tipo="<?= e($trem['tipo_de_dado']) ?>"
                                            data-horario="<?= e(substr($trem['horario'], 0, 5)) ?>"
                                            data-status="<?= e($trem['status']) ?>"
                                            data-usuario="<?= (int) ($trem['usuario_id'] ?? 0) ?>"
                                        >
                                            <i class="bi bi-pencil"></i>
                                            Editar
                                        </button>

                                        <form
                                            method="POST"
                                            action="excluir_trem.php"
                                            style="display: inline;"
                                            onsubmit="return confirm('Deseja realmente excluir este trem?');"
                                        >

                                            <input
                                                type="hidden"
                                                name="id"
                                                value="<?= (int) $trem['id'] ?>"
                                            >

                                            <input
                                                type="hidden"
                                                name="csrf_token"
                                                value="<?= e(gerarTokenCsrf()) ?>"
                                            >

                                            <button type="submit" class="btn btn-sm btn-danger">

                                                <i class="bi bi-trash"></i>
                                                Excluir

                                            </button>

                                        </form>

                                    </td>

                                </tr>

                            <?php endwhile; ?>

                        <?php else: ?>

                            <tr>
                                <td colspan="6" class="text-center">
                                    Nenhum trem cadastrado.
                                </td>
                            </tr>

                        <?php endif; ?>

                    </tbody>

                </table>

            </div>

        </div>

    </main>

</div>

<div class="modal fade" id="modalCadastrarTrem" tabindex="-1" aria-labelledby="tituloModalCadastro" aria-hidden="true">

    <div class="modal-dialog modal-lg modal-dialog-centered">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title" id="tituloModalCadastro">
                    <i class="bi bi-train-front"></i>
                    Cadastrar Trem
                </h5>

                <button
                    type="button"
                    class="btn-close btn-close-white"
                    data-bs-dismiss="modal"
                    aria-label="Fechar"
                ></button>

            </div>

            <form method="POST" action="tela_inicial.php">

                <input type="hidden" name="acao" value="cadastrar_trem">

                <div class="modal-body">

                    <input
                        type="hidden"
                        name="csrf_token"
                        value="<?= e(gerarTokenCsrf()) ?>"
                    >

                    <div class="row g-3">

                        <div class="col-md-6">

                            <label for="cadastro_localizacao" class="form-label">
                                Localização
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                id="cadastro_localizacao"
                                name="localizacao"
                                maxlength="200"
                                placeholder="Ex.: Estação Central"
                                value="<?= e($_POST['localizacao'] ?? '') ?>"
                                required
                            >

                        </div>

                        <div class="col-md-6">

                            <label for="cadastro_tipo" class="form-label">
                                Tipo de Dado
                            </label>

                            <select
                                class="form-select"
                                id="cadastro_tipo"
                                name="tipo_de_dado"
                                required
                            >

                                <option value="">Selecione o tipo</option>

                                <?php foreach ($tiposPermitidos as $opcao): ?>

                                    <option
                                        value="<?= e($opcao) ?>"
                                        <?= ($_POST['tipo_de_dado'] ?? '') === $opcao ? 'selected' : '' ?>
                                    >
                                        <?= e($opcao) ?>
                                    </option>

                                <?php endforeach; ?>

                            </select>

                        </div>

                        <div class="col-md-6">

                            <label for="cadastro_horario" class="form-label">
                                Horário
                            </label>

                            <input
                                type="time"
                                class="form-control"
                                id="cadastro_horario"
                                name="horario"
                                value="<?= e($_POST['horario'] ?? '') ?>"
                                required
                            >

                        </div>

                        <div class="col-md-6">

                            <label for="cadastro_status" class="form-label">
                                Status
                            </label>

                            <select
                                class="form-select"
                                id="cadastro_status"
                                name="status"
                                required
                            >

                                <option value="">Selecione um status</option>

                                <?php foreach ($statusPermitidos as $opcao): ?>

                                    <option
                                        value="<?= e($opcao) ?>"
                                        <?= ($_POST['status'] ?? '') === $opcao ? 'selected' : '' ?>
                                    >
                                        <?= e($opcao) ?>
                                    </option>

                                <?php endforeach; ?>

                            </select>

                        </div>

                        <div class="col-12">

                            <label for="cadastro_usuario" class="form-label">
                                Usuário responsável
                            </label>

                            <select
                                class="form-select"
                                id="cadastro_usuario"
                                name="usuario_id"
                                required
                            >

                                <option value="">Selecione um responsável</option>

                                <?php foreach ($usuarios as $usuario): ?>

                                    <option
                                        value="<?= (int) $usuario['id'] ?>"
                                        <?= (string) ($_POST['usuario_id'] ?? '') === (string) $usuario['id'] ? 'selected' : '' ?>
                                    >

                                        <?= e($usuario['nome']) ?>

                                        (<?= $usuario['tipo'] === 'administrador' ? 'Administrador' : 'Usuário' ?>)

                                    </option>

                                <?php endforeach; ?>

                            </select>

                        </div>

                    </div>

                    <?php if (empty($usuarios)): ?>

                        <div class="alert alert-warning mt-3 mb-0">
                            Não existem usuários cadastrados para selecionar como responsáveis.
                        </div>

                    <?php endif; ?>

                </div>

                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal"
                    >
                        Cancelar
                    </button>

                    <button
                        type="submit"
                        class="btn btn-primary"
                        <?= empty($usuarios) ? "disabled" : "" ?>
                    >
                        <i class="bi bi-check-circle"></i>
                        Cadastrar Trem
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

<div class="modal fade" id="modalEditarTrem" tabindex="-1" aria-labelledby="tituloModalTrem" aria-hidden="true">

    <div class="modal-dialog modal-lg modal-dialog-centered">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title" id="tituloModalTrem">
                    <i class="bi bi-train-front"></i>
                    Editar Trem
                </h5>

                <button
                    type="button"
                    class="btn-close btn-close-white"
                    data-bs-dismiss="modal"
                    aria-label="Fechar"
                ></button>

            </div>

            <form method="POST" action="atualizar_trem.php">

                <div class="modal-body">

                    <input
                        type="hidden"
                        name="id"
                        id="editar_id"
                    >

                    <input
                        type="hidden"
                        name="csrf_token"
                        value="<?= e(gerarTokenCsrf()) ?>"
                    >

                    <div class="row g-3">

                        <div class="col-md-6">

                            <label for="editar_localizacao" class="form-label">
                                Localização
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                id="editar_localizacao"
                                name="localizacao"
                                maxlength="200"
                                placeholder="Ex.: Estação Central"
                                required
                            >

                        </div>

                        <div class="col-md-6">

                            <label for="editar_tipo" class="form-label">
                                Tipo de Dado
                            </label>

                            <select
                                class="form-select"
                                id="editar_tipo"
                                name="tipo_de_dado"
                                required
                            >

                                <option value="">Selecione o tipo</option>
                                <option value="Velocidade">Velocidade</option>
                                <option value="Temperatura">Temperatura</option>
                                <option value="Pressão">Pressão</option>
                                <option value="Localização">Localização</option>

                            </select>

                        </div>

                        <div class="col-md-6">

                            <label for="editar_horario" class="form-label">
                                Horário
                            </label>

                            <input
                                type="time"
                                class="form-control"
                                id="editar_horario"
                                name="horario"
                                required
                            >

                        </div>

                        <div class="col-md-6">

                            <label for="editar_status" class="form-label">
                                Status
                            </label>

                            <select
                                class="form-select"
                                id="editar_status"
                                name="status"
                                required
                            >

                                <option value="">Selecione um status</option>
                                <option value="Ativo">Ativo</option>
                                <option value="Inativo">Inativo</option>
                                <option value="Manutenção">Manutenção</option>

                            </select>

                        </div>

                        <div class="col-12">

                            <label for="editar_usuario" class="form-label">
                                Usuário responsável
                            </label>

                            <select
                                class="form-select"
                                id="editar_usuario"
                                name="usuario_id"
                                required
                            >

                                <option value="">Selecione um responsável</option>

                                <?php foreach ($usuarios as $usuario): ?>

                                    <option value="<?= (int) $usuario['id'] ?>">

                                        <?= e($usuario['nome']) ?>

                                        (<?= $usuario['tipo'] === 'administrador' ? 'Administrador' : 'Usuário' ?>)

                                    </option>

                                <?php endforeach; ?>

                            </select>

                        </div>

                    </div>

                </div>

                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal"
                    >
                        Cancelar
                    </button>

                    <button
                        type="submit"
                        class="btn btn-primary"
                        <?= empty($usuarios) ? "disabled" : "" ?>
                    >
                        <i class="bi bi-check-circle"></i>
                        Salvar alterações
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

<script>

    const modalEditarTrem = document.getElementById('modalEditarTrem');

    modalEditarTrem.addEventListener('show.bs.modal', function (event) {

        const botao = event.relatedTarget;

        document.getElementById('editar_id').value = botao.dataset.id;
        document.getElementById('editar_localizacao').value = botao.dataset.localizacao;
        document.getElementById('editar_tipo').value = botao.dataset.tipo;
        document.getElementById('editar_horario').value = botao.dataset.horario;
        document.getElementById('editar_status').value = botao.dataset.status;
        document.getElementById('editar_usuario').value = botao.dataset.usuario;

    });

    <?php if ($abrirModalCadastro): ?>

        const modalCadastro = new bootstrap.Modal(
            document.getElementById('modalCadastrarTrem')
        );

        modalCadastro.show();

    <?php endif; ?>

</script>

</body>
</html>