<?php
require_once "../infra/seguranca.php";
require_once "../infra/conexao.php";

exigirLogin();

if (!isset($_SESSION["usuario_id"]) || ($_SESSION["usuario_tipo"] ?? "") !== "usuario") {
    header("Location: ../index.php");
    exit;
}

$sql = "SELECT r.id, r.nome, r.descricao, r.distancia_km, r.duracao_min,
               r.data_criacao, r.status, t.localizacao AS trem_localizacao,
               t.tipo_de_dado AS trem_tipo
        FROM ROTAS r
        INNER JOIN TRENS t ON r.trem_id = t.id
        ORDER BY r.data_criacao DESC";

$rotas = $conexao->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Rotas</title>

    <link rel="stylesheet" href="../assets/style/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="icon" href="../assets/icons/TREM_AZUL.svg">
</head>

<body>

<header class="cabecalho">
    <h2>
        <img src="../assets/icons/TREM_AZUL.svg" alt="">
        Bem vindo, <?= e($_SESSION["usuario_nome"] ?? "Usuário") ?>
    </h2>

    <a href="../logout.php">
        <img src="../assets/icons/exit.svg" class="item" alt="Sair">
    </a>
</header>

<div class="layout">

    <aside class="menu-lateral">
        <a href="tela_inicial_usuario.php" class="item">
            <img src="../assets/icons/dashboard_branco.svg" alt="">
            Dashboard
        </a>

        <a href="gerenciar_sensores_usuario.php" class="item">
            <img src="../assets/icons/sensor_branco.svg" alt="">
            Sensores
        </a>

        <a href="rotas_usuario.php" class="item ativo">
            <img src="../assets/icons/relatorio_preto.svg" alt="">
            Rotas
        </a>
    </aside>

    <main class="conteudo">

        <div class="container-sensor">


            <div class="planilha_dashboard table-responsive">

             <h3 style="color: white;">Rotas Cadastradas</h3>

                <table class="table table-borderless">

                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>NOME</th>
                            <th>DISTÂNCIA</th>
                            <th>TREM</th>
                            <th>DURAÇÃO</th>
                            <th>STATUS</th>
                            <th>CRIADA EM</th>
                            <th>DETALHES</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php if ($rotas && $rotas->num_rows > 0): ?>

                            <?php while ($rota = $rotas->fetch_assoc()): ?>

                                <tr>

                                    <td>
                                        <?= (int) $rota["id"] ?>
                                    </td>

                                    <td>
                                        <?= e($rota["nome"]) ?>
                                    </td>

                                    <td>
                                        <?= number_format((float) $rota["distancia_km"], 2, ",", ".") ?> km
                                    </td>

                                    <td>
                                        <span class="d-block">
                                            <?= e($rota["trem_localizacao"]) ?>
                                        </span>

                                        <small class="d-block mt-1">
                                            <?= e($rota["trem_tipo"]) ?>
                                        </small>
                                    </td>

                                    <td>
                                        <?= (int) $rota["duracao_min"] ?> min
                                    </td>

                                    <td>
                                        <?php if ($rota["status"] === "Ativa"): ?>

                                            <span class="badge bg-success">Ativa</span>

                                        <?php elseif ($rota["status"] === "Manutenção"): ?>

                                            <span class="badge bg-warning text-dark">Manutenção</span>

                                        <?php else: ?>

                                            <span class="badge bg-secondary">
                                                <?= e($rota["status"]) ?>
                                            </span>

                                        <?php endif; ?>
                                    </td>

                                    <td>
                                        <?= date("d/m/Y H:i", strtotime($rota["data_criacao"])) ?>
                                    </td>

                                    <td>
                                        <a
                                            href="detalhes_rota.php?id=<?= (int) $rota["id"] ?>"
                                            class="btn btn-sm btn-info">

                                            <i class="bi bi-eye"></i>
                                            Ver
                                        </a>
                                    </td>

                                </tr>

                            <?php endwhile; ?>

                        <?php else: ?>

                            <tr>
                                <td colspan="8" class="text-center">
                                    Nenhuma rota cadastrada.
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
```