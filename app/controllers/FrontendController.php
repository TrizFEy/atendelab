<?php

require_once __DIR__ . '/../Middleware/auth.php';

class FrontendController
{
    public function __construct()
    {
        exigirAutenticacao();
    }

    public function pessoas(): void
    {
        $tituloPagina = 'Pessoas';
        require_once __DIR__ . '/../views/pessoas/index.php';
    }

    public function tipos(): void
    {
        $tituloPagina = 'Tipos de Atendimento';
        require_once __DIR__ . '/../views/tipos-atendimentos/index.php';
    }

    public function atendimentos(): void
    {
        $tituloPagina = 'Atendimentos';
        require_once __DIR__ . '/../views/atendimentos/index.php';
    }
}