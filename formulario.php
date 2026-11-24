<?php include 'config.php';

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = trim($_POST['nombre']);
    $descripcion = trim($_POST['descripcion']);

    if (!empty($nombre)) {
        $stmt = $conexion->prepare("INSERT INTO registros (nombre, descripcion) VALUES (:n, :d)");
        $stmt->bindParam(':n', $nombre);
        $stmt->bindParam(':d', $descripcion);
        $stmt->execute();
        $mensaje = "✅ Registro guardado correctamente.";
    }
}

if (isset($_GET['eliminar'])) {
    $id = $_GET['eliminar'];
    $stmt = $conexion->prepare("DELETE FROM registros WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    header("Location: formulario.php");
    exit;
}

$stmt = $conexion->query("SELECT * FROM registros ORDER BY id DESC");
$registros = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Formulario de Registro</title>
    <style>
        * {box-sizing: border-box; font-family: Arial, sans-serif;}
        body {background: #f0f2f5; max-width: 900px; margin: 0 auto; padding: 30px 20px;}
        .volver {display: inline-block; margin-bottom: 20px; color: #667eea; text-decoration: none; font-weight: bold;}
        .volver:hover {text-decoration: underline;}
        h1 {color: #333; margin-bottom: 25px; font-size: 24px;}
        .card {background: white; padding: 25px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.08); margin-bottom: 30px;}
        h3 {color: #444; margin-bottom: 20px; font-size: 18px;}
        label {display: block; margin-top: 15px; margin-bottom: 5px; font-weight: bold; color: #555;}
        input, textarea {width: 100%; padding: 10px; border: 2px solid #ddd; border-radius: 5px; font-size: 15px;}
        input:focus, textarea:focus {outline: none; border-color: #667eea;}
        button {margin-top: 20px; padding: 10px 25px; background: #667eea; color: white; border: none; border-radius: 5px; font-size: 16px; cursor: pointer;}
        button:hover {background: #5568d3;}
        .mensaje {background: #d4edda; color: #155724; padding: 12px; border-radius: 5px; margin-bottom: 20px;}
        table {width: 100%; border-collapse: collapse; margin-top: 20px;}
        th, td {padding: 12px; text-align: left; border-bottom: 1px solid #eee;}
        th {background: #f8f9fa; color: #333;}
        .btn-eliminar {background: #f44336; padding: 5px 12px; font-size: 14px; text-decoration: none; color: white; border-radius: 4px;}
        .btn-eliminar:hover {background: #d32f2f;}
        .vacio {color: #888; text-align: center; padding: 30px;}
    </style>
</head>
<body>
    <a href="menu.php" class="volver">← Volver al Menú Principal</a>
    
    <h1>📝 Formulario de Registro de Información</h1>

    <div class="card">
        <h3>Agregar Nuevo Registro</h3>
        <?php if (isset($mensaje)): ?>
        <div class="mensaje"><?php echo $mensaje; ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <label for="nombre">Nombre / Título:</label>
            <input type="text" id="nombre" name="nombre" required>
            
            <label for="descripcion">Descripción:</label>
            <textarea id="descripcion" name="descripcion" rows="4" placeholder="Escribe una descripción..."></textarea>
            
            <button type="submit">💾 Guardar Registro</button>
        </form>
    </div>

    <div class="card">
        <h3>📋 Registros Almacenados (<?php echo count($registros); ?>)</h3>
        <?php if (count($registros) > 0): ?>
        <table>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Fecha de Registro</th>
                <th>Acción</th>
            </tr>
            <?php foreach ($registros as $r): ?>
            <tr>
                <td><?php echo $r['id']; ?></td>
                <td><?php echo htmlspecialchars($r['nombre']); ?></td>
                <td><?php echo htmlspecialchars($r['descripcion'] ?? '-'); ?></td>
                <td><?php echo date('d/m/Y H:i:s', strtotime($r['fecha'])); ?></td>
                <td>
                    <a href="formulario.php?eliminar=<?php echo $r['id']; ?>" class="btn-eliminar" 
                       onclick="return confirm('¿Está seguro de eliminar este registro? Esta acción no se puede deshacer.')">Eliminar</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php else: ?>
        <p class="vacio">📭 No hay registros almacenados aún. Completa el formulario de arriba para agregar el primero.</p>
        <?php endif; ?>
    </div>
</body>
</html>
