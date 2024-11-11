<?php
// Carregar dados JSON
$json = file_get_contents('arquivo.json');
$data = json_decode($json, true);

// Identificar a categoria selecionada e a página atual
$categoriaSelecionada = isset($_GET['categoria']) ? $_GET['categoria'] : null;
$paginaAtual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$servicosPorPagina = 2; // Quantidade de serviços por página

?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Serviços por Categoria</title>
    <style>
        /* CSS (mantido como antes) */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            height: 100vh;
        }
        .container { display: flex; width: 100%; }
        .sidebar {
            width: 25%; background-color: #f4f4f4; padding: 20px; border-right: 2px solid #ddd; box-sizing: border-box;
        }
        .sidebar h1 { font-size: 1.5em; color: #333; margin-bottom: 1em; }
        .sidebar ul { list-style: none; padding: 0; }
        .sidebar ul li { margin-bottom: 10px; }
        .sidebar ul li a { text-decoration: none; color: #007bff; font-weight: bold; }
        .sidebar ul li a:hover { text-decoration: underline; color: #0056b3; }
        .content {
            width: 75%; padding: 20px; box-sizing: border-box;
        }
        .content h2 { font-size: 1.5em; color: #333; margin-bottom: 1em; }
        .content ul { list-style: none; padding: 0; }
        .content ul li {
            margin-bottom: 20px; border-bottom: 1px solid #ddd; padding-bottom: 10px;
        }
        .content ul li h3 { font-size: 1.2em; color: #333; margin: 0; }
        .content ul li p { margin: 5px 0 0; color: #666; }
        .pagination {
            margin-top: 20px;
        }
        .pagination a {
            text-decoration: none; padding: 8px 12px; border: 1px solid #007bff; color: #007bff; margin-right: 5px;
        }
        .pagination a:hover { background-color: #007bff; color: #fff; }
    </style>
</head>
<body>
    <div class="container">
        <!-- Sidebar de Categorias -->
        <div class="sidebar">
            <h1>Categorias de Serviços</h1>
            <ul>
                <?php foreach ($data['categorias'] as $categoria): ?>
                    <li>
                        <a href="?categoria=<?php echo urlencode($categoria['nome']); ?>&pagina=1">
                            <?php echo htmlspecialchars($categoria['nome']); ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>

        <!-- Conteúdo de Serviços -->
        <div class="content">
            <?php if ($categoriaSelecionada): ?>
                <h2>Serviços em <?php echo htmlspecialchars($categoriaSelecionada); ?></h2>
                <ul>
                    <?php
                    // Encontrar a categoria selecionada no JSON
                    foreach ($data['categorias'] as $categoria) {
                        if ($categoria['nome'] === $categoriaSelecionada) {
                            $totalServicos = count($categoria['servicos']);
                            $totalPaginas = ceil($totalServicos / $servicosPorPagina);
                            $inicio = ($paginaAtual - 1) * $servicosPorPagina;
                            $servicosPagina = array_slice($categoria['servicos'], $inicio, $servicosPorPagina);

                            foreach ($servicosPagina as $servico) {
                                echo "<li><h3>" . htmlspecialchars($servico['titulo']) . "</h3>";
                                echo "<p>" . htmlspecialchars($servico['descricao']) . "</p></li>";
                            }
                        }
                    }
                    ?>
                </ul>

                <!-- Botões de Paginação -->
                <div class="pagination">
                    <?php if ($paginaAtual > 1): ?>
                        <a href="?categoria=<?php echo urlencode($categoriaSelecionada); ?>&pagina=<?php echo $paginaAtual - 1; ?>">Anterior</a>
                    <?php endif; ?>

                    <?php if ($paginaAtual < $totalPaginas): ?>
                        <a href="?categoria=<?php echo urlencode($categoriaSelecionada); ?>&pagina=<?php echo $paginaAtual + 1; ?>">Próximo</a>
                    <?php endif; ?>

                    <?php if ($totalPaginas > 1 && $paginaAtual < $totalPaginas): ?>
                        <!-- Botão para a Última Página -->
                        <a href="?categoria=<?php echo urlencode($categoriaSelecionada); ?>&pagina=<?php echo $totalPaginas; ?>">Último</a>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <p>Selecione uma categoria à esquerda para ver os serviços disponíveis.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
