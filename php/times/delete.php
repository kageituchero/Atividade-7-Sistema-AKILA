<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "futebol_db";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

$id = intval($_GET["id"] ?? 0);
$erro = "";
$sucesso = "";

$nome_time = "";
if ($id > 0) {
    $stmt = $conn->prepare("SELECT nome FROM times WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($nome_time);
    $stmt->fetch();
    $stmt->close();
    if (!$nome_time) {
        $erro = "Time não encontrado.";
    }
} else {
    $erro = "ID inválido.";
}

$tem_dependencias = false;
if (!$erro && $_SERVER["REQUEST_METHOD"] === "POST") {
    // Verifica jogadores vinculados
    $stmt = $conn->prepare("SELECT COUNT(*) FROM jogadores WHERE time_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($qtd_jogadores);
    $stmt->fetch();
    $stmt->close();
    
    $stmt = $conn->prepare("SELECT COUNT(*) FROM partidas WHERE mandante_id = ? OR visitante_id = ?");
    $stmt->bind_param("ii", $id, $id);
    $stmt->execute();
    $stmt->bind_result($qtd_partidas);
    $stmt->fetch();
    $stmt->close();

    if ($qtd_jogadores > 0 || $qtd_partidas > 0) {
        $tem_dependencias = true;
        $erro = "Não é possível excluir: o time possui jogadores ou partidas vinculados.";
    } else {
        
        $stmt = $conn->prepare("DELETE FROM times WHERE id = ?");
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            $sucesso = "Time excluído com sucesso!";
            $nome_time = "";
        } else {
            $erro = "Erro ao excluir time.";
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Excluir Time</title>
</head>
<body>
    <h1>Excluir Time</h1>
    <?php if ($erro): ?>
        <p style="color:red"><?= $erro ?></p>
    <?php elseif ($sucesso): ?>
        <p style="color:green"><?= $sucesso ?></p>
    <?php elseif ($nome_time): ?>
        <p>Tem certeza que deseja excluir o time <strong><?= htmlspecialchars($nome_time) ?></strong>?</p>
        <form method="post">
            <button type="submit">Confirmar exclusão</button>
            <a href="read.php">Cancelar</a>
        </form>
    <?php endif; ?>

    <?php if (!$nome_time): ?>
        <a href="read.php">Voltar à lista</a>
    <?php endif; ?>
</body>
</html>
