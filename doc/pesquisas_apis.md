# Pesquisa sobre APIs

**Projeto:** SA Ferrorama
**Equipe:** _(preencher)_
**Data:** _(preencher)_

---

## 1. O que é uma API e qual é sua finalidade

**API** (*Application Programming Interface*) é um conjunto de regras que permite que um sistema peça dados ou serviços a outro, sem precisar conhecer o código interno dele.

Analogia: é como o garçom de um restaurante. O cliente faz o pedido ao garçom, que leva até a cozinha e traz a resposta.

**Para que serve:**
- Integrar sistemas diferentes;
- Reaproveitar funcionalidades (pagamento, mapas, e-mail);
- Padronizar o acesso aos dados;
- Permitir que várias aplicações (site, app, outros sistemas) usem o mesmo backend.

---

## 2. Como funciona a comunicação entre sistemas

A comunicação segue o modelo **cliente-servidor**:

1. O **cliente** envia uma **requisição** (via HTTP/HTTPS);
2. O **servidor** (a API) valida, consulta o banco de dados e processa;
3. O servidor devolve uma **resposta**, com um código de status e, normalmente, dados em JSON.

```
Cliente  ── requisição ──▶  API  ──▶  Banco de dados
Cliente  ◀── resposta ────  API  ◀──  Banco de dados
```

---

## 3. O que são APIs REST

**REST** é um estilo de arquitetura para criar APIs usando HTTP. Uma API que segue esse estilo é chamada de **RESTful**.

Ideias principais:
- Tudo é um **recurso** (trem, sensor, usuário) com uma URL própria;
- Usa os **métodos HTTP** para indicar a ação;
- É **sem estado**: cada requisição leva todas as informações necessárias;
- Os dados costumam trafegar em **JSON**.

---

## 4. O papel dos métodos HTTP

| Método | Ação | Exemplo |
|---|---|---|
| **GET** | Consultar | `GET /trens` lista os trens |
| **POST** | Criar | `POST /sensores` cadastra um sensor |
| **PUT** | Substituir o recurso inteiro | `PUT /sensores/5` |
| **PATCH** | Alterar só alguns campos | `PATCH /sensores/5` |
| **DELETE** | Remover | `DELETE /sensores/5` |

**PUT x PATCH:** o PUT exige enviar todos os campos; o PATCH envia apenas o que mudou.

---

## 5. Requisições e respostas HTTP

**Requisição** (cliente → servidor) contém:
- Método e URL;
- Cabeçalhos (tipo de dado, autenticação);
- Corpo (dados enviados, em POST, PUT e PATCH).

```http
POST /api/sensores
Content-Type: application/json

{ "localizacao": "Pátio 1", "tipo_de_dado": "Velocidade", "trens_id": 2 }
```

**Resposta** (servidor → cliente) contém:
- Código de status;
- Cabeçalhos;
- Corpo (os dados).

```http
HTTP/1.1 201 Created

{ "id": 12 }
```

**Códigos de status mais comuns:**

| Código | Significado |
|---|---|
| 200 | OK |
| 201 | Criado |
| 400 | Requisição inválida |
| 401 | Não autenticado |
| 403 | Sem permissão |
| 404 | Não encontrado |
| 500 | Erro no servidor |

---

## 6. O que são endpoints

**Endpoint** é a URL por onde um recurso da API é acessado. O endpoint junto com o método HTTP define a operação.

```
https://exemplo.com/api/trens/3/sensores?status=ativo
```

- `/api` e `/trens/3/sensores`: caminho do recurso;
- `3`: id do trem;
- `?status=ativo`: filtro (query string).

Boas práticas: usar substantivos no plural (`/trens`, não `/buscarTrens`) e versionar a API (`/v1/`).

---

## 7. Troca de dados no formato JSON

**JSON** é um formato de texto simples e leve para representar dados. É o mais usado em APIs.

```json
{
  "id": 2,
  "localizacao": "Estação Central",
  "ativo": true,
  "sensores": [
    { "id": 5, "tipo_de_dado": "Temperatura" }
  ]
}
```

Em PHP, usam-se duas funções:

```php
$json  = json_encode($array);        // array PHP -> JSON
$array = json_decode($json, true);   // JSON -> array PHP
```

---

## 8. Diferenças entre uma API e um sistema web tradicional

| | Sistema web tradicional | API |
|---|---|---|
| **Entrega** | Páginas HTML | Dados (JSON) |
| **Quem usa** | Pessoas, no navegador | Outros programas |
| **Interação** | Formulários e links | Requisições HTTP |
| **Reuso** | Preso à interface | Atende site, app e outros sistemas |

Exemplo: `tela_de_cadastro.php` recebe um formulário e devolve uma página. Um endpoint de API receberia e devolveria JSON.

---

## 9. Como uma aplicação PHP pode consumir uma API

Consumir uma API é fazer uma requisição HTTP pelo PHP e tratar a resposta. Pode-se usar `file_get_contents()` (simples) ou **cURL** (mais completo).

```php
<?php
$resposta = file_get_contents('https://viacep.com.br/ws/89201000/json/');
$dados = json_decode($resposta, true);

echo $dados['logradouro'] . ' - ' . $dados['localidade'];
```

Com cURL, é possível definir método, cabeçalhos e verificar o status:

```php
<?php
$ch = curl_init('https://api.exemplo.com/trens');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: Bearer SEU_TOKEN']);

$resposta = curl_exec($ch);
$status   = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($status === 200) {
    $trens = json_decode($resposta, true);
}
```

Cuidados: verificar o status, tratar erros e validar os dados recebidos.

---

## 10. Como uma API pode disponibilizar informações para outros sistemas

A aplicação cria um arquivo PHP que recebe a requisição, consulta o banco e **devolve JSON**:

```php
<?php
// api/trens.php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../infra/conexao.php';

$resultado = $conexao->query('SELECT id, localizacao, status FROM TRENS');
$trens = $resultado->fetch_all(MYSQLI_ASSOC);

echo json_encode($trens);
```

Cuidados: devolver só o necessário (nunca a senha), usar os códigos de status corretos e exigir autenticação.

---

## 11. Autenticação, autorização e segurança

- **Autenticação:** confirma **quem** é o usuário (login, token);
- **Autorização:** define **o que** ele pode fazer (ex.: só o administrador cadastra sensores).

**Formas comuns de autenticar:** chave de API, token Bearer/JWT e OAuth 2.0.

**Boas práticas de segurança:**
- Usar **HTTPS**;
- Guardar senhas com `password_hash()`;
- Validar todas as entradas no servidor;
- Usar *prepared statements* contra SQL Injection;
- Dar a cada usuário só as permissões necessárias;
- Limitar o número de requisições;
- Não deixar tokens e senhas no código versionado.

---

## 12. Exemplos de uso de APIs em sistemas empresariais

- **Pagamentos:** Pix, cartão e boleto em lojas virtuais (Mercado Pago, Stripe);
- **Frete e rastreamento:** Correios e transportadoras;
- **Consulta de dados:** ViaCEP, CNPJ, cotação de moedas;
- **Notas fiscais e bancos:** emissão de NF-e e conciliação bancária;
- **ERP e CRM:** sincronizar clientes, pedidos e estoque;
- **Comunicação:** envio de e-mail, SMS e WhatsApp;
- **Mapas:** rotas e localização de veículos;
- **IoT:** sensores enviando leituras ao servidor;
- **Login social:** "Entrar com Google".

---

## Aplicação ao Ferrorama

No projeto, uma API permitiria, por exemplo:

| Método | Endpoint | Descrição |
|---|---|---|
| GET | `/api/trens` | Listar trens |
| GET | `/api/sensores` | Listar sensores |
| POST | `/api/sensores` | Cadastrar sensor (administrador) |
| GET | `/api/rotas` | Listar rotas |

---

## Conclusão

APIs permitem que sistemas troquem dados de forma padronizada. O estilo REST, com métodos HTTP, endpoints e JSON, é o mais usado. Em PHP, é possível tanto consumir quanto criar APIs, sempre com atenção a autenticação, autorização e segurança.

---

## Referências

- MDN Web Docs, HTTP: <https://developer.mozilla.org/pt-BR/docs/Web/HTTP>
- PHP Manual, cURL e JSON: <https://www.php.net/manual/pt_BR/>
- OWASP, API Security Top 10: <https://owasp.org/API-Security/>
- ViaCEP: <https://viacep.com.br/>