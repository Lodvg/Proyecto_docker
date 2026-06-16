<?php
session_start();

if (isset($_SESSION['usuario'])) {
    header("Location: dashboard.php");
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require 'db.php';

    $nombre_usuario = trim($_POST['nombre_usuario']);
    $contrasena     = trim($_POST['contrasena']);

    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE nombre_usuario = ? AND estado = 'activo'");
    $stmt->execute([$nombre_usuario]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario && password_verify($contrasena, $usuario['contrasena'])) {
        $_SESSION['usuario'] = $usuario['nombre_usuario'];
        $_SESSION['rol']     = $usuario['rol'];
        $_SESSION['id']      = $usuario['id'];
        header("Location: dashboard.php");
        exit;
    } else {
        $error = "Usuario o contraseña incorrectos, o cuenta inactiva.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restaurante - Login</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Georgia', serif;
            background-color: #1a0a00;
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23c8860a' fill-opacity='0.07'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .login-container {
            background-color: #2c1500;
            border: 2px solid #c8860a;
            border-radius: 12px;
            padding: 40px;
            width: 100%;
            max-width: 420px;
            box-shadow: 0 0 40px rgba(200, 134, 10, 0.3);
        }

        .logo {
            text-align: center;
            margin-bottom: 30px;
        }

        .logo .icono {
            font-size: 50px;
            display: block;
            margin-bottom: 10px;
        }

        .logo h1 {
            color: #c8860a;
            font-size: 26px;
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        .logo p {
            color: #a07040;
            font-size: 13px;
            margin-top: 4px;
            letter-spacing: 1px;
        }

        .divider {
            border: none;
            border-top: 1px solid #c8860a55;
            margin: 20px 0;
        }

        label {
            display: block;
            color: #c8860a;
            font-size: 13px;
            letter-spacing: 1px;
            text-transform: uppercase;
            margin-bottom: 6px;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 12px 15px;
            background-color: #1a0a00;
            border: 1px solid #c8860a66;
            border-radius: 6px;
            color: #f0d080;
            font-size: 15px;
            margin-bottom: 20px;
            transition: border-color 0.3s;
            outline: none;
        }

        input[type="text"]:focus,
        input[type="password"]:focus {
            border-color: #c8860a;
        }

        button[type="submit"] {
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
            transition: background-color 0.3s;
            font-family: 'Georgia', serif;
        }

        button[type="submit"]:hover {
            background-color: #e5a020;
        }

        .error {
            background-color: #4a0a0a;
            border: 1px solid #cc3333;
            color: #ff8080;
            padding: 10px 15px;
            border-radius: 6px;
            font-size: 13px;
            margin-bottom: 20px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="logo">
            <span class="icono">🍽️</span>
            <h1>La Mesa</h1>
            <p>Sistema de Gestión</p>
        </div>
        <hr class="divider">

        <?php if ($error): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST">
            <label for="nombre_usuario">Usuario</label>
            <input type="text" id="nombre_usuario" name="nombre_usuario" placeholder="Ingresa tu usuario" required autofocus>

            <label for="contrasena">Contraseña</label>
            <input type="password" id="contrasena" name="contrasena" placeholder="Ingresa tu contraseña" required>

            <button type="submit">Ingresar</button>
        </form>
    </div>
</body>
</html>
