<?php
include '../db.php';
$msg = "";

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    
    // Verificar se há dependências (participação em partidas, etc.)
    // Neste exemplo, não há tabelas que referenciam jogadores, então podemos excluir diretamente
    
    $sql = "DELETE FROM jogadores WHERE id_jogador = $id";
    
    if ($conn->query($sql) === TRUE) {
        $msg = "Jogador excluído com sucesso!";
    } else {
        $msg = "Erro ao excluir jogador: " . $conn->error;
    }
} else {
    $msg = "ID do jogador não fornecido.";
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Excluir Jogador</title>
<link rel="stylesheet" type="text/css" href="../style/style.css">
</head>
<body>

<div class="container">
    <h1>Excluir Jogador</h1>
    <?php if($msg) echo "<p class='" . (strpos($msg, "Erro") !== false ? "error-message" : "message") . "'>$msg</p>"; ?>
    
    <a class="btn" href="read.php">Voltar à Lista</a>
</div>

</body>
</html>