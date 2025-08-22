<?php
include '../db.php';

// Configuração de paginação
$itens_por_pagina = 10;
$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$offset = ($pagina - 1) * $itens_por_pagina;

// Filtros
$filtro_nome = isset($_GET['nome']) ? $_GET['nome'] : '';
$filtro_posicao = isset($_GET['posicao']) ? $_GET['posicao'] : '';
$filtro_time = isset($_GET['id_time']) ? (int)$_GET['id_time'] : '';

// Construir query com filtros
$where = "WHERE 1=1";
if (!empty($filtro_nome)) {
    $where .= " AND j.nome LIKE '%" . $conn->real_escape_string($filtro_nome) . "%'";
}
if (!empty($filtro_posicao)) {
    $where .= " AND j.posicao = '" . $conn->real_escape_string($filtro_posicao) . "'";
}
if (!empty($filtro_time)) {
    $where .= " AND j.id_time = " . $filtro_time;
}

// Buscar jogadores
$sql = "SELECT j.*, t.nome as nome_time 
        FROM jogadores j 
        INNER JOIN times t ON j.id_time = t.id_time 
        $where 
        ORDER BY j.nome 
        LIMIT $offset, $itens_por_pagina";
$result = $conn->query($sql);

// Contar total de registros para paginação
$sql_count = "SELECT COUNT(*) as total FROM jogadores j $where";
$result_count = $conn->query($sql_count);
$total_registros = $result_count->fetch_assoc()['total'];
$total_paginas = ceil($total_registros / $itens_por_pagina);

// Buscar times para filtro
$times_filtro = $conn->query("SELECT id_time, nome FROM times ORDER BY nome");

// Posições para filtro
$posicoes = array("Goleiro", "Zagueiro", "Lateral", "Volante", "Meia", "Atacante");
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Listar Jogadores</title>
<link rel="stylesheet" type="text/css" href="../style/style.css">
</head>
<body>

<div class="container">
    <h1>Lista de Jogadores</h1>
    
    <!-- Filtros -->
    <form method="GET" class="filtros">
        <h3>Filtros</h3>
        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
            <div>
                <label for="nome">Nome:</label>
                <input type="text" id="nome" name="nome" value="<?php echo $filtro_nome; ?>">
            </div>
            
            <div>
                <label for="posicao">Posição:</label>
                <select id="posicao" name="posicao">
                    <option value="">Todas</option>
                    <?php foreach ($posicoes as $p): ?>
                    <option value="<?php echo $p; ?>" <?php echo $filtro_posicao == $p ? 'selected' : ''; ?>>
                        <?php echo $p; ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div>
                <label for="id_time">Time:</label>
                <select id="id_time" name="id_time">
                    <option value="">Todos</option>
                    <?php while($time = $times_filtro->fetch_assoc()): ?>
                    <option value="<?php echo $time['id_time']; ?>" <?php echo $filtro_time == $time['id_time'] ? 'selected' : ''; ?>>
                        <?php echo $time['nome']; ?>
                    </option>
                    <?php endwhile; ?>
                </select>
            </div>
            
            <div style="align-self: flex-end;">
                <button type="submit">Filtrar</button>
                <a class="btn" href="read.php">Limpar</a>
            </div>
        </div>
    </form>
    
    <!-- Tabela de jogadores -->
    <?php
    if ($result->num_rows > 0) {
        echo "<table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Posição</th>
                        <th>Nº Camisa</th>
                        <th>Time</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>{$row['id_jogador']}</td>
                    <td>{$row['nome']}</td>
                    <td>{$row['posicao']}</td>
                    <td>{$row['numero_camisa']}</td>
                    <td>{$row['nome_time']}</td>
                    <td>
                        <a class='btn' href='update.php?id={$row['id_jogador']}'>Editar</a>
                        <a class='btn' href='delete.php?id={$row['id_jogador']}' onclick='return confirm(\"Tem certeza que deseja excluir este jogador?\")'>Excluir</a>
                    </td>
                  </tr>";
        }
        echo "</tbody></table>";
        
        // Paginação
        if ($total_paginas > 1) {
            echo "<div class='paginacao'>";
            if ($pagina > 1) {
                echo "<a class='btn' href='read.php?pagina=" . ($pagina - 1) . "&nome=$filtro_nome&posicao=$filtro_posicao&id_time=$filtro_time'>Anterior</a> ";
            }
            
            for ($i = 1; $i <= $total_paginas; $i++) {
                if ($i == $pagina) {
                    echo "<span class='btn' style='background-color: #5C4033; color: white;'>$i</span> ";
                } else {
                    echo "<a class='btn' href='read.php?pagina=$i&nome=$filtro_nome&posicao=$filtro_posicao&id_time=$filtro_time'>$i</a> ";
                }
            }
            
            if ($pagina < $total_paginas) {
                echo "<a class='btn' href='read.php?pagina=" . ($pagina + 1) . "&nome=$filtro_nome&posicao=$filtro_posicao&id_time=$filtro_time'>Próxima</a>";
            }
            echo "</div>";
        }
    } else {
        echo "<p>Nenhum jogador encontrado.</p>";
    }
    ?>
    
    <a class="btn" href="create.php">Cadastrar Novo Jogador</a>
    <a class="btn" href="index.php">Voltar</a>
</div>

</body>
</html>