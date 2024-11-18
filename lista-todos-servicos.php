<h1>Todos os serviços listados</h1>
    <?php
    $listagem_servicos = [];
    foreach ($data['secretarias'] as $secretaria) 
    {
        foreach ($secretaria['servicos'] as $servico) 
        {
            $listagem_servicos[] = 
            [
                'nomesecretaria' => $secretaria['nome'],
                'titulodoservico' => $servico['titulo'],
                'descricaodoservico' => $servico['descricao'],
                'publicoalvo' => $servico['publico_alvo']
            ];
        }
    }
    if ($categoryFilter) 
    {
        $listagem_servicos = array_filter($listagem_servicos, function ($servico) use ($categoryFilter) 
        {
            return $servico['publicoalvo'] === $categoryFilter;
        });
        $listagem_servicos = array_values($listagem_servicos);
    }
    // Apply search filter if search term exists
    if ($searchTerm) 
    {
        $listagem_servicos = array_filter($listagem_servicos, function ($servico) use ($searchTerm) 
        {
            return 
            (
                stripos($servico['titulodoservico'], $searchTerm) !== false ||
                stripos($servico['descricaodoservico'], $searchTerm) !== false
            );
        });
        $listagem_servicos = array_values($listagem_servicos);

        // Add search results count
        echo '<div class="search-results-count">';
        echo count($listagem_servicos) . ' serviço(s) encontrado(s) para "' . htmlspecialchars($searchTerm) . '"';
        echo '</div>';
    }

    // Show no results message if needed
    if (empty($listagem_servicos) && $searchTerm): ?>
        <div class="no-results">
            Nenhum serviço encontrado para sua busca. Tente outros termos.
        </div>
    <?php endif;

    // Calculate pagination
    $totalServicos = count($listagem_servicos);
    $totalPaginas = ceil($totalServicos / QUANT_LISTAGEM_SEM_SERVICO_SELECIONADO);

    // Get current page services
    $paginaAtual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
    $inicio = ($paginaAtual - 1) * QUANT_LISTAGEM_SEM_SERVICO_SELECIONADO;
    $servicosPaginaAtual = array_slice($listagem_servicos, $inicio, QUANT_LISTAGEM_SEM_SERVICO_SELECIONADO);

    // Display services
    echo "<ul>"; // Adiciona a tag de abertura da lista
    foreach ($servicosPaginaAtual as $servico) 
    {
        echo "<li>
                <h3>
                    <a href='?secretaria=" . urlencode($servico['nomesecretaria']) . "&servico=" . urlencode($servico['titulodoservico']) . "'>"
                        . htmlspecialchars($servico['titulodoservico']) .
                    "</a>
                </h3>
                <p>" . htmlspecialchars($servico['descricaodoservico']) . "</p>
                </li>";
    }
    echo "</ul>"; // Adiciona a tag de fechamento da lista
    ?>