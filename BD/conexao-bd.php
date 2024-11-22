<?php
// Configuração do banco de dados
$host = "localhost";
$usuario = "root";
$senha = "";
$database = "cartilha_de_servicos";

try {
    // Conexão com o servidor MySQL
    $pdo = new PDO("mysql:host=$host", $usuario, $senha);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Criação do banco de dados
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$database` DEFAULT CHARSET=utf8mb4");
    echo "Banco de dados '$database' criado ou já existente.<br>";

    // Seleciona o banco de dados
    $pdo->exec("USE `$database`");

    // Criação da tabela 'usuarios'
    $sqlUsuarios = "
        CREATE TABLE IF NOT EXISTS usuarios (
            id INT AUTO_INCREMENT PRIMARY KEY,
            nome VARCHAR(100) NOT NULL,
            email VARCHAR(100) NOT NULL
        );
    ";
    $pdo->exec($sqlUsuarios);
    echo "Tabela 'usuarios' criada com sucesso.<br>";

    // Inserção de dados na tabela 'usuarios'
    $sqlInsertUsuarios = "
        INSERT INTO usuarios (nome, email) VALUES
        ('João', 'joao@email.com'),
        ('Maria', 'maria@email.com');
    ";
    $pdo->exec($sqlInsertUsuarios);
    echo "Dados inseridos na tabela 'usuarios'.<br>";

    // Criação da tabela 'produtos'
    $sqlProdutos = "
        CREATE TABLE IF NOT EXISTS produtos (
            id INT AUTO_INCREMENT PRIMARY KEY,
            nome VARCHAR(100) NOT NULL,
            preco DECIMAL(10, 2) NOT NULL
        );
    ";
    $pdo->exec($sqlProdutos);
    echo "Tabela 'produtos' criada com sucesso.<br>";

    // Inserção de dados na tabela 'produtos'
    $sqlInsertProdutos = "
        INSERT INTO produtos (nome, preco) VALUES
        ('Produto A', 50.00),
        ('Produto B', 100.00);
    ";
    $pdo->exec($sqlInsertProdutos);
    echo "Dados inseridos na tabela 'produtos'.<br>";

    // Criação da tabela 'servicos'
    $sqlServicos = "
        CREATE TABLE IF NOT EXISTS servicos (
            id INT AUTO_INCREMENT PRIMARY KEY,
            nome_servico VARCHAR(100) NOT NULL,
            descricao TEXT,
            preco DECIMAL(10, 2) NOT NULL
        );
    ";
    $pdo->exec($sqlServicos);
    echo "Tabela 'servicos' criada com sucesso.<br>";

    // Inserção de dados na tabela 'servicos'
    $sqlInsertServicos = "
        INSERT INTO servicos (nome_servico, descricao, preco) VALUES
        ('Reparo', 'Reparo de equipamentos', 120.00),
        ('Manutenção', 'Manutenção preventiva', 80.00);
    ";
    $pdo->exec($sqlInsertServicos);
    echo "Dados inseridos na tabela 'servicos'.<br>";

} catch (PDOException $e) {
    echo "Erro ao configurar o banco de dados: " . $e->getMessage();
}




?>
