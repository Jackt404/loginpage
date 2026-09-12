<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: ../index.php");
    exit();
}
include_once('../connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $placa = strtoupper(trim($_POST['placa']));
    $modelo = $_POST['modelo'] !== '' ? (int)$_POST['modelo'] : null;
    $color = trim($_POST['color']);
    $estado = trim($_POST['estado_vehiculo']);

    $stmt = $conn->prepare("INSERT INTO vehiculos (placa, modelo, color, estado_vehiculo) VALUES (:placa, :modelo, :color, :estado)");
    $stmt->bindParam(':placa', $placa);
    $stmt->bindParam(':modelo', $modelo);
    $stmt->bindParam(':color', $color);
    $stmt->bindParam(':estado', $estado);
    $stmt->execute();

    header("Location: adminpage.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nuevo Vehículo</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f1f5f9; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .card { background: white; padding: 30px; border-radius: 8px; width: 350px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input { width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        .btn-save { width: 100%; padding: 10px; background-color: #10b981; color: white; border: none; border-radius: 4px; font-weight: bold; cursor: pointer; }
    </style>
</head>
<body>
    <div class="card">
        <h2>Registrar Vehículo</h2><br>
        <form method="POST">
            <div class="form-group">
                <label>Placa:</label>
                <input type="text" name="placa" maxlength="6" required style="text-transform: uppercase;">
            </div>
            <div class="form-group">
                <label>Modelo (Año):</label>
                <input type="number" name="modelo" placeholder="Ej: 2022">
            </div>
            <div class="form-group">
                <label>Color:</label>
                <input type="text" name="color">
            </div>
            <div class="form-group">
                <label>Estado del Vehículo:</label>
                <input type="text" name="estado_vehiculo" placeholder="Ej: Bueno, Rayon en puerta">
            </div>
            <button type="submit" class="btn-save">Guardar Vehículo</button>
        </form>
    </div>
</body>
</html>