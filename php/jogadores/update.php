<?php
include '../db.php';
$msg = "";
$jogador = null;

// Buscar times para o dropdown
$times = $conn->query("SELECT id_time, nome FROM times ORDER BY nome");

// Posições pré-definidas
$posicoes = array("Goleiro", "Zagueiro", "Lateral", "Volante", "Meia", "Atacante");

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    
    $sql = "SELECT j.*, t.nome as nome_time 
            FROM jogadores j 
            INNER JOIN times t ON j.id_time = t.id_time 
            WHERE j.id_jogador = $id";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        $jogador = $result->fetch_assoc();
    } else {
        $msg = "Jogador não encontrado.";
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = (int)$_POST['id'];
    $nome = $_POST['nome'];
    $posicao = $_POST['posicao'];
    $numero_camisa = $_POST['numero_camisa'];
    $id_time = $_POST['id_time'];
    
    // Validações
    $erros = array();
    
    if (empty($nome)) {
        $erros[] = "Nome é obrigatório.";
    }
    
    if (!in_array($posicao, $posicoes)) {
        $erros[] = "Posição inválida.";
    }
    
    if (!is_numeric($numero_camisa) || $numero_camisa < 1 || $numero_camisa > 99) {
        $erros[] = "Número da camisa deve ser entre 1 e 99.";
    }
    
    if (empty($id_time)) {
        $erros[] = "Time é obrigatório.";
    }
    
    // Verificar se número já existe no time (excluindo o próprio jogador)
    $checkNumero = $conn->query("SELECT id_jogador FROM jogadores WHERE id_time = $id_time AND numero_camisa = $numero_camisa AND id_jogador != $id");
    if ($checkNumero->num_rows > 0) {
        $erros[] = "Este número já está em uso por outro jogador do mesmo time.";
    }
    
    if (count($erros) == 0) {
        // Preparar dados para atualização
        $nome = $conn->real_escape_string($nome);
        $posicao = $conn->real_escape_string($posicao);
        $numero_camisa = (int)$numero_camisa;
        $id_time = (int)$id_time;
        
        $sql = "UPDATE jogadores 
                SET nome = '$nome', posicao = '$posicao', numero_camisa = $numero_camisa, id_time = $id_time 
                WHERE id_jogador = $id";
        
        if ($conn->query($sql) === TRUE) {
            $msg = "Jogador atualizado com sucesso!";
            
            // Recarregar dados do jogador
            $sql = "SELECT j.*, t.nome as nome_time 
                    FROM jogadores j 
                    INNER JOIN times t ON j.id_time = t.id_time 
                    WHERE j.id_jogador = $id";
            $result = $conn->query($sql);
            $jogador = $result->fetch_assoc();
        } else {
            $msg = "Erro ao atualizar jogador: " . $conn->error;
        }
    } else {
        $msg = "Erros encontrados:<br>" . implode("<br>", $erros);
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Editar Jogador</title>
<link rel="stylesheet" type="text/css" href="../style/style.css">
</head>
<body>

<div class="container">
    <h1>Editar Jogador</h1>
    <?php if($msg) echo "<p class='" . (strpos($msg, "Erro") !== false ? "error-message" : "message") . "'>$msg</p>"; ?>

    <?php if ($jogador): ?>
    <form method="POST">
        <input type="hidden" name="id" value="<?php echo $jogador['id_jogador']; ?>">
        
        <label for="nome">Nome:</label>
        <input type="text" id="nome" name="nome" value="<?php echo $jogador['nome']; ?>" required>
        
        <label for="posicao">Posição:</label>
        <select id="posicao" name="posicao" required>
            <option value="">Selecione a posição</option>
            <?php foreach ($posicoes as $p): ?>
            <option value="<?php echo $p; ?>" <?php echo $jogador['posicao'] == $p ? 'selected' : ''; ?>>
                <?php echo $p; ?>
            </option>
            <?php endforeach; ?>
        </select>
        
        <label for="numero_camisa">Número da Camisa (1-99):</label>
        <input type="number" id="numero_camisa" name="numero_camisa" 
               min="1" max="99" value="<?php echo $jogador['numero_camisa']; ?>" required>
        
        <label for="id_time">Time:</label>
        <select id="id_time" name="id_time" required>
            <option value="">Selecione o time</option>
            <?php 
            $times->data_seek(0); // Reset pointer para reutilizar a query
            while($time = $times->fetch_assoc()): 
            ?>
            <option value="<?php echo $time['id_time']; ?>" <?php echo $jogador['id_time'] == $time['id_time'] ? 'selected' : ''; ?>>
                <?php echo $time['nome']; ?>
            </option>
            <?php endwhile; ?>
        </select>
        
        <button type="submit">Salvar</button>
    </form>
    <?php else: ?>
        <p>Jogador não encontrado para edição.</p>
    <?php endif; ?>
    
    <a class="btn" href="read.php">Voltar à Lista</a>
</div>

</body>
</html>