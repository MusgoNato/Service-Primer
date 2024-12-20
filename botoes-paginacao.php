<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
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
    </body>
</html>