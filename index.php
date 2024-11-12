<?php
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

// Aqui eh criado um vetor, contera os servicos referente a qual secretaria for selecionada
$servicos = [];
foreach ($data['secretarias'] as $secretaria) {

    //Como foi validado pelo isset acima que esta variavel existe, entt comparo com a secretaria que foi selecionada, caso encontrado, tenho os serviços referente a secretaria selecionada.
    //Secretaria eh um vetor, entt existe um ou mais serviços, isso no arquivo .json, sendo a base de dados.
    if ($secretaria['nome'] === $secretariaSelecionada) {
        $servicos = $secretaria['servicos'];
        break;
    }
}

// Calcula as paginas para apresentação, serviços eh um vetor, entao somente conto os valores dentro do vetor
$totalServicos = count($servicos);

// Particiona o numero de paginas para nao ser pesado da propria pagina carregar o conteudo
$totalPaginas = ceil($totalServicos / $servicosPorPagina); 
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
            <!-- Aqui ha a verificacao se uma secretaria foi selecionada para ocorrer a listagem dos serviços referente a ela, por isso o ! no segundo parametro, pois caso nao for selecionado nenhum
            serviço, sera verdade, ja que seu valor sera null -->
            <?php if ($secretariaSelecionada && !$servicoSelecionado): ?>
                <h2>Serviços em <?php echo htmlspecialchars($secretariaSelecionada); ?></h2>
                <ul>
                    <?php
                    // Exibir os serviços da secretaria selecionada, respeitando a paginação. Obs: Precisa ser -1 o offset para nao acessar um elemento fora do array que vai de [0, total de paginas];
                    $servicosPagina = array_slice($servicos, ($paginaAtual - 1) * $servicosPorPagina, $servicosPorPagina);

                    //Como o vetor foi cortado de acordo com a pagina atual que o usuario esta, entao obtenho um vetor cortado, varro ele pois sera as paginas referente a secretaria selecionada
                    foreach ($servicosPagina as $servico) {
                        echo "<li><h3><a href='?secretaria=" . urlencode($secretariaSelecionada) . "&servico=" . urlencode($servico['titulo']) . "'>" . htmlspecialchars($servico['titulo']) . "</a></h3>";
                        echo "<p>" . htmlspecialchars($servico['descricao']) . "</p></li>";
                    }
                    ?>
                </ul>

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
            <?php elseif ($secretariaSelecionada && $servicoSelecionado): ?>

                <!-- Caso um serviço seja selecionado, lista seu conteudo de acordo com os dados no arquivo .json -->
                <?php
                // Exibe o serviço selecionado
                foreach ($data['secretarias'] as $secretaria) {
                    if ($secretaria['nome'] === $secretariaSelecionada) {
                        foreach ($secretaria['servicos'] as $servico) {
                            if ($servico['titulo'] === $servicoSelecionado) {
                                echo "<h2>" . htmlspecialchars($servico['titulo']) . "</h2>";
                                echo "<div class='service-detail'>";
                                echo "<dl>";
                                echo "<dt>Descrição do Serviço:</dt><dd>" . htmlspecialchars($servico['descricao']) . "</dd>";
                                echo "<dt>Órgão Responsável:</dt><dd>" . htmlspecialchars($servico['orgao_responsavel']) . "</dd>";
                                echo "<dt>Público Alvo:</dt><dd>" . htmlspecialchars($servico['publico_alvo']) . "</dd>";
                                echo "<dt>Requisitos para Utilização:</dt><dd>" . htmlspecialchars($servico['requisitos']) . "</dd>";
                                echo "<dt>Etapas do Processamento:</dt><dd>" . htmlspecialchars($servico['etapas_processamento']) . "</dd>";
                                echo "<dt>Custo do Serviço:</dt><dd>" . htmlspecialchars($servico['custo']) . "</dd>";
                                echo "<dt>Prazo Máximo para Atendimento:</dt><dd>" . htmlspecialchars($servico['prazo_maximo']) . "</dd>";
                                echo "<dt>Canais de Comunicação ao Usuário:</dt><dd>" . htmlspecialchars($servico['canais_comunicacao']) . "</dd>";
                                echo "<dt>Canais de Manifestação do Usuário:</dt><dd>" . htmlspecialchars($servico['manifestacoes_usuario']) . "</dd>";
                                echo "<dt>Compromisso de Atendimento:</dt><dd>" . htmlspecialchars($servico['compromisso_atendimento']) . "</dd>";
                                echo "<dt>Legislação:</dt><dd>" . htmlspecialchars($servico['legislacao']) . "</dd>";
                                echo "<dt>Equipe Responsável:</dt><dd>" . htmlspecialchars($servico['equipe_responsavel']) . "</dd>";
                                echo "<dt>Outras Informações:</dt><dd>" . htmlspecialchars($servico['outras_informacoes']) . "</dd>";
                                echo "</dl>";
                                echo "</div>";
                            }
                        }
                    }
                }
                ?>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
