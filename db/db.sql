-- Criação do banco de dados
CREATE DATABASE IF NOT EXISTS futebol_db;
USE futebol_db;

-- Tabela de Times
CREATE TABLE IF NOT EXISTS times (
    id_time INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL UNIQUE
);

-- Tabela de Jogadores
CREATE TABLE IF NOT EXISTS jogadores (
    id_jogador INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    posicao VARCHAR(50) NOT NULL, -- Ex: Goleiro, Zagueiro, Meio-campo, Atacante
    numero_camisa INT NOT NULL,
    id_time INT NOT NULL,
    FOREIGN KEY (id_time) REFERENCES times(id_time)
);

-- Tabela de Partidas
CREATE TABLE IF NOT EXISTS partidas (
    id_partida INT AUTO_INCREMENT PRIMARY KEY,
    id_time_mandante INT NOT NULL,
    id_time_visitante INT NOT NULL,
    data_hora DATETIME NOT NULL,
    placar_mandante INT DEFAULT 0,
    placar_visitante INT DEFAULT 0,
    FOREIGN KEY (id_time_mandante) REFERENCES times(id_time),
    FOREIGN KEY (id_time_visitante) REFERENCES times(id_time),
    CONSTRAINT chk_times_diferentes CHECK (id_time_mandante != id_time_visitante),
    CONSTRAINT chk_placar_mandante_positivo CHECK (placar_mandante >= 0),
    CONSTRAINT chk_placar_visitante_positivo CHECK (placar_visitante >= 0)
);