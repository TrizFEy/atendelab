<?php

class AtendimentosController
{
    private PDO $pdo;

    public function __construct()
    {
        require __DIR__ . '/../../config/database.php';
        $this->pdo = $pdo;
    }

    private function json(array $dados, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($dados, JSON_UNESCAPED_UNICODE);
    }

    public function listar(): void
    {
        $sql = 'SELECT a.id, p.nome AS pessoa_nome, 
                t.nome AS tipo_nome, 
                u.nome AS responsavel_nome, 
                a.descricao, a.status, 
                a.data_atendimento,
                a.observacao_final 
                FROM atendimentos a 
                INNER JOIN pessoas p ON p.id = a.pessoa_id 
                INNER JOIN tipos_atendimentos t 
                ON t.id = a.tipo_atendimento_id 
                INNER JOIN usuarios u ON u.id = a.usuario_id 
                ORDER BY a.id DESC';
        $this->json($this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC));
    }

    public function buscar(): void
    {
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        if (!$id) {
            $this->json(['erro' => 'ID inválido.'], 400);
            return;
        }

        $stmt = $this->pdo->prepare(
            'SELECT a.*, p.nome AS pessoa_nome, 
                t.nome AS tipo_nome, u.nome AS responsavel_nome 
                FROM atendimentos a 
                INNER JOIN pessoas p ON p.id = a.pessoa_id 
                INNER JOIN tipos_atendimentos t 
                ON t.id = a.tipo_atendimento_id 
                INNER JOIN usuarios u ON u.id = a.usuario_id 
                WHERE a.id = :id'
        );
        $stmt->execute(['id' => $id]);
        $atendimento = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$atendimento) {
            $this->json(['erro' => 'Atendimento não encontrado.'], 404);
            return;
        }
        $this->json($atendimento);
    }

    public function criar(): void
    {
        $pessoaId = filter_var(
            $_POST['pessoa_id'] ?? null, 
            FILTER_VALIDATE_INT
        );
        $tipoId = filter_var(
            $_POST['tipo_atendimento_id'] ?? null, 
            FILTER_VALIDATE_INT
        );
        $usuarioId = filter_var(
            $_POST['usuario_id'] ?? null, 
            FILTER_VALIDATE_INT
        );
        $descricao = trim($_POST['descricao'] ?? '');
        $data = $_POST['data_atendimento'] ?? '';
        $status = $_POST['status'] ?? 'aberto';

        if (!$pessoaId || !$tipoId || !$usuarioId || 
            $descricao === '' || $data === '') {
            $this->json(['erro' => 'Preencha os campos obrigatórios.'], 422);
            return;
        }
        if (!in_array($status, ['aberto', 'em_andamento'], true)) {
            $this->json(['erro' => 'Status inicial inválido.'], 422);
            return;
        }

        $stmt = $this->pdo->prepare(
            'INSERT INTO atendimentos 
                (pessoa_id, tipo_atendimento_id, usuario_id, descricao, 
                status, data_atendimento) 
                VALUES 
                (:pessoa_id, :tipo_id, :usuario_id, :descricao, 
                :status, :data_atendimento)'
        );
        $stmt->execute([
            'pessoa_id' => $pessoaId,
            'tipo_id' => $tipoId,
            'usuario_id' => $usuarioId,
            'descricao' => $descricao,
            'status' => $status,
            'data_atendimento' => $data,
        ]);
        $this->json(['mensagem' => 'Atendimento registrado com sucesso.'], 201);
    }

    public function inativar(): void
    {
        $id = filter_var($_POST['id'] ?? null, FILTER_VALIDATE_INT);
        $status = $_POST['status'] ?? '';
        $observacao = trim($_POST['observacao_final'] ?? '');

        if (!$id || !in_array(
            $status, 
            ['aberto', 'em_andamento', 'concluido'], 
            true
        )) {
            $this->json(['erro' => 'ID ou status inválido.'], 422);
            return;
        }
        if ($status === 'concluido' && $observacao === '') {
            $this->json([
                'erro' => 'Informe a observação final para concluir.'
            ], 422);
            return;
        }

        $stmt = $this->pdo->prepare(
            'UPDATE atendimentos 
                SET status = :status, observacao_final = :observacao 
                WHERE id = :id'
        );
        $stmt->execute([
            'id' => $id,
            'status' => $status,
            'observacao' => $observacao !== '' ? $observacao : null,
        ]);
        $this->json(['mensagem' => 'Status atualizado com sucesso.']);
    }
    public function atualizar(): void
    {
        header('Content-Type: application/json; charset=utf-8');

        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

        $nome = trim($_POST['nome'] ?? '');
        $documento = trim($_POST['documento'] ?? '');
        $telefone = trim($_POST['telefone'] ?? '');
        $curso = trim($_POST['curso'] ?? '');
        $periodo = trim($_POST['periodo'] ?? '');
        $status = trim($_POST['status'] ?? 'Ativo');

        if (!$id || $nome === '') {
            http_response_code(400);
            echo json_encode(['erro' => 'ID e nome são obrigatórios.']);
            return;
        }

        try {

            $sql = "UPDATE pessoas
                    SET
                        nome = :nome,
                        documento = :documento,
                        telefone = :telefone,
                        curso = :curso,
                        periodo = :periodo,
                        status = :status
                    WHERE id = :id";

            $stmt = $this->pdo->prepare($sql);

            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->bindValue(':nome', $nome);
            $stmt->bindValue(':documento', $documento);
            $stmt->bindValue(':telefone', $telefone);
            $stmt->bindValue(':curso', $curso);
            $stmt->bindValue(':periodo', $periodo);
            $stmt->bindValue(':status', $status);

            $stmt->execute();

            echo json_encode([
                'mensagem' => 'Pessoa atualizada com sucesso.'
            ], JSON_UNESCAPED_UNICODE);

        } catch (PDOException $e) {

            http_response_code(500);

            echo json_encode([
                'erro' => 'Erro ao atualizar pessoa.'
            ]);
        }
    }
}
