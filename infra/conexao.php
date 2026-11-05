<?php

$conexao = new mysqli(
    "localhost",
    "root",
    "",
    "sa_ferrorama",
<<<<<<< HEAD
    3306
=======
    3307
>>>>>>> 7dcc14c7bd6887edbd8fdbefae63f9e9d1f3720b

);

if ($conexao->connect_error) {
    die("Erro na conexão: " . $conexao->connect_error);
}

$conexao->set_charset("utf8mb4");

?>