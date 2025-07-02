# Planejamento de Sprints e Evolução do Projeto

## Sprint 1: Sistema Central de Tarefas

O foco desta sprint foi estabelecer as funcionalidades essenciais de gerenciamento de tarefas, que são o coração da aplicação. O conceito inicial de "senhas" foi evoluído para um sistema de "tarefas" mais robusto. As funcionalidades de edição e exclusão (planejadas para a Sprint 3 no documento original) foram incorporadas aqui para formar um CRUD completo.

**Valor da Sprint:** Permitir que usuários autenticados criem, visualizem, editem e removam suas tarefas diárias, além de acompanhar seu progresso.

### Funcionalidades Implementadas
* **CRUD de Tarefas:**
    * **Criação:** Usuários podem salvar novas tarefas com título, descrição e data de vencimento.
    * **Leitura:** As tarefas são carregadas dinamicamente no dashboard do usuário.
    * **Atualização:** Tarefas existentes podem ser editadas. [cite_start]O teste unitário em `EdicaoConclusaoTarefaTest.php` garante que um usuário não pode editar a tarefa de outro.
    * **Remoção:** Tarefas podem ser excluídas de forma segura.

* **Gerenciamento de Status:**
    * Usuários podem marcar tarefas como "concluídas" ou reabri-las como "pendentes".
    * O dashboard permite filtrar a visualização de tarefas por status (todas, pendentes, concluídas) através do controller `filtros.php`.

### Análise das User Stories

As user stories do planejamento original foram adaptadas para refletir a mudança de foco de "senhas" para "tarefas".

* **User Story: Cadastrar Tarefas** 
    > **Planejado:** "Eu, como usuário autenticado, desejo cadastrar novas senhas com detalhes (plataforma, usuário, senha, apelido)." 
    > **Implementado:** A funcionalidade foi implementada, mas para "tarefas". O controller `salvar_tarefa.php` recebe os dados do formulário do dashboard e utiliza o `TarefaModel` para persistir a tarefa no banco de dados.

* **User Story: Visualizar Tarefas Cadastradas** 
    > **Planejado:** "Eu, como usuário autenticado, desejo visualizar as senhas que cadastrei com todos os detalhes." 
    > **Implementado:** Totalmente. O `dashboard.html`  utiliza JavaScript para fazer requisições via `fetch` ao `get_atividades.php`, que retorna as tarefas do usuário logado em formato JSON.

* **User Story: Autenticar para Exibir Tarefas** 
    > **Planejado:** "Para isso devo realizar uma nova autenticação." 
    > **Implementado:** A segurança foi implementada através do sistema de sessão. Todos os controllers de tarefas (`get_atividades.php`, `editar_tarefa.php`, etc.) verificam se `$_SESSION['usuario']` existe. Não foi implementada uma *segunda* autenticação, mas o objetivo principal de garantir o acesso apenas ao usuário logado foi cumprido.

* **User Story: Editar Tarefas** 
    > **Planejado:** "Eu, como usuário, desejo editar os detalhes de uma tarefa já cadastrada." 
    > **Implementado:** Sim. O `dashboard.html`  possui um modal de edição que envia os dados para `editar_tarefa.php`, atualizando a tarefa no banco de dados.

* **User Story: Excluir Tarefas** 
    > **Planejado:** "Eu, como usuário, desejo excluir tarefas que não uso mais." 
    > **Implementado:** Sim, através do controller `excluir_tarefa.php`.

* **User Story: Confirmar Exclusão de Tarefas** 
    > **Planejado:** "Eu, como usuário, desejo ser solicitado a confirmar antes de excluir uma tarefa." 
    > **Implementado:** Sim. A função `deleteActivity` no JavaScript do `dashboard.html` utiliza um `confirm()` do navegador antes de prosseguir com a exclusão.

---

## Sprint 2: Autenticação e Gestão de Perfil

Esta sprint focou em criar um ambiente seguro e personalizado para cada usuário.

**Valor da Sprint:** Facilitar a entrada de novos usuários na plataforma e garantir a segurança de seus dados e o gerenciamento de suas contas.

### Funcionalidades Implementadas
* **Cadastro e Login:**
    * Formulário de cadastro para novos usuários.
    * Sistema de login seguro que autentica o usuário e inicia uma sessão.
    * As senhas são armazenadas de forma segura no banco usando `password_hash()`.

* **Gestão de Conta:**
    * Página de perfil onde o usuário pode alterar sua senha (`perfil.php`).
    * Funcionalidade para apagar a própria conta, o que remove todos os dados do usuário do sistema.

* **Personalização e Notificações:**
    * Envio de um e-mail de boas-vindas no momento do cadastro.
    * Opção para o usuário selecionar uma foto de perfil a partir de avatares pré-definidos.

### Análise das User Stories

* **User Story: Acessar a Tela Inicial** 
    > **Planejado:** "Eu, como usuário, desejo acessar a tela inicial do site. Para isso, não preciso estar logado..." 
    > **Implementado:** Sim. O arquivo `public/index.php` serve como a página de boas-vindas pública, com opções para login ou cadastro.

* **User Story: Cadastrar um Novo Usuário** 
    > **Planejado:** "Eu, como novo usuário, desejo me cadastrar na plataforma." 
    > **Implementado:** Totalmente. O fluxo de cadastro é gerenciado por `cadastrar_usuario.php` e `user_model.php`, que também verifica se o e-mail já existe.

* **User Story: Efetuar Login** 
    > **Planejado:** "Eu, como usuário registrado, desejo fazer login de forma segura." 
    > **Implementado:** Sim. O `login.php` lida com a submissão do formulário, e o `UserModel`  realiza a autenticação comparando a senha fornecida com o hash armazenado.

---

## Sprint 3: API de Microsserviço e E-mails

Esta sprint não estava detalhada no planejamento original, mas emergiu da necessidade de criar serviços desacoplados e mais robustos, como o envio de e-mails, que foi movido para uma API própria.

**Valor da Sprint:** Desenvolver uma API RESTful para expandir as capacidades do sistema, começando com um serviço de e-mail confiável.

### Funcionalidades Implementadas
* **Estrutura da API:**
    * API autônoma localizada em `DailyPlannerApi/` com gerenciamento de dependências via **Composer**.
    * Roteamento de requisições para controladores específicos.

* **Serviço de E-mail:**
    * Endpoint `POST /api/send-email` para o envio de e-mails.
    * Utiliza a biblioteca **PHPMailer** (`^6.9`) configurada para envio via SMTP.
    * A configuração de credenciais SMTP é carregada de forma segura a partir de um arquivo `.env`.

* **Segurança da API:**
    * Implementação de uma verificação de **API Key** via `Authorization: Bearer` para proteger o acesso aos endpoints.

### Análise das User Stories

Como esta sprint foi uma evolução técnica, não há user stories diretas no documento de planejamento. No entanto, podemos inferir a seguinte "technical story":

* **User Story (Técnica):**
    > **Como desenvolvedor,** preciso de um endpoint de API para enviar e-mails de forma programática.
    > **Para que** a aplicação principal possa delegar essa responsabilidade, melhorando a modularidade e permitindo futuras integrações.

Esta "story" foi completamente implementada através da `DailyPlannerApi`.

---

## Sprint 4: Dashboard Avançado e Recursos Adicionais

O escopo original da Sprint 4 focava no compartilhamento de tarefas. No entanto, o desenvolvimento priorizou o enriquecimento da experiência do usuário individual e a adição de funcionalidades administrativas.

**Valor da Sprint:** Melhorar a interface do usuário com visualizações de dados úteis e fornecer ferramentas de administração e suporte.

### Funcionalidades Implementadas
* **Dashboard Interativo (`dashboard.html` e `dashboard.css`):**
    * **Gráfico de Produtividade:** Um gráfico de rosca renderizado com **Chart.js** mostra a porcentagem de tarefas concluídas.
    * **Calendário Dinâmico:** Um calendário interativo permite que o usuário selecione um dia para visualizar as tarefas correspondentes.
    * **Tema Claro/Escuro:** Um botão permite ao usuário alternar entre os temas para melhor conforto visual.

* **Suporte ao Cliente (SAC):**
    * Implementação de um formulário de contato (SAC) flutuante no dashboard.
    * Criação de um modelo de dados para gerenciar os tickets (`sac_model.php`).

* **Painel Administrativo:**
    * Uma página dedicada (`painel_admin.php`) acessível apenas por usuários do tipo "admin".
    * O painel lista todos os usuários e todas as tarefas cadastradas no sistema.

### Análise das User Stories

As user stories planejadas para a Sprint 4 no documento original **não foram implementadas**.

* **User Story: Compartilhar Tarefas** 
    > **Planejado:** "Eu, como usuário, desejo compartilhar minhas tarefas com outras pessoas." 
    > **Status:** **Não implementado.** Não há evidências no código de funcionalidades de compartilhamento.

* **User Story: Gerenciar Permissões de Acesso** 
    > **Planejado:** "Eu, como usuário, desejo definir quem pode visualizar ou editar minhas tarefas compartilhadas." 
    > **Status:** **Não implementado.**

Em vez disso, as seguintes user stories (inferidas do código) foram implementadas:

* **User Story (Implementada):**
    > **Como usuário,** quero visualizar meu progresso em um gráfico de produtividade.
    > **Para que** eu possa me sentir motivado e acompanhar minha eficiência.

* **User Story (Implementada):**
    > **Como administrador,** quero um painel para visualizar todos os usuários e tarefas do sistema.
    > **Para que** eu possa monitorar a plataforma e fornecer suporte.
