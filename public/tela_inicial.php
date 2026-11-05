<?php

require_once "../infra/seguranca.php";
require_once "../infra/conexao.php";
require_once __DIR__ . '/clima.php';

exigirAdministrador();

// Clima (API Open-Meteo): cidade digitada pelo usuário, padrão Joinville
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

$statusCount = $conexao->query("
    SELECT status, COUNT(*) AS total
    FROM TRENS
    GROUP BY status
");

$statusData = [];

while ($linha = $statusCount->fetch_assoc()) {
    $statusData[$linha["status"]] = (int) $linha["total"];
}

$trensAtivos = $statusData["Ativo"] ?? 0;
$trensManutencao = $statusData["Manutenção"] ?? 0;
$trensInativos = $statusData["Inativo"] ?? 0;

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

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>

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

        <!-- ================= CLIMA (API Open-Meteo) ================= -->
        <div class="planilha_dashboard mt-4 mb-4" style="padding: 24px; color: #d9d9d9;">

            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-3">

                <h3 class="fs-5 mb-0" style="color: #d9d9d9 !important;">
                    <i class="bi bi-cloud-sun"></i>
                    Clima
                </h3>

                <form method="get" class="d-flex gap-2">
                    <input type="text" name="cidade" class="form-control form-control-sm"
                        placeholder="Digite a cidade"
                        value="<?= e($cidade) ?>">
                    <button type="submit" class="btn btn-sm btn-primary">Ver clima</button>
                </form>

            </div>

            <?php if ($clima): ?>

                <h4 class="fs-6" style="color: #d9d9d9;">
                    <i class="bi bi-geo-alt"></i>
                    <?= e($clima['cidade']) ?>
                </h4>

                <div style="font-size: 2.2rem; font-weight: bold;">
                    <?= (int) $clima['temperatura'] ?>°C
                </div>

                <div class="mb-2"><?= e($clima['condicao']) ?></div>

                <small>
                    Última atualização da API: <?= e($clima['atualizado']) ?><br>
                    Consulta feita em: <?= e($clima['consultado']) ?><br>
                    Dados: <a href="https://open-meteo.com" target="_blank" rel="noopener">Open-Meteo.com</a>
                </small>

            <?php else: ?>

                <p class="mb-0">Cidade não encontrada ou clima indisponível no momento.</p>

            <?php endif; ?>

        </div>
        <!-- =========================================================== -->

        <div class="planilha_dashboard">

            <div class="cabecalho_planilha">

                <h3 style="color: white;">Trens Cadastrados</h3>

                <a href="cadastrar_trens.php" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i>
                    Cadastrar Trens
                </a>

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
                                        <?= htmlspecialchars($trem["localizacao"]) ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($trem["tipo_de_dado"]) ?>
                                    </td>

                                    <td>

                                        <?php if (!empty($trem["responsavel"])): ?>

                                            <?= htmlspecialchars($trem["responsavel"]) ?>

                                        <?php else: ?>

                                            <span class="badge bg-secondary">
                                                Não definido
                                            </span>

                                        <?php endif; ?>

                                    </td>

                                    <td>

                                        <?php if ($trem["status"] === "Ativo" || $trem["status"] === "Ativa"): ?>

                                            <span class="badge bg-success">
                                                <?= htmlspecialchars($trem["status"]) ?>
                                            </span>

                                        <?php elseif ($trem["status"] === "Manutenção"): ?>

                                            <span class="badge bg-warning text-dark">
                                                Manutenção
                                            </span>

                                        <?php else: ?>

                                            <span class="badge bg-secondary">
                                                <?= htmlspecialchars($trem["status"]) ?>
                                            </span>

                                        <?php endif; ?>

                                    </td>

                                    <td>

                                        <a
                                            href="editar_trens.php?id=<?= (int) $trem['id'] ?>"
                                            class="btn btn-sm btn-warning">

                                            <i class="bi bi-pencil"></i>
                                            Editar

                                        </a>

                                        <form
                                            method="POST"
                                            action="excluir_trem.php"
                                            style="display: inline;"
                                            onsubmit="return confirm('Deseja realmente excluir este trem?');">

                                            <input
                                                type="hidden"
                                                name="id"
                                                value="<?= (int) $trem['id'] ?>">

                                            <input
                                                type="hidden"
                                                name="csrf_token"
                                                value="<?= e(gerarTokenCsrf()) ?>">

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

       
</script>

</body>
</html>