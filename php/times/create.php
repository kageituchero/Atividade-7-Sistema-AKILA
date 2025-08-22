<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "futebol";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

$erro = "";
$sucesso = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nome = trim($_POST["nome"] ?? "");

    if (empty($nome)) {
        $erro = "O nome do time é obrigatório!";
    } else {
        $stmt = $conn->prepare("INSERT INTO times (nome) VALUES (?)");
        $stmt->bind_param("s", $nome);

        if ($stmt->execute()) {
            $sucesso = "Time cadastrado com sucesso!";
        } else {
            $erro = "Erro ao cadastrar time: " . $conn->error;
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastrar Time</title>
</head>
<body>
    <h1>Cadastrar Time</h1>
    <?php if ($erro): ?>
        <p style="color: red"><?= $erro ?></p>
    <?php endif; ?>
    <?php if ($sucesso): ?>
        <p style="color: green"><?= $sucesso ?></p>
    <?php endif; ?>

    <form method="post">
        <label for="nome">Nome do Time:</label>
        <input type="text" id="nome" name="nome" required>
        <button type="submit">Cadastrar</button>
    </form>
    <br>
    <a href="index.php">Voltar</a>
</body>
</html>
