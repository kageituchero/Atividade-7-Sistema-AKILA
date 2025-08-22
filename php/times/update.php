<?php
include 'db.php';
$msg = "";
$produto = null;

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "SELECT * FROM produtos WHERE id_produto=$id";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $produto = $result->fetch_assoc();
    } else {
        $msg = "Produto não encontrado.";
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $nome = $_POST['nome'];
    $descricao = $_POST['descricao'];
    $preco = $_POST['preco'];
    $quantidade = $_POST['quantidade'];
    $id_usuario = $_POST['id_usuario'];

    $sql = "UPDATE produtos SET nome='$nome', descricao='$descricao', preco='$preco', quantidade_estoque='$quantidade', id_usuario='$id_usuario' WHERE id_produto=$id";

    if ($conn->query($sql) === TRUE) {
        $msg = "Produto atualizado com sucesso!";
        $sql = "SELECT * FROM produtos WHERE id_produto=$id";
        $result = $conn->query($sql);
        $produto = $result->fetch_assoc();
    } else {
        $msg = "Erro ao atualizar produto: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Atualizar Produto</title>
<link rel="stylesheet" type="text/css" href="../style/style.css">
</head>
<body>

<div class="container">
    <h1>Atualizar Produto</h1>
    <?php if($msg) echo "<p class='" . (strpos($msg, "Erro") !== false ? "error-message" : "message") . "'>$msg</p>"; ?>

    <?php if ($produto): ?>
    <form method="POST">
        <input type="hidden" name="id" value="<?php echo $produto['id_produto']; ?>">
        
        <label for="nome">Nome:</label>
        <input type="text" id="nome" name="nome" value="<?php echo $produto['nome']; ?>" required>
        
        <label for="descricao">Descrição:</label>
        <textarea id="descricao" name="descricao" required><?php echo $produto['descricao']; ?></textarea>
        
        <label for="preco">Preço:</label>
        <input type="number" step="0.01" id="preco" name="preco" value="<?php echo $produto['preco']; ?>" required>
        
        <label for="quantidade">Quantidade:</label>
        <input type="number" id="quantidade" name="quantidade" value="<?php echo $produto['quantidade_estoque']; ?>" required>
        
        <label for="id_usuario">ID Usuário:</label>
        <input type="number" id="id_usuario" name="id_usuario" value="<?php echo $produto['id_usuario']; ?>" required>
        
        <button type="submit">Salvar</button>
    </form>
    <?php else: ?>
        <p>Produto não encontrado para atualização.</p>
    <?php endif; ?>
    <a class="btn" href="../index.php">Voltar</a>
</div>

</body>
</html>
