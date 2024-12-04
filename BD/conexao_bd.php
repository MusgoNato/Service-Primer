<?php
// Configuração do banco de dados
$host = "localhost";
$usuario = "root";
$senha = "";
$database = "cartilha";

try 
{
    // Conecta no banco
    $conexao = new PDO('mysql:host=localhost;dbname=cartilha', $usuario, $senha);

    $conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Prepara uma conexao com o banco de dados e executa ela
    $query = $conexao->prepare('SHOW TABLES');
    $query->execute();

    // Prepara a conexao com o banco de dados para leitura da linha da tabela e executa
    $querylinhas = $conexao->prepare("SELECT * FROM servicos");
    $querylinhas->execute();

    
    $obj_servicos = $querylinhas->fetchObject();
    
    echo $obj_servicos->titulo;
    echo $obj_servicos->descricao;


}catch(PDOException $e)
{
    echo "ERROR: " . $e->getMessage();
}


?>
