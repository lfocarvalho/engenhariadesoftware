# Planejamento de Sprints e Evolução do Projeto

Este documento detalha as funcionalidades implementadas em cada fase do desenvolvimento do **Daily Planner**, com base no que está presente no código-fonte atual. Ele serve como um registro da evolução do projeto, que foi adaptado e expandido para além do [planejamento inicial](PlanejamentoSprint.pdf).

---

## Sprint 1: Sistema Central de Tarefas

O foco desta sprint foi estabelecer as funcionalidades essenciais de gerenciamento de tarefas, que são o coração da aplicação. O conceito inicial de "senhas" foi evoluído para um sistema de "tarefas" mais robusto.

**Valor da Sprint:** Permitir que usuários autenticados criem, visualizem, editem e removam suas tarefas diárias, além de acompanhar seu progresso.

### Funcionalidades Implementadas:
* **CRUD de Tarefas:**
    * **Criação:** Usuários podem salvar novas tarefas com título, descrição e data de vencimento (`salvar_tarefa.php`).
    * **Leitura:** As tarefas são carregadas dinamicamente no dashboard do usuário (`get_atividades.php`).
    * **Atualização:** Tarefas existentes podem ser editadas (`editar_tarefa.php`). O teste unitário em `EdicaoConclusaoTarefaTest.php` garante que um usuário não pode editar a tarefa de outro.
    * **Remoção:** Tarefas podem ser excluídas de forma segura (`excluir_tarefa.php`).

* **Gerenciamento de Status:**
    * Usuários podem marcar tarefas como "concluídas" ou reabri-las como "pendentes" (`alterar_status.php`).
    * O dashboard permite filtrar a visualização de tarefas por status (todas, pendentes, concluídas) através do controller `filtros.php`.

### User Stories Implementadas:

* **Cadastrar Tarefas:**
    > Como usuário, desejo cadastrar novas tarefas com detalhes (título, descrição, data).
    > Para que eu possa organizar minhas responsabilidades.

* **Visualizar Tarefas:**
    > Como usuário, desejo visualizar todas as minhas tarefas em um único local.
    > Para que eu possa consultar facilmente o que preciso fazer.

* **Editar e Excluir Tarefas:**
    > Como usuário, desejo editar ou excluir tarefas que já criei.
    > Para que eu possa manter minha lista de atividades sempre atualizada.

---

## Sprint 2: Autenticação e Gestão de Perfil

Esta sprint focou em criar um ambiente seguro e personalizado para cada usuário.

**Valor da Sprint:** Facilitar a entrada de novos usuários na plataforma e garantir a segurança de seus dados e o gerenciamento de suas contas.

### Funcionalidades Implementadas:
* **Cadastro e Login:**
    * Formulário de cadastro para novos usuários (`cadastrar_usuario.php`).
    * Sistema de login seguro que autentica o usuário e inicia uma sessão (`login.php`).
    * As senhas são armazenadas de forma segura no banco usando `password_hash()`.

* **Gestão de Conta:**
    * Página de perfil onde o usuário pode alterar sua senha (`trocar_senha.php`).
    * Funcionalidade para apagar a própria conta, o que remove todos os dados do usuário do sistema (`apagar_conta.php`).

* **Personalização e Notificações:**
    * Envio de um e-mail de boas-vindas para o usuário no momento do cadastro, utilizando o serviço de e-mail da API (`enviar_boas_vindas.php`).
    * Opção para o usuário selecionar uma foto de perfil a partir de avatares pré-definidos (`dashboard.html`).

---

## Sprint 3: API de Microsserviço e E-mails

Esta sprint foi dedicada à criação de uma API desacoplada para lidar com funcionalidades de serviço, como notificações por e-mail.

**Valor da Sprint:** Desenvolver uma API RESTful para expandir as capacidades do sistema, começando com um serviço de e-mail confiável.

### Funcionalidades Implementadas:
* **Estrutura da API:**
    * API autônoma localizada em `DailyPlannerApi/` com gerenciamento de dependências via **Composer**.
    * Roteamento de requisições para controladores específicos (`src/routes/api.php`).

* **Serviço de E-mail:**
    * Endpoint `POST /api/send-email` para o envio de e-mails.
    * Utiliza a biblioteca **PHPMailer** configurada para envio via SMTP, o que aumenta a confiabilidade da entrega.
    * A configuração de credenciais SMTP é carregada de forma segura a partir de um arquivo `.env`.

* **Segurança da API:**
    * Implementação de uma verificação de **API Key** via `Authorization: Bearer` para proteger o acesso aos endpoints.

---

## Sprint 4: Dashboard Avançado e Recursos Adicionais

A última fase do desenvolvimento concentrou-se em enriquecer a experiência do usuário com um dashboard mais interativo e adicionar funcionalidades de suporte e administração.

**Valor da Sprint:** Melhorar a interface do usuário com visualizações de dados úteis e fornecer ferramentas de administração e suporte.

### Funcionalidades Implementadas:
* **Dashboard Interativo (`dashboard.html` e `dashboard.css`):**
    * **Gráfico de Produtividade:** Um gráfico de rosca (doughnut chart) renderizado com **Chart.js** mostra a porcentagem de tarefas concluídas.
    * **Calendário Dinâmico:** Um calendário interativo permite que o usuário selecione um dia para visualizar as tarefas correspondentes.
    * **Carregamento Assíncrono:** O dashboard utiliza JavaScript e a `fetch` API para carregar e atualizar a lista de tarefas sem recarregar a página.
    * **Tema Claro/Escuro:** Um botão permite ao usuário alternar entre os temas para melhor conforto visual.

* **Suporte ao Cliente (SAC):**
    * Implementação de um formulário de contato (SAC) flutuante no dashboard, permitindo que os usuários enviem dúvidas ou sugestões.
    * Criação de um modelo de dados para gerenciar os tickets (`sac_model.php`).

* **Painel Administrativo:**
    * Uma página dedicada (`painel_admin.php`) acessível apenas por usuários do tipo "admin".
    * O painel lista todos os usuários e todas as tarefas cadastradas no sistema, permitindo uma visão geral da atividade na plataforma.
