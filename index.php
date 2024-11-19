<?php
// Quantidade de serviços por página, aqui eh o controle das paginas que serao apresentadas
const SERVICOS_POR_PAGINA = 4;
const QUANT_LISTAGEM_SEM_SERVICO_SELECIONADO = 8;

// Pega o conteudo do json e converte em uma string
$json = file_get_contents('arquivo.json');

//Decodifica a string convertida e transforma em um vetor
$data = json_decode($json, true);

// Se existir no GET, um vetor global, os valores abaixo, entt eh pego de acordo com o que esta no vetor, caso contrario eh atribuido null
$secretariaSelecionada = isset($_GET['secretaria']) ? $_GET['secretaria'] : null;
$servicoSelecionado = isset($_GET['servico']) ? $_GET['servico'] : null;

//Verifica a existencia da pagina no vetor global, caso exista ha o casting pra tipo int, caso contrario eh atribuido o numero 1 por ser a 1° pagina lida
$paginaAtual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$servicosPorPagina = 4; // Quantidade de serviços por página, aqui eh o controle das paginas que serao apresentadas

// Add new GET parameter for search
$searchTerm = isset($_GET['search']) ? trim($_GET['search']) : '';

// Add new GET parameter for category filter
$categoryFilter = isset($_GET['categoria']) ? $_GET['categoria'] : '';

// Aqui eh criado um vetor, contera os servicos referente a qual secretaria for selecionada
$servicos = [];
$todosServicos = []; // Array para armazenar todos os serviços

// Primeiro, coletamos todos os serviços de todas as secretarias se houver um termo de busca
if ($searchTerm) 
{
    foreach ($data['secretarias'] as $sec) 
    {
        foreach ($sec['servicos'] as $servico) 
        {
            $servico['secretaria'] = $sec['nome']; // Adiciona a informação da secretaria ao serviço
            $todosServicos[] = $servico;
        }
    }

    // Aplica o filtro de busca em todos os serviços
    $todosServicos = array_filter($todosServicos, function ($servico) use ($searchTerm) 
    {
        return 
        (
            stripos($servico['titulo'], $searchTerm) !== false ||
            stripos($servico['descricao'], $searchTerm) !== false
        );
    });
    $todosServicos = array_values($todosServicos);
}

// Se não houver termo de busca, continua com o comportamento normal
foreach ($data['secretarias'] as $secretaria) 
{
    if ($secretaria['nome'] === $secretariaSelecionada) 
    {
        $servicos = $secretaria['servicos'];

        // Apply category filter if set
        if ($categoryFilter) 
        {
            $servicos = array_filter($servicos, function ($servico) use ($categoryFilter) 
            {
                return $servico['publico_alvo'] === $categoryFilter;
            });
            $servicos = array_values($servicos);
        }
        break;
    }
}


// Se houver resultados da busca global, use-os em vez dos serviços da secretaria selecionada
if ($searchTerm && !empty($todosServicos)) 
{
    $servicos = $todosServicos;
}

// Calcula as paginas para apresentação, serviços eh um vetor, entao somente conto os valores dentro do vetor
$totalServicos = count($servicos);

// Particiona o numero de paginas para nao ser pesado da propria pagina carregar o conteudo
$totalPaginas = ceil($totalServicos / SERVICOS_POR_PAGINA);

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Pre-carrega o css antes de eexcutar o restante dos codigos, assim evita piscamento da tela por nao carregar o css pelos navegadores -->
    <link rel="preload" href="style.css" as="style" onload="this.rel='stylesheet'">
    <title>Serviços por Secretaria</title>
</head>

<body>
    <div class="container">
        <!-- Sidebar das secretarias -->
        <div class="sidebar">
            <?php include 'categorias-servicos.php';?>
        </div>

        <!-- Conteúdo de Serviços relacionados a secretaria selecionada-->
        <div class="content">
            <!-- Add search form -->
            <form method="GET" class="search-form">
                <input type="hidden" name="secretaria" value="<?php echo htmlspecialchars($secretariaSelecionada); ?>">
                <input type="hidden" name="pagina" value="1">
                <input type="text" name="search" value="<?php echo htmlspecialchars($searchTerm); ?>" placeholder="Buscar serviços..." class="search-input">
                <button type="submit" class="search-button">Buscar</button>
                <?php if ($searchTerm): ?>
                    <a href="?<?php echo $secretariaSelecionada ? 'secretaria=' . urlencode($secretariaSelecionada) : ''; ?>" class="clear-search">Limpar busca</a>
                <?php endif; ?>
            </form>

            <!-- Add search results count -->
            <?php if ($searchTerm): ?>
            <?php endif; ?>

            <?php if (!$secretariaSelecionada): ?>
                <!-- Exibe todos os serviços -->
                <?php include 'lista-todos-servicos.php'?>

                <!-- Update pagination links to include search term -->
                <?php include 'botoes-paginacao.php'?>

            <?php endif; ?>

            <!-- Aqui ha a verificacao se uma secretaria foi selecionada para ocorrer a listagem dos serviços referente a ela, por isso o ! no segundo parametro, pois caso nao for selecionado nenhum
            serviço, sera verdade, ja que seu valor sera null-->
            <?php if ($secretariaSelecionada && !$servicoSelecionado): ?>

                <!-- Add category results count -->
            <?php if ($categoryFilter): ?>
                <div class="category-results-count">
                    <?php echo count($servicos); ?> serviço(s) encontrado(s) para a categoria "<?php echo htmlspecialchars($categoryFilter); ?>"
                </div>
            <?php endif; ?>

            <!-- Existing services list code continues here -->
            <ul>
                <?php
                $servicosPagina = array_slice($servicos, ($paginaAtual - 1) * $servicosPorPagina, $servicosPorPagina);
                echo "<h1>". htmlspecialchars($secretariaSelecionada) . "</h1>";
                foreach ($servicosPagina as $servico): ?>
                    <li>
                        <h3>
                            <a href="?secretaria=<?php echo urlencode($secretariaSelecionada); ?>&servico=<?php echo urlencode($servico['titulo']); ?>">
                                <?php echo htmlspecialchars($servico['titulo']); ?>
                            </a>
                        </h3>
                        <p><?php echo htmlspecialchars($servico['descricao']); ?></p>
                    </li>
                <?php endforeach; ?>
            </ul>

            <!-- Botões de Paginação -->
            <?php include 'botoes-paginacao.php'?>
        <?php elseif ($secretariaSelecionada && $servicoSelecionado): ?>
            <!-- Exibição dos serviços -->
            <?php include 'exibe-servicos.php'; ?>
        <?php endif; ?>
        </div>
    </div>
</body>

</html>