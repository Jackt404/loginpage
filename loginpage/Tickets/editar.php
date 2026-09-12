<?php
session_start();

if (!isset($_SESSION['usuario'])) { 
    header("Location: ../index.php"); 
    exit(); 
}

include_once('../connection.php');

$id = $_GET['id'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $placa = strtoupper(trim($_POST['placa']));
    $salida = !empty($_POST['fecha_hora_salida']) ? $_POST['fecha_hora_salida'] : null;
    $valor = (float)$_POST['valor_cobrado'];

    // Actualizar solo los campos necesarios del ticket
    $stmt = $conn->prepare("UPDATE tickets SET placa = :placa, fecha_hora_salida = :salida, valor_cobrado = :valor WHERE numero_ticket = :id");

    $stmt->bindParam(':placa', $placa);
    $stmt->bindParam(':salida', $salida);
    $stmt->bindParam(':valor', $valor);
    $stmt->bindParam(':id', $id);

    $stmt->execute();

    header("Location: adminpage.php");
    exit();
}

// Consultar datos del ticket
$stmt = $conn->prepare("SELECT * FROM tickets WHERE numero_ticket = :id");
$stmt->bindParam(':id', $id);
$stmt->execute();
$ticket = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$ticket) {
    header("Location: adminpage.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Ticket</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f1f5f9; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .card { background: white; padding: 30px; border-radius: 8px; width: 350px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input { width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        .btn-update { width: 100%; padding: 10px; background-color: #f59e0b; color: white; border: none; border-radius: 4px; font-weight: bold; cursor: pointer; }
    </style>
</head>
<body>
    <div class="card">
        <h2>Editar Ticket #<?php echo htmlspecialchars($ticket['numero_ticket']); ?></h2><br>
        <form method="POST">
            <div class="form-group">
                <label>Placa:</label>
                <input type="text" name="placa" value="<?php echo htmlspecialchars($ticket['placa']); ?>" required style="text-transform: uppercase;">
            </div>
            <div class="form-group">
                <label>Fecha y Hora Salida:</label>
                <input type="datetime-local" name="fecha_hora_salida" value="<?php echo $ticket['fecha_hora_salida'] ? date('Y-m-d\TH:i', strtotime($ticket['fecha_hora_salida'])) : ''; ?>">
            </div>
            <div class="form-group">
                <label>Valor Cobrado ($):</label>
                <input type="number" step="0.01" name="valor_cobrado" value="<?php echo htmlspecialchars($ticket['valor_cobrado']); ?>">
            </div>
            <button type="submit" class="btn-update">Actualizar Ticket</button>
        </form>
    </div>
</body>
</html>