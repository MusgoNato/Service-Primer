<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <?php
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
                        echo "<dt>Canais de Comunicação:</dt><dd> " . htmlspecialchars($servico['canais_de_comunicacao']) . "</dd>";
                        echo "</dl>";
                        echo "</div>";
                    }
                }
            }
        ?>
    </body>
</html>