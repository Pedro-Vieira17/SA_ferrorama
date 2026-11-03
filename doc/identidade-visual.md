# Pesquisa de identidade visual

## Ideia da interface

A identidade visual da Ferrorama foi desenvolvida para representar um sistema de monitoramento ferroviário inteligente, utilizando uma aparência moderna, tecnológica e organizada.

A interface utiliza cores escuras, com predominância de azul-marinho e cinza azulado, além de cores de destaque para botões e estados dos elementos monitorados.

## O que observamos

Durante o desenvolvimento, buscamos manter um padrão visual entre as áreas de administrador e usuário.

O sistema possui dashboards, gráficos, tabelas e formulários para gerenciamento de usuários, trens, sensores e rotas.

A utilização de um CSS compartilhado ajuda a manter as mesmas cores, espaçamentos e estilos nas diferentes páginas.

## Cores

A paleta foi definida considerando a proposta tecnológica e ferroviária do sistema.

| Cor | Código HEX | Utilização |
|---|---|---|
| Azul-marinho | `#0f172a` | Cabeçalho, menu, cartões e tabelas |
| Cinza azulado | `#2e3747` | Fundo das páginas |
| Cinza claro | `#cdcdcd` | Efeito hover do menu |
| Cinza muito claro | `#dedede` | Menu selecionado |
| Branco | `#ffffff` | Textos e títulos |
| Verde | `#008000` | Status ativo |
| Vermelho | `#ff0000` | Alertas |
| Azul | `#0d6efd` | Botões de salvar |
| Cinza | `#6c757d` | Botões de cancelar |

O azul-marinho transmite tecnologia e organização. O cinza diferencia as áreas da interface, enquanto o verde e o vermelho facilitam a identificação dos estados dos elementos monitorados.

## Tipografia e organização

A interface utiliza textos simples, priorizando a leitura das informações. O CSS analisado não define uma família de fontes específica.

As páginas são organizadas com cabeçalho, menu lateral e área principal. Os dashboards utilizam cartões e gráficos, enquanto as páginas de gerenciamento apresentam tabelas e formulários.

## Usabilidade e responsividade

O sistema utiliza Bootstrap e Flexbox para organizar os elementos da interface.

O CSS também possui adaptações para telas menores, como a reorganização dos formulários de sensores e a utilização de rolagem horizontal nas tabelas.

Alguns pontos que merecem melhorias são o contraste do menu ao passar o mouse, a visibilidade do foco pelo teclado e o comportamento do menu lateral em celulares.

## Inteligência artificial e uso consciente

Utilizamos o ChatGPT como ferramenta de apoio à pesquisa, organização das ideias, compreensão da teoria das cores e sugestões de melhorias visuais.

As sugestões foram avaliadas considerando as necessidades reais da Ferrorama. A inteligência artificial foi utilizada como auxílio, sem substituir as decisões e a participação da equipe.

## Ferramentas utilizadas

- Visual Studio Code: edição do código.
- Bootstrap 5: organização dos componentes.
- HTML, CSS, JavaScript e PHP: desenvolvimento do sistema.
- MySQL: armazenamento dos dados.
- GitHub: controle e armazenamento do projeto.
- Google Chrome: visualização das páginas.
- ChatGPT: apoio à pesquisa e documentação.

## Testes e melhorias

Durante a análise do CSS, identificamos alguns pontos que precisam de atenção:

- Melhorar o contraste de alguns elementos do menu.
- Verificar a navegação pelo teclado.
- Testar o menu e as tabelas em celulares.
- Conferir a legibilidade dos estados dos trens e sensores.
- Organizar algumas regras CSS repetidas.

Os testes de acessibilidade e responsividade ainda precisam ser realizados e registrados pela equipe.

## Considerações finais

A identidade visual da Ferrorama busca unir tecnologia, organização e facilidade de utilização.

A padronização das cores, tabelas, formulários e dashboards permite que as diferentes páginas tenham a mesma aparência e facilita a identificação das informações.

A pesquisa também mostrou que uma boa interface precisa considerar não apenas a aparência, mas também a acessibilidade e a facilidade de uso.

## Referências

- Bootstrap 5.3: https://getbootstrap.com/docs/5.3/
- WebAIM – Verificador de contraste: https://webaim.org/resources/contrastchecker/
- OpenAI – ChatGPT: https://chatgpt.com/
- Projeto Ferrorama: arquivo `assets/style/style.css` e demais arquivos do frontend.