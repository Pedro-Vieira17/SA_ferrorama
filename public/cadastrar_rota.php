<?php

require_once "../infra/seguranca.php";
require_once "../infra/conexao.php";

exigirAdministrador();

$erro = $_GET["erro"] ?? null;
$sucesso = $_GET["sucesso"] ?? null;

$sql = "SELECT id, localizacao, tipo_de_dado
        FROM TRENS
        ORDER BY localizacao ASC";

$trens = $conexao->query($sql);

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Cadastrar Rota</title>

    <link rel="stylesheet" href="../assets/style/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="icon" href="../assets/icons/TREM_AZUL.svg">

    <style>
        .area-cadastro {
            width: 100%;
        }

        .formulario-rota {
            width: 100%;
            max-width: 850px;
            background: #0f172a;
            padding: 35px;
            border-radius: 16px;
        }

        .formulario-rota h3 {
            color: white;
            margin-bottom: 30px;
            font-size: 28px;
        }

        .linha-formulario {
            display: flex;
            gap: 20px;
        }

        .grupo-input {
            width: 100%;
            margin-bottom: 20px;
        }

        .grupo-input label {
            display: block;
            color: white;
            margin-bottom: 7px;
        }

        .grupo-input input,
        .grupo-input select,
        .grupo-input textarea {
            width: 100%;
            border: none;
            border-radius: 7px;
            padding: 10px 12px;
        }

        .grupo-input input,
        .grupo-input select {
            height: 46px;
        }

        .grupo-input textarea {
            min-height: 100px;
            resize: vertical;
        }

        .botoes-formulario {
            margin-top: 10px;
        }

        .botao_cancelar {
            border: none;
            padding: 8px 14px;
            border-radius: 6px;
            background: #6c757d;
            color: white;
        }

        .btn-salvar {
            border: none;
            padding: 8px 14px;
            border-radius: 6px;
            background: #0d6efd;
            color: white;
        }

        @media (max-width: 700px) {
            .linha-formulario {
                display: block;
            }

            .formulario-rota {
                padding: 25px;
            }
        }
    </style>

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

        <?php if ($erro): ?>
            <div class="alert alert-danger">
                <?= e($erro) ?>
            </div>
        <?php endif; ?>

        <?php if ($sucesso): ?>
            <div class="alert alert-success">
                Rota cadastrada com sucesso!
            </div>
        <?php endif; ?>

        <div class="area-cadastro">

            <div class="formulario-rota">

                <h3>
                    <i class="bi bi-signpost-2"></i>
                    Cadastrar Nova Rota
                </h3>

                <?php if (!$trens || $trens->num_rows === 0): ?>

                    <div class="alert alert-warning">
                        Cadastre um trem antes de criar uma rota.
                    </div>

                    <a href="cadastrar_trens.php" class="btn btn-primary">
                        Cadastrar Trem
                    </a>

                <?php else: ?>

                    <form method="POST" action="salvar_rota.php">

                        <input
                            type="hidden"
                            name="csrf_token"
                            value="<?= e(gerarTokenCsrf()) ?>">

                        <div class="grupo-input">
                            <label for="nome">Nome da rota</label>
                            <input
                                type="text"
                                id="nome"
                                name="nome"
                                maxlength="200"
                                placeholder="Ex: Joinville - Jaraguá do Sul"
                                required>
                        </div>

                        <div class="grupo-input">
                            <label for="descricao">Descrição</label>
                            <textarea
                                id="descricao"
                                name="descricao"
                                placeholder="Descreva o trajeto da rota"
                                required></textarea>
                        </div>

                        <div class="linha-formulario">

                            <div class="grupo-input">
                                <label for="distancia_km">Distância (km)</label>
                                <input
                                    type="number"
                                    id="distancia_km"
                                    name="distancia_km"
                                    min="0.01"
                                    step="0.01"
                                    placeholder="Ex: 45.50"
                                    required>
                            </div>

                            <div class="grupo-input">
                                <label for="duracao_min">Duração (minutos)</label>
                                <input
                                    type="number"
                                    id="duracao_min"
                                    name="duracao_min"
                                    min="1"
                                    step="1"
                                    placeholder="Ex: 60"
                                    required>
                            </div>

                        </div>

                        <div class="grupo-input">
                            <label for="trem_id">Trem vinculado</label>

                            <select id="trem_id" name="trem_id" required>

                                <option value="" disabled selected>
                                    Selecione um trem
                                </option>

                                <?php while ($trem = $trens->fetch_assoc()): ?>

                                    <option value="<?= (int) $trem["id"] ?>">
                                        <?= e($trem["localizacao"]) ?>
                                        — <?= e($trem["tipo_de_dado"]) ?>
                                        (ID: <?= (int) $trem["id"] ?>)
                                    </option>

                                <?php endwhile; ?>

                            </select>
                        </div>

                        <div class="grupo-input">
                            <label for="status">Status</label>

                            <select id="status" name="status" required>
                                <option value="Ativa" selected>Ativa</option>
                                <option value="Inativa">Inativa</option>
                                <option value="Manutenção">Manutenção</option>
                            </select>
                        </div>

                        <div class="botoes-formulario">

                            <button
                                type="button"
                                class="botao_cancelar"
                                onclick="window.location.href='rotas.php'">
                                Cancelar
                            </button>

                            <button type="submit" class="btn-salvar">
                                <i class="bi bi-check-circle"></i>
                                Salvar
                            </button>

                        </div>

                    </form>

                <?php endif; ?>

            </div>

        </div>

    </main>

</div>

</body>
</html>