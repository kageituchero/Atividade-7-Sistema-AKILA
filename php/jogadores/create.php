<?php
include '../db.php';
$msg = "";

// Buscar times para o dropdown
$times = $conn->query("SELECT id_time, nome FROM times ORDER BY nome");

// Posições pré-definidas
$posicoes = array("Goleiro", "Zagueiro", "Lateral", "Volante", "Meia", "Atacante");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
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
    
    // Verificar se número já existe no time
    $checkNumero = $conn->query("SELECT id_jogador FROM jogadores WHERE id_time = $id_time AND numero_camisa = $numero_camisa");
    if ($checkNumero->num_rows > 0) {
        $erros[] = "Este número já está em uso por outro jogador do mesmo time.";
    }
    
    if (count($erros) == 0) {
        // Preparar dados para inserção
        $nome = $conn->real_escape_string($nome);
        $posicao = $conn->real_escape_string($posicao);
        $numero_camisa = (int)$numero_camisa;
        $id_time = (int)$id_time;
        
        $sql = "INSERT INTO jogadores (nome, posicao, numero_camisa, id_time) 
                VALUES ('$nome', '$posicao', $numero_camisa, $id_time)";
        
        if ($conn->query($sql) === TRUE) {
            $msg = "Jogador cadastrado com sucesso!";
            // Limpar formulário
            $_POST = array();
        } else {
            $msg = "Erro ao cadastrar jogador: " . $conn->error;
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
<title>Cadastrar Jogador</title>
<link rel="stylesheet" type="text/css" href="../style/style.css">
</head>
<body>

<div class="container">
    <h1>Cadastrar Jogador</h1>
    <?php if($msg) echo "<p class='" . (strpos($msg, "Erro") !== false ? "error-message" : "message") . "'>$msg</p>"; ?>

    <form method="POST">
        <label for="nome">Nome:</label>
        <input type="text" id="nome" name="nome" value="<?php echo isset($_POST['nome']) ? $_POST['nome'] : ''; ?>" required>
        
        <label for="posicao">Posição:</label>
        <select id="posicao" name="posicao" required>
            <option value="">Selecione a posição</option>
            <?php foreach ($posicoes as $p): ?>
            <option value="<?php echo $p; ?>" <?php echo (isset($_POST['posicao']) && $_POST['posicao'] == $p) ? 'selected' : ''; ?>>
                <?php echo $p; ?>
            </option>
            <?php endforeach; ?>
        </select>
        
        <label for="numero_camisa">Número da Camisa (1-99):</label>
        <input type="number" id="numero_camisa" name="numero_camisa" 
               min="1" max="99" 
               value="<?php echo isset($_POST['numero_camisa']) ? $_POST['numero_camisa'] : ''; ?>" required>
        
        <label for="id_time">Time:</label>
        <select id="id_time" name="id_time" required>
            <option value="">Selecione o time</option>
            <?php while($time = $times->fetch_assoc()): ?>
            <option value="<?php echo $time['id_time']; ?>" <?php echo (isset($_POST['id_time']) && $_POST['id_time'] == $time['id_time']) ? 'selected' : ''; ?>>
                <?php echo $time['nome']; ?>
            </option>
            <?php endwhile; ?>
        </select>
        
        <button type="submit">Salvar</button>
    </form>

    <a class="btn" href="index.php">Voltar</a>
</div>

</body>
</html>