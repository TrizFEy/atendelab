CREATE DATABASE IF NOT EXISTS atendelab
CHARACTER SET utf8mb4
COLLATE utf8mb4_general_ci;
USE atendelab;

CREATE TABLE `usuarios` (
  `id` int(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `email` varchar(100) UNIQUE NOT NULL,
  `senha` varchar(255) NOT NULL,
  `perfil` enum('admin','aluno', 'atendente') DEFAULT 'atendente',
  `status` enum('ativo','inativo') DEFAULT 'ativo',
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `pessoas` (
  `id` int(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `documento` varchar(20) NOT NULL UNIQUE,
  `telefone` varchar(20) NOT NULL,
  `curso` varchar(100) NOT NULL,
  `periodo` varchar(100) NOT NULL,
  `status` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `tipos_atendimentos` (
  `id` int(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `descricao` TEXT NOT NULL,
  `status` ENUM('ativo', 'inativo') DEFAULT 'ativo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `atendimentos` (
  `id` int(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `pessoa_id` int(11), 
  `tipo_atendimento_id` int(11) NOT NULL,
  `usuario_id` int(11),
  `data_atendimento` DATE NOT NULL DEFAULT (CURRENT_DATE),
  `hora_atendimento` TIME NOT NULL DEFAULT (CURRENT_TIME),
  `descricao` TEXT NOT NULL,
  `observacao` TEXT,
  `status` ENUM('ativo', 'inativo') DEFAULT 'ativo',
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp(),
  CONSTRAINT `fk_atendimentos_pessoas` FOREIGN KEY (`pessoa_id`) REFERENCES `pessoas` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_atendimentos_tipos` FOREIGN KEY (`tipo_atendimento_id`) REFERENCES `tipos_atendimentos` (`id`),
  CONSTRAINT `fk_atendimentos_usuarios` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


INSERT INTO usuarios (nome, email, senha, perfil, status)
VALUES (
 'Administrador',
 'admin@atendelab.com',
 '$2y$10$J9P2kU2BAMZ3TZcuxTsW4e1D/lka8EocYHzvyoOZmCNcWDQz3RuVC',
 'admin',
 'ativo'
);

INSERT INTO pessoas (nome, documento, telefone, curso, periodo, status)
VALUES (
 'Lucas Silva',
 '123.456.789-00',
 '(11) 98765-4321',
 'Ciência da Computação',
 '4º Período',
 'Matriculado'
);

INSERT INTO tipos_atendimentos (nome, descricao, status)
VALUES (
 'Suporte de TI',
 'Atendimento para resolução de problemas com login, acesso aos computadores do laboratório ou rede.',
 'ativo'
);

INSERT INTO atendimentos (pessoa_id, tipo_atendimento_id, usuario_id, descricao, observacao, status)
VALUES (
 1,
 1,
 1,
 'Aluno não conseguia logar na máquina do laboratório 03.',
 'Foi realizado o reset da senha institucional e o acesso foi normalizado.',
 'ativo'
);
