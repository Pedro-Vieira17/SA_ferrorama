
<?php

require_once "../infra/seguranca.php";
require_once "../infra/conexao.php";

exigirAdministrador();

$stmt = $conexao->prepare(
    "SELECT id, nome, CPF, telefone, email, tipo,
            cep, logradouro, bairro, cidade, uf
     FROM USUARIO
     ORDER BY id DESC"
);

$stmt->execute();
$usuarios = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Usuários cadastrados</title>

    <link rel="stylesheet" href="../assets/style/style.css">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <link rel="icon" href="../assets/icons/TREM_AZUL.svg" type="image/svg+xml">

    <style>

        #modalEditarUsuario .modal-content {
            background-color: #2e3747;
            color: white;
            border: 1px solid #475569;
        }

        #modalEditarUsuario .modal-header,
        #modalEditarUsuario .modal-footer {
            border-color: #475569;
        }

        #modalEditarUsuario .form-label {
            color: white;
        }

        #modalEditarUsuario .form-control,
        #modalEditarUsuario .form-select {
            background-color: #ffffff;
            color: #212529;
        }

        @media (max-width: 576px) {
            #modalEditarUsuario .modal-dialog {
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

        <a href="rotas.php" class="item">
            <img src="../assets/icons/relatorio_branco.svg" alt="">
            Rotas
        </a>

        <a href="cadastro_admin.php" class="item">
            <img src="../assets/icons/cadastrar_branco.svg" alt="">
            Cadastrar ADMs e Usuários
        </a>

        <a href="usuarios_cadastrados.php" class="item ativo">
            <img src="../assets/icons/usuarios_preto.svg" alt="">
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

        <div class="planilha_dashboard">

            <h2 class="titulo_cadastro">
                Listagem de Usuários Cadastrados
            </h2>

            <div class="table-responsive">

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

                        <?php if ($usuarios->num_rows > 0): ?>

                            <?php while ($usuario = $usuarios->fetch_assoc()): ?>

                                <tr>

                                    <td><?= e($usuario["nome"]) ?></td>

                                    <td><?= e($usuario["CPF"]) ?></td>

                                    <td><?= e($usuario["telefone"]) ?></td>

                                    <td><?= e($usuario["email"]) ?></td>

                                    <td><?= e($usuario["tipo"]) ?></td>

                                    <td>

                                        <button
                                            type="button"
                                            class="btn btn-sm btn-warning"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalEditarUsuario"
                                            data-id="<?= (int) $usuario["id"] ?>"
                                            data-nome="<?= e($usuario["nome"]) ?>"
                                            data-cpf="<?= e($usuario["CPF"]) ?>"
                                            data-telefone="<?= e($usuario["telefone"]) ?>"
                                            data-email="<?= e($usuario["email"]) ?>"
                                            data-tipo="<?= e($usuario["tipo"]) ?>"
                                            data-cep="<?= e($usuario["cep"] ?? "") ?>"
                                            data-logradouro="<?= e($usuario["logradouro"] ?? "") ?>"
                                            data-bairro="<?= e($usuario["bairro"] ?? "") ?>"
                                            data-cidade="<?= e($usuario["cidade"] ?? "") ?>"
                                            data-uf="<?= e($usuario["uf"] ?? "") ?>"
                                        >
                                            <i class="bi bi-pencil"></i>
                                            Editar
                                        </button>

                                        <form
                                            method="POST"
                                            action="excluir_usuario.php"
                                            style="display: inline;"
                                            onsubmit="return confirm('Deseja realmente excluir este usuário?');"
                                        >

                                            <input
                                                type="hidden"
                                                name="id"
                                                value="<?= (int) $usuario["id"] ?>"
                                            >

                                            <input
                                                type="hidden"
                                                name="csrf_token"
                                                value="<?= e(gerarTokenCsrf()) ?>"
                                            >

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
                                <td colspan="6" class="text-center">
                                    Nenhum usuário cadastrado.
                                </td>
                            </tr>

                        <?php endif; ?>

                    </tbody>

                </table>

            </div>

        </div>

    </main>

</div>

<div class="modal fade" id="modalEditarUsuario" tabindex="-1" aria-labelledby="tituloModalUsuario" aria-hidden="true">

    <div class="modal-dialog modal-lg modal-dialog-centered">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title" id="tituloModalUsuario">
                    <i class="bi bi-person-gear"></i>
                    Editar Usuário
                </h5>

                <button
                    type="button"
                    class="btn-close btn-close-white"
                    data-bs-dismiss="modal"
                    aria-label="Fechar"
                ></button>

            </div>

            <form method="POST" action="atualizar_usuario.php">

                <div class="modal-body">

                    <input
                        type="hidden"
                        name="id"
                        id="editarId"
                    >

                    <input
                        type="hidden"
                        name="csrf_token"
                        value="<?= e(gerarTokenCsrf()) ?>"
                    >

                    <div class="row g-3">

                        <div class="col-md-6">

                            <label for="editarNome" class="form-label">
                                Nome completo
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                id="editarNome"
                                name="nome"
                                maxlength="200"
                                required
                            >

                        </div>

                        <div class="col-md-6">

                            <label for="editarCpf" class="form-label">
                                CPF
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                id="editarCpf"
                                name="cpf"
                                maxlength="11"
                                inputmode="numeric"
                                required
                            >

                        </div>

                        <div class="col-md-6">

                            <label for="editarTelefone" class="form-label">
                                Telefone
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                id="editarTelefone"
                                name="telefone"
                                maxlength="15"
                                inputmode="numeric"
                                required
                            >

                        </div>

                        <div class="col-md-6">

                            <label for="editarEmail" class="form-label">
                                E-mail
                            </label>

                            <input
                                type="email"
                                class="form-control"
                                id="editarEmail"
                                name="email"
                                maxlength="200"
                                required
                            >

                        </div>

                        <div class="col-md-6">

                            <label for="editarTipo" class="form-label">
                                Tipo de acesso
                            </label>

                            <select
                                class="form-select"
                                id="editarTipo"
                                name="tipo"
                                required
                            >

                                <option value="usuario">Usuário</option>
                                <option value="administrador">Administrador</option>

                            </select>

                        </div>

                        <div class="col-md-6">

                            <label for="editarCep" class="form-label">
                                CEP
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                id="editarCep"
                                name="cep"
                                placeholder="00000-000"
                                maxlength="9"
                                autocomplete="off"
                            >

                            <small id="mensagemCep"></small>

                        </div>

                        <div class="col-md-6">

                            <label for="editarLogradouro" class="form-label">
                                Logradouro
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                id="editarLogradouro"
                                name="logradouro"
                            >

                        </div>

                        <div class="col-md-6">

                            <label for="editarBairro" class="form-label">
                                Bairro
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                id="editarBairro"
                                name="bairro"
                            >

                        </div>

                        <div class="col-md-6">

                            <label for="editarCidade" class="form-label">
                                Cidade
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                id="editarCidade"
                                name="cidade"
                            >

                        </div>

                        <div class="col-md-6">

                            <label for="editarUf" class="form-label">
                                UF
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                id="editarUf"
                                name="uf"
                                maxlength="2"
                            >

                        </div>

                    </div>

                </div>

                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal"
                    >
                        Cancelar
                    </button>

                    <button
                        type="submit"
                        class="btn btn-primary"
                    >
                        <i class="bi bi-check-circle"></i>
                        Salvar alterações
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

<script>

    const modalEditarUsuario = document.getElementById("modalEditarUsuario");

    modalEditarUsuario.addEventListener("show.bs.modal", function (event) {

        const botao = event.relatedTarget;

        document.getElementById("editarId").value = botao.dataset.id;
        document.getElementById("editarNome").value = botao.dataset.nome;
        document.getElementById("editarCpf").value = botao.dataset.cpf;
        document.getElementById("editarTelefone").value = botao.dataset.telefone;
        document.getElementById("editarEmail").value = botao.dataset.email;
        document.getElementById("editarTipo").value = botao.dataset.tipo;

        document.getElementById("editarCep").value = botao.dataset.cep;
        document.getElementById("editarLogradouro").value = botao.dataset.logradouro;
        document.getElementById("editarBairro").value = botao.dataset.bairro;
        document.getElementById("editarCidade").value = botao.dataset.cidade;
        document.getElementById("editarUf").value = botao.dataset.uf;

        document.getElementById("mensagemCep").textContent = "";

    });

    const cepInput = document.getElementById("editarCep");
    const cepMsg = document.getElementById("mensagemCep");

    cepInput.addEventListener("input", function () {

        let valor = cepInput.value.replace(/\D/g, "").slice(0, 8);

        if (valor.length > 5) {
            valor = valor.slice(0, 5) + "-" + valor.slice(5);
        }

        cepInput.value = valor;

    });

    async function buscarCep() {

        if (cepInput.value.trim() === "") {
            cepMsg.textContent = "";
            return;
        }

        cepMsg.textContent = "Consultando...";
        cepMsg.style.color = "#555";

        try {

            const resposta = await fetch(
                "buscar_cep.php?cep=" + encodeURIComponent(cepInput.value)
            );

            const dados = await resposta.json();

            if (!resposta.ok) {

                cepMsg.textContent = dados.erro || "Erro ao consultar o CEP.";
                cepMsg.style.color = "#dc3545";

                return;

            }

            cepInput.value = dados.cep;

            document.getElementById("editarLogradouro").value = dados.logradouro || "";
            document.getElementById("editarBairro").value = dados.bairro || "";
            document.getElementById("editarCidade").value = dados.cidade || "";
            document.getElementById("editarUf").value = dados.uf || "";

            cepMsg.textContent = "CEP válido ✔";
            cepMsg.style.color = "#198754";

        } catch (erro) {

            cepMsg.textContent = "Erro de conexão com o servidor. Tente novamente.";
            cepMsg.style.color = "#dc3545";

        }

    }

    cepInput.addEventListener("blur", buscarCep);

</script>

</body>
</html>
