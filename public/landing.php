<?php
// Landing Page da Ferrorama (pública, não exige login). Arquivo único: HTML + CSS + PHP.
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
    <style>
/* Landing Page da Ferrorama.
   Cores da paleta em doc/identidade-visual.md */
:root{
  --marinho:#0f172a;        /* cabeçalho, cartões */
  --fundo:#2e3747;          /* fundo das páginas */
  --cinza-claro:#cdcdcd;    /* textos secundários */
  --branco:#ffffff;
  --verde:#008000;          /* status ativo */
  --vermelho:#ff0000;       /* alertas */
  --azul:#0d6efd;           /* botão principal */
  --cinza:#6c757d;          /* botão secundário */
  --borda:rgba(255,255,255,.12);
}
*{box-sizing:border-box;margin:0}
html{scroll-behavior:smooth;scroll-padding-top:72px}
body{background:var(--fundo);color:var(--branco);font-family:system-ui,-apple-system,"Segoe UI",Roboto,Arial,sans-serif;font-size:17px;line-height:1.6}
a{color:inherit}
:focus-visible{outline:3px solid var(--branco);outline-offset:3px}
.container{max-width:1080px;margin:0 auto;padding:0 24px}
h1,h2,h3{line-height:1.2;font-weight:700}
h2{font-size:clamp(1.6rem,3.4vw,2.2rem);margin-bottom:12px}
h3{font-size:1.15rem;margin-bottom:6px}
p{color:var(--cinza-claro);max-width:62ch}

/* Topo */
.topo{position:sticky;top:0;z-index:10;background:var(--marinho);border-bottom:1px solid var(--borda)}
.topo-conteudo{display:flex;align-items:center;justify-content:space-between;gap:20px;height:64px}
.logo{font-weight:700;font-size:1.3rem;text-decoration:none}
.topo-links{display:flex;gap:24px;margin-left:auto}
.topo-links a{text-decoration:none;color:var(--cinza-claro);font-size:.95rem;padding:6px 2px}
.topo-links a:hover{color:var(--branco);text-decoration:underline}

/* Botões */
.btn{display:inline-block;color:var(--branco);font-weight:600;text-decoration:none;padding:11px 24px;border-radius:6px;border:0;transition:filter .15s}
.btn:hover{filter:brightness(1.12)}
.btn-azul{background:var(--azul)}
.btn-cinza{background:var(--cinza)}
.acoes{display:flex;gap:14px;flex-wrap:wrap}

/* Seções */
.secao{padding:80px 0;border-top:1px solid var(--borda)}
.secao-clara{background:var(--marinho)}
.duas-colunas{display:grid;grid-template-columns:1fr 1fr;gap:48px;align-items:start}

/* Hero */
.hero{padding:88px 0 64px}
.hero h1{font-size:clamp(2.2rem,5.5vw,3.8rem);max-width:16ch;margin-bottom:20px}
.lead{font-size:1.15rem;margin-bottom:32px}
.trilho{display:block;width:100%;height:auto;margin-top:56px}
.trilho .via{stroke:var(--cinza-claro);stroke-width:3;fill:none}
.trilho .dormente{stroke:var(--cinza);stroke-width:2}
.trilho .sensor-ok{fill:var(--verde)}
.trilho .sensor-alerta{fill:var(--vermelho)}
.trilho .trem{fill:var(--azul);animation:andar 14s linear 1 forwards}
@keyframes andar{from{transform:translateX(0)}to{transform:translateX(760px)}}

/* Cartões */
.grade{display:grid;grid-template-columns:repeat(auto-fit,minmax(250px,1fr));gap:20px;margin-top:32px}
.cartao{background:var(--fundo);border:1px solid var(--borda);border-radius:8px;padding:24px}
.cartao p{font-size:.97rem}

/* Listas com destaque */
.lista-destaque{list-style:none;padding:0;display:grid;gap:22px;margin-top:8px}
.lista-destaque li{padding-left:18px;border-left:3px solid var(--azul);color:var(--cinza-claro)}
.lista-destaque strong{display:block;color:var(--branco);margin-bottom:2px}

/* Painel de sensores */
.painel{background:var(--marinho);border:1px solid var(--borda);border-radius:8px;padding:6px 20px}
.painel-linha{display:flex;justify-content:space-between;align-items:center;padding:14px 0;border-bottom:1px solid var(--borda);font-size:.97rem}
.painel-linha:last-child{border-bottom:0}
.painel-linha span:last-child{font-weight:600}
.ponto{display:inline-block;width:10px;height:10px;border-radius:50%;margin-right:8px}
.ponto-ok{background:var(--verde)}
.ponto-alerta{background:var(--vermelho)}
.nota{font-size:.85rem;margin-top:10px}

/* CTA e rodapé */
.cta{text-align:center;background:var(--marinho)}
.cta p{margin:0 auto 28px}
.rodape{background:var(--marinho);border-top:1px solid var(--borda);padding:26px 0;color:var(--cinza-claro);font-size:.9rem}

/* Celular */
@media (max-width:760px){
  .topo-links{display:none}
  .duas-colunas{grid-template-columns:1fr;gap:28px}
  .secao{padding:56px 0}
  .hero{padding:56px 0 40px}
}
@media (prefers-reduced-motion:reduce){
  html{scroll-behavior:auto}
  .trilho .trem{animation:none;transform:translateX(380px)}
}
    </style>
</head>
<body>

<!-- ===== TOPO ===== -->
<header class="topo">
    <div class="container topo-conteudo">
        <a class="logo" href="#topo">Ferrorama</a>
        <nav class="topo-links" aria-label="Seções da página">
            <a href="#problema">Problema</a>
            <a href="#funcionalidades">Funcionalidades</a>
            <a href="#monitoramento">Monitoramento</a>
            <a href="#beneficios">Benefícios</a>
        </nav>
        <a class="btn btn-azul" href="index.php">Entrar</a>
    </div>
</header>

<main id="topo">

<!-- ===== HERO ===== -->
<section class="hero">
    <div class="container">
        <h1>Trens, rotas e sensores em um só painel</h1>
        <p class="lead">A Ferrorama organiza as informações da operação ferroviária para que a equipe acompanhe a linha, identifique alertas e consulte o clima sem trocar de ferramenta.</p>
        <div class="acoes">
            <a class="btn btn-azul" href="index.php">Acessar o sistema</a>
            <a class="btn btn-cinza" href="#funcionalidades">Ver funcionalidades</a>
        </div>

        <svg class="trilho" viewBox="0 0 1000 120" role="img" aria-label="Ilustração de um trem passando por sensores ao longo do trilho">
            <path class="via" d="M0 70H1000M0 88H1000"/>
            <path class="dormente" d="M20 62v34M70 62v34M120 62v34M170 62v34M220 62v34M270 62v34M320 62v34M370 62v34M420 62v34M470 62v34M520 62v34M570 62v34M620 62v34M670 62v34M720 62v34M770 62v34M820 62v34M870 62v34M920 62v34M970 62v34"/>
            <circle class="sensor-ok" cx="200" cy="40" r="7"/>
            <circle class="sensor-ok" cx="450" cy="40" r="7"/>
            <circle class="sensor-alerta" cx="700" cy="40" r="7"/>
            <circle class="sensor-ok" cx="900" cy="40" r="7"/>
            <rect class="trem" x="40" y="46" width="110" height="24" rx="5"/>
        </svg>
    </div>
</section>

<!-- ===== PROBLEMA ===== -->
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

<!-- ===== FUNCIONALIDADES ===== -->
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

<!-- ===== MONITORAMENTO ===== -->
<section id="monitoramento" class="secao">
    <div class="container duas-colunas">
        <div>
            <h2>Sensores e informações operacionais à vista</h2>
            <p>Estado dos sensores e situação dos trens aparecem organizados, com cores que indicam o que está normal e o que pede atenção.</p>
            <p class="nota">Exemplo ilustrativo: os valores ao lado não são dados reais.</p>
        </div>
        <div class="painel" role="group" aria-label="Exemplo ilustrativo de painel de sensores">
            <div class="painel-linha"><span>Sensor 1</span><span><i class="ponto ponto-ok"></i>Ativo</span></div>
            <div class="painel-linha"><span>Sensor 2</span><span><i class="ponto ponto-ok"></i>Ativo</span></div>
            <div class="painel-linha"><span>Sensor 3</span><span><i class="ponto ponto-alerta"></i>Alerta</span></div>
            <div class="painel-linha"><span>Trem em operação</span><span>Rota 1</span></div>
        </div>
    </div>
</section>

<!-- ===== BENEFÍCIOS ===== -->
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

<!-- ===== CHAMADA FINAL ===== -->
<section class="secao cta">
    <div class="container">
        <h2>Pronto para acompanhar sua linha?</h2>
        <p>Entre com seu usuário e veja trens, rotas e sensores no painel.</p>
        <a class="btn btn-azul" href="index.php">Acessar o sistema</a>
    </div>
</section>

</main>

<!-- ===== RODAPÉ ===== -->
<footer class="rodape">
    <div class="container">Ferrorama – sistema de monitoramento ferroviário.</div>
</footer>
</body>
</html>