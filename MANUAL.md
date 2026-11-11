# Manual rápido de utilização — Ferrorama

Este guia apresenta as operações mais comuns do sistema. Os nomes de botões podem mudar ligeiramente conforme a versão instalada. O acesso às áreas internas exige uma conta criada previamente.

## 1. Acessar o sistema

1. Abra um navegador atualizado (por exemplo, Chrome, Firefox ou Edge).
2. Acesse o endereço web fornecido pela equipe responsável pelo sistema. Em uma instalação local com XAMPP, o endereço costuma ser `http://localhost/SA_ferrorama/` — use o nome da pasta configurado no seu computador.
3. Na página inicial, escolha a opção para entrar. Se o formulário não aparecer, use o botão ou link **Entrar/Login**.
4. Informe seu e-mail e sua senha e selecione **Entrar**.

Se a aplicação não abrir, confirme com o responsável se o servidor PHP e o banco de dados estão funcionando e se o endereço está correto. Para obter uma conta ou redefinir uma senha, contate um administrador; não compartilhe sua senha.

## 2. Login e logout

- **Login:** use o e-mail e a senha associados à sua conta. Se os dados estiverem incorretos, confira a digitação e tente novamente. O perfil da conta determina quais opções aparecem.
- **Logout:** selecione o ícone de saída no canto superior direito. Ao terminar, especialmente em computadores compartilhados, sempre encerre a sessão e feche a janela do navegador.

## 3. Navegação

Depois do login, use o menu lateral para abrir as áreas disponíveis:

- **Dashboard:** resumo da operação.
- **Sensores:** consulta e, para administradores, gerenciamento de sensores.
- **Rotas:** consulta e, para administradores, gerenciamento de rotas.
- **Cadastrar ADMs e Usuários** e **Usuários Cadastrados:** opções exclusivas do administrador.

O usuário comum tem acesso ao dashboard, à consulta de sensores e à consulta de rotas. Opções que não pertencem ao seu perfil não são exibidas.

## 4. Usuários

O cadastro e a administração de contas são exclusivos do administrador.

### Cadastrar um usuário

1. No menu lateral, abra **Cadastrar ADMs e Usuários**.
2. Preencha os campos solicitados, como nome, CPF, telefone, e-mail e senha.
3. Escolha o tipo de conta adequado (**usuário** ou **administrador**). Conceda perfil de administrador somente a quem realmente precisa dessas permissões.
4. Revise os dados e selecione **Cadastrar/Salvar**.
5. Leia a mensagem exibida. Em caso de erro, corrija os campos indicados e tente novamente.

### Consultar usuários

1. Abra **Usuários Cadastrados**.
2. Localize a pessoa na lista e confira os dados apresentados.
3. Use as ações disponíveis na própria linha para as operações permitidas. Confirme com atenção antes de excluir uma conta: a exclusão pode impedir o acesso daquela pessoa.

Para entrar pela primeira vez, o novo usuário deve receber suas credenciais de forma segura pelo administrador.

## 5. Trens

A área de trens fica no **Dashboard do administrador**, na seção ou tabela **Trens Cadastrados**. O usuário comum pode ver o resumo, mas não tem acesso às ações administrativas de cadastro e manutenção.

### Cadastrar um trem

1. Entre como administrador e abra **Dashboard**.
2. Na seção **Trens Cadastrados**, selecione **Cadastrar Trem/Adicionar**.
3. Preencha as informações solicitadas, como localização, tipo de dado, horário e status.
4. Revise e salve. Verifique a mensagem de confirmação e a presença do trem na lista.

### Consultar ou alterar um trem

Consulte a tabela **Trens Cadastrados** no dashboard. Para editar, use a ação de edição da linha, atualize os campos necessários e salve. A exclusão pode exigir confirmação; verifique antes se há sensores associados, pois isso pode impedir a operação.

## 6. Rotas

### Cadastrar uma rota (administrador)

1. No menu, abra **Rotas**.
2. Selecione **Cadastrar Rota/Adicionar**.
3. Informe nome, descrição, distância em quilômetros, duração em minutos, status e o trem vinculado.
4. Salve e confira a mensagem de sucesso. O trem precisa estar cadastrado antes de ser associado à rota.

### Consultar rotas

Na tabela de **Rotas**, consulte nome, distância, trem associado, duração, status e data de criação. Selecione **Ver** para abrir os detalhes. O usuário comum também pode consultar rotas, mas não pode cadastrá-las, editá-las ou excluí-las.

O administrador pode usar as ações **Editar** e **Excluir** na lista. Antes de excluir, confira a rota selecionada e confirme a operação.

## 7. Sensores

Cada sensor deve estar vinculado a um trem já cadastrado.

### Cadastrar um sensor (administrador)

1. Abra **Sensores** no menu.
2. Selecione **Cadastrar Sensor/Adicionar**.
3. Informe a localização, o tipo de dado, o status e o trem associado.
4. Salve e confirme que o sensor aparece na lista.

### Consultar sensores

Na lista de **Sensores**, confira identificação, localização, tipo de dado, trem relacionado e status. Quando disponíveis, use os filtros de tipo de dado, trem ou pesquisa para encontrar um registro. O usuário comum pode consultar os sensores, mas não pode cadastrá-los, editá-los ou excluí-los.

O administrador pode editar ou excluir um sensor pelas ações da lista. Leia a confirmação antes de excluir. Se a operação falhar, confira se todos os campos estão válidos e se o trem associado continua cadastrado.

## 8. O que é exclusivo do administrador

O administrador tem acesso às operações de gestão que não aparecem para o usuário comum, incluindo:

- cadastrar contas de usuário e de administrador e consultar a lista de usuários;
- cadastrar, editar e excluir trens;
- cadastrar, editar e excluir sensores;
- cadastrar, editar e excluir rotas.

O usuário comum pode consultar o dashboard, sensores e rotas, sem alterar esses cadastros. Se precisar de uma ação indisponível, solicite ajuda a um administrador em vez de usar outra conta.

## 9. Como interpretar o dashboard

Os cartões do dashboard mostram totais resumidos:

- **Sensores Cadastrados:** quantidade de sensores registrados.
- **Trens Cadastrados:** quantidade de trens registrados.
- **Alertas:** quantidade de registros que o sistema classifica como alerta.
- **Sensores Funcionando:** quantidade de sensores contabilizados como ativos/funcionando.

As tabelas e gráficos complementam os cartões com dados dos trens e sensores. Verifique o status indicado junto a cada registro: **Ativo** representa funcionamento normal; **Alerta** pede atenção; **Manutenção** indica que o equipamento está em manutenção; **Inativo** indica que não está ativo. A disponibilidade e o significado de gráficos adicionais dependem dos dados exibidos na instalação.

O dashboard resume os dados existentes no sistema; não interprete os números como garantia de atualização em tempo real. Se precisar conferir uma alteração recém-feita, atualize a página e confira a lista correspondente. O cartão de clima, quando exibido, é informação auxiliar e não substitui a consulta operacional.

## 10. Dicas para evitar problemas

- Preencha todos os campos obrigatórios e confira e-mail, números e status antes de salvar.
- Cadastre o trem antes de criar um sensor ou uma rota que dependa dele.
- Leia mensagens de sucesso e erro após cada operação.
- Antes de excluir, confira o registro e leia a confirmação; exclusões podem ser difíceis de reverter.
- Se uma opção não aparecer, confirme se entrou com o perfil correto. Não tente contornar as permissões.
- Se os dados não aparecerem, atualize a página uma vez. Se o problema persistir, anote a tela e a mensagem exibida e procure o administrador.

> **Imagens:** recomenda-se que a equipe acrescente capturas de tela da instalação atual ao lado das seções de login, dashboard e formulários. As telas podem variar entre versões; por isso, este guia descreve os passos sem incluir imagens de uma versão diferente.
