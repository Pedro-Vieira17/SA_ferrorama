<?php

require_once "../infra/seguranca.php";
require_once "../infra/conexao.php";

exigirAdministrador();

$sql = "SELECT id, localizacao FROM TRENS ORDER BY id";

$resultadoTrens = $conexao->query($sql);

$erro = $_GET['erro'] ?? null;
$sucesso = $_GET['sucesso'] ?? null;

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Cadastrar Sensor</title>

    <link rel="stylesheet" href="../assets/style/style.css">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
        rel="stylesheet">

    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <link rel="icon"
        href="../assets/icons/TREM_AZUL.svg"
        type="image/x-icon">

    <style>

        .area-cadastro {
            width: 100%;
        }

        .formulario-sensor {
            width: 100%;
            max-width: 850px;
            background: #0f172a;
            padding: 35px;
            border-radius: 16px;
        }

        .formulario-sensor h3 {
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
        .grupo-input select {
            width: 100%;
            height: 46px;
            border: none;
            border-radius: 7px;
            padding: 0 12px;
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

            .formulario-sensor {
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

        <img
            src="../assets/icons/exit.svg"
            class="item"
            alt="Sair">

    </a>

</header>

<div class="layout">

    <aside class="menu-lateral">

        <a href="tela_inicial.php" class="item">

            <img
                src="../assets/icons/dashboard_branco.svg"
                alt="">

            Dashboard

        </a>

        <a href="gerenciar_sensores.php" class="item ativo">

            <img
                src="../assets/icons/sensor_preto.svg"
                alt="">

            Sensores

        </a>

        <a href="rotas.php" class="item">

            <img
                src="../assets/icons/relatorio_branco.svg"
                alt="">

            Rotas

        </a>

        <a href="cadastro_admin.php" class="item">

            <img
                src="../assets/icons/cadastrar_branco.svg"
                alt="">

            Cadastrar ADMs e Usuários

        </a>

        <a href="usuarios_cadastrados.php" class="item">

            <img
                src="../assets/icons/usuarios_branco.svg"
                alt="">

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

                Sensor cadastrado com sucesso!

            </div>

        <?php endif; ?>

        <div class="area-cadastro">

            <div class="formulario-sensor">

                <h3>
                    <i class="bi bi-cpu"></i>
                   Cadastrar Novo Sensor
                </h3>

                <form
                    method="POST"
                    action="salvar_sensor.php">

                    <input
                        type="hidden"
                        name="csrf_token"
                        value="<?= e(gerarTokenCsrf()) ?>">

                    <div class="linha-formulario">

                        <div class="grupo-input">

                            <label for="localizacao">
                                Localização
                            </label>

                            <input
                                type="text"
                                id="localizacao"
                                name="localizacao"
                                required
                                placeholder="Ex: Estação central - Eixo B">

                        </div>

                        <div class="grupo-input">

                            <label for="tipoDado">
                                Tipo de Dado
                            </label>

                            <select
                                id="tipoDado"
                                name="tipoDado"
                                required>

                                <option value="" disabled selected>
                                    Selecione
                                </option>

                                <option value="Velocidade">
                                    Velocidade
                                </option>

                                <option value="Temperatura">
                                    Temperatura
                                </option>

                                <option value="Pressão">
                                    Pressão
                                </option>

                            </select>

                        </div>

                    </div>

                    <div class="grupo-input">

                        <label for="tremVinculado">
                            Trem Vinculado
                        </label>

                        <select
                            id="tremVinculado"
                            name="tremVinculado"
                            required>

                            <option value="" disabled selected>
                                Selecione um trem
                            </option>

                            <?php while ($trem = $resultadoTrens->fetch_assoc()): ?>

                                <option value="<?= (int)$trem['id'] ?>">

                                    Trem <?= str_pad($trem['id'], 2, "0", STR_PAD_LEFT) ?>

                                    -

                                    <?= e($trem['localizacao']) ?>

                                </option>

                            <?php endwhile; ?>

                        </select>

                    </div>

                    <div class="botoes-formulario">

                        <button
                            type="button"
                            class="botao_cancelar"
                            onclick="window.location.href='gerenciar_sensores.php'">

                            Cancelar

                        </button>

                        <button
                            type="submit"
                            class="btn-salvar">

                            <i class="bi bi-check-circle"></i>
                            Salvar

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </main>

</div>

</body>

</html>