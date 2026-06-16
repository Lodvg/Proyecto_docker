<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit;
}

require 'db.php';

$id = $_GET['id'] ?? null;

if (!$id) {
    header("Location: dashboard.php?error=ID no válido");
    exit;
}

// Obtener estado actual y cambiar al opuesto
$stmt = $pdo->prepare("SELECT estado FROM usuarios WHERE id = ?");
$stmt->execute([$id]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$usuario) {
    header("Location: dashboard.php?error=Usuario no encontrado");
    exit;
}

$nuevo_estado = $usuario['estado'] === 'activo' ? 'inactivo' : 'activo';

$stmt = $pdo->prepare("UPDATE usuarios SET estado = ? WHERE id = ?");
$stmt->execute([$nuevo_estado, $id]);

header("Location: dashboard.php?msg=Estado cambiado a $nuevo_estado correctamente");
exit;
?>

