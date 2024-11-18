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
if ($searchTerm) {
    foreach ($data['secretarias'] as $sec) {
        foreach ($sec['servicos'] as $servico) {
            $servico['secretaria'] = $sec['nome']; // Adiciona a informação da secretaria ao serviço
            $todosServicos[] = $servico;
        }
    }

    // Aplica o filtro de busca em todos os serviços
    $todosServicos = array_filter($todosServicos, function($servico) use ($searchTerm) {
        return (
            stripos($servico['titulo'], $searchTerm) !== false ||
            stripos($servico['descricao'], $searchTerm) !== false
        );
    });
    $todosServicos = array_values($todosServicos);
}

// Se não houver termo de busca, continua com o comportamento normal
foreach ($data['secretarias'] as $secretaria) {
    if ($secretaria['nome'] === $secretariaSelecionada) {
        $servicos = $secretaria['servicos'];

        // Apply category filter if set
        if ($categoryFilter) {
            $servicos = array_filter($servicos, function($servico) use ($categoryFilter) {
                return $servico['publico_alvo'] === $categoryFilter;
            });
            $servicos = array_values($servicos);
        }
        break;
    }
}

// Se houver resultados da busca global, use-os em vez dos serviços da secretaria selecionada
if ($searchTerm && !empty($todosServicos)) {
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
    <link rel="stylesheet" href="styles.css">
    <title>Serviços por Secretaria</title>
</head>
<body>
    <div class="container">
        <!-- Sidebar das secretarias -->
        <div class="sidebar">
            <div id="Categorias">
                <ul>
                    <!-- Botão para listar todos os serviços -->
                    <li><h1><a href="?pagina=1">Listagem de todos os serviços</a></h1></li>
                </ul>
            </div>
            <!-- Adiciona os filtros de categoria no topo da sidebar -->
            <div class="category-filters">
                <h1>Categorias</h1>
                <ul>
                    <li>
                        <a href="?secretaria=<?php echo urlencode($secretariaSelecionada); ?><?php echo $categoryFilter === 'servidor' ? '' : '&categoria=servidor'; ?>"
                           class="<?php echo $categoryFilter === 'servidor' ? 'active' : ''; ?>">
                            Servidor
                        </a>
                    </li>
                    <li>
                        <a href="?secretaria=<?php echo urlencode($secretariaSelecionada); ?><?php echo $categoryFilter === 'empresa' ? '' : '&categoria=empresa'; ?>"
                           class="<?php echo $categoryFilter === 'empresa' ? 'active' : ''; ?>">
                            Empresa
                        </a>
                    </li>
                    <li>
                        <a href="?secretaria=<?php echo urlencode($secretariaSelecionada); ?><?php echo $categoryFilter === 'cidadao' ? '' : '&categoria=cidadao'; ?>"
                           class="<?php echo $categoryFilter === 'cidadao' ? 'active' : ''; ?>">
                            Cidadão
                        </a>
                    </li>
                </ul>
            </div>

            <h1>Secretarias</h1>
            <ul>
                <!-- Por meio de um for, apresento as secretarias disponiveis para o usuario, pegando os dados do json que foi lido no começo deste arquivo -->
                <?php foreach ($data['secretarias'] as $secretaria): ?>
                    <li>
                        <!-- O urlencode codifica o nome passado para fixar na url do link a ser passado, eh usado pois precisa passar a informação para próxima pagina, ela funciona tambem
                        para codificar espaços em branco e outros caracteres especiais que possam surgir, evitando erros -->
                        <a href="?secretaria=<?php echo urlencode($secretaria['nome']); ?>&pagina=1">

                            <!--Converte para uma entidade valida tipo HTML, para casos de segurança. Se for passado um codigo via url, entao o htmlspecialchars tratara essa url e convertera ela para um texto html simples,
                            evitando que aquele codigo execute, isso eh essencial neste codigo ja que os dados sao pegos via GET, um vetor global que contera os valores passados por formulario ou links via url.
                            Entao o <script> por exemplo eh convertido em &lt, isso o navegador interpreta e converte para '<', fazendo ser uma entidade html, que a paritr disso sera exibido em modo texto, evitando execuções por injeção
                            de script por meio da url-->
                            <?php echo htmlspecialchars($secretaria['nome']); ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
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
                        <a href="?secretaria=<?php echo urlencode($secretariaSelecionada); ?>" class="clear-search">Limpar busca</a>
                    <?php endif; ?>
                </form>

                <!-- Add search results count -->
                <?php if ($searchTerm): ?>
                    <div class="search-results-count">
                        <?php echo count($servicos); ?> serviço(s) encontrado(s) para "<?php echo htmlspecialchars($searchTerm); ?>"
                    </div>
                <?php endif; ?>

                <!-- No results message -->
                <?php if (empty($servicos) && $searchTerm): ?>
                    <div class="no-results">
                        Nenhum serviço encontrado para sua busca. Tente outros termos.
                    </div>
                <?php endif; ?>
                
            <!-- Caso nao sejam selecionados nenhum servico, eh listado todos eles juntamente com a url referente ao servico em especifico -->
            <?php if (!$secretariaSelecionada): ?>
                <h1>Todos os serviços listados</h1>

                <!-- Crio mais um vetor com todos os serviços e depois crio uma url para listagem dos serviços -->
                <?php

                    $listagem_servicos = [];
                    foreach($data['secretarias'] as $secretaria)
                    {
                        // Varro os serviços de todas as secretarias
                        foreach($secretaria['servicos'] as $servico)
                        {
                            $listagem_servicos[] = 
                            [
                                'nomesecretaria' => $secretaria['nome'],
                                'titulodoservico' => $servico['titulo']
                            ];
                        }
                    }

                    // Calcule o total de páginas
                    $totalServicos = count($listagem_servicos);
                    $totalPaginas = ceil($totalServicos / QUANT_LISTAGEM_SEM_SERVICO_SELECIONADO);

                    // Verifique qual é a página atual (defina como 1 se não for especificada)
                    $paginaAtual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;

                    // Calcule o índice inicial e final para a página atual
                    $inicio = ($paginaAtual - 1) * QUANT_LISTAGEM_SEM_SERVICO_SELECIONADO;
                    $servicosPaginaAtual = array_slice($listagem_servicos, $inicio, QUANT_LISTAGEM_SEM_SERVICO_SELECIONADO);

                    // Exiba os serviços da página atual
                    foreach ($servicosPaginaAtual as $servico)
                    {
                        echo "<li><h3><a href='?secretaria=" . urlencode($servico['nomesecretaria']) . "&servico=" . urlencode($servico['titulodoservico']) . "'>" . htmlspecialchars($servico['titulodoservico']) . "</a></h3></li>";
                    }
                ?>    
                    <!-- Botões de Paginação -->
                    <div class="pagination">
                        <!-- Cada botao eh um link, onde eh construido uma url para ele, assim posso navegar entre as paginas somente pelo link construido dentro do botão-->
                        <?php if ($paginaAtual > 1): ?>
                            <a href="?secretaria=<?php echo urlencode($secretariaSelecionada); ?>&pagina=<?php echo $paginaAtual - 1; ?>">Anterior</a>
                        <?php endif; ?>

                        <?php if ($paginaAtual < $totalPaginas): ?>
                            <a href="?secretaria=<?php echo urlencode($secretariaSelecionada); ?>&pagina=<?php echo $paginaAtual + 1; ?>">Próximo</a>
                        <?php endif; ?>

                        <!-- Botão para a Última Página -->
                        <a href="?secretaria=<?php echo urlencode($secretariaSelecionada); ?>&pagina=<?php echo $totalPaginas; ?>">Último</a>
                    </div>
                           
            <?php endif; ?>
            
            <!-- Aqui ha a verificacao se uma secretaria foi selecionada para ocorrer a listagem dos serviços referente a ela, por isso o ! no segundo parametro, pois caso nao for selecionado nenhum
            serviço, sera verdade, ja que seu valor sera null
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
                <div class="pagination">
                    <!-- Cada botao eh um link, onde eh construido uma url para ele, assim posso navegar entre as paginas somente pelo link construido dentro do botão-->
                    <?php if ($paginaAtual > 1): ?>
                        <a href="?secretaria=<?php echo urlencode($secretariaSelecionada); ?>&pagina=<?php echo $paginaAtual - 1; ?>&search=<?php echo urlencode($searchTerm); ?>">Anterior</a>
                    <?php endif; ?>

                    <?php if ($paginaAtual < $totalPaginas): ?>
                        <a href="?secretaria=<?php echo urlencode($secretariaSelecionada); ?>&pagina=<?php echo $paginaAtual + 1; ?>&search=<?php echo urlencode($searchTerm); ?>">Próximo</a>
                    <?php endif; ?>

                    <!-- Botão para a Última Página -->
                    <a href="?secretaria=<?php echo urlencode($secretariaSelecionada); ?>&pagina=<?php echo $totalPaginas; ?>&search=<?php echo urlencode($searchTerm); ?>">Último</a>
                </div>
            <?php elseif ($secretariaSelecionada && $servicoSelecionado): ?>

                <!-- Caso um serviço seja selecionado, lista seu conteudo de acordo com os dados no arquivo .json -->
                <?php
                // Exibe o serviço selecionado
                foreach ($data['secretarias'] as $secretaria)
                {
                    foreach ($secretaria['servicos'] as $servico)
                    {
                        if ($servico['titulo'] === $servicoSelecionado)
                        {
                            echo "<h2>" . htmlspecialchars($servico['titulo']) . "</h2>";
                            echo "<div class='service-detail'>";
                            echo "<dl>";
                            echo "<dt>Descrição do Serviço:</dt><dd>" . htmlspecialchars($servico['descricao']) . "</dd>";
                            echo "<dt>Órgão Responsável:</dt><dd>" . htmlspecialchars($servico['local_de_acesso']) . "</dd>";
                            echo "<dt>Público Alvo:</dt><dd>" . htmlspecialchars($servico['canais_de_acesso_link']) . "</dd>";
                            echo "<dt>Requisitos para Utilização:</dt><dd>" . htmlspecialchars($servico['forma_de_solicitação']) . "</dd>";
                            echo "<dt>Etapas do Processamento:</dt><dd>" . htmlspecialchars($servico['publico_alvo']) . "</dd>";
                            echo "<dt>Custo do Serviço:</dt><dd>" . htmlspecialchars($servico['categoria_do_servico']) . "</dd>";
                            echo "<dt>Prazo Máximo para Atendimento:</dt><dd>" . htmlspecialchars($servico['setor_inicial']) . "</dd>";
                            echo "<dt>Canais de Comunicação ao Usuário:</dt><dd>" . htmlspecialchars($servico['documentos_obrigatorios']) . "</dd>";
                            echo "<dt>Canais de Manifestação do Usuário:</dt><dd>" . htmlspecialchars($servico['legislacao']) . "</dd>";
                            echo "<dt>Compromisso de Atendimento:</dt><dd>" . htmlspecialchars($servico['observacoes']) . "</dd>";
                            echo "<dt>Legislação:</dt><dd>" . htmlspecialchars($servico['tipo']) . "</dd>";
                            echo "<dt>Equipe Responsável:</dt><dd>" . htmlspecialchars($servico['tempo_estimado_dias']) . "</dd>";
                            echo "<dt>Outras Informações:</dt><dd>" . htmlspecialchars($servico['custo_de_servico']) . "</dd>";
                            echo "<dt>Canais de Comunicação:</dt><dd> ". htmlspecialchars($servico['canais_de_comunicacao']) . "</dd>";
                            echo "</dl>";
                            echo "</div>";
                        }
                    }
                }
                ?>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
