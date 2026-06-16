<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit;
}

require 'db.php';

$nombre_usuario       = trim($_POST['nombre_usuario']);
$correo               = trim($_POST['correo']);
$contrasena           = trim($_POST['contrasena']);
$confirmar_contrasena = trim($_POST['confirmar_contrasena']);
$rol                  = $_POST['rol'];
$estado               = $_POST['estado'];
$id                   = $_POST['id'] ?? null;

// Validar que las contraseñas coincidan si se ingresó una
if (!empty($contrasena) && $contrasena !== $confirmar_contrasena) {
    header("Location: " . ($id ? "crear.php?id=$id" : "crear.php") . "&error=Las contraseñas no coinciden");
    exit;
}

if ($id) {
    // EDITAR
    if (!empty($contrasena)) {
        $hash = password_hash($contrasena, PASSWORD_BCRYPT);
        $stmt = $pdo->prepare("UPDATE usuarios SET nombre_usuario=?, correo=?, contrasena=?, rol=?, estado=? WHERE id=?");
        $stmt->execute([$nombre_usuario, $correo, $hash, $rol, $estado, $id]);
    } else {
        $stmt = $pdo->prepare("UPDATE usuarios SET nombre_usuario=?, correo=?, rol=?, estado=? WHERE id=?");
        $stmt->execute([$nombre_usuario, $correo, $rol, $estado, $id]);
    }
    header("Location: dashboard.php?msg=Usuario actualizado correctamente");
} else {
    // CREAR
    if (empty($contrasena)) {
        header("Location: crear.php?error=La contraseña es obligatoria");
        exit;
    }
    $hash = password_hash($contrasena, PASSWORD_BCRYPT);
    $stmt = $pdo->prepare("INSERT INTO usuarios (nombre_usuario, correo, contrasena, rol, estado) VALUES (?,?,?,?,?)");
    $stmt->execute([$nombre_usuario, $correo, $hash, $rol, $estado]);
    header("Location: dashboard.php?msg=Usuario creado correctamente");
}
exit;
?>

