<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit;
}

require 'db.php';

$usuario = null;
$es_edicion = false;

if (isset($_GET['id'])) {
    $es_edicion = true;
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE id = ?");
    $stmt->execute([$_GET['id']]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$usuario) {
        header("Location: dashboard.php?error=Usuario no encontrado");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $es_edicion ? 'Editar' : 'Crear' ?> Usuario - La Mesa</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Georgia', serif;
            background-color: #1a0a00;
            color: #f0d080;
            min-height: 100vh;
        }

        header {
            background-color: #2c1500;
            border-bottom: 2px solid #c8860a;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        header h1 { color: #c8860a; font-size: 22px; letter-spacing: 2px; }

        .btn-volver {
            background-color: #2c1500;
            color: #c8860a;
            border: 1px solid #c8860a55;
            padding: 8px 16px;
            border-radius: 6px;
            cursor: pointer;
            font-family: 'Georgia', serif;
            font-size: 13px;
            text-decoration: none;
        }
        .btn-volver:hover { background-color: #c8860a22; }

        .container {
            padding: 40px 30px;
            max-width: 550px;
            margin: 0 auto;
        }

        h2 {
            color: #c8860a;
            font-size: 20px;
            margin-bottom: 25px;
            letter-spacing: 1px;
            border-bottom: 1px solid #c8860a44;
            padding-bottom: 10px;
        }

        .form-group { margin-bottom: 20px; }

        label {
            display: block;
            color: #c8860a;
            font-size: 13px;
            letter-spacing: 1px;
            text-transform: uppercase;
            margin-bottom: 6px;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"],
        select {
            width: 100%;
            padding: 12px 15px;
            background-color: #1a0a00;
            border: 1px solid #c8860a66;
            border-radius: 6px;
            color: #f0d080;
            font-size: 15px;
            font-family: 'Georgia', serif;
            outline: none;
            transition: border-color 0.3s;
        }

        input:focus, select:focus { border-color: #c8860a; }

        select option { background-color: #2c1500; }

        .hint {
            font-size: 12px;
            color: #a07040;
            margin-top: 5px;
        }

        .btn-guardar {
            width: 100%;
            padding: 13px;
            background-color: #c8860a;
            color: #1a0a00;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            font-weight: bold;
            letter-spacing: 2px;
            text-transform: uppercase;
            cursor: pointer;
            font-family: 'Georgia', serif;
            transition: background-color 0.3s;
            margin-top: 10px;
        }

        .btn-guardar:hover { background-color: #e5a020; }
    </style>
</head>
<body>

<header>
    <h1>🍽️ La Mesa &mdash; <?= $es_edicion ? 'Editar' : 'Crear' ?> Usuario</h1>
    <a href="dashboard.php" class="btn-volver">← Volver</a>
</header>

<div class="container">
    <h2><?= $es_edicion ? 'Modificar datos del usuario' : 'Nuevo usuario' ?></h2>

    <form method="POST" action="guardar.php">

        <?php if ($es_edicion): ?>
            <input type="hidden" name="id" value="<?= $usuario['id'] ?>">
        <?php endif; ?>

        <div class="form-group">
            <label for="nombre_usuario">Nombre de Usuario</label>
            <input type="text" id="nombre_usuario" name="nombre_usuario"
                   value="<?= htmlspecialchars($usuario['nombre_usuario'] ?? '') ?>" required>
        </div>

        <div class="form-group">
            <label for="correo">Correo</label>
            <input type="email" id="correo" name="correo"
                   value="<?= htmlspecialchars($usuario['correo'] ?? '') ?>" required>
        </div>

        <div class="form-group">
            <label for="contrasena">Contraseña</label>
            <input type="password" id="contrasena" name="contrasena"
                   placeholder="<?= $es_edicion ? 'Dejar vacío para no cambiar' : 'Ingresa la contraseña' ?>">
            <?php if ($es_edicion): ?>
                <p class="hint">Si no deseas cambiar la contraseña, deja este campo vacío.</p>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label for="confirmar_contrasena">Confirmar Contraseña</label>
            <input type="password" id="confirmar_contrasena" name="confirmar_contrasena"
                   placeholder="Repite la contraseña">
        </div>

        <div class="form-group">
            <label for="rol">Rol</label>
            <select id="rol" name="rol">
                <option value="usuario" <?= ($usuario['rol'] ?? '') === 'usuario' ? 'selected' : '' ?>>Usuario</option>
                <option value="admin"   <?= ($usuario['rol'] ?? '') === 'admin'   ? 'selected' : '' ?>>Admin</option>
            </select>
        </div>

        <div class="form-group">
            <label for="estado">Estado</label>
            <select id="estado" name="estado">
                <option value="activo"   <?= ($usuario['estado'] ?? 'activo') === 'activo'   ? 'selected' : '' ?>>Activo</option>
                <option value="inactivo" <?= ($usuario['estado'] ?? '') === 'inactivo' ? 'selected' : '' ?>>Inactivo</option>
            </select>
        </div>

        <button type="submit" class="btn-guardar">
            <?= $es_edicion ? 'Guardar Cambios' : 'Crear Usuario' ?>
        </button>

    </form>
</div>

</body>
</html>
