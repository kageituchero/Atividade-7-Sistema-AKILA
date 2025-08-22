<?php 
include '../db.php'; 

$id = $_GET['id'];
$sql = "SELECT * FROM partidas WHERE id=$id";
$res = $conn->query($sql);
$partida = $res->fetch_assoc();
?>

<form method="POST">
    Time Mandante: <input type="number" name="time_mandante" value="<?= $partida['time_mandante'] ?>" required><br>
    Time Visitante: <input type="number" name="time_visitante" value="<?= $partida['time_visitante'] ?>" required><br>
    Data: <input type="date" name="data_partida" value="<?= $partida['data_partida'] ?>" required><br>
    Placar Mandante: <input type="number" name="placar_mandante" value="<?= $partida['placar_mandante'] ?>"><br>
    Placar Visitante: <input type="number" name="placar_visitante" value="<?= $partida['placar_visitante'] ?>"><br>
    <button type="submit">Atualizar</button>
</form>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $mandante = $_POST['time_mandante'];
    $visitante = $_POST['time_visitante'];
    $data = $_POST['data_partida'];
    $pm = $_POST['placar_mandante'];
    $pv = $_POST['placar_visitante'];

    if ($mandante == $visitante) {
        echo "Erro: os times não podem ser iguais!";
    } else {
        $sql = "UPDATE partidas SET 
                    time_mandante='$mandante',
                    time_visitante='$visitante',
                    data_partida='$data',
                    placar_mandante='$pm',
                    placar_visitante='$pv'
                WHERE id=$id";
        if ($conn->query($sql)) {
            echo "Partida atualizada!";
        } else {
            echo "Erro: " . $conn->error;
        }
    }
}
?>
