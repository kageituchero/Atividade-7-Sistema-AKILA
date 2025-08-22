<?php
// Conexão com o banco de dados
$host = "localhost";
$user = "root";
$pass = "";
$db = "db.php";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

$id = intval($_GET["id"] ?? 0);
$erro = "";
$sucesso = "";

// Buscar dados atuais do time
$nome = "";
if ($id > 0) {
    $stmt = $conn->prepare("SELECT nome FROM times WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($nome);
    $stmt->fetch();
    $stmt->close();

    if (!$nome) {
        $erro = "Time não encontrado.";
    }
} else {
    $erro = "ID inválido.";
}

// Atualizar dados ao receber POST
if (!$erro && $_SERVER["REQUEST_METHOD"] === "POST") {
    $novo_nome = trim($_POST["nome"] ?? "");

    if (empty($novo_nome)) {
        $erro = "O nome do time é obrigatório!";
    } else {
        $stmt = $conn->prepare("UPDATE times SET nome = ? WHERE id = ?");
        $stmt->bind_param("si", $novo_nome, $id);

        if ($stmt->execute()) {
            $sucesso = "Time atualizado com sucesso!";
            $nome = $novo_nome;
        } else {
            $erro = "Erro ao atualizar o time.";
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Editar Time</title>
</head>
<body>
    <h1>Editar Time</h1>
    <?php if ($erro): ?>
        <p style="color:red"><?= $erro ?></p>
    <?php elseif ($sucesso): ?>
        <p style="color:green"><?= $sucesso ?></p>
    <?php endif; ?>

    <?php if (!$erro): ?>
        <form method="post">
            <label for="nome">Nome do Time:</label>
            <input type="text" id="nome" name="nome" value="<?= htmlspecialchars($nome) ?>" required>
            <button type="submit">Salvar</button>
            <a href="read.php">Cancelar</a>
        </form>
    <?php else: ?>
        <a href="read.php">Voltar à lista</a>
    <?php endif; ?>
</body>
</html>
