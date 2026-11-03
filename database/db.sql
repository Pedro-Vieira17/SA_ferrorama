CREATE DATABASE sa_ferrorama
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE sa_ferrorama;

CREATE TABLE USUARIO (
    id INT AUTO_INCREMENT PRIMARY KEY,
    CPF VARCHAR(14) NOT NULL UNIQUE,
    telefone VARCHAR(20) NOT NULL,
    nome VARCHAR(150) NOT NULL,
    email VARCHAR(180) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    tipo ENUM('usuario', 'administrador') NOT NULL DEFAULT 'usuario'
) ENGINE=InnoDB;

CREATE TABLE USUARIO (
    id INT AUTO_INCREMENT PRIMARY KEY,
    CPF VARCHAR(14) NOT NULL UNIQUE,
    telefone VARCHAR(20) NOT NULL,
    nome VARCHAR(150) NOT NULL,
    email VARCHAR(180) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    tipo ENUM('usuario', 'administrador') NOT NULL DEFAULT 'usuario',
    cep CHAR(8) NULL,
    logradouro VARCHAR(200) NULL,
    bairro VARCHAR(100) NULL,
    cidade VARCHAR(100) NULL,
    uf CHAR(2) NULL
) ENGINE=InnoDB;

CREATE TABLE SENSORES (
    id INT AUTO_INCREMENT PRIMARY KEY,
    localizacao VARCHAR(150) NOT NULL,
    tipo_de_dado VARCHAR(50) NOT NULL,
    status ENUM('Ativo', 'Inativo', 'Manutenção', 'Alerta') NOT NULL DEFAULT 'Ativo',
    trens_id INT NOT NULL,
    CONSTRAINT fk_sensores_trens
        FOREIGN KEY (trens_id) REFERENCES TRENS(id)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE ROTAS (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(200) NOT NULL,
    descricao TEXT NOT NULL,
    distancia_km DECIMAL(10,2) NOT NULL,
    trem_id INT NOT NULL,
    duracao_min INT NOT NULL,
    data_criacao DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    status ENUM('Ativa', 'Inativa', 'Manutenção') NOT NULL DEFAULT 'Ativa',
    CONSTRAINT fk_rotas_trens
        FOREIGN KEY (trem_id) REFERENCES TRENS(id)
        ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB;

INSERT INTO USUARIO (CPF, telefone, nome, email, senha, tipo) VALUES
('00000000000', '47999999999', 'Administrador', 'admin@ferrovia.com.br',
 '$2y$12$IIUBheIqm208Lwwm8HGLa.2jMx7Zynkh31fQox1wtCtpQrLv5gTqS', 'administrador');

INSERT INTO TRENS (localizacao, tipo_de_dado, horario, status, usuario_id) VALUES
('Joinville - SC', 'Localização', '08:00:00', 'Ativo', 1),
('São Francisco do Sul - SC', 'Velocidade', '10:30:00', 'Ativo', 1),
('Jaraguá do Sul - SC', 'Temperatura', '14:00:00', 'Manutenção', 1);

INSERT INTO SENSORES (localizacao, tipo_de_dado, status, trens_id) VALUES
('Joinville - SC', 'GPS', 'Ativo', 1),
('São Francisco do Sul - SC', 'Velocidade', 'Ativo', 2),
('Jaraguá do Sul - SC', 'Temperatura', 'Manutenção', 3);

INSERT INTO ROTAS (nome, descricao, distancia_km, trem_id, duracao_min, status) VALUES
('Joinville - São Francisco do Sul',
 'Rota ferroviária de ligação entre Joinville e São Francisco do Sul.',
 45.00, 1, 60, 'Ativa'),
('São Francisco do Sul - Jaraguá do Sul',
 'Rota ferroviária de ligação entre São Francisco do Sul e Jaraguá do Sul.',
 70.00, 2, 90, 'Ativa'),
('Jaraguá do Sul - Blumenau',
 'Rota ferroviária de ligação entre Jaraguá do Sul e Blumenau.',
 65.00, 3, 85, 'Manutenção');
