# 🚂 Ferrorama

Sistema web para consulta e gerenciamento de informações ferroviárias — trens, sensores e rotas — desenvolvido como Situação de Aprendizagem do curso Técnico em Desenvolvimento de Sistemas.


## 📋 Sobre o projeto

O Ferrorama reúne em uma aplicação web informações que, quando dispersas, dificultam o acompanhamento da operação ferroviária. O sistema oferece um dashboard e módulos para consultar informações de trens, sensores e rotas, além de administração de contas e cadastros conforme o perfil de acesso.

### Problema que busca resolver

A falta de um local único para organizar informações dos trens, acompanhar o estado dos sensores e consultar rotas pode atrasar a identificação de situações que exigem atenção. O Ferrorama busca centralizar esses registros e facilitar sua consulta.

### Objetivos

- Reunir informações de trens, sensores e rotas em uma interface web.
- Apresentar indicadores e estados operacionais no dashboard.
- Permitir que administradores gerenciem os cadastros e as contas de acesso.
- Permitir que usuários autenticados consultem as informações autorizadas ao seu perfil.

## ⚙️ Funcionalidades implementadas

- Autenticação por e-mail e senha, com redirecionamento conforme o perfil, e logout.
- Dashboard com indicadores de sensores cadastrados, trens cadastrados, alertas e sensores funcionando; o painel administrativo também inclui a área de consulta/gerenciamento de trens.
- Consulta de sensores, com informações como localização, tipo de dado, status e trem associado; há filtros disponíveis na área administrativa.
- Consulta de rotas e visualização de detalhes, incluindo distância, duração, status e trem associado.
- Para administradores: cadastro e gerenciamento de usuários, trens, sensores e rotas, incluindo operações de edição e exclusão onde disponíveis.
- Informações auxiliares de clima no dashboard.

> A disponibilidade de botões de edição e exclusão depende do perfil e da tela. O cadastro de sensor exige a associação a um trem existente. Para instruções passo a passo, consulte o [Manual rápido de utilização](MANUAL.md).

## 🧰 Tecnologias

| Tecnologia | Uso |
|---|---|
| PHP 8 | Lógica de aplicação e páginas do servidor |
| MySQL / MariaDB | Persistência dos usuários, trens, sensores e rotas |
| HTML5, CSS3 | Interface e interações no navegador |
| Bootstrap 5 e Bootstrap Icons | Componentes e ícones da interface |
| Chart.js | Gráficos usados no dashboard |
| Git e GitHub | Versionamento e colaboração |

## ✅ Requisitos para executar

- PHP 8 ou compatível, com extensões `mysqli` e suporte a sessões habilitados.
- MySQL ou MariaDB, com permissão para criar o banco e as tabelas.
- Apache ou outro servidor web configurado para executar PHP. Para desenvolvimento local, pode-se usar XAMPP com Apache e MySQL.
- Navegador atualizado.
- Conexão à internet para carregar recursos externos utilizados pela interface e o serviço de clima.

Não há arquivo de dependências Composer no projeto. A configuração atual usa MySQL local na porta `3306`, usuário `root`, senha vazia e banco `sa_ferrorama`; ajuste-a para corresponder ao seu ambiente antes de executar.

## 🚀 Instalação e configuração

### 1. Obter o código

Clone o repositório ou baixe o ZIP e coloque a pasta do projeto no diretório servido pelo Apache. No XAMPP, normalmente é `htdocs`.

```bash
git clone https://github.com/Pedro-Vieira17/SA_ferrorama.git
```

Se usar o clone, mova ou clone a pasta `SA_ferrorama` para `htdocs` (ou configure o Apache para servir o diretório do projeto).

### 2. Criar e importar o banco de dados

O script [`database/db.sql`](database/db.sql) cria o banco `sa_ferrorama`, suas tabelas e inclui registros de demonstração.

**Pelo phpMyAdmin:**

1. Inicie Apache e MySQL no XAMPP.
2. Abra `http://localhost/phpmyadmin`.
3. Selecione **Importar**, escolha `database/db.sql` e execute. O script já contém `CREATE DATABASE` e `USE`.

**Pelo terminal:**

```bash
mysql -u root -p < database/db.sql
```

Se sua instalação local do MySQL não usa senha para `root`, pressione Enter quando solicitada. Em instalações com outras credenciais, ajuste o comando conforme sua configuração.

> A importação cria dados de exemplo e uma conta administrativa inicial. A senha dessa conta não é documentada aqui; não presuma uma senha padrão. Em um ambiente local de teste, configure a credencial de forma segura conforme o procedimento da equipe. Nunca exponha credenciais de demonstração nem use uma conta padrão em produção.

### 3. Configurar a conexão

Edite [`infra/conexao.php`](infra/conexao.php) para usar host, usuário, senha, banco de dados e porta corretos no seu ambiente. Os valores que vêm no projeto são para desenvolvimento local (`localhost`, usuário `root`, senha vazia, banco `sa_ferrorama`, porta `3306`).

Não publique credenciais reais no repositório. Para um ambiente compartilhado ou de produção, use credenciais próprias com permissões mínimas e configuração segura do servidor.

### 4. Iniciar a aplicação

Com Apache e MySQL em execução e o banco importado, abra no navegador:

```text
http://localhost/SA_ferrorama/
```

Se o diretório do projeto tiver outro nome ou o Apache usar outra porta, adapte o endereço. A página inicial apresenta a landing page; use o link/botão de entrada para abrir o formulário de login. Entre com uma conta cadastrada no banco.

## 👥 Perfis de acesso

| Perfil | Permissões principais |
|---|---|
| **Administrador** | Acessa o dashboard administrativo; cadastra e consulta contas; gerencia trens, sensores e rotas, incluindo edição e exclusão nas telas disponíveis. |
| **Usuário** | Acessa seu dashboard, consulta tens, sensores e rotas. Não tem acesso às opções administrativas de cadastro, edição e exclusão. |

As páginas protegidas exigem autenticação. Contas e permissões devem ser administradas por pessoas autorizadas. Não compartilhe credenciais.

## 🗂️ Estrutura do projeto

```text
SA_ferrorama/
├── assets/
│   ├── icons/          # Ícones
│   ├── img/            # Imagens utilizadas na interface
│   └── style/          # Folhas de estilo
├── database/
│   └── db.sql          # Criação do banco, tabelas e dados de demonstração
├── doc/                # Documentação e pesquisas do projeto
├── infra/
│   ├── conexao.php     # Conexão com MySQL
│   └── seguranca.php   # Sessão, permissões e funções de segurança
├── public/             # Landing page e telas/módulos da aplicação
├── index.php           # Entrada da aplicação e login
├── logout.php          # Encerramento da sessão
├── MANUAL.md           # Manual rápido de utilização
└── readme.md           # Este documento
```

Os arquivos em `public/` incluem dashboards, gerenciamento de sensores, rotas, usuários e operações relacionadas. A estrutura pode evoluir conforme o projeto.

## 👨‍👩‍👧‍👦 Equipe

Projeto desenvolvido pela equipe da Situação de Aprendizagem do curso Técnico em Desenvolvimento de Sistemas — SENAI, turma DS24/M4.

| Integrante | Contribuição registrada neste README |
|---|---|
| Pedro Vieira | Integrante da equipe de desenvolvimento. |
| Davi Zilz | Integrante da equipe de desenvolvimento. |
| Enzo Vegini | Integrante da equipe de desenvolvimento. |
| Francisco Goulart | Integrante da equipe de desenvolvimento. |

O repositório não mantém um mapeamento validado de cada funcionalidade para uma pessoa específica; por isso, as contribuições individuais não são atribuídas aqui. A equipe desenvolve e revisa o sistema de forma colaborativa.

## 🖼️ Imagens e capturas de tela

As imagens atualmente armazenadas em `assets/img/` são recursos visuais da interface, não capturas de telas funcionais do sistema. Para documentar a versão final, a equipe deve adicionar capturas reais — sem dados pessoais ou credenciais — por exemplo em `doc/imagens/`, e atualizar esta seção com os caminhos:

- `doc/imagens/login.png` — tela de entrada;
- `doc/imagens/dashboard.png` — dashboard após login;
- `doc/imagens/sensores.png` — consulta de sensores;
- `doc/imagens/rotas.png` — consulta de rotas.

As imagens não estão incluídas como capturas neste momento, para não apresentar ilustrações como se fossem telas reais do sistema.

## ⚠️ Limitações conhecidas

- O projeto está em desenvolvimento; algumas operações ainda precisam de testes práticos e correções.
- A atualização automática em tempo real não está implementada; os dados mostrados correspondem ao que está salvo no banco quando a página é carregada.
- O módulo de relatórios com geração e filtros descrito em requisitos anteriores não foi localizado na versão atual.
- A validação do projeto registra inconsistências conhecidas em status de sensores, cadastro e operações de exclusão. Verifique os dados antes de confirmar alterações e exclusões.
- A conexão MySQL vem configurada para um ambiente local de desenvolvimento e deve ser alterada em outros ambientes.
- Recursos externos (por exemplo, bibliotecas e informações de clima) podem depender de internet e de serviços de terceiros.
- A instalação não deve ser exposta diretamente à internet usando credenciais de desenvolvimento ou dados de demonstração.

Para passos detalhados de login, navegação e consulta/cadastro, veja o [Manual rápido de utilização](MANUAL.md). Sugestões e problemas devem ser relatados à equipe do projeto.
