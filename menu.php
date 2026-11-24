<?php include 'config.php';

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit;
}

$stmt = $conexion->query("SELECT * FROM menu ORDER BY orden ASC, nombre ASC");
$menuItems = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Menú Principal — Portal de Ejercicios</title>
    <style>
        * {margin: 0; padding: 0; box-sizing: border-box; font-family: Arial, sans-serif;}
        body {background: #f8fafc; min-height: 100vh;}
        .header {background: linear-gradient(135deg, #2563eb 0%, #7c3aed 100%); color: white; padding: 30px; text-align: center;}
        .header h1 {font-size: 26px; margin-bottom: 8px;}
        .header p {opacity: 0.9; font-size: 15px;}
        .container {max-width: 800px; margin: 30px auto; padding: 0 20px;}
        .welcome {background: white; padding: 25px; border-radius: 16px; margin-bottom: 25px; box-shadow: 0 4px 20px rgba(37, 99, 235, 0.12); border-left: 5px solid #2563eb;}
        .welcome h2 {color: #1e40af; font-size: 22px;}
        .menu-grid {display: grid; gap: 14px;}
        .menu-item {background: white; padding: 20px 24px; border-radius: 14px; box-shadow: 0 3px 15px rgba(0,0,0,0.06); transition: all 0.3s; text-decoration: none; color: inherit; display: flex; align-items: center; justify-content: space-between;}
        .menu-item:hover {transform: translateY(-3px); box-shadow: 0 8px 25px rgba(37, 99, 235, 0.2); border-left: 4px solid #2563eb;}
        .menu-item span {font-size: 17px; font-weight: 500; color: #334155;}
        .menu-item .icon {font-size: 22px;}
        .salir {border-left: 4px solid #ef4444;}
        .salir span {color: #ef4444;}
        .externo::after {content: "↗"; font-size: 13px; color: #94a3b8; margin-left: 8px;}
    </style>
</head>
<body>
    <div class="header">
        <h1>📚 Portal Integrado de Ejercicios</h1>
        <p>Laboratorio de Lenguaje de Programación III — Dorka Portalatin Feliz</p>
    </div>

    <div class="container">
        <div class="welcome">
            <h2>👋 ¡Bienvenido!</h2>
            <p>Selecciona una opción del menú para acceder a tus ejercicios:</p>
        </div>

        <div class="menu-grid">
            <?php foreach ($menuItems as $item): ?>
                <?php 
                $esSalir = strpos(strtolower($item['nombre']), 'salir') !== false;
                $esExterno = strpos($item['enlace'], 'http') === 0;
                ?>
                <a href="<?php echo $item['enlace']; ?>" 
                   class="menu-item <?php if ($esSalir) echo 'salir'; ?>"
                   <?php if ($esExterno) echo 'target="_blank"'; ?>>
                    <span class="<?php if ($esExterno && !$esSalir) echo 'externo'; ?>">
                        <?php echo $item['nombre']; ?>
                    </span>
                    <span class="icon"><?php echo $esSalir ? '🚪' : ($esExterno ? '🌐' : '→'); ?></span>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>
