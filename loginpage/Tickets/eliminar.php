<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: ../index.php");
    exit();
}

include_once('../connection.php');

$id = $_GET['id'] ?? null;
$placa = $_GET['placa'] ?? null;

if (!empty($id)) {
    // Eliminar por número de ticket
    $stmt = $conn->prepare("DELETE FROM tickets WHERE numero_ticket = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
} elseif (!empty($placa)) {
    // Si no tiene número de ticket, eliminar por placa
    $stmt = $conn->prepare("DELETE FROM tickets WHERE placa = :placa");
    $stmt->bindParam(':placa', $placa);
    $stmt->execute();
}

header("Location: adminpage.php");
exit();
?>