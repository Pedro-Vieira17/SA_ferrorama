# 🚂 Ferrorama

Aplicação web acadêmica para consultar e gerenciar informações ferroviárias, como trens, sensores e rotas. O projeto foi desenvolvido como Situação de Aprendizagem do curso Técnico em Desenvolvimento de Sistemas do SENAI, turma DS24/M4.



## Sobre o sistema

O Ferrorama reúne cadastros e consultas em uma aplicação web com autenticação por perfil. Os dados são armazenados em um banco MySQL/MariaDB. A aplicação também consulta informações de clima da cidade escolhida no dashboard por meio dos serviços Open-Meteo.

## Funcionalidades disponíveis

- Página pública de apresentação e formulário de login por e-mail e senha.
- Redirecionamento para áreas diferentes conforme o perfil: administrador ou usuário.
- Dashboard com indicadores de sensores, trens e alertas, lista de trens e consulta de clima.
- Consulta de sensores. Na área administrativa, há pesquisa e filtros por tipo de dado, trem e status, além de operações para cadastrar, editar e excluir sensores.
- Consulta de rotas e visualização de detalhes. Administradores podem cadastrar, editar e excluir rotas.
- Na área administrativa, cadastro e gerenciamento de usuários e trens.
- Encerramento da sessão (logout).

As opções de gerenciamento são restritas ao perfil administrador. Usuários comuns têm acesso ao dashboard e às consultas disponíveis de sensores e rotas. Cada sensor e cada rota são associados a um trem cadastrado.

## Tecnologias e recursos

- **PHP** para a lógica do servidor, páginas e comunicação com o banco de dados.
- **MySQL/MariaDB** para persistência dos usuários, trens, sensores e rotas.
- **HTML e CSS** para a estrutura e a apresentação das páginas; os estilos próprios ficam em `assets/style/style.css`.
- **JavaScript** é usado pontualmente nas interações da interface, como preencher formulários de edição em janelas modais. O Bootstrap também utiliza seu JavaScript para componentes da interface.
- **Bootstrap 5** e **Bootstrap Icons** são carregados pelo CDN nas páginas que os utilizam.
- **Open-Meteo** fornece os dados de clima e geocodificação consultados pelo dashboard.

O projeto não possui dependências instaladas via Composer. Bootstrap, Bootstrap Icons e Open-Meteo dependem de conexão à internet.

## Requisitos

- PHP 8 ou compatível, com as extensões `mysqli`, `curl` e suporte a sessões habilitados.
- MySQL ou MariaDB.
- Apache ou outro servidor web configurado para executar PHP. Para desenvolvimento local, é possível usar XAMPP.
- Navegador atualizado e conexão à internet para os recursos externos e a consulta de clima.

## Instalação local

### 1. Obter o projeto

Clone o repositório para o diretório servido pelo Apache, como `htdocs` no XAMPP:

```bash
git clone https://github.com/Pedro-Vieira17/SA_ferrorama.git
```

### 2. Criar e importar o banco de dados

O arquivo [`database/db.sql`](database/db.sql) cria o banco `sa_ferrorama`, suas tabelas e registros iniciais de demonstração.

Com Apache e MySQL iniciados, importe o arquivo pelo phpMyAdmin ou use o terminal:

```bash
mysql -u root -p < database/db.sql
```

Ajuste o comando às credenciais do seu MySQL. O script inclui uma conta administrativa inicial (`admin@ferrovia.com.br`), mas a senha correspondente não é documentada neste repositório. Não presuma uma senha padrão; configure uma credencial válida para o ambiente local antes de usar a aplicação.

### 3. Configurar a conexão

A configuração está em [`infra/conexao.php`](infra/conexao.php). Os valores atuais apontam para MySQL local (`localhost`, usuário `root`, senha vazia, banco `sa_ferrorama` e porta `3306`). Altere-os para corresponder ao seu ambiente.

Não use nem publique credenciais de desenvolvimento em ambientes compartilhados ou de produção.

### 4. Abrir a aplicação

Com o banco configurado e Apache/MySQL em execução, acesse:

```text
http://localhost/SA_ferrorama/
```

A página inicial é a apresentação pública. Use **Entrar** para abrir o formulário de login. Se a pasta ou a porta do servidor tiver outro nome, ajuste o endereço.

## Estrutura do repositório

```text
SA_ferrorama/
├── assets/
│   ├── icons/              # Ícones SVG
│   ├── img/                # Imagens da interface
│   └── style/style.css     # Estilos compartilhados
├── database/db.sql         # Banco, tabelas e dados de demonstração
├── doc/                    # Manual, pesquisas e documentação do projeto
├── infra/
│   ├── conexao.php         # Conexão com MySQL
│   └── seguranca.php       # Sessão, controle de acesso e funções auxiliares
├── public/                 # Landing page, dashboards e módulos da aplicação
├── index.php               # Página inicial e login
├── logout.php              # Encerramento da sessão
└── readme.md               # Este documento
```

Para instruções de navegação e uso, consulte o [Manual rápido de utilização](doc/MANUAL.md). A documentação de validação está em [`doc/validacao_requisitos.md`](doc/validacao_requisitos.md).

## Limitações e observações

- Os dados não são atualizados automaticamente em tempo real; as páginas consultam o estado salvo no banco quando são carregadas.
- Não há módulo de relatórios implementado na versão atual, embora esse recurso apareça em requisitos/documentos anteriores.
- A documentação de validação registra funcionalidades que precisam de testes práticos e problemas pendentes em algumas operações, incluindo cadastro e exclusão, além de inconsistências nos status de sensores. O funcionamento completo não deve ser considerado validado.
- A consulta de clima depende da disponibilidade dos serviços Open-Meteo e da extensão PHP `curl`.
- A configuração de banco incluída é voltada ao desenvolvimento local. Não exponha a instalação na internet com credenciais locais ou dados de demonstração.

## Equipe

Projeto desenvolvido pela equipe da Situação de Aprendizagem do curso Técnico em Desenvolvimento de Sistemas — SENAI, turma DS24/M4.

- Pedro Vieira
- Davi Zilz
- Enzo Vegini
- Francisco Goulart
