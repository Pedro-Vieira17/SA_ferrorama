
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
            r.trem_id,
            t.localizacao AS trem_localizacao,
            t.tipo_de_dado AS trem_tipo
        FROM ROTAS r
        INNER JOIN TRENS t ON r.trem_id = t.id
        ORDER BY r.data_criacao DESC";

$rotas = $conexao->query($sql);

$trensCadastro = $conexao->query(
    "SELECT id, localizacao, tipo_de_dado FROM TRENS ORDER BY localizacao ASC"
);

$trensEdicao = $conexao->query(
    "SELECT id, localizacao, tipo_de_dado FROM TRENS ORDER BY localizacao ASC"
);

function h($valor) {
    return htmlspecialchars((string)$valor, ENT_QUOTES, "UTF-8");
}
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

    <style>
        .modal-rota .modal-content {
            background: #2e3747;
            color: white;
            border: 1px solid #475569;
            border-radius: 14px;
        }

        .modal-rota .modal-header {
            border-bottom: 1px solid #475569;
        }

        .modal-rota .modal-footer {
            border-top: 1px solid #475569;
        }

        .modal-rota .btn-close {
            filter: invert(1);
        }

        .modal-rota label {
            display: block;
            color: white;
            margin-bottom: 7px;
        }

        .modal-rota .form-control,
        .modal-rota .form-select {
            min-height: 44px;
            border-radius: 7px;
        }

        .modal-rota textarea.form-control {
            min-height: 100px;
            resize: vertical;
        }

        .modal-rota .grupo-modal {
            margin-bottom: 18px;
        }

        @media (max-width: 700px) {
            .modal-rota .modal-dialog {
                margin: 10px;
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

            <div class="planilha_dashboard">

                <div class="cabecalho_planilha">

                    <h3 style="color: white;">Rotas Cadastradas</h3>

                    <?php if ($trensCadastro && $trensCadastro->num_rows > 0): ?>

                        <button
                            type="button"
                            class="btn btn-primary"
                            data-bs-toggle="modal"
                            data-bs-target="#modalCadastrarRota">

                            <i class="bi bi-plus-circle"></i>
                            Cadastrar Rota

                        </button>

                    <?php else: ?>

                        <button type="button" class="btn btn-primary" disabled>
                            <i class="bi bi-plus-circle"></i>
                            Cadastrar Rota
                        </button>

                    <?php endif; ?>

                </div>

                <?php if (!$trensCadastro || $trensCadastro->num_rows === 0): ?>
                    <div class="alert alert-warning m-3">
                        Cadastre um trem antes de criar uma rota.
                        <a href="cadastrar_trens.php" class="alert-link">Cadastrar Trem</a>
                    </div>
                <?php endif; ?>

                <div class="table-responsive">

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
                                            <?= h($rota["id"]) ?>
                                        </td>

                                        <td>
                                            <?= h($rota["nome"]) ?>
                                        </td>

                                        <td>
                                            <?= number_format((float)$rota["distancia_km"], 2, ",", ".") ?> km
                                        </td>

                                        <td>
                                            <?= h($rota["trem_localizacao"]) ?>
                                            <br>
                                            <small><?= h($rota["trem_tipo"]) ?></small>
                                        </td>

                                        <td>
                                            <?= h($rota["duracao_min"]) ?> min
                                        </td>

                                        <td>

                                            <?php if ($rota["status"] === "Ativa"): ?>
                                                <span class="badge bg-success">Ativa</span>

                                            <?php elseif ($rota["status"] === "Manutenção"): ?>
                                                <span class="badge bg-warning text-dark">Manutenção</span>

                                            <?php else: ?>
                                                <span class="badge bg-secondary"><?= h($rota["status"]) ?></span>
                                            <?php endif; ?>

                                        </td>

                                        <td>
                                            <?= date("d/m/Y H:i", strtotime($rota["data_criacao"])) ?>
                                        </td>

                                        <td>

                                            <button
                                                type="button"
                                                class="btn btn-sm btn-warning"
                                                data-bs-toggle="modal"
                                                data-bs-target="#modalEditarRota"
                                                data-id="<?= (int)$rota["id"] ?>"
                                                data-nome="<?= h($rota["nome"]) ?>"
                                                data-descricao="<?= h($rota["descricao"]) ?>"
                                                data-distancia="<?= h($rota["distancia_km"]) ?>"
                                                data-duracao="<?= (int)$rota["duracao_min"] ?>"
                                                data-trem="<?= (int)$rota["trem_id"] ?>"
                                                data-status="<?= h($rota["status"]) ?>"
                                                data-criacao="<?= h($rota["data_criacao"]) ?>">

                                                <i class="bi bi-pencil"></i>
                                                Editar

                                            </button>

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
                                                    value="<?= (int)$rota["id"] ?>">

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

        </div>

    </main>

</div>

<div class="modal fade modal-rota" id="modalCadastrarRota" tabindex="-1" aria-hidden="true">

    <div class="modal-dialog modal-lg modal-dialog-centered">

        <div class="modal-content">

            <form method="POST" action="salvar_rota.php">

                <div class="modal-header">

                    <h5 class="modal-title">
                        <i class="bi bi-signpost-2"></i>
                        Cadastrar Nova Rota
                    </h5>

                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>

                </div>

                <div class="modal-body">

                    <input
                        type="hidden"
                        name="csrf_token"
                        value="<?= e(gerarTokenCsrf()) ?>">

                    <div class="grupo-modal">
                        <label for="cadNomeRota">Nome da rota</label>

                        <input
                            type="text"
                            class="form-control"
                            id="cadNomeRota"
                            name="nome"
                            maxlength="200"
                            placeholder="Ex: Joinville - Jaraguá do Sul"
                            required>
                    </div>

                    <div class="grupo-modal">
                        <label for="cadDescricaoRota">Descrição</label>

                        <textarea
                            class="form-control"
                            id="cadDescricaoRota"
                            name="descricao"
                            placeholder="Descreva o trajeto da rota"
                            required></textarea>
                    </div>

                    <div class="row">

                        <div class="col-md-6 grupo-modal">
                            <label for="cadDistanciaRota">Distância (km)</label>

                            <input
                                type="number"
                                class="form-control"
                                id="cadDistanciaRota"
                                name="distancia_km"
                                min="0.01"
                                step="0.01"
                                placeholder="Ex: 45.50"
                                required>
                        </div>

                        <div class="col-md-6 grupo-modal">
                            <label for="cadDuracaoRota">Duração (minutos)</label>

                            <input
                                type="number"
                                class="form-control"
                                id="cadDuracaoRota"
                                name="duracao_min"
                                min="1"
                                step="1"
                                placeholder="Ex: 60"
                                required>
                        </div>

                    </div>

                    <div class="grupo-modal">
                        <label for="cadTremRota">Trem vinculado</label>

                        <select
                            class="form-select"
                            id="cadTremRota"
                            name="trem_id"
                            required>

                            <option value="" disabled selected>
                                Selecione um trem
                            </option>

                            <?php while ($trem = $trensCadastro->fetch_assoc()): ?>

                                <option value="<?= (int)$trem["id"] ?>">
                                    <?= h($trem["localizacao"]) ?>
                                    — <?= h($trem["tipo_de_dado"]) ?>
                                    (ID: <?= (int)$trem["id"] ?>)
                                </option>

                            <?php endwhile; ?>

                        </select>
                    </div>

                    <div class="grupo-modal">
                        <label for="cadStatusRota">Status</label>

                        <select
                            class="form-select"
                            id="cadStatusRota"
                            name="status"
                            required>

                            <option value="Ativa" selected>Ativa</option>
                            <option value="Inativa">Inativa</option>
                            <option value="Manutenção">Manutenção</option>

                        </select>
                    </div>

                </div>

                <div class="modal-footer">

                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Cancelar
                    </button>

                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle"></i>
                        Salvar
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

<div class="modal fade modal-rota" id="modalEditarRota" tabindex="-1" aria-hidden="true">

    <div class="modal-dialog modal-lg modal-dialog-centered">

        <div class="modal-content">

            <form method="POST" action="atualizar_rota.php">

                <div class="modal-header">

                    <h5 class="modal-title">
                        <i class="bi bi-pencil-square"></i>
                        Editar Rota
                    </h5>

                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>

                </div>

                <div class="modal-body">

                    <input type="hidden" name="csrf_token" value="<?= e(gerarTokenCsrf()) ?>">

                    <input type="hidden" name="id" id="editIdRota">

                    <div class="grupo-modal">
                        <label for="editNomeRota">Nome da rota</label>

                        <input
                            type="text"
                            class="form-control"
                            id="editNomeRota"
                            name="nome"
                            maxlength="200"
                            required>
                    </div>

                    <div class="grupo-modal">
                        <label for="editDescricaoRota">Descrição</label>

                        <textarea
                            class="form-control"
                            id="editDescricaoRota"
                            name="descricao"
                            required></textarea>
                    </div>

                    <div class="row">

                        <div class="col-md-6 grupo-modal">
                            <label for="editDistanciaRota">Distância (km)</label>

                            <input
                                type="number"
                                class="form-control"
                                id="editDistanciaRota"
                                name="distancia_km"
                                min="0.01"
                                step="0.01"
                                required>
                        </div>

                        <div class="col-md-6 grupo-modal">
                            <label for="editDuracaoRota">Duração (minutos)</label>

                            <input
                                type="number"
                                class="form-control"
                                id="editDuracaoRota"
                                name="duracao_min"
                                min="1"
                                step="1"
                                required>
                        </div>

                    </div>

                    <div class="grupo-modal">
                        <label for="editTremRota">Trem vinculado</label>

                        <select
                            class="form-select"
                            id="editTremRota"
                            name="trem_id"
                            required>

                            <?php while ($trem = $trensEdicao->fetch_assoc()): ?>

                                <option value="<?= (int)$trem["id"] ?>">
                                    <?= h($trem["localizacao"]) ?>
                                    — <?= h($trem["tipo_de_dado"]) ?>
                                    (ID: <?= (int)$trem["id"] ?>)
                                </option>

                            <?php endwhile; ?>

                        </select>
                    </div>

                    <div class="grupo-modal">
                        <label for="editStatusRota">Status</label>

                        <select
                            class="form-select"
                            id="editStatusRota"
                            name="status"
                            required>

                            <option value="Ativa">Ativa</option>
                            <option value="Inativa">Inativa</option>
                            <option value="Manutenção">Manutenção</option>

                        </select>
                    </div>

                    <p class="text-light mb-0" id="dataCriacaoRota"></p>

                </div>

                <div class="modal-footer">

                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Cancelar
                    </button>

                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle"></i>
                        Salvar Alterações
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.getElementById("modalEditarRota").addEventListener("show.bs.modal", function (event) {
    const botao = event.relatedTarget;

    document.getElementById("editIdRota").value = botao.getAttribute("data-id");
    document.getElementById("editNomeRota").value = botao.getAttribute("data-nome");
    document.getElementById("editDescricaoRota").value = botao.getAttribute("data-descricao");
    document.getElementById("editDistanciaRota").value = botao.getAttribute("data-distancia");
    document.getElementById("editDuracaoRota").value = botao.getAttribute("data-duracao");
    document.getElementById("editTremRota").value = botao.getAttribute("data-trem");
    document.getElementById("editStatusRota").value = botao.getAttribute("data-status");

    const data = botao.getAttribute("data-criacao");

    if (data) {
        const partes = data.split(/[- :]/);
        document.getElementById("dataCriacaoRota").textContent =
            "Criada em: " + partes[2] + "/" + partes[1] + "/" + partes[0] + " " + partes[3] + ":" + partes[4];
    }
});
</script>

</body>
</html>
