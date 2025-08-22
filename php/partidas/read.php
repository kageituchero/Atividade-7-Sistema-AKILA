<?php include '../db.php'; ?>

<h2>Lista de Partidas</h2>
<table border="1">
<tr>
    <th>ID</th>
    <th>Mandante</th>
    <th>Visitante</th>
    <th>Data</th>
    <th>Placar</th>
    <th>Ações</th>
</tr>

<?php
$sql = "SELECT * FROM partidas";
$result = $conn->query($sql);

while($row = $result->fetch_assoc()) {
    echo "<tr>
        <td>{$row['id']}</td>
        <td>{$row['time_mandante']}</td>
        <td>{$row['time_visitante']}</td>
        <td>{$row['data_partida']}</td>
        <td>{$row['placar_mandante']} x {$row['placar_visitante']}</td>
        <td>
            <a href='update.php?id={$row['id']}'>Editar</a> | 
            <a href='delete.php?id={$row['id']}'>Excluir</a>
        </td>
    </tr>";
}
?>
</table>
