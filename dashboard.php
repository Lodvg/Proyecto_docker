<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit;
}

require 'db.php';

$filtro = $_GET['filtro'] ?? 'todos';

if ($filtro === 'activos') {
    $stmt = $pdo->query("SELECT * FROM usuarios WHERE estado = 'activo'");
} elseif ($filtro === 'inactivos') {
    $stmt = $pdo->query("SELECT * FROM usuarios WHERE estado = 'inactivo'");
} else {
    $stmt = $pdo->query("SELECT * FROM usuarios");
}

$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Usuarios - La Mesa</title>
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

        header h1 {
            color: #c8860a;
            font-size: 22px;
            letter-spacing: 2px;
        }

        header span {
            color: #a07040;
            font-size: 14px;
        }

        .btn-logout {
            background-color: #4a0a0a;
            color: #ff8080;
            border: 1px solid #cc3333;
            padding: 8px 16px;
            border-radius: 6px;
            cursor: pointer;
            font-family: 'Georgia', serif;
            font-size: 13px;
            text-decoration: none;
        }

        .btn-logout:hover { background-color: #6a1010; }

        .container {
            padding: 30px;
            max-width: 1100px;
            margin: 0 auto;
        }

        .acciones {
            display: flex;
            gap: 10px;
            margin-bottom: 25px;
            flex-wrap: wrap;
            align-items: center;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-family: 'Georgia', serif;
            font-size: 14px;
            text-decoration: none;
            display: inline-block;
            letter-spacing: 1px;
            transition: background-color 0.2s;
        }

        .btn-crear {
            background-color: #c8860a;
            color: #1a0a00;
            font-weight: bold;
        }
        .btn-crear:hover { background-color: #e5a020; }

        .btn-filtro {
            background-color: #2c1500;
            color: #c8860a;
            border: 1px solid #c8860a55;
        }
        .btn-filtro:hover, .btn-filtro.activo {
            background-color: #c8860a22;
            border-color: #c8860a;
        }

        .btn-editar {
            background-color: #1a3a5c;
            color: #80c0ff;
            border: 1px solid #4080cc55;
            padding: 6px 12px;
            font-size: 12px;
        }
        .btn-editar:hover { background-color: #1a3a5c99; }

        .btn-eliminar {
            background-color: #4a0a0a;
            color: #ff8080;
            border: 1px solid #cc333355;
            padding: 6px 12px;
            font-size: 12px;
        }
        .btn-eliminar:hover { background-color: #6a1010; }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #2c1500;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 0 20px rgba(200, 134, 10, 0.15);
        }

        thead {
            background-color: #c8860a;
            color: #1a0a00;
        }

        thead th {
            padding: 14px 16px;
            text-align: left;
            font-size: 13px;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        tbody tr {
            border-bottom: 1px solid #c8860a22;
            transition: background-color 0.2s;
        }

        tbody tr:hover { background-color: #3a1f00; }

        tbody td {
            padding: 12px 16px;
            font-size: 14px;
            color: #e0c070;
        }

        .badge {
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .badge-activo {
            background-color: #0a3a0a;
            color: #50d050;
            border: 1px solid #50d05055;
        }

        .badge-inactivo {
            background-color: #3a0a0a;
            color: #d05050;
            border: 1px solid #d0505055;
        }

        .badge-admin {
            background-color: #c8860a22;
            color: #c8860a;
            border: 1px solid #c8860a55;
        }

        .badge-usuario {
            background-color: #1a3a5c44;
            color: #80c0ff;
            border: 1px solid #4080cc55;
        }

        .td-acciones { display: flex; gap: 8px; }

        .mensaje {
            padding: 12px 16px;
            border-radius: 6px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .mensaje-ok {
            background-color: #0a3a0a;
            color: #50d050;
            border: 1px solid #50d05055;
        }

        .mensaje-error {
            background-color: #4a0a0a;
            color: #ff8080;
            border: 1px solid #cc333355;
        }
    </style>
</head>
<body>

<header>
    <h1>🍽️ La Mesa &mdash; Gestión de Usuarios</h1>
    <div>
        <span>Bienvenido, <?= htmlspecialchars($_SESSION['usuario']) ?> (<?= $_SESSION['rol'] ?>)</span>
        &nbsp;&nbsp;
        <a href="logout.php" class="btn-logout">Cerrar sesión</a>
    </div>
</header>

<div class="container">

    <?php if (isset($_GET['msg'])): ?>
        <div class="mensaje mensaje-ok"><?= htmlspecialchars($_GET['msg']) ?></div>
    <?php endif; ?>

    <?php if (isset($_GET['error'])): ?>
        <div class="mensaje mensaje-error"><?= htmlspecialchars($_GET['error']) ?></div>
    <?php endif; ?>

    <div class="acciones">
        <a href="crear.php" class="btn btn-crear">+ Crear Usuario</a>
        <a href="dashboard.php?filtro=todos"     class="btn btn-filtro <?= $filtro === 'todos'     ? 'activo' : '' ?>">Todos</a>
        <a href="dashboard.php?filtro=activos"   class="btn btn-filtro <?= $filtro === 'activos'   ? 'activo' : '' ?>">Activos</a>
        <a href="dashboard.php?filtro=inactivos" class="btn btn-filtro <?= $filtro === 'inactivos' ? 'activo' : '' ?>">Inactivos</a>
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Usuario</th>
                <th>Correo</th>
                <th>Rol</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($usuarios) === 0): ?>
                <tr>
                    <td colspan="6" style="text-align:center; color:#a07040; padding: 30px;">
                        No hay usuarios para mostrar.
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach ($usuarios as $u): ?>
                <tr>
                    <td><?= $u['id'] ?></td>
                    <td><?= htmlspecialchars($u['nombre_usuario']) ?></td>
                    <td><?= htmlspecialchars($u['correo']) ?></td>
                    <td><span class="badge badge-<?= $u['rol'] ?>"><?= $u['rol'] ?></span></td>
                    <td><span class="badge badge-<?= $u['estado'] ?>"><?= $u['estado'] ?></span></td>
                    <td>
                        <div class="td-acciones">
                            <a href="crear.php?id=<?= $u['id'] ?>" class="btn btn-editar">Editar</a>
                            <a href="eliminar.php?id=<?= $u['id'] ?>" class="btn btn-eliminar"
                               onclick="return confirm('¿Cambiar estado de este usuario?')">
                               <?= $u['estado'] === 'activo' ? 'Desactivar' : 'Activar' ?>
                            </a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

</body>
</html>
