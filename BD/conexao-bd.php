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
    
    // Busca por coluna no banco de dados
    $tables_bd = $query->fetchAll(PDO::FETCH_COLUMN);

    $i = 0;

    // Percorre as linhas da tabela
    foreach($tables_bd as $tabela)
    {
        echo "Tabela $i" . $tabela;
        
        // Prepara a conexao com o banco de dados para leitura da linha da tabela e executa
        $querylinhas = $conexao->prepare("SELECT * FROM $tabela");
        $querylinhas->execute();

        // Retorna um vetoR com chave -> valor
        $linhas = $querylinhas->fetchAll(PDO::FETCH_ASSOC);
        
        // Percorre o vetor das linhas
        if(count($linhas) > 0)
        {
            foreach($linhas as $linha)
            {
                print_r($linha);
            }
        }
        else
        {
            echo "Sem linhas na tabela";
        }
    }


}catch(PDOException $e)
{
    echo "ERROR: " . $e->getMessage();
}


?>
