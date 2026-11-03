<?php

require_once "../infra/seguranca.php";
require_once "../infra/conexao.php";

exigirAdministrador();

$sql = "SELECT 
            r.id,
            r.nome,
            r.descricao,
            r.distancia_km,
            r.duracao_min,
            r.data_criacao,
            r.status,
            t.localizacao AS trem_localizacao,
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

        <a href="rotas.php" class="item ativo">
            <img src="../assets/icons/relatorio_preto.svg" alt="">
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

        <div class="container-sensor">

            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">


               

            </div>

            <div class="planilha_dashboard">

            <div class="cabecalho_planilha">

                <h3 style="color: white;">Rotas Cadastradas</h3>

               <a href="cadastrar_rota.php" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i>
                    Cadastrar Rota
                </a>

            </div>

                <table class="table table-borderless">

                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>NOME</th>
                            <th>DISTÂNCIA</th>
                            <th>TREM</th>
                            <th>DURAÇÃO</th>
                            <th>STATUS</th>
                            <th>DATA DE CRIAÇÃO</th>
                            <th>AÇÕES</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php if ($rotas && $rotas->num_rows > 0): ?>

                            <?php while ($rota = $rotas->fetch_assoc()): ?>

                                <tr>
                                    <td>
                                        <?= e($rota["id"]) ?>
                                    </td>

                                    <td>
                                        <?= e($rota["nome"]) ?>
                                    </td>

                                    <td>
                                        <?= number_format((float) $rota["distancia_km"], 2, ",", ".") ?> km
                                    </td>

                                    <td>
                                        <?= e($rota["trem_localizacao"]) ?>
                                        <br>
                                        <small><?= e($rota["trem_tipo"]) ?></small>
                                    </td>

                                    <td>
                                        <?= e($rota["duracao_min"]) ?> min
                                    </td>

                                    <td>
                                        <?php if ($rota["status"] === "Ativa"): ?>
                                            <span class="badge bg-success">Ativa</span>
                                        <?php elseif ($rota["status"] === "Manutenção"): ?>
                                            <span class="badge bg-warning text-dark">Manutenção</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary"><?= e($rota["status"]) ?></span>
                                        <?php endif; ?>
                                    </td>

                                    <td>
                                        <?= date("d/m/Y H:i", strtotime($rota["data_criacao"])) ?>
                                    </td>

                                    <td>
                                        <a
                                            href="editar_rota.php?id=<?= (int) $rota["id"] ?>"
                                            class="btn btn-sm btn-warning">
                                            <i class="bi bi-pencil"></i>
                                            Editar
                                        </a>

                                        <form
    method="POST"
    action="excluir_rota.php"
    class="d-inline"
    onsubmit="return confirm('Tem certeza que deseja excluir esta rota?');">

    <input
        type="hidden"
        name="csrf_token"
        value="<?= e(gerarTokenCsrf()) ?>">

    <input
        type="hidden"
        name="id"
        value="<?= (int) $rota["id"] ?>">

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