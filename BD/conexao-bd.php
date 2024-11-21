<?php
$host = "localhost";   
$usuario = "root";     
$senha = "";          
$database = "cartilha_de_serviços";  

// Conecta no Banco de dados

try
{
    $pdo = new PDO("mysql:dbname=cartilha_de_serviços;host:localhost", $usuario, $senha);

    echo "Deu certo";
}catch (PDOException $e)
{
    echo "Erro com banco de dados: " . $e->getMessage();
}
catch(Exception $e)
{
    echo "Erro generico" . $e->getMessage();
}

$res = $pdo->prepare("INSERT INTO secretaria(titulo, descricao) VALUES (:t, :d)");


?>