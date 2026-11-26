Comando	        Para que serve
SELECT	        Consultar informações
INSERT INTO	    Cadastrar informações
UPDATE	        Atualizar informações
DELETE	        Excluir informações
WHERE	        Filtrar registros
ORDER BY	    Ordenar resultados
COUNT	        Contar registros
DISTINCT	    Evitar valores repetidos
INNER JOIN	    Juntar informações relacionadas
LEFT JOIN	    Juntar tabelas mantendo todos os registros da primeira
GROUP BY	    Agrupar informações
LIMIT	        Limitar resultados
AS	            Criar apelidos
CREATE DATABASE	Criar banco de dados
CREATE TABLE	Criar tabelas
PRIMARY KEY	    Identificar registros
FOREIGN KEY	    Relacionar tabelas

Comandos SQL de cadastro, consulta, edição e exclusão
# Cadastro de informações (INSERT INTO)
- INSERT INTO USUARIO — Insere novos usuários no banco de dados, armazenando informações como CPF, telefone, nome, e-mail, senha e tipo de usuário. Utilizado em cadastro_admin.php e database/db.sql.
- INSERT INTO TRENS — Cadastra novos trens, armazenando localização, tipo de dado, horário, status e usuário responsável. Utilizado em salvar_trem.php, tela_inicial.php e database/db.sql.
- INSERT INTO SENSORES — Cadastra novos sensores, armazenando localização, tipo de dado, trem vinculado e status. Utilizado em salvar_sensor.php e database/db.sql.
- INSERT INTO ROTAS — Cadastra novas rotas, armazenando nome, descrição, distância, trem vinculado, duração e status. Utilizado em salvar_rota.php e database/db.sql.


# Consulta de informações (SELECT)
- SELECT — Consulta informações armazenadas nas tabelas do banco de dados. É utilizado em praticamente todas as páginas que precisam buscar ou apresentar informações.
- SELECT id, nome, email, senha, tipo FROM USUARIO — Busca os dados de um usuário pelo e-mail informado durante o login. Utilizado em index.php.
- SELECT id FROM USUARIO — Consulta se já existe um usuário com determinado e-mail ou CPF. Utilizado em cadastro_admin.php.
- SELECT id FROM TRENS — Verifica se determinado trem existe antes de realizar operações de cadastro ou edição. Utilizado em salvar_sensor.php, salvar_rota.php, atualizar_trem.php e atualizar_rota.php.
- SELECT id FROM SENSORES — Verifica se determinado sensor existe antes de realizar sua edição. Utilizado em salvar_edicao_sensores.php.
- SELECT id, nome, CPF, telefone, email, tipo... FROM USUARIO — Busca os usuários cadastrados para apresentar suas informações na página de gerenciamento. Utilizado em usuarios_cadastrados.php.
- SELECT * FROM TRENS — Consulta todos os dados dos trens cadastrados para exibição no sistema. Utilizado em tela_inicial_usuario.php.
- SELECT id, localizacao, tipo_de_dado FROM TRENS — Busca os trens cadastrados para preencher os campos de seleção durante o cadastro e a edição de rotas. Utilizado em rotas.php.
- SELECT id, localizacao FROM TRENS — Busca o ID e a localização dos trens para permitir a vinculação de sensores. Utilizado em gerenciar_sensores.php.
- SELECT DISTINCT tipo_de_dado FROM SENSORES — Consulta os tipos de sensores existentes, sem apresentar valores repetidos. Utilizado em gerenciar_sensores.php e gerenciar_sensores_usuario.php.
- SELECT DISTINCT status FROM SENSORES — Consulta os diferentes status dos sensores para preencher os filtros. Utilizado em gerenciar_sensores.php.
- SELECT DISTINCT status FROM TRENS — Consulta os diferentes status dos trens, evitando repetições. Utilizado em gerenciar_sensores_usuario.php.
- SELECT id, nome, tipo FROM USUARIO — Busca os usuários cadastrados para preencher o campo de seleção de responsáveis pelos trens. Utilizado em tela_inicial.php.


# Edição de informações (UPDATE)
- UPDATE USUARIO — Atualiza os dados de um usuário cadastrado, como nome, CPF, telefone, e-mail, tipo e endereço. Utilizado em atualizar_usuario.php.
- UPDATE TRENS — Atualiza as informações de um trem, como localização, tipo de dado, horário e usuário responsável. Utilizado em atualizar_trem.php.
- UPDATE SENSORES — Atualiza localização, tipo de dado, trem vinculado e status de um sensor. Utilizado em salvar_edicao_sensores.php.
- UPDATE ROTAS — Atualiza nome, descrição, distância, trem vinculado, duração e status de uma rota. Utilizado em atualizar_rota.php.


# Exclusão de informações (DELETE)
- DELETE FROM USUARIO — Exclui um usuário cadastrado, identificando-o pelo seu ID. Utilizado em excluir_usuario.php.
- DELETE FROM TRENS — Exclui um trem cadastrado, depois de verificar se existem sensores ou rotas vinculados a ele. Utilizado em excluir_trem.php.
- DELETE FROM SENSORES — Exclui um sensor específico, identificado pelo seu ID. Utilizado em excluir_informacoes_sensor.php.
- DELETE FROM ROTAS — Exclui uma rota cadastrada, identificada pelo seu ID. Utilizado em excluir_rota.php.


Condições e filtros utilizados nas Queries
# Filtragem de informações (WHERE)
- WHERE — Define uma condição para selecionar, atualizar ou excluir somente os registros desejados.
- WHERE email = ? — Localiza um usuário pelo e-mail informado. Utilizado no login em index.php.
- WHERE email = ? OR CPF = ? — Verifica se já existe um usuário com o mesmo e-mail ou CPF. Utilizado em cadastro_admin.php.
- WHERE id = ? — Localiza um registro pelo seu ID. Utilizado nas operações de consulta, edição e exclusão de usuários, trens, sensores e rotas.
- WHERE status = 'Manutenção' — Seleciona somente os trens que estão com status de manutenção. Utilizado nos dashboards.
- WHERE trens_id = ? — Localiza sensores vinculados a determinado trem. Utilizado nas verificações de exclusão.
- WHERE trem_id = ? — Localiza rotas vinculadas a determinado trem. Utilizado em excluir_trem.php.
- WHERE 1 = 1 — Condição que sempre é verdadeira, utilizada como ponto inicial para adicionar filtros dinamicamente. Aparece em gerenciar_sensores.php e gerenciar_sensores_usuario.php.


# Operadores de condições (AND e OR)
- AND — Utilizado para exigir que duas ou mais condições sejam verdadeiras ao mesmo tempo. Nas páginas de gerenciamento, permite combinar filtros de sensores.
- OR — Utilizado quando pelo menos uma das condições precisa ser verdadeira. Aparece na verificação de e-mail ou CPF em cadastro_admin.php.


Organização e agrupamento de informações
# Ordenação de resultados (ORDER BY)
- ORDER BY — Organiza os resultados de uma consulta de acordo com uma coluna.
- ORDER BY id DESC — Apresenta os registros começando pelo ID mais alto. Utilizado nas listagens de usuários, sensores e trens.
- ORDER BY id ASC — Organiza os registros pelo ID em ordem crescente. Utilizado na consulta de trens para seleção de sensores.
- ORDER BY nome ASC — Organiza os usuários em ordem alfabética pelo nome. Utilizado em tela_inicial.php.
- ORDER BY tipo_de_dado — Organiza os tipos de sensores em ordem crescente. Utilizado nos filtros de sensores.
- ORDER BY status — Organiza os status em ordem crescente. Utilizado nos filtros.
- ORDER BY localizacao ASC — Organiza os trens pela localização. Utilizado em rotas.php.
- ORDER BY data_criacao DESC — Apresenta as rotas começando pelas mais recentes. Utilizado em rotas.php e rotas_usuario.php.


# Contagem de registros (COUNT)
- COUNT(*) — Conta a quantidade total de registros de uma tabela ou de registros que atendem a uma condição.
- COUNT(*) AS total FROM SENSORES — Conta a quantidade total de sensores cadastrados. Utilizado nos dashboards.
- COUNT(*) AS total FROM TRENS — Conta a quantidade total de trens cadastrados. Utilizado nos dashboards.
- COUNT(*) AS total FROM TRENS WHERE status = 'Manutenção' — Conta quantos trens estão em manutenção. Utilizado nos dashboards.
- COUNT(*) AS total FROM SENSORES WHERE trens_id = ? — Conta quantos sensores estão vinculados a determinado trem. Utilizado nas verificações de exclusão.
- COUNT(*) AS total FROM ROTAS WHERE trem_id = ? — Conta quantas rotas estão vinculadas a determinado trem. Utilizado em excluir_trem.php.


# Agrupamento de informações (GROUP BY)
- GROUP BY — Agrupa registros que possuem valores iguais em determinada coluna.
- GROUP BY status — Agrupa os trens de acordo com seus status, permitindo contar quantos existem em cada categoria. Utilizado em tela_inicial_usuario.php.
- GROUP BY TRENS.status — Agrupa os sensores de acordo com o status dos trens aos quais estão vinculados. Utilizado em gerenciar_sensores_usuario.php.


# Eliminação de valores repetidos (DISTINCT)
- DISTINCT — Retorna somente valores diferentes, evitando que informações iguais apareçam repetidas nos resultados.
- DISTINCT tipo_de_dado — Apresenta cada tipo de sensor apenas uma vez.
- DISTINCT status — Apresenta cada status apenas uma vez nos filtros de sensores e trens.


# Limitação de resultados (LIMIT)
- LIMIT — Define a quantidade máxima de registros que uma consulta pode retornar.
- LIMIT 1 — Limita o resultado a apenas um registro. Utilizado na verificação de login e na verificação de cadastro existente, em index.php e cadastro_admin.php.


Relacionamento entre tabelas
# Junção de tabelas (INNER JOIN)
- INNER JOIN — Junta informações de duas tabelas quando existe uma correspondência entre elas.
- INNER JOIN TRENS ON ROTAS.trem_id = TRENS.id — Relaciona as rotas aos trens cadastrados, permitindo apresentar informações das duas tabelas juntas. Utilizado em rotas.php, rotas_usuario.php e detalhes_rota.php.
- INNER JOIN TRENS ON SENSORES.trens_id = TRENS.id — Relaciona os sensores aos trens correspondentes, permitindo consultar informações relacionadas aos dois. Utilizado em gerenciar_sensores_usuario.php.


# Junção mantendo todos os registros da primeira tabela (LEFT JOIN)
- LEFT JOIN — Junta informações de duas tabelas, mantendo todos os registros da primeira tabela, mesmo quando não existe correspondência na segunda.
- LEFT JOIN USUARIO ON TRENS.usuario_id = USUARIO.id — Relaciona os trens aos usuários responsáveis, mantendo todos os trens na listagem, mesmo quando não possuem um responsável associado. Utilizado em tela_inicial.php.


# Apelidos para tabelas e colunas (AS)
- AS — Cria um nome alternativo para uma coluna ou tabela durante uma consulta.
- AS total — Dá o nome total ao resultado de uma contagem, facilitando sua identificação no PHP.
- AS responsavel — Dá o nome responsavel à coluna que apresenta o nome do usuário responsável pelo trem.
- AS trem_localizacao — Identifica a localização do trem em consultas que relacionam rotas e trens.
- AS trem_tipo — Identifica o tipo de dado do trem em consultas que relacionam rotas e trens.
- r e t — São apelidos utilizados para representar as tabelas ROTAS e TRENS nas consultas, facilitando a escrita dos JOINs.


Criação e estrutura do banco de dados
# Criação do banco e das tabelas
- CREATE DATABASE — Cria um novo banco de dados. Utilizado em database/db.sql para criar o banco sa_ferrorama.
- USE — Seleciona o banco de dados que será utilizado na execução dos comandos SQL.
- CREATE TABLE — Cria as tabelas que armazenam as informações do sistema, como USUARIO, TRENS, SENSORES e ROTAS.
- AUTO_INCREMENT — Faz com que o ID seja gerado automaticamente a cada novo registro.
- NOT NULL — Define que uma coluna não pode ficar sem valor.
- UNIQUE — Impede que determinados valores sejam repetidos em uma coluna.
- VARCHAR — Define uma coluna que armazena textos com tamanho variável.
- INT — Define uma coluna que armazena números inteiros.
- DECIMAL — Define uma coluna que armazena números decimais.


# Chaves e relacionamentos
- PRIMARY KEY — Define a chave primária, utilizada para identificar cada registro de maneira única.
- FOREIGN KEY — Define uma chave estrangeira, utilizada para relacionar uma tabela com outra.
- REFERENCES — Indica qual tabela e coluna serão referenciadas pela chave estrangeira.
- ON DELETE CASCADE — Exclui automaticamente os registros relacionados quando o registro principal é excluído. Utilizado no relacionamento entre TRENS e SENSORES.
- ON DELETE SET NULL — Define o valor NULL na chave estrangeira quando o registro relacionado é excluído. Utilizado no relacionamento entre USUARIO e TRENS.
- ON DELETE RESTRICT — Impede a exclusão de um registro principal enquanto existirem registros relacionados. Utilizado no relacionamento entre TRENS e ROTAS.
- ON UPDATE CASCADE — Atualiza automaticamente os valores relacionados quando a chave referenciada é alterada.


Parâmetros utilizados nas consultas preparadas
# Prepared Statements (consultas preparadas)
- ? — Representa um espaço reservado para um valor que será enviado pelo PHP à consulta SQL. Utilizado nas consultas preparadas com MySQLi.
- :id — Representa um parâmetro nomeado, utilizado no lugar do ponto de interrogação nas consultas preparadas com PDO. Aparece em excluir_informacoes_trem.php.
- prepare() — Prepara uma consulta SQL antes de sua execução.
- bind_param() — Associa valores aos parâmetros representados por ? nas consultas MySQLi.
- execute() — Executa uma consulta preparada depois que seus parâmetros são definidos.