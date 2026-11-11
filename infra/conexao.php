<?php

$conexao = new mysqli(
    "localhost",
    "root",
    "",
    "sa_ferrorama",
    3306
);

if ($conexao->connect_error) {
    die("Erro na conexão: " . $conexao->connect_error);
}

$conexao->set_charset("utf8mb4");

?>