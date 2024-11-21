<?php
$host = "localhost";   
$usuario = "root";     
$senha = "";          
$database = "cartilha_de_serviços";  

// Conecta no Banco de dados

try
{
    $conexao = new PDO("mysql:host=$host;dbname=$database", $usuario, $senha);

    echo "Conexao bem sucedida!";

    $comando = "SELECT * FROM `cartilha`";

    $consulta = $conexao->prepare($comando);

    $consulta->execute();

    $resultados = $consulta->fetchAll(PDO::FETCH_ASSOC);

    if($resultados)
    {
        foreach ($resultados as $linha)
        {
            echo $linha["id"];
        }
    }
    else
    {
        echo "0 resultados";
    }

    // Saida
    $conexao = null;

} catch (PDOException $excecoes)
{
    echo "Erro: " . $excecoes->getMessage();
}