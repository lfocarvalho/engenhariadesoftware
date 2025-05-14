-- Criação do banco de dados
CREATE DATABASE IF NOT EXISTS daily_planner;
USE daily_planner;

CREATE TABLE IF NOT EXISTS usuarios (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    senha_hash VARCHAR(255) COMMENT 'Opcional: manter hash também',
    tipo ENUM('admin', 'usuario') DEFAULT 'usuario',
    data_criacao DATETIME DEFAULT CURRENT_TIMESTAMP
);
);

-- Tabela de tarefas
CREATE TABLE IF NOT EXISTS tarefas (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(100) NOT NULL,
    descricao TEXT,
    data_vencimento DATETIME NOT NULL,
    concluida BOOLEAN DEFAULT FALSE,
    usuario_id INT NOT NULL,
    data_criacao DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
);

-- Inserção do usuário admin
INSERT IGNORE INTO usuarios (nome, email, senha, tipo) 
VALUES ('Admin', 'admin@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');
