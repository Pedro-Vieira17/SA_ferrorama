
<?php

require_once "../infra/seguranca.php";
require_once "../infra/conexao.php";
require_once __DIR__ . '/clima.php';

exigirLogin();

if (
    !isset($_SESSION["usuario_id"]) ||
    ($_SESSION["usuario_tipo"] ?? "") !== "usuario"
) {
    header("Location: ../index.php");
    exit;
}

// ==========================================
// CLIMA
// ==========================================

$cidade = trim($_GET['cidade'] ?? '') ?: 'Joinville';
$clima = obterClima($cidade);

// ==========================================
// INFORMAÇÕES DO DASHBOARD
// ==========================================

$sensores = $conexao->query(
    "SELECT COUNT(*) AS total FROM SENSORES"
)->fetch_assoc()["total"];

$trens = $conexao->query(
    "SELECT COUNT(*) AS total FROM TRENS"
)->fetch_assoc()["total"];

$alertas = $conexao->query(
    "SELECT COUNT(*) AS total FROM TRENS WHERE status = 'Manutenção'"
)->fetch_assoc()["total"];

$sensores_ativos = $sensores;

// ==========================================
// STATUS DOS TRENS
// ==========================================

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

// ==========================================
// LISTAGEM DE TRENS
// ==========================================

$trens_cadastrados = $conexao->query("
    SELECT *
    FROM TRENS
    ORDER BY id DESC
");

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dashboard - Ferrorama</title>

    <link rel="stylesheet" href="../assets/style/style.css">

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
    >

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

        @media (max-width: 1100px) {

            .painel_topo {
                grid-template-columns: 1fr;
            }

        }

        @media (max-width: 576px) {

            .painel_topo .informacoes_dashboard {
                grid-template-columns: 1fr;
            }

            .painel_topo .clima_card {
                padding: 20px;
            }

        }

    </style>

</head>

<body>

<!-- ========================================== -->
<!-- CABEÇALHO -->
<!-- ========================================== -->

<header class="cabecalho">

    <h2>

        <img src="../assets/icons/TREM_AZUL.svg" alt="">

        Bem vindo, <?= e($_SESSION["usuario_nome"] ?? "Usuário") ?>

    </h2>

    <span class="badge bg-secondary ms-auto me-3">
        Usuário
    </span>

    <a href="../logout.php">

        <img
            src="../assets/icons/exit.svg"
            class="item"
            alt="Sair"
        >

    </a>

</header>

<div class="layout">

    <!-- ========================================== -->
    <!-- MENU LATERAL -->
    <!-- ========================================== -->

    <aside class="menu-lateral">

        <a href="tela_inicial_usuario.php" class="item ativo">

            <img src="../assets/icons/dashboard_preto.svg" alt="">

            Dashboard

        </a>

        <a href="gerenciar_sensores_usuario.php" class="item">

            <img src="../assets/icons/sensor_branco.svg" alt="">

            Sensores

        </a>

        <a href="rotas_usuario.php" class="item">

            <img src="../assets/icons/relatorio_branco.svg" alt="">

            Rotas

        </a>

    </aside>

    <!-- ========================================== -->
    <!-- CONTEÚDO PRINCIPAL -->
    <!-- ========================================== -->

    <main class="conteudo">

        <!-- ========================================== -->
        <!-- PAINEL SUPERIOR -->
        <!-- ========================================== -->

        <div class="painel_topo">

            <!-- CARDS DE INFORMAÇÕES -->

            <div class="informacoes_dashboard">

                <div class="informacoes">

                    <h4>

                        <img src="../assets/icons/ENGRENAGEM.svg" alt="">

                        Sensores Cadastrados

                    </h4>

                    <h3><?= (int) $sensores ?></h3>

                </div>

                <div class="informacoes">

                    <h4>

                        <img src="../assets/icons/TREM_AZUL.svg" alt="">

                        Trens Cadastrados

                    </h4>

                    <h3><?= (int) $trens ?></h3>

                </div>

                <div class="informacoes">

                    <h4>

                        <img src="../assets/icons/ALERTA.svg" alt="">

                        Alertas

                    </h4>

                    <h3><?= (int) $alertas ?></h3>

                </div>

                <div class="informacoes">

                    <h4>

                        <img src="../assets/icons/OK_VERDE.svg" alt="">

                        Sensores Funcionando

                    </h4>

                    <h3><?= (int) $sensores_ativos ?></h3>

                </div>

            </div>

            <!-- ========================================== -->
            <!-- CARD DE CLIMA -->
            <!-- ========================================== -->

            <div class="planilha_dashboard clima_card">

                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

                    <h3
                        class="fs-5 mb-0"
                        style="color: #d9d9d9 !important;"
                    >

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

                        <button
                            type="submit"
                            class="btn btn-sm btn-primary"
                        >

                            Ver clima

                        </button>

                    </form>

                </div>

                <?php if ($clima): ?>

                    <div class="clima_corpo">

                        <div>

                            <h4
                                class="fs-6 clima_cidade"
                                style="color: #d9d9d9;"
                            >

                                <i class="bi bi-geo-alt"></i>

                                <?= e($clima['cidade']) ?>

                            </h4>

                            <div
                                class="clima_temp"
                                style="font-size: 2.2rem; font-weight: bold; line-height: 1.1;"
                            >

                                <?= (int) $clima['temperatura'] ?>°C

                            </div>

                            <div>

                                <?= e($clima['condicao']) ?>

                            </div>

                        </div>

                        <small class="clima_horarios">

                            Última atualização da API:
                            <?= e($clima['atualizado']) ?>

                            <br>

                            Consulta feita em:
                            <?= e($clima['consultado']) ?>

                            <br>

                            Dados:

                            <a
                                href="https://open-meteo.com"
                                target="_blank"
                                rel="noopener"
                            >
                                Open-Meteo.com
                            </a>

                        </small>

                    </div>

                <?php else: ?>

                    <p class="mb-0">

                        Cidade não encontrada ou clima indisponível no momento.

                    </p>

                <?php endif; ?>

            </div>

        </div>

        <!-- ========================================== -->
        <!-- LISTAGEM DE TRENS -->
        <!-- ========================================== -->

        <div class="planilha_dashboard painel_trens">

            <div class="cabecalho_planilha">

                <h3 style="color: white;">

                    Trens Cadastrados

                </h3>

            </div>

            <div class="table-responsive">

                <table class="table table-borderless">

                    <thead>

                        <tr>

                            <th>ID</th>

                            <th>LOCALIZAÇÃO</th>

                            <th>TIPO DE DADO</th>

                            <th>STATUS</th>

                        </tr>

                    </thead>

                    <tbody>

                        <?php if ($trens_cadastrados && $trens_cadastrados->num_rows > 0): ?>

                            <?php while ($trem = $trens_cadastrados->fetch_assoc()): ?>

                                <tr>

                                    <th>

                                        #<?= str_pad(
                                            (int) $trem["id"],
                                            3,
                                            "0",
                                            STR_PAD_LEFT
                                        ) ?>

                                    </th>

                                    <td>

                                        <?= e($trem["localizacao"]) ?>

                                    </td>

                                    <td>

                                        <?= e($trem["tipo_de_dado"]) ?>

                                    </td>

                                    <td>

                                        <?php if (
                                            $trem["status"] === "Ativo" ||
                                            $trem["status"] === "Ativa"
                                        ): ?>

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

                                </tr>

                            <?php endwhile; ?>

                        <?php else: ?>

                            <tr>

                                <td colspan="4" class="text-center">

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

</body>

</html>
