# Pesquisa: o que é `$stmt` no PHP?

## 1. O que é `$stmt`?

`$stmt` é uma variável usada em PHP para guardar uma consulta preparada que será executada no banco de dados.

O nome `stmt` vem da palavra inglesa *statement*, que significa instrução ou comando.

**Importante:** `$stmt` não é uma função nem uma palavra especial do PHP. É apenas o nome que o programador escolheu para a variável.

## 2. Para que serve?

Ele é usado para executar comandos SQL de forma organizada e segura, principalmente quando os dados vêm de formulários.

Por exemplo, quando alguém preenche um cadastro com nome e e-mail, o PHP precisa enviar essas informações ao banco de dados. O `$stmt` ajuda nessa tarefa sem colocar diretamente os dados enviados pelo usuário dentro do comando SQL.

## 3. Por que isso é importante?

O principal motivo é a segurança.

Quando usamos consultas preparadas corretamente, os dados recebidos pelo sistema são enviados separados do comando SQL. Isso ajuda a impedir um problema chamado **SQL Injection**, ou injeção de SQL.

A SQL Injection acontece quando alguém tenta inserir conteúdo malicioso em um campo para alterar o comando enviado ao banco de dados.

Além de aumentar a segurança, o uso de `$stmt` deixa o código mais organizado e facilita a manutenção das consultas.

## 4. Onde podemos usar?

Podemos utilizar `$stmt` nas quatro operações do CRUD:

| Operação | Significado | Exemplo |
|---|---|---|
| Create | Cadastrar | Inserir um usuário |
| Read | Consultar | Buscar um usuário |
| Update | Atualizar | Alterar um e-mail |
| Delete | Excluir | Apagar um cadastro |

## 5. O que significam `i`, `d` e `s`?

No método `bind_param()`, informamos o tipo de cada valor que será enviado ao banco:

- `s`: texto (*string*), como nome, e-mail ou status.
- `i`: número inteiro (*integer*), como um ID.
- `d`: número decimal (*double*), como um preço ou uma medida.

Exemplo:

```php
$stmt->bind_param("si", $nome, $idade);
```

Nesse caso, o primeiro valor é texto e o segundo é um número inteiro.

## 6. Exemplo aplicado ao sistema de gerenciamento de sensores

No projeto Ferrorama, a página `gerenciar_sensores_usuario.php` utiliza `$stmt` para pesquisar os sensores e aplicar filtros sem inserir diretamente os valores recebidos pela URL no comando SQL.

```php
if ($filtroStatus != "") {
    $sql .= " AND TRENS.status = ?";
    $tipos .= "s";
    $valores[] = $filtroStatus;
}

$sql .= " ORDER BY SENSORES.id DESC";

$stmt = $conexao->prepare($sql);

if ($tipos != "") {
    $stmt->bind_param($tipos, ...$valores);
}

$stmt->execute();
$resultado = $stmt->get_result();

$listaTipos = $conexao->query(
    "SELECT DISTINCT tipo_de_dado FROM SENSORES ORDER BY tipo_de_dado"
);

$listaTrens = $conexao->query(
    "SELECT id FROM TRENS ORDER BY id"
);

$listaStatus = $conexao->query(
    "SELECT DISTINCT status FROM TRENS ORDER BY status"
);
```

### Entendendo o exemplo

- O sinal `?` representa o local da consulta em que o filtro será colocado.
- `$tipos` guarda a sequência dos tipos dos valores. Por exemplo, `s` representa texto e `i` representa número inteiro.
- `$valores` guarda os valores dos filtros informados pelo usuário.
- `$conexao->prepare()` prepara o comando SQL antes de executá-lo.
- `$stmt->bind_param()` associa os valores aos sinais `?`.
- O operador `...` envia cada item do array `$valores` como um parâmetro separado para `bind_param()`.
- `$stmt->execute()` executa a consulta no banco de dados.
- `$stmt->get_result()` obtém o resultado da consulta.
- `$conexao->query()` é utilizado nas consultas que não precisam receber valores enviados pelo usuário.

Por exemplo, se o filtro de status for igual a `Ativo`, o comando preparado compara `TRENS.status` com o valor `Ativo`. Esse valor não é unido diretamente ao texto da consulta SQL.

### Como os filtros podem ser montados

O projeto começa com uma consulta que busca os sensores e os dados dos trens relacionados:

```php
$sql = "SELECT
            SENSORES.id,
            SENSORES.localizacao,
            SENSORES.tipo_de_dado,
            SENSORES.trens_id,
            TRENS.status
        FROM SENSORES
        INNER JOIN TRENS
            ON SENSORES.trens_id = TRENS.id
        WHERE 1 = 1";

$tipos = "";
$valores = [];
```

O trecho `WHERE 1 = 1` permite adicionar diferentes filtros sem precisar verificar qual será o primeiro filtro da consulta.

Se o usuário pesquisar por um texto:

```php
if ($pesquisa != "") {
    $sql .= " AND (SENSORES.tipo_de_dado LIKE ? OR SENSORES.localizacao LIKE ?)";
    $busca = "%" . $pesquisa . "%";
    $tipos .= "ss";
    $valores[] = $busca;
    $valores[] = $busca;
}
```

Nesse caso, os dois sinais `?` recebem o mesmo texto contendo `%`, permitindo uma pesquisa parcial. A variável `$tipos` recebe `ss`, indicando que os dois valores são textos.

Se o usuário selecionar um trem:

```php
if ($filtroTrem != "") {
    $sql .= " AND SENSORES.trens_id = ?";
    $tipos .= "i";
    $valores[] = $filtroTrem;
}
```

O tipo `i` indica que o valor do ID do trem é um número inteiro.

No final, todos os filtros são utilizados juntos:

```php
$sql .= " ORDER BY SENSORES.id DESC";

$stmt = $conexao->prepare($sql);

if ($tipos != "") {
    $stmt->bind_param($tipos, ...$valores);
}

$stmt->execute();
$resultado = $stmt->get_result();
```

Essa forma permite combinar vários filtros dinamicamente. Além disso, os valores são mantidos separados do comando SQL, aumentando a segurança contra SQL Injection.

## 7. Como explicar para o professor?

Você pode explicar assim:

> O `$stmt` é uma variável usada no PHP para guardar uma consulta preparada para o banco de dados. Ela é utilizada para executar comandos SQL de maneira mais segura. Primeiro usamos o `prepare()` para preparar o comando, depois o `bind_param()` para passar os valores e, por último, o `execute()` para executar. Esse recurso ajuda a evitar SQL Injection e pode ser usado nas operações de cadastrar, consultar, atualizar e excluir informações.

## 8. Conclusão

O `$stmt` é importante no desenvolvimento com PHP e MySQL porque permite trabalhar com consultas preparadas. Com ele, podemos enviar dados ao banco de forma organizada e protegida contra SQL Injection. Esse recurso é utilizado em sistemas que possuem cadastro, consulta, edição e exclusão de informações.
