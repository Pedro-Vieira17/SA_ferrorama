
<?php

if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
        'httponly' => true,
        'samesite' => 'Lax'
    ]);

    ini_set('session.use_strict_mode', '1');

    session_start();
}

function exigirLogin(): void
{
    if (!isset($_SESSION['usuario_id'])) {
        header('Location: ../index.php');
        exit;
    }
}

function exigirAdministrador(): void
{
    exigirLogin();

    if (
        !isset($_SESSION['usuario_tipo']) ||
        $_SESSION['usuario_tipo'] !== 'administrador'
    ) {
        session_unset();
        session_destroy();

        header('Location: ../index.php');
        exit;
    }
}

function gerarTokenCsrf(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
}

function validarTokenCsrf(): void
{
    $token = $_POST['csrf_token'] ?? '';

    if (
        empty($_SESSION['csrf_token']) ||
        !hash_equals($_SESSION['csrf_token'], $token)
    ) {
        http_response_code(403);
        exit('Requisição inválida.');
    }
}

function e(string $valor): string
{
    return htmlspecialchars(
        $valor,
        ENT_QUOTES,
        'UTF-8'
    );
}
