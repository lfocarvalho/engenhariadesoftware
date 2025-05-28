<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <script>
    // Exibe mensagem de sucesso do SAC se o parâmetro estiver na URL
    window.onload = function() {
        const params = new URLSearchParams(window.location.search);
        if (params.get('sucesso_sac') === '1') {
            const msg = document.createElement('div');
            msg.className = 'sac-success-overlay';
            msg.innerHTML = `
                <div class="sac-success-box">
                    <h2>SAC enviado com sucesso!</h2>
                    <button class="sac-success-btn" onclick="window.location.href='dashboard.html'">Fechar</button>
                </div>
            `;
            document.body.appendChild(msg);
        }
    }
    </script>
    <div class="dashboard-container">
        <div class="sidebar">
            <div class="user-profile">
                <img id="profile-pic" src="foto perfil/perfil 1.svg" alt="Foto do usuário" class="profile-pic">
                <button class="change-pic-btn" onclick="openPicModal()" title="Alterar foto de perfil">
                    <i class="fas fa-camera"></i>
                </button>
                <h3 id="username">Nome do Usuário</h3>
                <p id="user-email">usuario@email.com</p>
            </div>
            <nav>
                <ul>
                    <li><a href="home.html"><i class="fas fa-home"></i> <span>Início</span></a></li>
                    <li><a href="perfil.html"><i class="fas fa-user"></i> <span>Perfil</span></a></li>
                    <li><a href="config.html"><i class="fas fa-cog"></i> <span>Configurações</span></a></li>
                </ul>
            </nav>
            <a href="#" class="logout-btn" onclick="redirectToIndex()">
                <i class="fas fa-sign-out-alt"></i> Sair
            </a>
        </div>
        <div class="content-area">
            <div class="theme-toggle-container">
                <button class="theme-toggle" onclick="toggleTheme()" title="Alternar tema">
                    <i class="fas fa-moon"></i>
                </button>
            </div>
            <header class="dashboard-header">
                <h1>Bem-vindo ao Dashboard</h1>
                <p>Gerencie suas tarefas e produtividade</p>
            </header>
            <div class="dashboard-grid">
                <!-- Seção de Agenda do Dia -->
                <div class="card">
                    <h2>Agenda do Dia</h2>
                    <div class="activity-container">
                        <ul class="agenda-list" id="activity-list">
                            <!-- As atividades serão carregadas dinamicamente aqui -->
                        </ul>
                    </div>
                    <button class="add-button" onclick="openAddActivityModal()">Adicionar Atividade</button>
                </div>

                <!-- Seção de Produtividade -->
                <div class="card">
                    <h2>Produtividade</h2>
                    <div class="productivity-circle">
                        75%
                    </div>
                </div>

                <!-- Seção de Calendário -->
                <div class="card">
                    <h2>Calendário</h2>
                    <div class="calendar-header">
                        <button class="prev-button">Anterior</button>
                        <span class="current-month">Maio 2025</span>
                        <button class="next-button">Próximo</button>
                    </div>
                    <div class="week-days">
                        <span>Dom</span>
                        <span>Seg</span>
                        <span>Ter</span>
                        <span>Qua</span>
                        <span>Qui</span>
                        <span>Sex</span>
                        <span>Sáb</span>
                    </div>
                    <div class="calendar-grid">
                        <div class="empty-day"></div>
                        <div class="day">1</div>
                        <div class="day">2</div>
                        <div class="day">3</div>
                        <!-- Continue com os dias do mês -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para adicionar/editar atividade -->
    <div id="activity-modal" class="popup hidden">
        <div class="popup-content">
            <h2 id="modal-title">Adicionar Atividade</h2>
            <form id="activity-form" action="salvar_tarefa.php" method="POST">
                <input type="hidden" id="activity-id" name="id">
                <div class="form-group">
                    <label for="activity-title">Título</label>
                    <input type="text" id="activity-title" name="titulo" class="input-field" placeholder="Digite o título" required>
                </div>
                <div class="form-group">
                    <label for="activity-description">Descrição</label>
                    <textarea id="activity-description" name="descricao" class="input-field" placeholder="Digite a descrição" required></textarea>
                </div>
                <div class="form-group">
                    <label for="activity-date">Data</label>
                    <input type="date" id="activity-date" name="date" class="input-field" required>
                </div>
                <div class="form-group">
                    <label for="activity-time">Hora</label>
                    <input type="time" id="activity-time" name="time" class="input-field" required>
                </div>
                <button type="submit" class="save-button">Salvar</button>
                <button type="button" class="cancel-button" onclick="closeModal()">Cancelar</button>
            </form>
        </div>
    </div>

    <!-- Modal de seleção de foto de perfil -->
    <div id="pic-modal" class="pic-modal hidden">
        <div class="pic-modal-content">
            <span class="close-pic-modal" onclick="closePicModal()">&times;</span>
            <h3>Escolha sua foto de perfil</h3>
            <div class="pic-options">
                <img src="foto perfil/perfil 1.svg" onclick="selectProfilePic('foto perfil/perfil 1.svg')" alt="Perfil 1">
                <img src="foto perfil/perfil 2.svg" onclick="selectProfilePic('foto perfil/perfil 2.svg')" alt="Perfil 2">
                <img src="foto perfil/perfil 3.svg" onclick="selectProfilePic('foto perfil/perfil 3.svg')" alt="Perfil 3">
                <img src="foto perfil/perfil 4.svg" onclick="selectProfilePic('foto perfil/perfil 4.svg')" alt="Perfil 4">
                <img src="foto perfil/perfil 5.svg" onclick="selectProfilePic('foto perfil/perfil 5.svg')" alt="Perfil 5">
                <img src="foto perfil/perfil 6.svg" onclick="selectProfilePic('foto perfil/perfil 6.svg')" alt="Perfil 6">
            </div>
        </div>
    </div>

    <script>
        let selectedDate = null;

        // Função para carregar atividades do dia selecionado
        async function loadActivities(date = null) {
            // Se não passar data, usa a de hoje
            if (!date) {
                const today = new Date();
                date = today.toISOString().split('T')[0];
            }
            // Exemplo: busca atividades por data (ajuste para seu backend se necessário)
            const response = await fetch('get_atividades.php?date=' + date);
            const activities = await response.json();
            const activityList = document.getElementById('activity-list');
            activityList.innerHTML = '';

            if (activities.length === 0) {
                activityList.innerHTML = '<li class="activity">Nenhuma atividade para este dia.</li>';
            } else {
                activities.forEach(activity => {
                    const li = document.createElement('li');
                    li.classList.add('activity');
                    li.innerHTML = `
                        <div class="activity-header" onclick="toggleActivity(this)">
                            <span class="activity-title">${activity.time} - ${activity.title}</span>
                            <button class="toggle-button">+</button>
                        </div>
                        <div class="activity-details hidden">
                            <p class="activity-description">${activity.description}</p>
                            <div class="activity-actions">
                                <button class="edit-button" onclick="openEditActivityModal(${activity.id})">Editar</button>
                                <button class="delete-button" onclick="deleteActivity(${activity.id})">Excluir</button>
                            </div>
                        </div>
                    `;
                    activityList.appendChild(li);
                });
            }
        }

        // Função para abrir o modal de adicionar atividade
        function openAddActivityModal() {
            document.getElementById('modal-title').textContent = 'Adicionar Atividade';
            document.getElementById('activity-form').reset();
            document.getElementById('activity-id').value = '';
            document.getElementById('activity-modal').classList.remove('hidden');
        }

        // Função para fechar o modal
        function closeModal() {
            document.getElementById('activity-modal').classList.add('hidden');
        }

        // Função para expandir/recolher detalhes da atividade
        function toggleActivity(header) {
            const details = header.nextElementSibling;
            const toggleButton = header.querySelector('.toggle-button');

            if (details.classList.contains('hidden')) {
                details.classList.remove('hidden');
                toggleButton.textContent = '-';
            } else {
                details.classList.add('hidden');
                toggleButton.textContent = '+';
            }
        }

        // Gera o calendário ao carregar a página
        document.addEventListener('DOMContentLoaded', function() {
            generateCalendar();
            loadActivities(); // <-- Isso garante que as atividades de hoje aparecem ao entrar
        });

        // Função para gerar os dias do calendário
        function generateCalendar() {
            const calendarGrid = document.querySelector('.calendar-grid');
            const currentMonthElement = document.querySelector('.current-month');

            // Obtém a data atual
            const today = new Date();
            let currentMonth = today.getMonth(); // Mês atual (0-11)
            let currentYear = today.getFullYear(); // Ano atual

            // Função para atualizar o calendário
            function updateCalendar() {
                // Limpa o calendário
                calendarGrid.innerHTML = '';

                // Define o primeiro dia do mês
                const firstDay = new Date(currentYear, currentMonth, 1).getDay(); // Dia da semana (0-6)
                const daysInMonth = new Date(currentYear, currentMonth + 1, 0).getDate(); // Total de dias no mês

                // Atualiza o título do mês
                const monthNames = [
                    'Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho',
                    'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'
                ];
                currentMonthElement.textContent = `${monthNames[currentMonth]} ${currentYear}`;

                // Preenche os dias vazios antes do primeiro dia do mês
                for (let i = 0; i < firstDay; i++) {
                    const emptyCell = document.createElement('div');
                    emptyCell.classList.add('empty-day');
                    calendarGrid.appendChild(emptyCell);
                }

                // Preenche os dias do mês
                for (let day = 1; day <= daysInMonth; day++) {
                    const dayCell = document.createElement('div');
                    dayCell.classList.add('day');
                    dayCell.textContent = day;

                    // Marca o dia atual
                    if (
                        day === today.getDate() &&
                        currentMonth === today.getMonth() &&
                        currentYear === today.getFullYear()
                    ) {
                        dayCell.classList.add('today');
                    }

                    // Clique para selecionar o dia
                    dayCell.addEventListener('click', function() {
                        // Formata a data para yyyy-mm-dd
                        const selected = new Date(currentYear, currentMonth, day);
                        selectedDate = selected.toISOString().split('T')[0];
                        loadActivities(selectedDate);

                        // Destaca o dia selecionado
                        document.querySelectorAll('.calendar-grid .day').forEach(d => d.classList.remove('selected'));
                        dayCell.classList.add('selected');
                    });

                    calendarGrid.appendChild(dayCell);
                }
            }

            // Botões de navegação do calendário
            document.querySelector('.prev-button').addEventListener('click', () => {
                currentMonth--;
                if (currentMonth < 0) {
                    currentMonth = 11;
                    currentYear--;
                }
                updateCalendar();
            });

            document.querySelector('.next-button').addEventListener('click', () => {
                currentMonth++;
                if (currentMonth > 11) {
                    currentMonth = 0;
                    currentYear++;
                }
                updateCalendar();
            });

            // Gera o calendário inicial
            updateCalendar();
        }

        // Função para redirecionar ao clicar no botão "Sair"
        function redirectToIndex() {
            const confirmLogout = confirm("Você deseja mesmo sair da conta?");
            if (confirmLogout) {
                // Redireciona para a página inicial (index.php)
                window.location.href = "index.php";
            } else {
                // Permanece na página atual (dashboard.html)
                window.location.href = "dashboard.html";
            }
        }

        function toggleTheme() {
            document.body.classList.toggle('dark-mode');
            // Troca o ícone do botão correto
            const btn = document.querySelector('.theme-toggle-container .theme-toggle i');
            if(document.body.classList.contains('dark-mode')) {
                btn.classList.remove('fa-moon');
                btn.classList.add('fa-sun');
            } else {
                btn.classList.remove('fa-sun');
                btn.classList.add('fa-moon');
            }
        }

        function openPicModal() {
            document.getElementById('pic-modal').classList.remove('hidden');
        }
        function closePicModal() {
            document.getElementById('pic-modal').classList.add('hidden');
        }
        function selectProfilePic(src) {
            document.getElementById('profile-pic').src = src;
            localStorage.setItem('profilePic', src); // Salva escolha no navegador
            closePicModal();
        }
        // Carrega a foto escolhida ao abrir o dashboard
        window.addEventListener('DOMContentLoaded', function() {
            const savedPic = localStorage.getItem('profilePic');
            if (savedPic) {
                document.getElementById('profile-pic').src = savedPic;
            }
        });
    </script>
            <!-- Botão do SAC -->
    <div class="sac-flutuante">
        <button type="button" class="botao-sac" onclick="toggleModalSac()">SAC</button>
    </div>

    <!-- Modal do SAC -->
    <div class="modal-sac" id="modalSac" style="display: none;">
        <div class="modal-conteudo">
            <span class="fechar-modal" onclick="toggleModalSac()">&times;</span>
            <h2>Enviar Mensagem ao SAC</h2>
            <form method="post" action="salvar_sac.php">
                <input type="text" name="titulo" placeholder="Assunto" required><br>
                <textarea name="descricao" placeholder="Sua mensagem..." required rows="5"></textarea><br>
                <button type="submit">Enviar</button>
                
            </form>
        </div>
    </div>

    <script>
    function toggleModalSac() {
        var modal = document.getElementById('modalSac');
        if (modal.style.display === 'none' || modal.style.display === '') {
            modal.style.display = 'block';
        } else {
            modal.style.display = 'none';
        }
    }
    </script>
</body>
</html>
``` 