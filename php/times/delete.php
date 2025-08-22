<?php
include 'db.php';
$msg = "";

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "DELETE FROM produtos WHERE id_produto=$id";
    if ($conn->query($sql) === TRUE) {
        $msg = "Produto excluído com sucesso!";
    } else {
        $msg = "Erro ao excluir produto: " . $conn->error;
    }
} else {
    $msg = "ID do produto não fornecido.";
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Excluir Produto</title>
<link rel="stylesheet" type="text/css" href="../style/style.css">
</head>
<body>

<div class="container">
    <h1>Excluir Produto</h1>
    <?php if($msg) echo "<p class='" . (strpos($msg, "Erro") !== false ? "error-message" : "message") . "'>$msg</p>"; ?>
    <a class="btn" href="../index.php">Voltar</a>
</div>

</body>
</html>
