<?php
require_once "../infra/seguranca.php";
require_once "../infra/conexao.php";
exigirLogin();

$tipoUsuario = $_SESSION["usuario_tipo"] ?? "";
if (!isset($_SESSION["usuario_id"]) || !in_array($tipoUsuario, ["usuario", "administrador"], true)) {
    header("Location: ../index.php");
    exit;
}

$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);
if (!$id || $id <= 0) {
    header("Location: " . ($tipoUsuario === "administrador" ? "rotas.php" : "rotas_usuario.php"));
    exit;
}

$stmt = $conexao->prepare("SELECT r.id, r.nome, r.descricao, r.distancia_km, r.duracao_min, r.data_criacao, r.status, t.localizacao AS trem_localizacao, t.tipo_de_dado AS trem_tipo FROM ROTAS r INNER JOIN TRENS t ON r.trem_id = t.id WHERE r.id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$rota = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$rota) {
    header("Location: " . ($tipoUsuario === "administrador" ? "rotas.php" : "rotas_usuario.php") . "?erro=" . urlencode("Rota não encontrada."));
    exit;
}

$voltar = $tipoUsuario === "administrador" ? "rotas.php" : "rotas_usuario.php";
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalhes da Rota</title>
    <link rel="stylesheet" href="../assets/style/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>
<header class="cabecalho">
    <h2><img src="../assets/icons/TREM_AZUL.svg" alt=""><?= $tipoUsuario === "administrador" ? "Bem vindo, Administrador" : "Bem vindo, " . e($_SESSION["usuario_nome"] ?? "Usuário") ?></h2>
    <a href="../logout.php"><img src="../assets/icons/exit.svg" class="item" alt="Sair"></a>
</header>
<div class="layout">
    <aside class="menu-lateral">
        <?php if ($tipoUsuario === "administrador"): ?>
            <a href="tela_inicial.php" class="item"><img src="../assets/icons/dashboard_branco.svg" alt="">Dashboard</a>
            <a href="gerenciar_sensores.php" class="item"><img src="../assets/icons/sensor_branco.svg" alt="">Sensores</a>
            <a href="rotas.php" class="item ativo"><img src="../assets/icons/relatorio_preto.svg" alt="">Rotas</a>
            <a href="cadastro_admin.php" class="item"><img src="../assets/icons/cadastrar_branco.svg" alt="">Cadastrar ADMs e Usuários</a>
            <a href="usuarios_cadastrados.php" class="item"><img src="../assets/icons/usuarios_branco.svg" alt="">Usuários Cadastrados</a>
        <?php else: ?>
            <a href="tela_inicial_usuario.php" class="item"><img src="../assets/icons/dashboard_branco.svg" alt="">Dashboard</a>
            <a href="gerenciar_sensores_usuario.php" class="item"><img src="../assets/icons/sensor_branco.svg" alt="">Sensores</a>
            <a href="rotas_usuario.php" class="item ativo"><img src="../assets/icons/relatorio_preto.svg" alt="">Rotas</a>
        <?php endif; ?>
    </aside>
    <main class="conteudo">
        <div class="container-sensor">
            <h1 class="titulo-sensor" style="color:white;"><i class="bi bi-signpost-2"></i> Detalhes da Rota</h1>
            <div class="card mt-4" style="max-width:850px;">
                <div class="card-body">
                    <h3 class="card-title"><?= e($rota["nome"]) ?></h3>
                    <p><strong>ID:</strong> <?= (int) $rota["id"] ?></p>
                    <p><strong>Descrição:</strong><br><?= nl2br(e($rota["descricao"])) ?></p>
                    <p><strong>Distância:</strong> <?= number_format((float) $rota["distancia_km"], 2, ",", ".") ?> km</p>
                    <p><strong>Trem vinculado:</strong> <?= e($rota["trem_localizacao"]) ?> (<?= e($rota["trem_tipo"]) ?>)</p>
                    <p><strong>Duração:</strong> <?= (int) $rota["duracao_min"] ?> minutos</p>
                    <p><strong>Status:</strong> <?= e($rota["status"]) ?></p>
                    <p><strong>Data de criação:</strong> <?= date("d/m/Y H:i", strtotime($rota["data_criacao"])) ?></p>
                    <a href="<?= e($voltar) ?>" class="btn btn-secondary">Voltar</a>
                    <?php if ($tipoUsuario === "administrador"): ?>
                        <a href="editar_rota.php?id=<?= (int) $rota["id"] ?>" class="btn btn-warning"><i class="bi bi-pencil"></i> Editar</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>
</div>
</body>
</html>
