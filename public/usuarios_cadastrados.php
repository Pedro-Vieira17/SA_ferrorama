<?php

require_once "../infra/seguranca.php";
require_once "../infra/conexao.php";

exigirAdministrador();

$stmt = $conexao->prepare(
    "SELECT id, nome, CPF, telefone, email, tipo
     FROM USUARIO
     ORDER BY id DESC"
);

$stmt->execute();

$usuarios = $stmt->get_result();

?>



<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuarios cadastrados</title>
    <link rel="stylesheet" href="../assets/style/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
          <link rel="icon" href="../assets/icons/TREM_AZUL.svg" type="image/x-icon">
</head>

<body>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>

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

            <img
                src="../assets/icons/dashboard_branco.svg"
                alt="">

            Dashboard

        </a>


        <a href="gerenciar_sensores.php" class="item">

            <img
                src="../assets/icons/sensor_branco.svg"
                alt="">

            Sensores

        </a>


        <a href="rotas.php" class="item">

            <img src="../assets/icons/relatorio_branco.svg" alt="">

            Rotas

        </a>


        <a href="cadastro_admin.php" class="item">

            <img
                src="../assets/icons/cadastrar_branco.svg"
                alt="">

            Cadastrar ADMs e Usuários

        </a>


        <a href="usuarios_cadastrados.php" class="item ativo">

            <img
                src="../assets/icons/usuarios_preto.svg"
                alt="">

            Usuários Cadastrados

        </a>

    </aside>


        <main class="conteudo">

        

            <div class="planilha_dashboard">

            <h2 class="titulo_cadastro">
                    Listagem de Usuários Cadastrados
                </h2>

    <table class="table table-borderless">
        <thead>
    <tr>
        <th>NOME COMPLETO</th>
        <th>CPF</th>
        <th>TELEFONE</th>
        <th>E-MAIL</th>
           <th>TIPO DE ACESSO</th>
        <th>AÇÕES</th>
    </tr>
</thead>

        <tbody>

   <?php while ($usuario = $usuarios->fetch_assoc()): ?>

<tr>

    <td>
        <?= e($usuario["nome"]) ?>
    </td>

    <td>
        <?= e($usuario["CPF"]) ?>
    </td>

    <td>
        <?= e($usuario["telefone"]) ?>
    </td>

    <td>
        <?= e($usuario["email"]) ?>
    </td>

    <td>
        <?= e($usuario["tipo"]) ?>
    </td>

    <td>
        <a href="editar_usuario.php?id=<?= (int) $usuario["id"] ?>" class="btn btn-sm btn-warning">
            <i class="bi bi-pencil"></i> Editar
        </a>

        <form method="POST" action="excluir_usuario.php" style="display:inline;"
            onsubmit="return confirm('Deseja realmente excluir este usuário?');">

            <input type="hidden" name="id" value="<?= (int) $usuario["id"] ?>">

            <input type="hidden" name="csrf_token" value="<?= e(gerarTokenCsrf()) ?>">

            <button type="submit" class="btn btn-sm btn-danger">
                <i class="bi bi-trash"></i> Excluir
            </button>

        </form>
    </td>

</tr>

<?php endwhile; ?>

</tbody>

    </table>
</div>

        </main>

    </div>
    <script src="../scripts/cadastro.js"></script>
</body>

</html>