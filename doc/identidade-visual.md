# Pesquisa de identidade visual

## Ideia da interface

A proposta foi manter o estilo escuro que o Ferrorama já tinha e fazer alguns ajustes para melhorar a leitura e aproximar as cores do tema ferroviário. Azul-marinho continua no cabeçalho, no menu e nos cartões. O verde destaca ações principais, e o amarelo é usado em títulos de tabela e foco. Os nomes dos status continuam aparecendo junto das cores.

## O que observamos

Na revisão do CSS e das páginas existentes, vimos que o sistema já tinha menu lateral, cartões com indicadores e tabelas. Como várias telas usam o mesmo arquivo `assets/style/style.css`, fizemos os ajustes nele para manter o padrão e evitar mudanças na estrutura das páginas.

Também identificamos alguns pontos para melhorar: o foco do teclado não era muito evidente, o menu lateral ocupava espaço em telas pequenas e o login podia ficar apertado no celular.

## Cores

| Cor | Código | Uso |
| --- | --- | --- |
| Azul-marinho | `#0f172a` | Cabeçalho, menu e cartões |
| Cinza azulado | `#2e3747` | Fundo das páginas |
| Verde | `#176b63` | Botões principais e página selecionada |
| Verde escuro | `#10564f` | Estado hover dos botões |
| Amarelo | `#f0c36d` | Destaques e foco do teclado |
| Branco | `#ffffff` | Textos e campos |

Escolhemos o verde e o azul por serem cores sóbrias para uma ferramenta de monitoramento. O amarelo serve como destaque e lembra a sinalização de atenção usada no contexto ferroviário. Vermelho e verde dos status existentes continuam indicando situações específicas, sempre acompanhados do texto do status.

## Tipografia e organização

Mantivemos a fonte padrão do navegador para não depender de downloads externos. Também mantivemos os ícones e imagens que já fazem parte do projeto. As telas continuam com a mesma estrutura; os ajustes foram feitos principalmente nas cores de botões, no item selecionado do menu, no foco dos campos e no comportamento em telas estreitas.

## Mudanças feitas

- Botões principais e item selecionado do menu passaram a usar verde.
- O foco de campos e links ficou mais visível para quem navega pelo teclado.
- Em telas estreitas, o menu pode ser rolado na horizontal e o conteúdo usa a largura disponível.
- A imagem da tela de login fica limitada para não ocupar a tela toda no celular.

## Testes e próximos passos

As alterações foram feitas no CSS compartilhado. Ainda é necessário abrir as páginas no navegador e conferir os tamanhos de computador e celular, além de testar formulários, tabelas, gráficos e navegação por teclado. Não incluímos capturas de tela porque elas precisam ser feitas com o sistema em execução.

Antes de finalizar a entrega, a equipe deve verificar o contraste das combinações de cores com uma ferramenta de acessibilidade e adicionar capturas reais de antes e depois, caso tenha a versão anterior disponível.

## Referências

- WCAG 2.2, contraste de texto: https://www.w3.org/TR/WCAG22/#contrast-minimum
- Bootstrap 5, tabelas responsivas: https://getbootstrap.com/docs/5.3/content/tables/#responsive-tables
- Código do próprio Ferrorama: `assets/style/style.css` e páginas de login, dashboard, sensores e rotas.

A documentação foi escrita com auxílio de IA e deve ser revisada pela equipe para confirmar que corresponde às decisões e aos testes realizados.
