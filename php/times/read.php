<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "db.php";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

$nome = trim($_GET["nome"] ?? "");
$page = max(1, intval($_GET["page"] ?? 1));
$perPage = 5;
$offset = ($page - 1) * $perPage;

$where = "";
$params = [];
$types = "";

if ($nome !== "") {
    $where = "WHERE nome LIKE ?";
    $params[] = "%$nome%";
    $types .= "s";
}

$sqlCount = "SELECT COUNT(*) FROM times $where";
$stmtCount = $conn->prepare($sqlCount);
if ($where !== "") $stmtCount->bind_param($types, ...$params);
$stmtCount->execute();
$stmtCount->bind_result($total);
$stmtCount->fetch();
$stmtCount->close();

$sql = "SELECT id, nome FROM times $where ORDER BY nome LIMIT ? OFFSET ?";
$stmt = $conn->prepare($sql);

if ($where !== "") {
    $typesPage = $types . "ii";
    $paramsPage = [...$params, $perPage, $offset];
    $stmt->bind_param($typesPage, ...$paramsPage);
} else {
    $stmt->bind_param("ii", $perPage, $offset);
}

$stmt->execute();
$result = $stmt->get_result();

$totalPages = max(1, ceil($total / $perPage));
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Listar Times</title>
</head>
<body>
    <h1>Listar Times</h1>

    <form method="get">
        <input type="text" name="nome" placeholder="Filtrar por nome" value="<?= htmlspecialchars($nome) ?>">
        <button type="submit">Filtrar</button>
    </form>
    <br>

    <table border="1" cellpadding="5">
        <tr>
            <th>ID</th>
            <th>Nome</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row["id"] ?></td>
            <td><?= htmlspecialchars($row["nome"]) ?></td>
        </tr>
        <?php endwhile; ?>
    </table>

    <div style="margin-top: 20px;">
        <?php if ($page > 1): ?>
            <a href="?nome=<?= urlencode($nome) ?>&page=<?= $page - 1 ?>">Anterior</a>
        <?php endif; ?>
        Página <?= $page ?> de <?= $totalPages ?>
        <?php if ($page < $totalPages): ?>
            <a href="?nome=<?= urlencode($nome) ?>&page=<?= $page + 1 ?>">Próxima</a>
        <?php endif; ?>
    </div>

    <br>
    <a href="Create.php">Cadastrar novo time</a>
</body>
</html>
