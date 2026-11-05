<?php
require_once "../infra/seguranca.php";
require_once "../infra/conexao.php";
exigirAdministrador();

$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);
if (!$id || $id <= 0) {
    header("Location: rotas.php");
    exit;
}

$stmt = $conexao->prepare("SELECT id, nome, descricao, distancia_km, trem_id, duracao_min, data_criacao, status FROM ROTAS WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$rota = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$rota) {
    header("Location: rotas.php?erro=" . urlencode("Rota não encontrada."));
    exit;
}

$trens = $conexao->query("SELECT id, localizacao, tipo_de_dado FROM TRENS ORDER BY localizacao ASC");
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Rota</title>
    <link rel="stylesheet" href="../assets/style/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        .formulario-rota{width:100%;max-width:850px;background:#0f172a;padding:35px;border-radius:16px}.formulario-rota h3{color:white;margin-bottom:25px}
        .linha-formulario{display:flex;gap:20px}.grupo-input{width:100%;margin-bottom:20px}.grupo-input label{display:block;color:white;margin-bottom:7px}
        .grupo-input input,.grupo-input select,.grupo-input textarea{width:100%;border:none;border-radius:7px;padding:10px 12px}.grupo-input input,.grupo-input select{height:46px}
        .grupo-input textarea{min-height:100px;resize:vertical}.botao_cancelar,.btn-salvar{border:none;padding:8px 14px;border-radius:6px;color:white;text-decoration:none;display:inline-block}.botao_cancelar{background:#6c757d}.btn-salvar{background:#0d6efd}
        @media(max-width:700px){.linha-formulario{display:block}.formulario-rota{padding:25px}}
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
        <a href="tela_inicial.php" class="item"><img src="../assets/icons/dashboard_branco.svg" alt="">Dashboard</a>
        <a href="gerenciar_sensores.php" class="item"><img src="../assets/icons/sensor_branco.svg" alt="">Sensores</a>
        <a href="rotas.php" class="item ativo"><img src="../assets/icons/relatorio_preto.svg" alt="">Rotas</a>
        <a href="cadastro_admin.php" class="item"><img src="../assets/icons/cadastrar_branco.svg" alt="">Cadastrar ADMs e Usuários</a>
        <a href="usuarios_cadastrados.php" class="item"><img src="../assets/icons/usuarios_branco.svg" alt="">Usuários Cadastrados</a>
    </aside>
    <main class="conteudo">
        <div class="formulario-rota">
  <h3 style="color: white;">
    <i class="bi bi-signpost-2"></i>
    Editar Rota
</h3>
            <form method="POST" action="atualizar_rota.php">
                <input type="hidden" name="csrf_token" value="<?= e(gerarTokenCsrf()) ?>">
                <input type="hidden" name="id" value="<?= (int) $rota["id"] ?>">
                <div class="grupo-input"><label for="nome">Nome da rota</label><input type="text" id="nome" name="nome" maxlength="200" value="<?= e($rota["nome"]) ?>" required></div>
                <div class="grupo-input"><label for="descricao">Descrição</label><textarea id="descricao" name="descricao" required><?= e($rota["descricao"]) ?></textarea></div>
                <div class="linha-formulario">
                    <div class="grupo-input"><label for="distancia_km">Distância (km)</label><input type="number" id="distancia_km" name="distancia_km" min="0.01" step="0.01" value="<?= e($rota["distancia_km"]) ?>" required></div>
                    <div class="grupo-input"><label for="duracao_min">Duração (minutos)</label><input type="number" id="duracao_min" name="duracao_min" min="1" step="1" value="<?= (int) $rota["duracao_min"] ?>" required></div>
                </div>
                <div class="grupo-input"><label for="trem_id">Trem vinculado</label><select id="trem_id" name="trem_id" required>
                    <?php if ($trens): while ($trem = $trens->fetch_assoc()): ?>
                        <option value="<?= (int) $trem["id"] ?>" <?= (int) $rota["trem_id"] === (int) $trem["id"] ? "selected" : "" ?>><?= e($trem["localizacao"]) ?> — <?= e($trem["tipo_de_dado"]) ?> (ID: <?= (int) $trem["id"] ?>)</option>
                    <?php endwhile; endif; ?>
                </select></div>
                <div class="grupo-input"><label for="status">Status</label><select id="status" name="status" required>
                    <option value="Ativa" <?= $rota["status"] === "Ativa" ? "selected" : "" ?>>Ativa</option>
                    <option value="Inativa" <?= $rota["status"] === "Inativa" ? "selected" : "" ?>>Inativa</option>
                    <option value="Manutenção" <?= $rota["status"] === "Manutenção" ? "selected" : "" ?>>Manutenção</option>
                </select></div>
                <p class="text-light">Criada em: <?= date("d/m/Y H:i", strtotime($rota["data_criacao"])) ?></p>
                <div class="mt-3"><a href="rotas.php" class="botao_cancelar">Cancelar</a> <button type="submit" class="btn-salvar">Salvar Alterações</button></div>
            </form>
        </div>
    </main>
</div>
</body>
</html>
