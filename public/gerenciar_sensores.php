
<?php
require_once "../infra/seguranca.php";
require_once "../infra/conexao.php";

exigirAdministrador();

$pesquisa = isset($_GET["pesquisa"]) ? trim($_GET["pesquisa"]) : "";
$filtroTipo = isset($_GET["tipo"]) ? trim($_GET["tipo"]) : "";
$filtroTrem = isset($_GET["trem"]) ? trim($_GET["trem"]) : "";
$filtroStatus = isset($_GET["status"]) ? trim($_GET["status"]) : "";

$sql = "SELECT id, localizacao, tipo_de_dado, trens_id, status FROM SENSORES WHERE 1 = 1";

$tipos = "";
$valores = [];

if ($pesquisa !== "") {
    $sql .= " AND (tipo_de_dado LIKE ? OR localizacao LIKE ?)";
    $busca = "%" . $pesquisa . "%";
    $tipos .= "ss";
    $valores[] = $busca;
    $valores[] = $busca;
}

if ($filtroTipo !== "") {
    $sql .= " AND tipo_de_dado = ?";
    $tipos .= "s";
    $valores[] = $filtroTipo;
}

if ($filtroTrem !== "") {
    $sql .= " AND trens_id = ?";
    $tipos .= "i";
    $valores[] = $filtroTrem;
}

if ($filtroStatus !== "") {
    $sql .= " AND status = ?";
    $tipos .= "s";
    $valores[] = $filtroStatus;
}

$sql .= " ORDER BY id DESC";

$stmt = $conexao->prepare($sql);

if ($tipos !== "") {
    $stmt->bind_param($tipos, ...$valores);
}

$stmt->execute();
$resultado = $stmt->get_result();

$listaTipos = $conexao->query(
    "SELECT DISTINCT tipo_de_dado FROM SENSORES ORDER BY tipo_de_dado"
);

$listaTrens = $conexao->query(
    "SELECT id FROM TRENS ORDER BY id"
);

$trensModal = $conexao->query(
    "SELECT id, localizacao FROM TRENS ORDER BY id"
);

$listaStatus = $conexao->query(
    "SELECT DISTINCT status FROM SENSORES ORDER BY status"
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

    <title>Gerenciar Sensores</title>

    <link rel="stylesheet" href="../assets/style/style.css">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <link rel="icon" href="../assets/icons/TREM_AZUL.svg" type="image/x-icon">

    <style>
        .modal-sensor .modal-content {
    background: #2e3747;
    color: white;
    border: 1px solid #475569;
    border-radius: 14px;
}

        .modal-sensor .modal-header {
            border-bottom: 1px solid #334155;
        }

        .modal-sensor .modal-footer {
            border-top: 1px solid #334155;
        }

        .modal-sensor .btn-close {
            filter: invert(1);
        }

        .modal-sensor label {
            display: block;
            color: white;
            margin-bottom: 7px;
        }

        .modal-sensor .form-control,
        .modal-sensor .form-select {
            min-height: 44px;
            border-radius: 7px;
        }

        .modal-sensor .grupo-modal {
            margin-bottom: 18px;
        }

        .modal-sensor .modal-title {
            font-size: 22px;
        }

        .form_excluir_sensor {
            display: inline-block;
            margin-left: 4px;
        }

        @media (max-width: 700px) {
            .modal-sensor .modal-dialog {
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

        <a href="gerenciar_sensores.php" class="item ativo">
            <img src="../assets/icons/sensor_preto.svg" alt="">
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
                <?= h($_GET["erro"]) ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($_GET["sucesso"])): ?>
            <div class="alert alert-success">
                <?= h($_GET["sucesso"]) ?>
            </div>
        <?php endif; ?>

        <div class="planilha_dashboard">

            <div class="cabecalho_planilha">

                <h3>Sensores Cadastrados</h3>

                <form action="gerenciar_sensores.php" method="GET" class="filtros_sensor">

                    <input type="hidden" name="pesquisa" value="<?= h($pesquisa) ?>">

                    <div>
                        <label for="tipo" class="form-label">Tipo de Dado</label>

                        <select name="tipo" id="tipo" class="form-select">
                            <option value="">Todos</option>

                            <?php while ($t = $listaTipos->fetch_assoc()): ?>
                                <option value="<?= h($t["tipo_de_dado"]) ?>" <?= $filtroTipo === $t["tipo_de_dado"] ? "selected" : "" ?>>
                                    <?= h($t["tipo_de_dado"]) ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div>
                        <label for="trem" class="form-label">Trem</label>

                        <select name="trem" id="trem" class="form-select">
                            <option value="">Todos</option>

                            <?php while ($tr = $listaTrens->fetch_assoc()): ?>
                                <option value="<?= (int)$tr["id"] ?>" <?= $filtroTrem === (string)$tr["id"] ? "selected" : "" ?>>
                                    Trem <?= str_pad($tr["id"], 2, "0", STR_PAD_LEFT) ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div>
                        <label for="status" class="form-label">Status</label>

                        <select name="status" id="status" class="form-select">
                            <option value="">Todos</option>

                            <?php while ($s = $listaStatus->fetch_assoc()): ?>
                                <option value="<?= h($s["status"]) ?>" <?= $filtroStatus === $s["status"] ? "selected" : "" ?>>
                                    <?= h($s["status"]) ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <button type="submit" class="botao_cancelar">
                        Filtrar
                    </button>

                    <a href="gerenciar_sensores.php" class="btn btn-secondary">
                        Limpar
                    </a>

                </form>

                <button type="button" class="btn btn-primary botao_cadastrar_sensor" data-bs-toggle="modal" data-bs-target="#modalCadastrarSensor">
                    <i class="bi bi-plus-circle"></i>
                    Cadastrar Sensor
                </button>

            </div>

            <div class="tabela_responsiva">

                <table class="table table-borderless">

                    <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">NOME</th>
                            <th scope="col">LOCALIZAÇÃO</th>
                            <th scope="col">TREM</th>
                            <th scope="col">STATUS</th>
                            <th scope="col">AÇÕES</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php if ($resultado->num_rows > 0): ?>

                            <?php while ($sensor = $resultado->fetch_assoc()): ?>

                                <tr>

                                    <th scope="row">
                                        #<?= str_pad($sensor["id"], 3, "0", STR_PAD_LEFT) ?>
                                    </th>

                                    <td><?= h($sensor["tipo_de_dado"]) ?></td>

                                    <td><?= h($sensor["localizacao"]) ?></td>

                                    <td>
                                        Trem <?= str_pad($sensor["trens_id"], 2, "0", STR_PAD_LEFT) ?>
                                    </td>

                                    <td>

                                        <?php if ($sensor["status"] === "Ativo"): ?>
                                            <span class="badge bg-success">Ativo</span>

                                        <?php elseif ($sensor["status"] === "Manutenção"): ?>
                                            <span class="badge bg-warning text-dark">Manutenção</span>

                                        <?php elseif ($sensor["status"] === "Alerta"): ?>
                                            <span class="badge bg-danger">Alerta</span>

                                        <?php else: ?>
                                            <span class="badge bg-secondary">Inativo</span>
                                        <?php endif; ?>

                                    </td>

                                    <td>

                                        <button
                                            type="button"
                                            class="btn btn-sm btn-warning"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalEditarSensor"
                                            data-id="<?= (int)$sensor["id"] ?>"
                                            data-localizacao="<?= h($sensor["localizacao"]) ?>"
                                            data-tipo="<?= h($sensor["tipo_de_dado"]) ?>"
                                            data-trem="<?= (int)$sensor["trens_id"] ?>"
                                            data-status="<?= h($sensor["status"]) ?>">

                                            <i class="bi bi-pencil"></i>
                                            Editar

                                        </button>

                                        <form
                                            method="POST"
                                            action="excluir_informacoes_sensor.php"
                                            class="form_excluir_sensor"
                                            onsubmit="return confirm('Deseja realmente excluir este sensor?');">

                                            <input type="hidden" name="id" value="<?= (int)$sensor["id"] ?>">

                                            <input type="hidden" name="csrf_token" value="<?= e(gerarTokenCsrf()) ?>">

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
                                <td colspan="6" class="nenhum_sensor">
                                    Nenhum sensor encontrado.
                                </td>
                            </tr>

                        <?php endif; ?>

                    </tbody>

                </table>

            </div>

        </div>

        <p class="quantidade_sensores">
            <?= $resultado->num_rows ?> sensor(es) encontrado(s).
        </p>

    </main>

</div>

<div class="modal fade modal-sensor" id="modalCadastrarSensor" tabindex="-1" aria-hidden="true">

    <div class="modal-dialog modal-lg modal-dialog-centered">

        <div class="modal-content">

            <form method="POST" action="salvar_sensor.php">

                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-cpu"></i>
                        Cadastrar Novo Sensor
                    </h5>

                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>

                <div class="modal-body">

                    <input type="hidden" name="csrf_token" value="<?= e(gerarTokenCsrf()) ?>">

                    <div class="row">

                        <div class="col-md-6 grupo-modal">
                            <label for="cadLocalizacao">Localização</label>

                            <input
                                type="text"
                                class="form-control"
                                id="cadLocalizacao"
                                name="localizacao"
                                maxlength="150"
                                required
                                placeholder="Ex: Estação central - Eixo B">
                        </div>

                        <div class="col-md-6 grupo-modal">
                            <label for="cadTipoDado">Tipo de Dado</label>

                            <select class="form-select" id="cadTipoDado" name="tipoDado" required>
                                <option value="" selected disabled>Selecione</option>
                                <option value="Velocidade">Velocidade</option>
                                <option value="Temperatura">Temperatura</option>
                                <option value="Pressão">Pressão</option>
                            </select>
                        </div>

                    </div>

                    <div class="grupo-modal">
                        <label for="cadTremVinculado">Trem Vinculado</label>

                        <select class="form-select" id="cadTremVinculado" name="tremVinculado" required>
                            <option value="" selected disabled>Selecione um trem</option>

                            <?php while ($trem = $trensModal->fetch_assoc()): ?>
                                <option value="<?= (int)$trem["id"] ?>">
                                    Trem <?= str_pad($trem["id"], 2, "0", STR_PAD_LEFT) ?> - <?= h($trem["localizacao"]) ?>
                                </option>
                            <?php endwhile; ?>

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

<div class="modal fade modal-sensor" id="modalEditarSensor" tabindex="-1" aria-hidden="true">

    <div class="modal-dialog modal-lg modal-dialog-centered">

        <div class="modal-content">

            <form method="POST" action="salvar_edicao_sensores.php">

                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-pencil-square"></i>
                        Editar Sensor
                    </h5>

                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>

                <div class="modal-body">

                    <input type="hidden" name="id" id="editIdSensor">

                    <input type="hidden" name="csrf_token" value="<?= e(gerarTokenCsrf()) ?>">

                    <div class="row">

                        <div class="col-md-6 grupo-modal">
                            <label for="editLocalizacaoSensor">Localização</label>

                            <input
                                type="text"
                                class="form-control"
                                id="editLocalizacaoSensor"
                                name="localizacao"
                                maxlength="150"
                                required>
                        </div>

                        <div class="col-md-6 grupo-modal">
                            <label for="editTipoSensor">Tipo de Dado</label>

                            <select class="form-select" id="editTipoSensor" name="tipoDado" required>
                                <option value="Velocidade">Velocidade</option>
                                <option value="Temperatura">Temperatura</option>
                                <option value="Pressão">Pressão</option>
                                <option value="GPS">GPS</option>
                            </select>
                        </div>

                    </div>

                    <div class="row">

                        <div class="col-md-6 grupo-modal">
                            <label for="editTremSensor">Trem Vinculado</label>

                            <select class="form-select" id="editTremSensor" name="tremVinculado" required>

                                <?php
                                $trensEdicao = $conexao->query("SELECT id, localizacao FROM TRENS ORDER BY id");

                                while ($trem = $trensEdicao->fetch_assoc()):
                                ?>
                                    <option value="<?= (int)$trem["id"] ?>">
                                        Trem <?= str_pad($trem["id"], 2, "0", STR_PAD_LEFT) ?> - <?= h($trem["localizacao"]) ?>
                                    </option>
                                <?php endwhile; ?>

                            </select>
                        </div>

                        <div class="col-md-6 grupo-modal">
                            <label for="editStatusSensor">Status do Sensor</label>

                            <select class="form-select" id="editStatusSensor" name="status" required>
                                <option value="Ativo">Ativo</option>
                                <option value="Inativo">Inativo</option>
                                <option value="Manutenção">Manutenção</option>
                                <option value="Alerta">Alerta</option>
                            </select>
                        </div>

                    </div>

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
document.getElementById("modalEditarSensor").addEventListener("show.bs.modal", function (event) {
    const botao = event.relatedTarget;

    document.getElementById("editIdSensor").value = botao.getAttribute("data-id");
    document.getElementById("editLocalizacaoSensor").value = botao.getAttribute("data-localizacao");
    document.getElementById("editTipoSensor").value = botao.getAttribute("data-tipo");
    document.getElementById("editTremSensor").value = botao.getAttribute("data-trem");
    document.getElementById("editStatusSensor").value = botao.getAttribute("data-status");
});
</script>

</body>
</html>
