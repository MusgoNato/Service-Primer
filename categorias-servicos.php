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
    <li>
        <a href="?pagina=1">Todas as Secretarias</a>
    </li>
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