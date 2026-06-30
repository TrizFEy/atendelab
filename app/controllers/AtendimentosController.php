
<?php

class AtendimentosController
{
    private PDO $pdo;

    public function __construct()
    {
        require __DIR__ . '/../../config/database.php';
        $this->pdo = $pdo;
    }

    public function listar(): void
    {
        header('Content-Type: application/json; charset=utf-8');

        $sql = "SELECT
                    a.id,
                    a.pessoa_id,
                    p.nome AS pessoa_nome,
                    a.tipo_atendimento_id,
                    t.nome AS tipo_atendimento_nome,
                    a.usuario_id,
                    u.nome AS usuario_nome,
                    a.data_atendimento,
                    a.descricao,
                    a.observacao,
                    a.status,
                    a.criado_em
                FROM atendimentos a
                LEFT JOIN pessoas p ON p.id = a.pessoa_id
                LEFT JOIN tipos_atendimentos t ON t.id = a.tipo_atendimento_id
                LEFT JOIN usuarios u ON u.id = a.usuario_id
                ORDER BY a.id DESC";

        $stmt = $this->pdo->query($sql);
        $dados = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($dados, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }

    public function buscarPorId(): void
    {
        header('Content-Type: application/json; charset=utf-8');

        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

        if (!$id) {
            http_response_code(400);
            echo json_encode(['erro' => 'ID inválido.']);
            return;
        }

        $sql = "SELECT
                    a.id,
                    a.pessoa_id,
                    p.nome AS pessoa_nome,
                    a.tipo_atendimento_id,
                    t.nome AS tipo_atendimento_nome,
                    a.usuario_id,
                    u.nome AS usuario_nome,
                    a.data_atendimento,
                    a.descricao,
                    a.observacao,
                    a.status,
                    a.criado_em
                FROM atendimentos a
                LEFT JOIN pessoas p ON p.id = a.pessoa_id
                LEFT JOIN tipos_atendimentos t ON t.id = a.tipo_atendimento_id
                LEFT JOIN usuarios u ON u.id = a.usuario_id
                WHERE a.id = :id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $atendimento = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$atendimento) {
            http_response_code(404);
            echo json_encode(['erro' => 'Atendimento não encontrado.']);
            return;
        }

        echo json_encode($atendimento, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }

    public function criar(): void
    {
        header('Content-Type: application/json; charset=utf-8');

        $pessoa_id = filter_input(INPUT_POST, 'pessoa_id', FILTER_VALIDATE_INT);
        $tipo_atendimento_id = filter_input(INPUT_POST, 'tipo_atendimento_id', FILTER_VALIDATE_INT);
        $usuario_id = filter_input(INPUT_POST, 'usuario_id', FILTER_VALIDATE_INT);

        $data_atendimento = trim($_POST['data_atendimento'] ?? '');
        $descricao = trim($_POST['descricao'] ?? '');
        $observacao = trim($_POST['observacao'] ?? '');
        $status = trim($_POST['status'] ?? 'aberto');

        if (
            !$pessoa_id ||
            !$tipo_atendimento_id ||
            !$usuario_id ||
            $data_atendimento === ''
        ) {
            http_response_code(400);

            echo json_encode([
                'erro' => 'Campos obrigatórios não preenchidos.'
            ]);

            return;
        }

        try {

            $sql = "INSERT INTO atendimentos
                    (
                        pessoa_id,
                        tipo_atendimento_id,
                        usuario_id,
                        data_atendimento,
                        descricao,
                        observacao,
                        status
                    )
                    VALUES
                    (
                        :pessoa_id,
                        :tipo_atendimento_id,
                        :usuario_id,
                        :data_atendimento,
                        :descricao,
                        :observacao,
                        :status
                    )";

            $stmt = $this->pdo->prepare($sql);

            $stmt->bindValue(':pessoa_id', $pessoa_id, PDO::PARAM_INT);
            $stmt->bindValue(':tipo_atendimento_id', $tipo_atendimento_id, PDO::PARAM_INT);
            $stmt->bindValue(':usuario_id', $usuario_id, PDO::PARAM_INT);
            $stmt->bindValue(':data_atendimento', $data_atendimento);
            $stmt->bindValue(':descricao', $descricao);
            $stmt->bindValue(':observacao', $observacao);
            $stmt->bindValue(':status', $status);

            $stmt->execute();

            http_response_code(201);

            echo json_encode([
                'mensagem' => 'Atendimento criado com sucesso.',
                'id' => $this->pdo->lastInsertId()
            ], JSON_UNESCAPED_UNICODE);

        } catch (PDOException $e) {

            http_response_code(500);

            echo json_encode([
                'erro' => 'Erro ao criar atendimento.'
            ]);
        }
    }

    public function atualizar(): void
    {
        header('Content-Type: application/json; charset=utf-8');

        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        $status = trim($_POST['status'] ?? '');
        $observacao = trim($_POST['observacao'] ?? '');

        if (!$id) {
            http_response_code(400);
            echo json_encode(['erro' => 'ID é obrigatório.']);
            return;
        }

        if ($status === '') {
            http_response_code(400);
            echo json_encode(['erro' => 'O status é obrigatório.']);
            return;
        }

        try {
            $sql = "UPDATE atendimentos 
                    SET status = :status, observacao = :observacao 
                    WHERE id = :id";

            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':status', $status);
            $stmt->bindValue(':observacao', $observacao);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            echo json_encode([
                'mensagem' => 'Status do atendimento atualizado com sucesso.'
            ], JSON_UNESCAPED_UNICODE);
            return;

        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['erro' => 'Erro ao atualizar status do atendimento.']);
            return;
        }
    }
    public function inativar(): void
    {
        header('Content-Type: application/json; charset=utf-8');

        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

        if (!$id) {
            http_response_code(400);
            echo json_encode(['erro' => 'ID inválido.']);
            return;
        }

        try {

            $sql = "UPDATE atendimentos
                    SET status = 'Inativo'
                    WHERE id = :id";

            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            echo json_encode([
                'mensagem' => 'Atendimento inativado com sucesso.'
            ], JSON_UNESCAPED_UNICODE);

        } catch (PDOException $e) {

            http_response_code(500);

            echo json_encode([
                'erro' => 'Erro ao inativar atendimento.'
            ]);
        }
    }
}
