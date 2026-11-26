<?php
require_once "../infra/seguranca.php";
require_once "../infra/conexao.php";


exigirAdministrador();

$id = $_GET['id'] ?? null;

if (!$id || !ctype_digit((string)$id)) {
    header('Location: gerenciar_sensores.html?erro=' . urlencode('Trem inválido.'));
    exit;
}

// Busca o trem
$stmt = $pdo->prepare('SELECT id, nome, localizacao, tipo_de_dado, status FROM TRENS WHERE id = :id');
$stmt->execute(['id' => $id]);
$trem = $stmt->fetch();

if (!$trem) {
    header('Location: gerenciar_sensores.html?erro=' . urlencode('Trem não encontrado.'));
    exit;
}

// Conta quantos sensores estão vinculados a esse trem (serão apagados junto, por causa do ON DELETE CASCADE)
$stmtSensores = $pdo->prepare('SELECT COUNT(*) AS total FROM SENSORES WHERE trens_id = :id');
$stmtSensores->execute(['id' => $id]);
$totalSensores = $stmtSensores->fetch()['total'];
?>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Excluir informações do trem</title>
    <link rel="stylesheet" href="../assets/style/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">

          <link rel="icon" href="../assets/icons/TREM_AZUL.svg" type="image/x-icon">
</head>

<body>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>

    <header class="cabecalho">
        <h2><img src="../assets/icons/TREM_AZUL.svg" alt=""> Bem vindo, Administrador</h2>
        <a href="login.html">
            <img src="../assets/icons/exit.svg" class="item" alt=""> 
        </a>
    </header>

    <div class="layout">

        <aside class="menu-lateral">
            <a href="tela_inicial.html" class="item">
                <img src="../assets/icons/dashboard_branco.svg" alt=""> Dashboard
            </a>
            <a href="gerenciar_sensores.html" class="item ativo">
                <img src="../assets/icons/sensor_preto.svg" alt=""> Sensores
            </a>
            <a href="relatorios.html" class="item">
                <img src="../assets/icons/relatorio_branco.svg" alt=""> Relatórios
            </a>
            <a href="tela_de_cadastro.html" class="item">
                <img src="../assets/icons/cadastrar_branco.svg" alt=""> Cadastrar Usuários
            </a>
            <a href="usuarios_cadastrados.html" class="item">
                <img src="../assets/icons/usuarios_branco.svg" alt=""> Usuários Cadastrados
            </a>
        </aside>

        <main class="conteudo">

            <div class="excluir">
                <img src="../assets/img/cuidado-vermelho-do-triângulo-que-adverte-ilustração-alerta-do-vetor-do-sinal-isolada-no-fundo-branco-seja-cuidadoso-não-82145994-removebg-preview.png"
                    alt="">
                <h3>Deseja realmente excluir o trem "<?= htmlspecialchars($trem['nome']) ?>"?</h3>
                <p>Localização: <?= htmlspecialchars($trem['localizacao']) ?></p>

                <?php if ($totalSensores > 0): ?>
                    <p class="trem_excluir_aviso">
                        Atenção: <?= $totalSensores ?>
                        sensor<?= $totalSensores > 1 ? 'es' : '' ?> vinculado<?= $totalSensores > 1 ? 's' : '' ?>
                        a este trem também <?= $totalSensores > 1 ? 'serão excluídos' : 'será excluído' ?>.
                    </p>
                <?php endif; ?>

                <p>Esta ação não pode ser desfeita.</p>

                <form method="POST" action="excluir_trem.php" class="form_inline">
                    <input type="hidden" name="id" value="<?= $trem['id'] ?>">
                    <button type="button" class="botao_cancelar"
                        onclick="window.location.href='gerenciar_sensores.html'">Cancelar</button>
                    <button type="submit" class="botao_excluir">Excluir</button>
                </form>
            </div>

        </main>

    </div>

</body>

</html>