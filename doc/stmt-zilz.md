# Pesquisa: o que é `$stmt` no PHP?

## 1. O que é `$stmt`?

`$stmt` é uma variável usada em PHP para guardar uma consulta preparada que será executada no banco de dados.

O nome `stmt` vem da palavra inglesa *statement*, que significa instrução ou comando.

**Importante:** `$stmt` não é uma função nem uma palavra especial do PHP. É apenas o nome que o programador escolheu para a variável.

## 2. Para que serve?

Ele é usado para executar comandos SQL de uma forma mais organizada e segura, principalmente quando os dados vêm de formulários.

Por exemplo: quando alguém preenche um cadastro com nome e e-mail, o PHP precisa enviar essas informações para o MySQL. O `$stmt` ajuda a fazer isso sem colocar diretamente os dados do usuário dentro do comando SQL.

## 3. Exemplo simples

Imagine que temos uma tabela chamada `usuarios`, com os campos `nome` e `email`.






```php
$nome = "João";
$email = "joao@email.com";

$stmt = $conexao->prepare(
    "INSERT INTO usuarios (nome, email) VALUES (?, ?)"
);

$stmt->bind_param("ss", $nome, $email);

$stmt->execute();
```








### Entendendo cada linha

- `$stmt = $conexao->prepare(...)`: prepara o comando SQL que será enviado ao banco.
- `?`: indica onde os valores serão colocados.
- `bind_param("ss", $nome, $email)`: informa quais valores serão usados. O `s` significa texto (*string*).
- `execute()`: executa o comando e cadastra o usuário.

**Resumindo:** primeiro preparamos o comando, depois colocamos os valores e, por último, executamos.

## 4. Por que isso é importante?

O principal motivo é a segurança.

Quando usamos consultas preparadas corretamente, os dados recebidos do usuário são enviados separados do comando SQL. Isso ajuda a evitar um problema chamado **SQL Injection**.

SQL Injection acontece quando alguém tenta inserir um conteúdo malicioso em um campo do sistema para interferir no comando do banco de dados.

Além da segurança, o uso de `$stmt` deixa o código mais organizado.

## 5. Onde podemos usar?

Podemos utilizar `$stmt` nas quatro operações do CRUD:

| Operação | Significado | Exemplo |
|---|---|---|
| Create | Cadastrar | Inserir um usuário |
| Read | Consultar | Buscar um usuário |
| Update | Atualizar | Alterar um e-mail |
| Delete | Excluir | Apagar um cadastro |

## 6. O que significam `i`, `d` e `s`?

No `bind_param()`, informamos o tipo de cada valor:

- `s`: texto, como nome ou e-mail.
- `i`: número inteiro, como um ID.
- `d`: número decimal, como um preço.

Exemplo:

```php
$stmt->bind_param("si", $nome, $idade);
```

Nesse caso, o primeiro valor é texto e o segundo é inteiro.

## 7. Como explicar para o professor?

Você pode explicar assim:

> O `$stmt` é uma variável usada no PHP para guardar uma consulta preparada para o banco de dados. Ela é utilizada para executar comandos SQL de maneira mais segura. Primeiro usamos o `prepare()` para preparar o comando, depois o `bind_param()` para passar os valores e, por último, o `execute()` para executar. Isso ajuda a evitar SQL Injection e pode ser usado para cadastrar, consultar, atualizar e excluir informações.

## 8. Perguntas que podem aparecer

### 1. `$stmt` é uma função?

Não. É uma variável que guarda uma instrução preparada.

### 2. Para que serve `prepare()`?

Para preparar o comando SQL antes de executá-lo.

### 3. Para que serve `bind_param()`?

Para passar os valores que serão utilizados no comando.

### 4. Para que serve `execute()`?

Para executar o comando no banco de dados.

### 5. O que é SQL Injection?

É uma tentativa de interferir em um comando SQL usando dados enviados ao sistema. Consultas preparadas ajudam a evitar esse problema.

## 9. Conclusão

O `$stmt` é importante no desenvolvimento com PHP e MySQL porque permite trabalhar com consultas preparadas. Com ele, podemos enviar dados ao banco de forma organizada e ajudar a proteger o sistema contra SQL Injection. É um recurso muito utilizado em sistemas que possuem cadastro, consulta, edição e exclusão de informações.
