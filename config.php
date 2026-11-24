<?php
session_start();

try {
    // Conexión con SQLite
    $conexion = new PDO("sqlite:proyecto.db");
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Tabla de usuarios
    $conexion->exec("CREATE TABLE IF NOT EXISTS usuarios (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        usuario TEXT NOT NULL UNIQUE,
        contraseña TEXT NOT NULL
    )");

    // Tabla del formulario
    $conexion->exec("CREATE TABLE IF NOT EXISTS registros (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        nombre TEXT NOT NULL,
        descripcion TEXT,
        fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

    // Tabla del menú dinámico
    $conexion->exec("CREATE TABLE IF NOT EXISTS menu (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        nombre TEXT NOT NULL,
        enlace TEXT NOT NULL,
        orden INTEGER DEFAULT 0
    )");

    // Usuario por defecto: admin / 123456
    $stmt = $conexion->prepare("SELECT * FROM usuarios WHERE usuario = 'admin'");
    $stmt->execute();
    if (!$stmt->fetch()) {
        $hash = password_hash("123456", PASSWORD_DEFAULT);
        $stmt = $conexion->prepare("INSERT INTO usuarios (usuario, contraseña) VALUES (:usu, :pass)");
        $stmt->execute([
            ':usu' => 'admin',
            ':pass' => $hash
        ]);
    }

    // Elementos del menú con TUS enlaces
    $stmt = $conexion->prepare("SELECT COUNT(*) FROM menu");
    $stmt->execute();
    if ($stmt->fetchColumn() == 0) {
        $items = [
            ['Página Personal', 'https://dorkaportalatin20-stack.github.io/pagina-personal/', 1],
            ['Mi Biografía', 'https://dorkaportalatin20-stack.github.io/mi-biografia/', 2],
            ['Banco del Pueblo', 'https://dorkaportalatin20-stack.github.io/banco-del-pueblo/', 3],
            ['Validador de Cédula', 'https://dorkaportalatin20-stack.github.io/Validar-cedula/', 4],
            ['Práctica 5.1 — Cypress', 'https://dorkaportalatin20-stack.github.io/Practica-5.1_Cypress/', 5],
            ['Práctica 5.2 — Gherkin', 'https://dorkaportalatin20-stack.github.io/practica-BDD-Gherkin/', 6],
            ['Práctica 5.3 — k6', 'https://dorkaportalatin20-stack.github.io/Practica-5.3/', 7],
            ['Formulario de Registro', 'formulario.php', 8],
            ['Salir del Sistema', 'logout.php', 99]
        ];
        foreach ($items as $i) {
            $stmt = $conexion->prepare("INSERT INTO menu (nombre, enlace, orden) VALUES (:n, :e, :o)");
            $stmt->execute([
                ':n' => $i[0],
                ':e' => $i[1],
                ':o' => $i[2]
            ]);
        }
    }

} catch(PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
?>
