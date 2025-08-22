<?php include 'db.php'; ?>
<form method="POST">
    Time Mandante: <input type="number" name="time_mandante" required><br>
    Time Visitante: <input type="number" name="time_visitante" required><br>
    Data: <input type="date" name="data_partida" required><br>
    <button type="submit">Criar</button>
</form>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $mandante = $_POST['time_mandante'];
    $visitante = $_POST['time_visitante'];
    $data = $_POST['data_partida'];

    if ($mandante == $visitante) {
        echo "Erro: os times não podem ser iguais!";
    } else {
        $sql = "INSERT INTO partidas (time_mandante, time_visitante, data_partida) 
                VALUES ('$mandante','$visitante','$data')";
        if ($conn->query($sql)) {
            echo "Partida criada com sucesso!";
        } else {
            echo "Erro: " . $conn->error;
        }
    }
}
?>
