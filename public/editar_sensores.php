<?php
require_once "../infra/seguranca.php";
require_once "../infra/conexao.php";

exigirAdministrador();

$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

if (!$id || $id < 1) {
    header("Location: gerenciar_sensores.php");
    exit;
}

$sql = "SELECT id, localizacao, tipo_de_dado, trens_id, status FROM SENSORES WHERE id = ?";
$stmt = $conexao->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows === 0) {
    header("Location: gerenciar_sensores.php");
    exit;
}

$sensor = $resultado->fetch_assoc();

$resultadoTrens = $conexao->query("SELECT id, localizacao FROM TRENS ORDER BY id");
$erro = $_GET["erro"] ?? null;

function h($valor) {
    return htmlspecialchars((string)$valor, ENT_QUOTES, "UTF-8");
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Sensor</title>
    <link rel="stylesheet" href="../assets/style/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="icon" href="../assets/icons/TREM_AZUL.svg" type="image/x-icon">
</head>
<body>
<header class="cabecalho">
    <h2><img src="../assets/icons/TREM_AZUL.svg" alt=""> Bem vindo, Administrador</h2>
    <a href="../logout.php"><img src="../assets/icons/exit.svg" class="item" alt="Sair"></a>
</header>
<div class="layout">
    <aside class="menu-lateral">
        <a href="tela_inicial.php" class="item"><img src="../assets/icons/dashboard_branco.svg" alt=""> Dashboard</a>
        <a href="gerenciar_sensores.php" class="item ativo"><img src="../assets/icons/sensor_preto.svg" alt=""> Sensores</a>
        <a href="rotas.php" class="item"><img src="../assets/icons/relatorio_branco.svg" alt=""> Rotas</a>
        <a href="cadastro_admin.php" class="item"><img src="../assets/icons/cadastrar_branco.svg" alt=""> Cadastrar ADMs e Usuários</a>
        <a href="usuarios_cadastrados.php" class="item"><img src="../assets/icons/usuarios_branco.svg" alt=""> Usuários Cadastrados</a>
    </aside>
    <main class="conteudo">
        <div class="container-sensor">
          
            <?php if ($erro): ?>
                <div class="alert alert-danger"><?= h($erro) ?></div>
            <?php endif; ?>
            <form class="form-sensor" method="POST" action="salvar_edicao_sensores.php">
                <input type="hidden" name="id" value="<?= (int)$sensor['id'] ?>">
                <input type="hidden" name="csrf_token" value="<?= h(gerarTokenCsrf()) ?>">

                <h3 style="color: white;">
                    <i class="bi bi-cpu"></i>
                   Editar Sensor
                </h3>
                <div class="linha-formulario">

                
                    <div class="grupo-input">
                        <label for="localizacao">Localização</label>
                        <input type="text" id="localizacao" name="localizacao" value="<?= h($sensor['localizacao']) ?>" maxlength="150" required placeholder="Ex: Estação central - Eixo B">
                    </div>
                    <div class="grupo-input">
                        <label for="tipoDado">Tipo de Dado</label>
                        <select id="tipoDado" name="tipoDado" required>
                            <option value="" disabled>Selecione o tipo de dado</option>
                            <?php foreach (["Velocidade", "Temperatura", "Pressão", "GPS"] as $tipo): ?>
                                <option value="<?= h($tipo) ?>" <?= $sensor["tipo_de_dado"] === $tipo ? "selected" : "" ?>><?= h($tipo) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="linha-formulario">
                    <div class="grupo-input">
                        <label for="tremVinculado">Trem Vinculado</label>
                        <select id="tremVinculado" name="tremVinculado" required>
                            <option value="" disabled>Selecione um trem</option>
                            <?php while ($trem = $resultadoTrens->fetch_assoc()): ?>
                                <option value="<?= (int)$trem['id'] ?>" <?= (int)$trem['id'] === (int)$sensor['trens_id'] ? 'selected' : '' ?>>
                                    Trem <?= str_pad((string)$trem['id'], 2, "0", STR_PAD_LEFT) ?> - <?= h($trem['localizacao']) ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="grupo-input">
                        <label for="status">Status do Sensor</label>
                        <select id="status" name="status" required>
                            <?php foreach (["Ativo", "Inativo", "Manutenção", "Alerta"] as $status): ?>
                                <option value="<?= h($status) ?>" <?= $sensor["status"] === $status ? "selected" : "" ?>><?= h($status) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="botoes-formulario">
                    <button type="button" class="botao_cancelar" onclick="window.location.href='gerenciar_sensores.php'">Cancelar</button>
                    <button type="submit" class="btn-salvar">Salvar Alterações</button>
                </div>
            </form>
        </div>
    </main>
</div>
</body>
</html>
