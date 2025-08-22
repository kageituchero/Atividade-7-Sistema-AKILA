<?php 
include 'db.php'; 

$id = $_GET['id'];

$sql = "DELETE FROM partidas WHERE id=$id";
if ($conn->query($sql)) {
    echo "Partida excluída!";
} else {
    echo "Erro: " . $conn->error;
}
?>
<a href="read.php">Voltar</a>
