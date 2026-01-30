<?php

$banco = "smo";

// LOCAL
/*
    $host = "localhost";
    $user = "root";
    $pass = "root";
    */

// Produção

$host = "localhost";
$user = "root";
$pass = "aplicativos@mariadb";


try {
    $conexao = new PDO("mysql:dbname=$banco; host=$host; charset=utf8", $user, $pass);
} catch (Exception $ex) {
    // Em testes
    echo 'Erro ao se conectar no banco de dados 0001: ' . $ex->getMessage();

    //Em produção
    #echo "Erro 0001: Não foi possível acessar a aplicação!";

    exit();
}
