<?php include 'config.php';

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = trim($_POST['usuario']);
    $contraseña = trim($_POST['contraseña']);

    $stmt = $conexion->prepare("SELECT * FROM usuarios WHERE usuario = :usu");
    $stmt->bindParam(':usu', $usuario);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($contraseña, $user['contraseña'])) {
        $_SESSION['usuario'] = $usuario;
        header("Location: menu.php");
        exit;
    } else {
        $error = "❌ Usuario o contraseña incorrectos";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar Sesión — Portal de Ejercicios</title>
    <style>
        * {box-sizing: border-box; font-family: Arial, sans-serif;}
        body {background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; display: flex; align-items: center; justify-content: center; margin: 0;}
        .login-card {background: white; padding: 40px 30px; border-radius: 12px; box-shadow: 0 8px 30px rgba(0,0,0,0.2); width: 100%; max-width: 400px;}
        h1 {text-align: center; color: #333; margin-bottom: 10px; font-size: 22px;}
        h2 {text-align: center; color: #666; font-size: 16px; margin-bottom: 30px; font-weight: normal;}
        .logo {text-align: center; font-size: 50px; margin-bottom: 15px;}
        label {display: block; margin-bottom: 8px; font-weight: bold; color: #555;}
        input {width: 100%; padding: 12px; margin-bottom: 20px; border: 2px solid #ddd; border-radius: 6px; font-size: 16px;}
        input:focus {outline: none; border-color: #667eea;}
        button {width: 100%; padding: 12px; background: #667eea; color: white; border: none; border-radius: 6px; font-size: 18px; font-weight: bold; cursor: pointer; transition: background 0.3s;}
        button:hover {background: #5568d3;}
        .error {background: #ffebee; color: #c62828; padding: 10px; border-radius: 6px; margin-bottom: 20px; text-align: center;}
        .credenciales {margin-top: 20px; padding: 12px; background: #f5f5f5; border-radius: 6px; font-size: 14px; color: #666; text-align: center;}
    </style>
</head>
<body>
    <div class="login-card">
        <div class="logo">🔐</div>
        <h1>Portal de Ejercicios</h1>
        <h2>Iniciar Sesión</h2>
        
        <?php if ($error): ?>
        <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST">
            <label for="usuario">Usuario:</label>
            <input type="text" id="usuario" name="usuario" required>
            
            <label for="contraseña">Contraseña:</label>
            <input type="password" id="contraseña" name="contraseña" required>
            
            <button type="submit">Ingresar</button>
        </form>

        <div class="credenciales">
            <strong>Credenciales de acceso:</strong><br>
            Usuario: <code>admin</code><br>
            Contraseña: <code>123456</code>
        </div>
    </div>
</body>
</html>
