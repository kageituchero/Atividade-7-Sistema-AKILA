<?php
include 'db.php';
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Ver Produtos</title>
<link rel="stylesheet" type="text/css" href="../style/style.css">
</head>
<body>

<div class="container">
    <h1>Produtos Cadastrados</h1>
    <?php
    $sql = "SELECT * FROM produtos";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Descrição</th>
                        <th>Preço</th>
                        <th>Qtd Estoque</th>
                        <th>ID Usuário</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>{$row['id_produto']}</td>
                    <td>{$row['nome']}</td>
                    <td>{$row['descricao']}</td>
                    <td>{$row['preco']}</td>
                    <td>{$row['quantidade_estoque']}</td>
                    <td>{$row['id_usuario']}</td>
                    <td>
                        <a class='btn' href='update.php?id={$row['id_produto']}'>Editar</a>
                        <a class='btn' href='delete.php?id={$row['id_produto']}' onclick='return confirm(\"Tem certeza que deseja excluir este produto?\")'>Excluir</a>
                    </td>
                  </tr>";
        }
        echo "</tbody></table>";
    } else {
        echo "<p>Nenhum produto encontrado.</p>";
    }
    ?>
    <a class="btn" href="../index.php">Voltar</a>
</div>

</body>
</html>
