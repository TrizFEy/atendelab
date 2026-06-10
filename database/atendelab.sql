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
 'Ingrid Silva',
 '119.359.105-91',
 '(47) 99944-6664',
 'Ciência da Computação',
 '6º Período',
 'Matriculado'
);

INSERT INTO tipos_atendimentos (nome, descricao, status)
VALUES (
 'Treinamento de Sistema',
 'Atendimento para orientar o uso do portal acadêmico, emissão de documentos e primeiro acesso.',
 'ativo'
);

INSERT INTO atendimentos (pessoa_id, tipo_atendimento_id, usuario_id, descricao, observacao, status)
VALUES (
 1,
 1,
 1,
 'Aluna com dúvidas sobre como lançar as horas complementares no portal.',
 'Foi realizado um acesso remoto para demonstrar o passo a passo. Dúvida sanada.',
 'ativo'
);
