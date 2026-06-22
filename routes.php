<?php

require_once __DIR__ . '/app/Controllers/AuthController.php';
require_once __DIR__ . '/app/Controllers/UsuariosController.php';
require_once __DIR__ . '/app/Controllers/PessoasController.php';
require_once __DIR__ . '/app/Controllers/AtendimentosController.php';
require_once __DIR__ . '/app/Controllers/TiposAtendimentosController.php';
require_once __DIR__ . '/app/Middleware/auth.php';

$controller = $_GET['controller'] ?? 'auth';
$action = $_GET['action'] ?? 'login';

switch ($controller) {
    case 'auth':
        $obj = new AuthController();
        switch ($action) {
            case 'login': $obj->exibirLogin(); break;
            case 'entrar': $obj->entrar(); break;
            case 'dashboard': $obj->dashboard(); break;
            case 'logout': $obj->logout(); break;
            default: http_response_code(404); echo 'Ação não encontrada.';
        }
        break;

    case 'usuarios':
        exigirAutenticacao();
        $obj = new UsuariosController();
        switch ($action) {
            case 'listar': $obj->listar(); break;
            case 'buscarPorId': $obj->buscarPorId(); break;
            case 'criar': $obj->criar(); break;
            case 'atualizar': $obj->atualizar(); break;
            case 'excluir': $obj->excluir(); break;
            default: http_response_code(404); echo 'Ação não encontrada.';
        }
        break;

    case 'pessoas':
        exigirAutenticacao();
        $obj = new PessoasController();
        switch ($action) {
            case 'listar': $obj->listar(); break;
            case 'buscarPorId': $obj->buscarPorId(); break;
            case 'criar': $obj->criar(); break;
            case 'atualizar': $obj->atualizar(); break;
            case 'excluir': $obj->excluir(); break;
            default: http_response_code(404); echo 'Ação não encontrada.';
        }
        break;

    case 'atendimentos':
        exigirAutenticacao();
        $obj = new AtendimentosController();
        switch ($action) {
            case 'listar': $obj->listar(); break;
            case 'buscarPorId': $obj->buscarPorId(); break;
            case 'criar': $obj->criar(); break;
            case 'atualizar': $obj->atualizar(); break;
            case 'excluir': $obj->excluir(); break;
            default: http_response_code(404); echo 'Ação não encontrada.';
        }
        break;

    case 'tipos_atendimentos':
        exigirAutenticacao();
        $obj = new TiposAtendimentosController();
        switch ($action) {
            case 'listar': $obj->listar(); break;
            case 'buscarPorId': $obj->buscarPorId(); break;
            case 'criar': $obj->criar(); break;
            case 'atualizar': $obj->atualizar(); break;
            case 'excluir': $obj->excluir(); break;
            default: http_response_code(404); echo 'Ação não encontrada.';
        }
        break;

    default:
        http_response_code(404);
        echo 'Controller não encontrado.';
}