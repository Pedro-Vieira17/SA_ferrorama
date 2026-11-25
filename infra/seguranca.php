<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function exigirLogin()
{
    if (!isset($_SESSION['usuario_id'])) {
        header('Location: ../index.php');
        exit;
    }
}

function exigirAdministrador()
{
    exigirLogin();

    if (!isset($_SESSION['usuario_tipo']) || $_SESSION['usuario_tipo'] !== 'administrador') {
        header('Location: ../index.php');
        exit;
    }
}

function e($valor)
{
    return htmlspecialchars($valor, ENT_QUOTES, 'UTF-8');
}
