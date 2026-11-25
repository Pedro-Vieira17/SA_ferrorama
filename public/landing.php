<?php
// Landing Page da Ferrorama (pública, não exige login). Arquivo único: HTML + CSS + PHP.
// Quando carregada pelo index.php, ele define $base = '' (raiz do projeto).
// Quando aberta direto em public/landing.php, $base vira '../'.
$base = $base ?? '../';
$linkEntrar = $base . 'index.php?login';
$imagemTrem = $base . 'assets/img/trem-png%20novo.webp';
$tituloPagina = 'Ferrorama | Monitoramento ferroviário';
$descricaoPagina = 'A Ferrorama reúne trens, rotas, sensores e alertas em um só painel para acompanhar a operação ferroviária.';

// Para incluir ou remover um módulo, edite apenas este array.
$funcionalidades = [
    ['Trens',          'Cadastre, consulte, edite e exclua as informações de cada trem da frota.'],
    ['Rotas',          'Veja os detalhes de cada rota e organize por onde os trens circulam.'],
    ['Sensores',       'Gerencie os sensores instalados e consulte as informações de cada um.'],
    ['Alertas',        'Identifique rapidamente o que está fora do normal e precisa de atenção.'],
    ['Clima',          'Consulte as condições do tempo na tela inicial, direto de uma API de previsão.'],
    ['Acesso por perfil', 'Administradores gerenciam o sistema; usuários consultam o que lhes cabe.'],
];
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($tituloPagina) ?></title>
    <meta name="description" content="<?= htmlspecialchars($descricaoPagina) ?>">
    <link rel="stylesheet" href="<?= $base ?>assets/style/style.css">
</head>
<body>

<header class="topo">
    <div class="container topo-conteudo">
        <a class="logo" href="#topo">Ferrorama</a>
        <nav class="topo-links" aria-label="Seções da página">
            <a href="#problema">Problema</a>
            <a href="#funcionalidades">Funcionalidades</a>
            <a href="#monitoramento">Monitoramento</a>
            <a href="#beneficios">Benefícios</a>
        </nav>
        <a class="btn btn-azul" href="<?= $linkEntrar ?>">Entrar</a>
    </div>
</header>

<main id="topo">

<section class="hero">
    <div class="container">
        <div class="hero-grade">
        <div>
        <h1>Trens, rotas e sensores em um só painel</h1>
        <p class="lead">A Ferrorama organiza as informações da operação ferroviária para que a equipe acompanhe a linha, identifique alertas e consulte o clima sem trocar de ferramenta.</p>
        <div class="acoes">
            <a class="btn btn-azul" href="<?= $linkEntrar ?>">Acessar o sistema</a>
            <a class="btn btn-cinza" href="#funcionalidades">Ver funcionalidades</a>
        </div>
        </div>
        <img class="hero-img" src="<?= $imagemTrem ?>" alt="Ilustração de um trem moderno" width="525" height="350">
        </div>

       
    </div>
</section>

<section id="problema" class="secao">
    <div class="container duas-colunas">
        <div>
            <h2>Acompanhar a operação no improviso custa caro</h2>
            <p>Quando as informações ficam espalhadas, ninguém tem a visão completa da linha.</p>
        </div>
        <ul class="lista-destaque">
            <li><strong>Dados dispersos</strong>Cadastro de trens, rotas e leituras de sensores em lugares diferentes.</li>
            <li><strong>Problemas descobertos tarde</strong>Sem alertas organizados, um sensor fora do normal passa despercebido.</li>
            <li><strong>Decisão sem contexto</strong>Planejar uma saída exige conferir clima, rota e estado do trem separadamente.</li>
        </ul>
    </div>
</section>

<section id="funcionalidades" class="secao secao-clara">
    <div class="container">
        <h2>O que você faz na Ferrorama</h2>
        <p>Cada módulo cuida de uma parte da operação, todos dentro do mesmo painel.</p>
        <div class="grade">
            <?php foreach ($funcionalidades as $item): ?>
                <article class="cartao">
                    <h3><?= htmlspecialchars($item[0]) ?></h3>
                    <p><?= htmlspecialchars($item[1]) ?></p>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section id="monitoramento" class="secao">
    <div class="container duas-colunas">
        <div>
            <h2>Sensores e informações operacionais à vista</h2>
            <p>Estado dos sensores e situação dos trens aparecem organizados para facilitar o acompanhamento da operação.</p>
        </div>
        <div class="painel">
            <div class="painel-linha"><span>Sensor 1</span><span><i class="ponto ponto-ok"></i>Ativo</span></div>
            <div class="painel-linha"><span>Sensor 2</span><span><i class="ponto ponto-ok"></i>Ativo</span></div>
            <div class="painel-linha"><span>Sensor 3</span><span><i class="ponto ponto-alerta"></i>Alerta</span></div>
            <div class="painel-linha"><span>Trem em operação</span><span>Rota 1</span></div>
        </div>
    </div>
</section>

<section id="beneficios" class="secao secao-clara">
    <div class="container">
        <h2>O que a equipe ganha</h2>
        <ul class="lista-destaque">
            <li><strong>Mais segurança</strong>Alertas e informações dos sensores no mesmo lugar ajudam a agir cedo.</li>
            <li><strong>Mais eficiência</strong>Menos tempo procurando informação, mais tempo decidindo.</li>
            <li><strong>Mais acompanhamento</strong>A situação de trens e rotas fica visível para quem precisa, com o nível de acesso certo.</li>
        </ul>
    </div>
</section>

<section class="secao cta">
    <div class="container">
        <h2>Pronto para acompanhar sua linha?</h2>
        <p>Entre com seu usuário e veja trens, rotas e sensores no painel.</p>
        <a class="btn btn-azul" href="<?= $linkEntrar ?>">Acessar o sistema</a>
    </div>
</section>

</main>

<footer class="rodape">
    <div class="container">Ferrorama – sistema de monitoramento ferroviário.</div>
</footer>
</body>
</html>