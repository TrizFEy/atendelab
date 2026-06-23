<?php

require_once __DIR__ . '/app/Controllers/AuthController.php';
require_once __DIR__ . '/app/Middleware/auth.php';

$controller = $_GET['controller'] ?? 'auth';
$action = $_GET['action'] ?? 'login';

switch ($controller) {
    case 'auth':
        $obj = new AuthController();
        switch ($action) {
            case 'login':
                $obj->exibirLogin();
                break;
            case 'entrar':
                $obj->entrar();
                break;
            case 'dashboard':
                $obj->dashboard();
                break;
            case 'logout':
                $obj->logout();
                break;
            default:
                http_response_code(404);
                echo 'Ação não encontrada.';
        }
        break;

    case 'usuarios':
        exigirAutenticacao();
        require_once __DIR__ . '/app/Controllers/UsuariosController.php';
        $usuariosController = new UsuariosController();
        switch ($action) {
            case 'listar':
                $usuariosController->listar();
                break;
            case 'buscarPorId':
                $usuariosController->buscarPorId();
                break;
            case 'criar':
                $usuariosController->criar();
                break;
            case 'atualizar':
                $usuariosController->atualizar();
                break;
            case 'excluir':
                $usuariosController->excluir();
                break;
            default:
                responderRotaNaoEncontrada('Ação de usuários não encontrada.');
        }
        break;

    case 'pessoas':
        exigirAutenticacao();
        require_once __DIR__ . '/app/Controllers/PessoasController.php';
        $pessoasController = new PessoasController();
        switch ($action) {
            case 'listar':
                $pessoasController->listar();
                break;
            case 'buscarPorId':
                $pessoasController->buscarPorId();
                break;
            case 'criar':
                $pessoasController->criar();
                break;
            case 'atualizar':
                $pessoasController->atualizar();
                break;
            case 'excluir':
                $pessoasController->excluir();
                break;
            default:
                responderRotaNaoEncontrada('Ação de pessoas não encontrada.');
        }
        break;

    case 'atendimentos':
        exigirAutenticacao();
        require_once __DIR__ . '/app/Controllers/AtendimentosController.php';
        $atendimentosController = new AtendimentosController();
        switch ($action) {
            case 'listar':
                $atendimentosController->listar();
                break;
            case 'buscarPorId':
                $atendimentosController->buscarPorId();
                break;
            case 'criar':
                $atendimentosController->criar();
                break;
            case 'atualizar':
                $atendimentosController->atualizar();
                break;
            case 'excluir':
                $atendimentosController->excluir();
                break;
            default:
                responderRotaNaoEncontrada('Ação de atendimentos não encontrada.');
        }
        break;

    case 'tipos':
        exigirAutenticacao();
        require_once __DIR__ . '/app/Controllers/TiposAtendimentosController.php';
        $tiposController = new TiposAtendimentosController();
        switch ($action) {
            case 'listar':
                $tiposController->listar();
                break;
            case 'buscarPorId':
                $tiposController->buscarPorId();
                break;
            case 'criar':
                $tiposController->criar();
                break;
            case 'atualizar':
                $tiposController->atualizar();
                break;
            case 'inativar':
                $tiposController->inativar();
                break;
            default:
                responderRotaNaoEncontrada('Ação de tipos de atendimento não encontrada.');
        }
        break;

    default:
        responderRotaNaoEncontrada('Controller não encontrado.');
}

function responderRotaNaoEncontrada($mensagem) {
    http_response_code(404);
    echo $mensagem;
}
