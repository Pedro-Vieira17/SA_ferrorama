## 1. Funções nativas do PHP

# Validação e tratamento de dados

- ctype_digit() — Verifica se um texto contém somente números. Arquivo: public/excluir_informacoes_trem.php.
- filter_input() — Recebe diretamente um dado de entrada, como GET ou POST, e permite validá-lo ou filtrá-lo. Arquivo: public/salvar_sensor.php, public/atualizar_usuario.php, public/salvar_rota.php, public/salvar_edicao_sensores.php, public/excluir_usuario.php, public/excluir_informacoes_sensor.php, public/detalhes_rota.php, public/excluir_trem.php, public/excluir_rota.php.
- filter_var() — Valida ou filtra um valor que já está em uma variável, como um e-mail ou número. Arquivo: index.php, public/atualizar_usuario.php, public/tela_inicial.php, public/cadastro_admin.php, public/atualizar_rota.php, public/atualizar_trem.php.
- function_exists() — Verifica se uma função já foi declarada, evitando uma nova declaração com o mesmo nome. Arquivo: public/clima.php, public/buscar_cep.php.
- in_array() — Verifica se determinado valor existe dentro de um array. Arquivo: public/salvar_sensor.php, public/tela_inicial.php, public/salvar_rota.php, public/salvar_edicao_sensores.php, public/cadastro_admin.php, public/atualizar_rota.php, public/detalhes_rota.php, public/atualizar_trem.php.
- is_array() — Verifica se uma variável é um array. Arquivo: public/clima.php, public/buscar_cep.php.
- mb_strlen() — Conta a quantidade de caracteres de um texto, considerando acentos e caracteres especiais. Arquivo: public/salvar_sensor.php, public/salvar_edicao_sensores.php, public/atualizar_rota.php.
- preg_match() — Verifica se um texto corresponde a um padrão definido por expressão regular. Arquivo: public/tela_inicial.php, public/atualizar_trem.php.
- preg_replace() — Substitui partes de um texto que correspondem a um padrão. No projeto, remove caracteres que não são números. Arquivo: public/atualizar_usuario.php, public/cadastro_admin.php, public/buscar_cep.php.
- strlen() — Retorna o tamanho de um texto em bytes. No projeto, ajuda a conferir a quantidade de números do CEP e CPF. Arquivo: public/atualizar_usuario.php, public/tela_inicial.php, public/cadastro_admin.php, public/atualizar_trem.php, public/buscar_cep.php.
- trim() — Remove espaços em branco do início e do final de um texto. Arquivo: index.php, public/salvar_sensor.php, public/salvar_trem.php, public/atualizar_usuario.php, public/tela_inicial.php, public/salvar_rota.php, public/gerenciar_sensores_usuario.php, public/gerenciar_sensores.php, public/salvar_edicao_sensores.php, public/cadastro_admin.php, public/atualizar_rota.php, public/tela_inicial_usuario.php, public/atualizar_trem.php.

# Segurança e sessões
- htmlspecialchars() — Converte caracteres especiais para que sejam exibidos como texto no HTML, ajudando a evitar ataques XSS. Arquivo: index.php, infra/seguranca.php, public/landing.php, public/gerenciar_sensores_usuario.php, public/gerenciar_sensores.php, public/rotas.php, public/cadastro_admin.php, public/excluir_informacoes_trem.php.
- password_hash() — Gera um hash seguro da senha para armazená-la sem salvar a senha original. Arquivo: public/cadastro_admin.php.
- password_verify() — Confere se a senha digitada corresponde ao hash armazenado. Arquivo: index.php.
- session_destroy() — Encerra a sessão atual. Arquivo: logout.php.
- session_get_cookie_params() — Retorna as configurações do cookie da sessão, como caminho, domínio e opções de segurança. Arquivo: logout.php.
- session_name() — Retorna o nome utilizado para identificar o cookie da sessão. Arquivo: logout.php.
- session_regenerate_id() — Gera um novo identificador para a sessão, aumentando a segurança após o login. Arquivo: index.php.
- session_start() — Inicia uma nova sessão ou recupera uma sessão existente. Arquivo: logout.php, infra/seguranca.php.
- session_status() — Informa o estado atual da sessão, permitindo verificar se ela está ativa. Arquivo: logout.php, infra/seguranca.php.
- setcookie() — Envia ou altera um cookie no navegador. No logout, é usado para expirar o cookie da sessão. Arquivo: logout.php.

# Respostas HTTP e URLs
- header() — Envia cabeçalhos HTTP. No projeto, é utilizado principalmente para redirecionar o usuário para outra página e definir o tipo de conteúdo da resposta. Arquivo: index.php, logout.php, infra/seguranca.php, public/salvar_sensor.php, public/salvar_trem.php, public/atualizar_usuario.php, public/tela_inicial.php, public/salvar_rota.php, public/gerenciar_sensores_usuario.php, public/salvar_edicao_sensores.php, public/atualizar_rota.php, public/excluir_usuario.php, public/rotas_usuario.php, public/tela_inicial_usuario.php, public/excluir_informacoes_sensor.php, public/detalhes_rota.php, public/excluir_trem.php, public/atualizar_trem.php, public/excluir_informacoes_trem.php, public/buscar_cep.php, public/excluir_rota.php.
- http_response_code() — Define o código de resposta HTTP, como 400 para requisição inválida, 404 para conteúdo não encontrado e 502 para falha na comunicação com um serviço. Arquivo: public/excluir_usuario.php, public/buscar_cep.php.
- urlencode() — Codifica um texto para que possa ser colocado corretamente em uma URL, como mensagens de erro ou sucesso. Arquivo: public/salvar_sensor.php, public/salvar_trem.php, public/atualizar_usuario.php, public/tela_inicial.php, public/salvar_rota.php, public/clima.php, public/salvar_edicao_sensores.php, public/atualizar_rota.php, public/excluir_informacoes_sensor.php, public/detalhes_rota.php, public/excluir_trem.php, public/atualizar_trem.php, public/excluir_informacoes_trem.php, public/excluir_rota.php.

# Comunicação com APIs e JSON
- curl_init() — Inicia uma conexão cURL com o endereço de uma API. Arquivo: public/clima.php, public/api.php, public/buscar_cep.php.
- curl_setopt_array() — Define várias configurações da conexão cURL de uma só vez, como tempo limite e forma de receber a resposta. Arquivo: public/clima.php, public/api.php, public/buscar_cep.php.
- curl_exec() — Executa a requisição à API e obtém a resposta. Arquivo: public/clima.php, public/api.php, public/buscar_cep.php.
- curl_getinfo() — Retorna informações da requisição, como o código HTTP recebido. Arquivo: public/clima.php, public/api.php, public/buscar_cep.php.
- curl_close() — Encerra a conexão cURL depois da requisição. Arquivo: public/clima.php, public/api.php, public/buscar_cep.php.
- json_decode() — Converte um texto JSON recebido de uma API em dados que o PHP consegue utilizar. Arquivo: public/clima.php, public/api.php, public/buscar_cep.php.
- json_encode() — Converte dados do PHP em um texto no formato JSON para enviar uma resposta. Arquivo: public/buscar_cep.php.

# Datas, horários e formatação
- date() — Formata uma data ou horário para exibição, por exemplo, no formato 26/11/2026 09:30. Arquivo: public/clima.php, public/rotas.php, public/rotas_usuario.php, public/detalhes_rota.php.
- nl2br() — Transforma quebras de linha de um texto em elementos HTML <br>, mantendo as linhas na página. Arquivo: public/detalhes_rota.php.
- number_format() — Formata um número definindo casas decimais e separadores. No projeto, é utilizado para apresentar distâncias. Arquivo: public/rotas.php, public/rotas_usuario.php, public/detalhes_rota.php.
- round() — Arredonda um número. No projeto, é utilizado para arredondar a temperatura recebida pela API. Arquivo: public/clima.php.
- str_pad() — Completa um texto até atingir determinado tamanho. No projeto, adiciona zeros antes dos IDs, como 01 ou 001. Arquivo: public/tela_inicial.php, public/gerenciar_sensores_usuario.php, public/gerenciar_sensores.php, public/tela_inicial_usuario.php.
- strtotime() — Converte um texto que representa uma data ou horário em um valor que o PHP consegue manipular. Arquivo: public/clima.php, public/rotas.php, public/rotas_usuario.php, public/detalhes_rota.php.
- substr() — Extrai uma parte de um texto. No projeto, é utilizado para exibir somente a parte necessária de um horário. Arquivo: public/tela_inicial.php.
- time() — Retorna o horário atual em timestamp Unix, utilizado no projeto para definir a expiração do cookie da sessão. Arquivo: logout.php.

# Registro de erros
- error_log() — Registra uma mensagem no log de erros do PHP/servidor, ajudando a identificar problemas durante a execução. Arquivo: public/salvar_sensor.php, public/salvar_rota.php, public/salvar_edicao_sensores.php, public/excluir_rota.php.
- ini_get() — Consulta o valor atual de uma configuração do PHP. No projeto, verifica se as sessões utilizam cookies. Arquivo: logout.php.

## 2. Funções criadas pela equipe em PHP
- exigirLogin() — Verifica se existe um usuário autenticado na sessão. Caso não exista, redireciona para a página de login. Arquivo: infra/seguranca.php, public/gerenciar_sensores_usuario.php, public/rotas_usuario.php, public/tela_inicial_usuario.php, public/detalhes_rota.php.
- exigirAdministrador() — Verifica se o usuário está autenticado e se possui o tipo de acesso administrador. Caso contrário, impede o acesso à página administrativa. Arquivo: infra/seguranca.php, public/salvar_sensor.php, public/salvar_trem.php, public/atualizar_usuario.php, public/tela_inicial.php, public/salvar_rota.php, public/gerenciar_sensores.php, public/salvar_edicao_sensores.php, public/rotas.php, public/usuarios_cadastrados.php, public/cadastro_admin.php, public/atualizar_rota.php, public/excluir_usuario.php, public/excluir_informacoes_sensor.php, public/excluir_trem.php, public/atualizar_trem.php, public/excluir_informacoes_trem.php, public/excluir_rota.php.
- e($valor) — Recebe um valor e utiliza htmlspecialchars() para exibi-lo com segurança no HTML. Arquivo: infra/seguranca.php, public/tela_inicial.php, public/gerenciar_sensores.php, public/rotas.php, public/usuarios_cadastrados.php, public/cadastro_admin.php, public/rotas_usuario.php, public/tela_inicial_usuario.php, public/detalhes_rota.php.
- h($valor) — Também utiliza htmlspecialchars() para proteger a exibição de um valor no HTML. Tem a mesma finalidade da função e(). Arquivo: public/gerenciar_sensores.php, public/rotas.php.
- chamarApi($url) — Recebe o endereço de uma API, realiza a requisição com cURL e retorna os dados recebidos em formato utilizável pelo PHP. Quando a requisição falha ou não retorna sucesso, devolve null. Arquivo: public/clima.php, public/api.php, public/buscar_cep.php.
- obterClima($cidade) — Recebe o nome de uma cidade, consulta a API de localização para obter as coordenadas e depois consulta a API de clima. Retorna informações como cidade, temperatura, condição climática e horário da consulta. Arquivo: public/tela_inicial.php, public/clima.php, public/tela_inicial_usuario.php.
- voltarEdicao($id, $mensagem) — Recebe o ID de um trem e uma mensagem e redireciona para a página de edição, levando a mensagem pela URL. Arquivo: public/atualizar_trem.php.

## 3. Funções nativas do JavaScript e do navegador
- confirm() — Exibe uma caixa de confirmação com as opções de aceitar ou cancelar. Retorna true se o usuário confirmar e false se cancelar. Arquivo: public/tela_inicial.php, public/gerenciar_sensores.php, public/rotas.php, public/usuarios_cadastrados.php.
- encodeURIComponent() — Codifica um texto para que possa ser utilizado com segurança em uma parte de uma URL. No projeto, é utilizado ao enviar o CEP para consulta. Arquivo: public/usuarios_cadastrados.php, public/cadastro_admin.php.
- JSON.parse() — Converte um texto JSON em um objeto JavaScript para que seus dados possam ser acessados. Arquivo: public/usuarios_cadastrados.php, public/cadastro_admin.php.

## 4. Métodos do JavaScript

# Elementos HTML e eventos
- document.getElementById() — Localiza e retorna um elemento HTML pelo seu atributo id. Arquivo: public/tela_inicial.php, public/gerenciar_sensores_usuario.php, public/gerenciar_sensores.php, public/rotas.php, public/usuarios_cadastrados.php, public/cadastro_admin.php.
- addEventListener() — Associa uma função a um evento, como digitar em um campo, sair dele ou abrir um modal. Arquivo: public/tela_inicial.php, public/gerenciar_sensores.php, public/rotas.php, public/usuarios_cadastrados.php, public/cadastro_admin.php.
- getAttribute() — Obtém o valor de um atributo HTML de um elemento, como os atributos data-id, data-nome e data-status. Arquivo: public/gerenciar_sensores.php, public/rotas.php.

# Manipulação de arrays e textos
- forEach() — Percorre cada elemento de um array e executa uma função para cada item. Arquivo: public/cadastro_admin.php.
- replace() — Substitui partes de um texto. No projeto, remove caracteres que não são números do CEP. Arquivo: public/usuarios_cadastrados.php, public/cadastro_admin.php.
- slice() — Extrai uma parte de um texto. No projeto, limita o CEP a oito números e ajuda a inserir o hífen na posição correta. Arquivo: public/usuarios_cadastrados.php, public/cadastro_admin.php.
- split() — Divide um texto em partes usando um separador. No projeto, separa partes da data e do horário. Arquivo: public/rotas.php.
- trim() — Remove espaços em branco do início e do final de um texto digitado. Arquivo: public/usuarios_cadastrados.php, public/cadastro_admin.php.

# Requisições e modais
- open() — Método do XMLHttpRequest que configura a requisição, informando o método HTTP, o endereço e se ela será assíncrona. Arquivo: public/usuarios_cadastrados.php, public/cadastro_admin.php.
- send() — Método do XMLHttpRequest que envia a requisição ao servidor. Arquivo: public/usuarios_cadastrados.php, public/cadastro_admin.php.
- show() — Método do modal do Bootstrap que exibe o modal na tela. Arquivo: public/tela_inicial.php.

## 5. Construtores de objetos e bibliotecas utilizados no JavaScript
- XMLHttpRequest() — Constrói um objeto utilizado para enviar requisições ao servidor sem precisar recarregar a página. Arquivo: public/usuarios_cadastrados.php, public/cadastro_admin.php.
- bootstrap.Modal() — Cria um objeto de modal do Bootstrap, permitindo controlar sua exibição. Arquivo: public/tela_inicial.php.
- Chart() — Cria um gráfico utilizando a biblioteca Chart.js. No projeto, é utilizado para apresentar os dados dos sensores. Arquivo: public/gerenciar_sensores_usuario.php.

## 6. Funções criadas pela equipe em JavaScript
- limparEndereco() — Limpa os campos de endereço do formulário, como logradouro, bairro, cidade e UF. Arquivo: public/cadastro_admin.php.
- buscarCep() — Consulta o CEP informado por meio de uma requisição ao arquivo de busca de CEP. Depois, utiliza os dados recebidos para preencher automaticamente os campos de endereço. Arquivo: public/usuarios_cadastrados.php, public/cadastro_admin.php.

## 7. Funções anônimas (callbacks) utilizadas no JavaScript
São funções sem nome, definidas no próprio local em que serão executadas. No projeto, aparecem como respostas a eventos e ao resultado das requisições de CEP.

- function (event) { ... } — Função anônima executada quando um modal é aberto. Recebe o evento e permite recuperar o botão que abriu o modal. Arquivo: public/tela_inicial.php, public/gerenciar_sensores.php, public/rotas.php, public/usuarios_cadastrados.php.
- function () { ... } no evento input — Função anônima executada quando o usuário digita no campo de CEP. Arquivo: public/usuarios_cadastrados.php, public/cadastro_admin.php.
- function (id) { ... } — Função anônima passada ao forEach(). Recebe cada ID dos campos de endereço para limpá-los ou preenchê-los. Arquivo: public/cadastro_admin.php.
- requisicao.onload = function () { ... } — Função executada quando a resposta da requisição de CEP é recebida. Arquivo: public/usuarios_cadastrados.php, public/cadastro_admin.php.
- requisicao.onerror = function () { ... } — Função executada quando ocorre um erro de comunicação na requisição de CEP. Arquivo: public/usuarios_cadastrados.php, public/cadastro_admin.php.