![Logo do Projeto](https://github.com/user-attachments/assets/7dcb55c4-6fe8-4dcb-b55f-b83438be5bed)

# Daily Planner

O Daily Planner é uma aplicação de agenda pessoal online, inspirada em ferramentas como o Notion, mas com foco em simplicidade e objetividade. O projeto permite que os usuários gerenciem tarefas, organizem suas atividades diárias e acompanhem sua produtividade através de uma interface limpa e responsiva.

Este projeto foi desenvolvido como parte da disciplina de Engenharia de Software da Universidade Federal do Tocantins.

### Links Rápidos
* **[Planejamento de Sprints e Evolução do Projeto](SPRINT_PLAN.md)**
* **[Protótipo no Figma](https://www.figma.com/design/jv0gykfRstoIKWcypGvp1A/Untitled?node-id=1-2&t=cr9aatuTIzlfmUJu-1)**

---

## Funcionalidades Implementadas

* **Gestão de Usuários:**
    * Cadastro completo de novos usuários.
    * Autenticação segura com `password_hash`.
    * Página de perfil para alteração de senha e exclusão de conta.
    * Envio de e-mail de boas-vindas na criação da conta.

* **Gestão de Tarefas (CRUD):**
    * Criação, edição e exclusão de tarefas.
    * Visualização de tarefas em um dashboard interativo.
    * Capacidade de marcar tarefas como "concluídas" ou "pendentes".
    * Filtro de tarefas por status (todas, pendentes, concluídas).

* **Dashboard Interativo:**
    * Gráfico de produtividade que exibe a porcentagem de tarefas concluídas.
    * Calendário para visualização e filtro de tarefas por data.
    * Interface com temas claro e escuro (Dark Mode).

* **API RESTful:**
    * Endpoint dedicado para envio de e-mails (`/api/send-email`).
    * Estrutura de API separada com seu próprio roteamento e controladores.

* **Administração:**
    * Painel de administrador para visualização de todos os usuários e tarefas cadastradas no sistema.

---

## Arquitetura e Tecnologias

O projeto é dividido em duas partes principais: a **Aplicação Web Principal** e uma **API de Microsserviço**, cada uma com suas responsabilidades.

### 1. Aplicação Web Principal (`/Daily_Planner/app`)

Estruturada seguindo um padrão próximo ao **Model-View-Controller (MVC)** para separação de responsabilidades.

* **Models (`/models`):** Contêm a lógica de negócios e a interação com o banco de dados.
    * `UserModel.php`: Gerencia todas as operações de CRUD para usuários, incluindo hashing de senhas e autenticação.
    * `TarefaModel.php`: Gerencia o CRUD de tarefas, filtros e alterações de status.
    * `sac_model.php`: Modela os chamados de suporte ao cliente.
* **Views (`/views`):** Camada de apresentação, composta por arquivos HTML, CSS e PHP para renderização.
    * Utiliza HTML5, CSS3 (com Flexbox e Grid para layout responsivo) e JavaScript para interatividade no frontend.
    * **Dashboard (`dashboard.html`):** Interface principal onde o usuário interage com suas tarefas. Utiliza a biblioteca **Chart.js** para renderizar o gráfico de produtividade e `fetch` API do JavaScript para carregar dados dinamicamente dos controllers.
* **Controllers (`/controllers`):** Orquestram a lógica, recebendo requisições da view e interagindo com os models.
    * `cadastrar_usuario.php`, `login.php`, `salvar_tarefa.php`, `editar_tarefa.php`, `excluir_tarefa.php`, etc.

### 2. API (`/Daily_Planner/DailyPlannerApi`)

Uma API RESTful independente para lidar com serviços desacoplados, como o envio de e-mails.

* **Gerenciamento de Dependências:** Utiliza o **Composer**.
* **Roteamento:** Um roteador manual em `src/routes/api.php` direciona as requisições para os controladores apropriados.
* **Controladores (`/src/controllers`):**
    * `EmailController.php`: Orquestra o envio de e-mails, processando requisições JSON.
* **Serviços (`/src/services`):**
    * **PHPMailer (`^6.9`):** Biblioteca utilizada para o envio de e-mails. A configuração é feita para usar **SMTP do Gmail** com autenticação, garantindo mais confiabilidade na entrega.
    * `MailService.php` e `EmailSender.php`: Abstraem a configuração e o uso do PHPMailer.
* **Segurança:**
    * A API utiliza um sistema simples de autenticação por **API Key** (Bearer Token) para proteger seus endpoints.
* **Configuração:** Carrega variáveis de ambiente (como credenciais de e-mail) a partir de um arquivo `.env`, que é ignorado pelo Git.

### Tecnologias e Métodos Adicionais

* **Backend:** **PHP 7.4+**.
* **Banco de Dados:** **MySQL**, com interação via **PDO** para prevenção de SQL Injection. O schema do banco está definido em `app/criar_banco.sql`.
* **Testes:** O projeto utiliza **PHPUnit** (`^9.6`) para testes unitários. A suíte de testes está configurada em `phpunit.xml` e exemplos de testes podem ser encontrados em `test/UserModelTest.php` e `test/EdicaoConclusaoTarefaTest.php`.
* **DevOps:**
    * **Git/GitHub:** Para controle de versão.
    * **Shell Script (`setup-db.sh`):** Script para automatizar a configuração inicial do banco de dados em ambientes de desenvolvimento.

---

## Como Instalar e Executar o Projeto

1.  **Pré-requisitos:**
    * PHP 7.4 ou superior.
    * MySQL.
    * Composer.
    * Git.

2.  **Clone o repositório:**
    ```bash
    git clone [https://github.com/lfocarvalho/engenhariadesoftware.git](https://github.com/lfocarvalho/engenhariadesoftware.git)
    cd engenhariadesoftware
    ```

3.  **Instale as dependências da API:**
    Navegue até o diretório da API e execute o Composer.
    ```bash
    cd Daily_Planner/DailyPlannerApi
    composer install
    ```

4.  **Configure o Banco de Dados:**
    * Certifique-se de que seu servidor MySQL está em execução.
    * Execute o script de configuração para criar o banco de dados e as tabelas. Pode ser necessário fornecer a senha do seu usuário root do MySQL.
    ```bash
    # Navegue de volta para a raiz do Daily_Planner se necessário
    cd ../ 
    # Dê permissão de execução ao script
    chmod +x scripts/setup-db.sh
    # Execute o script (substitua 'sua_senha_root' pela sua senha)
    ./scripts/setup-db.sh sua_senha_root
    ```
    * Se preferir, execute manualmente o arquivo `app/criar_banco.sql` no seu cliente MySQL.

5.  **Configure os arquivos de configuração:**
    * **Banco de Dados Principal:** Verifique se as credenciais em `Daily_Planner/config/config.php` estão corretas para o seu ambiente.
    * **API de E-mail:** Crie um arquivo `.env` dentro de `Daily_Planner/DailyPlannerApi/` e adicione as credenciais do seu SMTP (exemplo para o Gmail):
        ```env
        GMAIL_HOST=smtp.gmail.com
        GMAIL_USERNAME=seu-email@gmail.com
        GMAIL_PASSWORD=sua-senha-de-app
        GMAIL_PORT=587
        GMAIL_ENCRYPTION=tls
        ```

6.  **Acesse a aplicação:**
    Inicie seu servidor web (XAMPP, WAMP, etc.) e acesse o projeto pelo navegador, apontando para o diretório `Daily_Planner/public/`.
    * URL de exemplo: `http://localhost/engenhariadesoftware/Daily_Planner/public/`

---

## Informações Acadêmicas

* **Universidade:** Universidade Federal do Tocantins
* **Curso:** Ciências da Computação
* **Disciplina:** Engenharia de Software - 2025.1
* **Professor:** Edeilson Milhomem da Silva

### Equipe

| Nome                              | Github                                           |
| --------------------------------- | ------------------------------------------------ |
| Isabela Barros de Oliveira.       | [@Isabelabarros-o](https://github.com/isabelabarros-o) |
| Letícia Gomes Lopes.              | [@LeticiaGLopes-151](https://github.com/LeticiaGLopes-151) |
| Luiz Fernando De Oliveira Carvalho. | [@Lfocarvalho](https://github.com/lfocarvalho) |
| Mateus Leopoldo Santiago da Silva. | [@MateusLeopoldo](https://github.com/MateusLeopoldo) |
| Natália Morais Nerys.             | [@natalia-nerys](https://github.com/natalia-nerys)     |
| Ranor Victor dos Santos Araújo.   | [@ranorvictor](https://github.com/ranorvictor)     |

[Link para o repositório do projeto](https://github.com/lfocarvalho/engenhariadesoftware)
