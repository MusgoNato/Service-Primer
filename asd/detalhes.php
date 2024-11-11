<?php
// Carregar dados JSON
$json = file_get_contents('arquivo.json');
$data = json_decode($json, true);

// Obter a categoria e serviço selecionados
$categoriaSelecionada = isset($_GET['categoria']) ? $_GET['categoria'] : null;
$servicoSelecionado = isset($_GET['servico']) ? $_GET['servico'] : null;

$servicoDetalhes = null;

// Encontrar o serviço selecionado
foreach ($data['categorias'] as $categoria) {
    if ($categoria['nome'] === $categoriaSelecionada) {
        foreach ($categoria['servicos'] as $servico) {
            if ($servico['titulo'] === $servicoSelecionado) {
                $servicoDetalhes = $servico;
                break;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Detalhes do Serviço</title>
    <style>
        /* Estilos CSS */
    </style>
</head>
<body>
    <div class="container">
        <h1>Detalhes do Serviço: <?php echo htmlspecialchars($servicoDetalhes['titulo']); ?></h1>
        <p><strong>Descrição:</strong> <?php echo htmlspecialchars($servicoDetalhes['descricao']); ?></p>
        <p><strong>Órgão Responsável:</strong> <?php echo htmlspecialchars($servicoDetalhes['orgaos_responsaveis']); ?></p>
        <p><strong>Público Alvo:</strong> <?php echo htmlspecialchars($servicoDetalhes['publico_alvo']); ?></p>
        <p><strong>Requisitos:</strong> <?php echo htmlspecialchars($servicoDetalhes['requisitos']); ?></p>
        <p><strong>Etapas:</strong> <?php echo htmlspecialchars($servicoDetalhes['etapas']); ?></p>
        <p><strong>Custo:</strong> <?php echo htmlspecialchars($servicoDetalhes['custo']); ?></p>
        <p><strong>Prazo Máximo para Atendimento:</strong> <?php echo htmlspecialchars($servicoDetalhes['prazo_maximo']); ?></p>
        <p><strong>Canais de Comunicação ao Usuário:</strong> <?php echo htmlspecialchars($servicoDetalhes['canais_comunicacao']); ?></p>
        <p><strong>Canais de Apresentação de Manifestação:</strong> <?php echo htmlspecialchars($servicoDetalhes['manifestacao_usuario']); ?></p>
        <p><strong>Compromisso de Atendimento:</strong> <?php echo htmlspecialchars($servicoDetalhes['compromisso_atendimento']); ?></p>
        <p><strong>Legislação Aplicável:</strong> <?php echo htmlspecialchars($servicoDetalhes['legislacao']); ?></p>
        <p><strong>Equipe Responsável:</strong> <?php echo htmlspecialchars($servicoDetalhes['equipe_responsavel']); ?></p>
        <p><strong>Outras Informações:</strong> <?php echo htmlspecialchars($servicoDetalhes['outras_informacoes']); ?></p>
    </div>
</body>
</html>
