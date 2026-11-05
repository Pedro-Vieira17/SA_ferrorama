# Processo de identidade visual — Ferrorama

> Registro do processo de análise visual do sistema de monitoramento e gerenciamento de trens, sensores e rotas. Este documento descreve o estado observado no repositório e separa as verificações já feitas por inspeção das que ainda precisam ser executadas em navegador e com usuários.

## 1. Contexto e objetivo

O Ferrorama é uma aplicação web para acompanhar trens e sensores, consultar indicadores e gerenciar cadastros, rotas e relatórios. A interface precisa transmitir organização e confiabilidade, destacar rapidamente situações que exigem atenção e funcionar tanto para administradores quanto para usuários.

A análise foi feita sobre as telas existentes, especialmente os dashboards em `public/tela_inicial.php` e `public/tela_inicial_usuario.php`, e a folha compartilhada `assets/style/style.css`. O projeto já possui uma pesquisa anterior em `doc/identidade-visual.md`; este arquivo registra uma versão mais detalhada do processo no diretório `pesquisas/`.

## 2. Referências visuais pesquisadas e aprendizados

Foram consideradas as referências e padrões a seguir:

| Referência | O que observamos e aprendemos | Aplicação no Ferrorama |
|---|---|---|
| Telas de dashboard, tabelas e formulários já existentes no próprio Ferrorama | Indicadores em cartões, navegação persistente e tabelas tornam possível localizar os dados operacionais sem trocar de contexto. O CSS compartilhado ajuda a manter consistência entre páginas. | Preservar cabeçalho, menu lateral, cartões de indicadores e área principal comum às telas de administração e usuário. |
| Componentes e sistema responsivo do [Bootstrap 5](https://getbootstrap.com/docs/5.3/) | Componentes conhecidos, grades e utilitários responsivos aceleram a construção de formulários, botões e tabelas. A aparência padrão ainda precisa ser harmonizada com a identidade própria. | Reutilizar componentes com estilos consistentes e manter tabelas em contêineres com rolagem horizontal quando necessário. |
| Boas práticas de contraste da [WCAG 2.2](https://www.w3.org/TR/WCAG22/) e orientação do [WebAIM Contrast Checker](https://webaim.org/resources/contrastchecker/) | A cor sozinha não deve comunicar um estado; textos e controles precisam de contraste suficiente e os estados devem também ser identificados por rótulos, ícones ou outras pistas. | Manter rótulos como “Ativo” e “Manutenção”, revisar combinações de texto e fundo e verificar o foco de teclado. |
| Convenções de interfaces de monitoramento | Em interfaces operacionais, números e alertas devem ser localizáveis rapidamente, com grupos visuais claros e linguagem consistente. | Dar destaque aos indicadores, separar visualmente clima e listagem de trens e reservar cores de estado para semântica consistente. |

As referências são princípios e padrões para orientar a interface, não alegações de que uma auditoria formal ou teste com usuários tenha sido conduzido.

## 3. Teoria das cores aplicada

- **Temperatura e associação:** azuis escuros e azul-marinho remetem a tecnologia, estabilidade e operação; são usados como base para a navegação e os painéis.
- **Hierarquia por contraste:** fundos escuros e texto claro distinguem conteúdo e estrutura, enquanto as cores de estado chamam atenção para informação operacional.
- **Cores semânticas:** verde indica situação normal/ativa, amarelo indica atenção ou manutenção, e vermelho deve ser reservado a falhas ou alertas críticos. A cor não deve ser a única forma de transmitir o estado: texto e ícone devem acompanhar o indicador.
- **Consistência:** a mesma cor deve manter a mesma função em todas as telas; tons decorativos não devem competir com alertas e ações principais.

## 4. Paleta identificada no projeto

A tabela resume os valores encontrados no CSS e nas telas atuais. Ela documenta o que já existe; não significa que todas as combinações tenham sido aprovadas em teste de acessibilidade.

| Cor | HEX | Função observada ou definida |
|---|---|---|
| Azul-marinho | `#0F172A` | Cabeçalho, menu lateral, cartões, formulários e tabelas; estrutura e superfícies principais. |
| Cinza azulado | `#2E3747` | Fundo da área de conteúdo. |
| Azul de interação | `#0D6EFD` | Botões de ação principal e elementos interativos do Bootstrap. |
| Azul de hover de tabela | `#17233B` | Realce de linha sob o cursor. |
| Branco | `#FFFFFF` | Texto e títulos sobre superfícies escuras; campos claros. |
| Cinza claro | `#CDCDCD` | Fundo do item do menu sob o cursor. |
| Cinza muito claro | `#DEDEDE` | Fundo do item ativo do menu. |
| Cinza secundário | `#6C757D` | Ação secundária/cancelamento e estados neutros. |
| Verde | `#008000` | Estado ativo/funcionando, acompanhado de rótulo ou ícone. |
| Amarelo Bootstrap | `#FFC107` | Estado de manutenção/atenção, normalmente com texto escuro. |
| Vermelho | `#FF0000` | Estado de alerta na regra CSS atual; precisa de revisão de contraste e consistência antes de ser considerado final. |

**Observação:** as páginas também usam classes de estado do Bootstrap, como `bg-success`, `bg-warning` e `bg-secondary`. Por isso, a aparência final depende dos estilos do Bootstrap e das regras locais. Recomenda-se consolidar esses tokens em uma paleta única e revisar especialmente texto vermelho sobre fundo escuro.

## 5. Tipografia e elementos gráficos

O CSS compartilhado não define uma família tipográfica específica. Assim, a interface depende da tipografia padrão do navegador/Bootstrap, o que evita carregar uma fonte externa, mas pode produzir pequenas diferenças entre plataformas. A decisão recomendada é manter uma fonte de sistema sem serifa para boa leitura de interface, declarar uma pilha de fontes explicitamente e usar pesos e tamanhos consistentes. Isso é uma recomendação de padronização, não uma alteração já implementada.

Os elementos gráficos atuais incluem ícones SVG (trem, sensor, alerta, engrenagem e ações) e ícones Bootstrap. Eles ajudam a reconhecer rapidamente áreas e ações. Devem manter tamanho e estilo consistentes; ícones decorativos podem ter texto alternativo vazio, enquanto ícones que sejam a única identificação de um controle precisam de nome acessível.

## 6. Layout e hierarquia visual

- **Cabeçalho:** identifica a área/usuário e mantém a ação de sair visível.
- **Menu lateral:** agrupa a navegação principal e indica a página ativa.
- **Área de conteúdo:** concentra a tarefa atual, sem competir com os elementos persistentes.
- **Dashboard:** cartões colocam totais e alertas em primeiro plano; a seção de clima e a tabela de trens aparecem em áreas separadas.
- **Tabelas:** cabeçalhos, linhas e ações agrupadas facilitam comparar registros. Em telas estreitas, a tabela deve poder rolar horizontalmente sem provocar rolagem de toda a página.
- **Formulários:** rótulos acima dos campos e agrupamento de campos relacionados tornam o preenchimento mais previsível.
- **Responsividade observada no CSS:** o menu lateral reduz sua largura em telas menores; filtros e formulários podem mudar de disposição e há contêineres com rolagem para tabelas.

A hierarquia proposta para cada tela é: título/contexto da página → indicadores ou ação principal → conteúdo de trabalho (formulário ou tabela) → ações secundárias. Rótulos devem ser claros e as ações destrutivas devem continuar visualmente distintas e exigir confirmação, conforme os requisitos do sistema.

## 7. Problemas de usabilidade identificados nas telas anteriores

Por inspeção do CSS e do HTML existentes, foram identificados pontos de atenção, não necessariamente problemas confirmados por testes com usuários:

1. No breakpoint de celular, o menu lateral oculta os rótulos ao reduzir o tamanho da fonte a zero. Os ícones permanecem, mas é preciso assegurar nomes acessíveis e que a navegação seja compreensível e utilizável por toque.
2. A regra de hover do menu troca o fundo para cinza claro e o texto para preto, mas o estado de foco de teclado não está explicitamente definido na folha analisada.
3. Há estilos locais inline e estilos Bootstrap junto ao CSS compartilhado, por exemplo na área de clima. Isso pode dificultar a manutenção e gerar diferenças visuais entre telas.
4. A paleta de status não está totalmente centralizada: aparecem cores locais e classes do Bootstrap. O vermelho `#FF0000` é intenso e precisa ser revisto, inclusive em combinação com texto/fundo escuro.
5. A área de clima usa texto secundário pequeno; a legibilidade em celular e com zoom precisa ser conferida.
6. Cartões com títulos que não quebram linha podem pressionar o layout em larguras estreitas; é necessário validar o comportamento real em diferentes larguras.
7. O uso de ícones sem texto alternativo em navegação e cabeçalho deve ser verificado junto à presença de texto visível ou nome acessível, especialmente nos controles que ficam apenas com ícone.

## 8. Mudanças realizadas e justificativas

Este registro não afirma que mudanças de interface foram implementadas por meio desta pesquisa. As decisões visuais já observáveis no projeto e as melhorias propostas são:

| Decisão/estado | Justificativa |
|---|---|
| CSS compartilhado e estrutura de cabeçalho, navegação lateral e conteúdo principal | Mantêm a identidade consistente entre as páginas de administrador e usuário e tornam a localização das seções previsível. |
| Cartões para totais e alertas, seguidos de clima e tabela | Priorizam leitura rápida dos indicadores sem remover o acesso aos dados detalhados. |
| Azul-marinho e cinza azulado como base | Reforçam uma aparência tecnológica e mantêm o conteúdo agrupado em superfícies reconhecíveis. |
| Diferenciação do item ativo e realce de hover | Ajudam a indicar localização e resposta da interface; o contraste e o foco ainda devem ser testados. |
| Manter rótulos junto às cores de status | Evita depender exclusivamente da percepção de cor e melhora a interpretação dos dados. |
| Consolidar tokens, definir fonte de sistema e rever foco/cores de estado (próximas melhorias) | Reduz inconsistências, melhora legibilidade e facilita uma futura validação WCAG. Essas ações ainda precisam ser implementadas e verificadas. |

## 9. Testes de contraste, responsividade e legibilidade

**Verificação registrada:** foi feita inspeção estática dos estilos e das telas no repositório. O CSS contém breakpoints em `1400px`, `900px`, `700px` e `500px`, além de regras para formulários flexíveis e tabelas responsivas. Isso confirma a existência de adaptações, mas não comprova o resultado visual em dispositivos reais.

**Não realizado/sem evidência no repositório:** não há registro de execução de medidor de contraste, auditoria automatizada, testes em navegadores/tamanhos específicos ou teste de leitura com usuários. Portanto, não se atribuem resultados WCAG ou proporções de contraste a esta análise.

**Verificações necessárias antes da finalização:**

- Medir as combinações reais de texto, fundo, botão, hover, foco e status no [WebAIM Contrast Checker](https://webaim.org/resources/contrastchecker/), considerando tamanhos e pesos tipográficos; corrigir as combinações que não atendam ao nível AA aplicável.
- Conferir cada estado com zoom de 200% e texto ampliado, sem perda de conteúdo ou controles.
- Testar ao menos larguras de celular, tablet e desktop; verificar navegação, cartões, filtros, formulários, tabela, rolagem e ausência de corte/overflow inesperado.
- Percorrer todas as ações somente com teclado e confirmar que o foco está sempre visível e que os ícones/controles têm nomes acessíveis.
- Verificar estados ativo, manutenção, alerta e vazio usando texto e ícone além da cor.
- Registrar data, navegador/dispositivo, ferramenta, combinações testadas e resultado quando os testes forem realizados.

## 10. Ferramentas utilizadas

- **GitHub:** consulta dos arquivos do projeto e registro da documentação.
- **Visual Studio Code:** listado na documentação anterior do projeto como editor utilizado pela equipe.
- **Bootstrap 5 e Bootstrap Icons:** componentes, utilitários responsivos e ícones usados no frontend.
- **HTML, CSS, JavaScript e PHP:** tecnologias da interface e aplicação.
- **Google Chrome:** listado na documentação anterior como ferramenta de visualização; não há registro nesta pesquisa de uma sessão de teste específica.
- **ChatGPT (OpenAI):** apoio à organização e redação desta documentação e à síntese de princípios de teoria das cores e acessibilidade. As recomendações precisam ser avaliadas pela equipe; a IA não realizou testes visuais, medições ou validação com usuários.

## 11. Fontes consultadas

- [Repositório Ferrorama](https://github.com/Pedro-Vieira17/SA_ferrorama)
- [CSS compartilhado — `assets/style/style.css`](https://github.com/Pedro-Vieira17/SA_ferrorama/blob/main/assets/style/style.css)
- [Dashboard do administrador — `public/tela_inicial.php`](https://github.com/Pedro-Vieira17/SA_ferrorama/blob/main/public/tela_inicial.php)
- [Dashboard do usuário — `public/tela_inicial_usuario.php`](https://github.com/Pedro-Vieira17/SA_ferrorama/blob/main/public/tela_inicial_usuario.php)
- [Pesquisa anterior de identidade visual — `doc/identidade-visual.md`](https://github.com/Pedro-Vieira17/SA_ferrorama/blob/main/doc/identidade-visual.md)
- [Bootstrap 5.3 — documentação](https://getbootstrap.com/docs/5.3/)
- [Bootstrap Icons](https://icons.getbootstrap.com/)
- [W3C — Web Content Accessibility Guidelines (WCAG) 2.2](https://www.w3.org/TR/WCAG22/)
- [WebAIM — Contrast Checker](https://webaim.org/resources/contrastchecker/)
- [OpenAI — ChatGPT](https://chatgpt.com/)
