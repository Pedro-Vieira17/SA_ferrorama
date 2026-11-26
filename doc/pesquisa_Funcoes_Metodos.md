## 1. Funções nativas do PHP

# Validação e tratamento de dados
- ctype_digit() — Verifica se um texto contém somente números.
- filter_input() — Recebe um dado enviado pelo usuário, como por GET ou POST, e permite validá-lo ou filtrá-lo.
- filter_var() — Valida ou filtra um valor que já está armazenado em uma variável, como e-mail, número inteiro ou decimal.
- function_exists() — Verifica se uma função já foi declarada, evitando que ela seja definida novamente.
- in_array() — Verifica se determinado valor existe dentro de um array.
- is_array() — Verifica se uma variável é um array.
- mb_strlen() — Conta a quantidade de caracteres de um texto, considerando acentos e caracteres especiais.
- preg_match() — Verifica se um texto corresponde a um padrão definido por expressão regular.
- preg_replace() — Substitui partes de um texto que correspondem a um padrão. No projeto, remove caracteres que não são números.
- strlen() — Retorna o tamanho de um texto em bytes. É utilizada, por exemplo, para conferir a quantidade de números do CEP e do CPF.
- trim() — Remove espaços em branco do início e do final de um texto.

# Segurança e sessões
- htmlspecialchars() — Converte caracteres especiais para que sejam exibidos como texto no HTML, ajudando a evitar a execução de códigos maliciosos (XSS).
- password_hash() — Gera um hash seguro da senha para armazená-la sem salvar a senha original.
- password_verify() — Confere se a senha digitada corresponde ao hash armazenado.
- session_destroy() — Encerra a sessão atual.
- session_get_cookie_params() — Retorna as configurações do cookie da sessão, como caminho, domínio e opções de segurança.
- session_name() — Retorna o nome utilizado para identificar o cookie da sessão.
- session_regenerate_id() — Gera um novo identificador para a sessão, aumentando a segurança após o login.
- session_start() — Inicia uma nova sessão ou recupera uma sessão existente.
- session_status() — Informa o estado atual da sessão, permitindo verificar se ela está ativa.
- setcookie() — Envia ou altera um cookie no navegador. No logout, é usado para expirar o cookie da sessão.

# Respostas HTTP e URLs
- header() — Envia cabeçalhos HTTP. No projeto, é utilizado principalmente para redirecionar o usuário para outra página e definir o tipo de conteúdo da resposta.
- http_response_code() — Define o código de resposta HTTP, como 400 para requisição inválida, 404 para conteúdo não encontrado e 502 para falha na comunicação com um serviço.
- urlencode() — Codifica um texto para que possa ser colocado corretamente em uma URL, como mensagens de erro ou sucesso.

# Comunicação com APIs e JSON
- curl_init() — Inicia uma conexão cURL com o endereço de uma API.
- curl_setopt_array() — Define várias configurações da conexão cURL de uma só vez, como tempo limite e forma de receber a resposta.
- curl_exec() — Executa a requisição à API e obtém a resposta.
- curl_getinfo() — Retorna informações da requisição, como o código HTTP recebido.
- curl_close() — Encerra a conexão cURL depois da requisição.
- json_decode() — Converte um texto JSON recebido de uma API em dados que o PHP consegue utilizar.
- json_encode() — Converte dados do PHP em um texto no formato JSON para enviar uma resposta.

# Datas, horários e formatação
- date() — Formata uma data ou horário para exibição, por exemplo, no formato 26/11/2026 09:30.
- nl2br() — Transforma quebras de linha de um texto em elementos HTML <br>, mantendo as linhas na página.
- number_format() — Formata um número definindo casas decimais e separadores. No projeto, é utilizado para apresentar distâncias.
- round() — Arredonda um número. No projeto, é utilizado para arredondar a temperatura recebida pela API.
- str_pad() — Completa um texto até atingir determinado tamanho. No projeto, adiciona zeros antes dos IDs, como 01 ou 001.
- strtotime() — Converte um texto que representa uma data ou horário em um valor que o PHP consegue manipular.
- substr() — Extrai uma parte de um texto. No projeto, é utilizado para exibir somente a parte necessária de um horário.
- time() — Retorna o horário atual em timestamp Unix, utilizado no projeto para definir a expiração do cookie da sessão.

# Registro de erros
- error_log() — Registra uma mensagem no log de erros do PHP/servidor, ajudando a identificar problemas durante a execução.

## 2. Funções criadas pela equipe em PHP
- exigirLogin() — Verifica se existe um usuário autenticado na sessão. Caso não exista, redireciona para a página de login.
- exigirAdministrador() — Verifica se o usuário está autenticado e se possui o tipo de acesso administrador. Caso contrário, impede o acesso à página administrativa.
- e($valor) — Recebe um valor e utiliza htmlspecialchars() para exibi-lo com segurança no HTML.
- h($valor) — Também utiliza htmlspecialchars() para proteger a exibição de um valor no HTML. Tem a mesma finalidade da função e().
- chamarApi($url) — Recebe o endereço de uma API, realiza a requisição com cURL e retorna os dados recebidos em formato utilizável pelo PHP. Quando a requisição falha ou não retorna sucesso, devolve null. Essa função aparece definida em mais de um arquivo do projeto.
- obterClima($cidade) — Recebe o nome de uma cidade, consulta a API de localização para obter as coordenadas e depois consulta a API de clima. Retorna informações como cidade, temperatura, condição climática e horário da consulta.
- voltarEdicao($id, $mensagem) — Recebe o ID de um trem e uma mensagem e redireciona para a página de edição, levando a mensagem pela URL.

## 3. Funções nativas do JavaScript e do navegador
- confirm() — Exibe uma caixa de confirmação com as opções de aceitar ou cancelar. Retorna true se o usuário confirmar e false se cancelar.
- encodeURIComponent() — Codifica um texto para que possa ser utilizado com segurança em uma parte de uma URL. No projeto, é utilizado ao enviar o CEP para consulta.
- JSON.parse() — Converte um texto JSON em um objeto JavaScript para que seus dados possam ser acessados.

## 4. Métodos do JavaScript
Elementos HTML e eventos
- document.getElementById() — Localiza e retorna um elemento HTML pelo seu atributo id.
- addEventListener() — Associa uma função a um evento, como digitar em um campo, sair dele ou abrir um modal.
- getAttribute() — Obtém o valor de um atributo HTML de um elemento, como os atributos data-id, data-nome e data-status.

# Manipulação de arrays e textos
- forEach() — Percorre cada elemento de um array e executa uma função para cada item.
- replace() — Substitui partes de um texto. No projeto, remove caracteres que não são números do CEP.
- slice() — Extrai uma parte de um texto. No projeto, limita o CEP a oito números e ajuda a inserir o hífen na posição correta.
- split() — Divide um texto em partes usando um separador. No projeto, separa partes da data e do horário.
- trim() — Remove espaços em branco do início e do final de um texto digitado.

# Requisições e modais
- open() — Método do XMLHttpRequest que configura a requisição, informando o método HTTP, o endereço e se ela será assíncrona.
- send() — Método do XMLHttpRequest que envia a requisição ao servidor.
- show() — Método do modal do Bootstrap que exibe o modal na tela.

## 5. Construtores de objetos e bibliotecas utilizados no JavaScript
- XMLHttpRequest() — Constrói um objeto utilizado para enviar requisições ao servidor sem precisar recarregar a página.
- bootstrap.Modal() — Cria um objeto de modal do Bootstrap, permitindo controlar sua exibição.
- Chart() — Cria um gráfico utilizando a biblioteca Chart.js. No projeto, é utilizado para apresentar os dados dos sensores.

## 6. Funções criadas pela equipe em JavaScript
- limparEndereco() — Limpa os campos de endereço do formulário, como logradouro, bairro, cidade e UF.
- buscarCep() — Consulta o CEP informado por meio de uma requisição ao arquivo de busca de CEP. Depois, utiliza os dados recebidos para preencher automaticamente os campos de endereço.

## 7. Funções anônimas (callbacks) utilizadas no JavaScript
- function (event) { ... } — Função anônima utilizada como resposta a um evento, por exemplo, quando um modal é aberto.
- function (id) { ... } — Função anônima utilizada pelo forEach() para executar uma ação para cada ID presente no array de campos de endereço.