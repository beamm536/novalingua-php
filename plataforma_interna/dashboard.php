<?php
// 1. Inicializamos la sesión para poder comprobar quién está navegando
session_start();

// 🛡️ 2. Control de seguridad estricto
// Si no existe la variable id_alumno o el rol inyectado no es 'alumno', se le expulsa al login
if (!isset($_SESSION['id_alumno']) || $_SESSION['rol'] !== 'alumno') {
    header("Location: ../login.php");
    exit();
}

// 🗄️ 3. Conexión a la base de datos
// Ajustamos la ruta para subir un nivel y entrar a la carpeta 'config'
require_once "../config/conexion.php"; 

// Capturamos de forma segura el ID del alumno que se guardó en el login
$id_alumno = $_SESSION['id_alumno'];

// Inicializamos las variables para evitar problemas de visualización si la BD falla
$alumno_datos = [];
$curso_datos = null;
$error_db = "";

try {
    // 📊 CONSULTA 1: Obtener los datos personales del alumno logueado
    $sql_alumno = "SELECT nombre, apellidos, email FROM alumno_info WHERE id_alumno = :id_alumno LIMIT 1";
    $stmt_al = $pdo->prepare($sql_alumno);
    $stmt_al->execute(['id_alumno' => $id_alumno]);
    $alumno_datos = $stmt_al->fetch(PDO::FETCH_ASSOC);

    // 📚 CONSULTA 2: Obtener el curso activo y el progreso a través de la tabla intermedia alumno_curso
    $sql_curso = "SELECT c.descripcion, c.nivel, c.modalidad, c.franja_horaria, ac.progreso 
                  FROM curso c
                  INNER JOIN alumno_curso ac ON c.id_curso = ac.id_curso
                  WHERE ac.id_alumno = :id_alumno LIMIT 1";
    $stmt_cu = $pdo->prepare($sql_curso);
    $stmt_cu->execute(['id_alumno' => $id_alumno]);
    $curso_datos = $stmt_cu->fetch(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    // Si algo falla a nivel de base de datos, guardamos el error en lugar de romper la página
    $error_db = "Error al conectar con los datos: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Estudiante — Novalingua</title>
    <link rel="stylesheet" href="../scss/scss_interno/main.css">
</head>
<body>

    <div class="dashboard-container" style="display: flex; min-height: 100vh;">
        
        <?php include "../include/menulateral.php"; ?>

        <main class="main-content" style="flex: 1; padding: 40px; background-color: #f7fafc; font-family: sans-serif;">
            
            <?php if (!empty($error_db)): ?>
                <div style="background: #fed7d7; color: #742a2a; padding: 15px; border-radius: 6px; margin-bottom: 20px; font-weight: bold;">
                     <?php echo htmlspecialchars($error_db); ?>
                </div>
            <?php endif; ?>

            <header class="dashboard-header" style="margin-bottom: 30px;">
                <h1 style="color: #1a365d; font-size: 2rem; margin: 0;">
                    Bienvenida, <?php echo htmlspecialchars($alumno_datos['nombre'] ?? 'Estudiante'); ?>
                </h1>
                <p style="color: #718096; margin-top: 5px;">Sigue aprendiendo y gestionando tus clases de idiomas.</p>
            </header>

            <div class="dashboard-grid" style="display: grid; grid-template-columns: 2fr 1fr; gap: 30px;">
                
                <div class="card-box" style="background: white; padding: 30px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
                    <h2 style="color: #2d3748; font-size: 1.2rem; margin-bottom: 20px; border-bottom: 2px solid #edf2f7; padding-bottom: 10px; text-transform: uppercase; font-size: 0.85rem; letter-spacing: 1px; color: #718096;">
                        Curso Actual
                    </h2>

                    <?php if ($curso_datos): ?>
                        <div class="info-curso">
                            <h3 style="color: #1a365d; font-size: 1.5rem; margin-bottom: 15px;">
                                <?php echo htmlspecialchars($curso_datos['descripcion']); ?>
                            </h3>
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 20px; color: #4a5568;">
                                <p><strong>🎯 Nivel:</strong> <span style="background: #e2e8f0; padding: 3px 8px; border-radius: 4px; font-size: 0.9rem; font-weight: bold;"><?php echo htmlspecialchars($curso_datos['nivel']); ?></span></p>
                                <p><strong>📍 Modalidad:</strong> <?php echo htmlspecialchars($curso_datos['modalidad']); ?></p>
                                <p><strong>🕒 Horario:</strong> <?php echo htmlspecialchars($curso_datos['franja_horaria']); ?></p>
                            </div>

                            <div class="progreso-container" style="margin-top: 25px;">
                                <div style="display: flex; justify-content: space-between; margin-bottom: 5px; font-weight: 600; color: #4a5568;">
                                    <span>Progreso del curso</span>
                                    <span><?php echo intval($curso_datos['progreso']); ?>%</span>
                                </div>
                                <div style="background: #edf2f7; border-radius: 10px; height: 12px; width: 100%; overflow: hidden;">
                                    <div style="background: #3182ce; height: 100%; width: <?php echo intval($curso_datos['progreso']); ?>%; transition: width 0.5s ease;"></div>
                                </div>
                            </div>
                        </div>
                    <?php else: ?>
                        <div style="text-align: center; padding: 30px; color: #a0aec0;">
                            <p style="font-size: 1.1rem; margin-bottom: 10px;"> No estás matriculado en ningún curso activo.</p>
                            <p style="font-size: 0.9rem;">Ponte en contacto con secretaría si crees que es un error.</p>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="card-box" style="background: white; padding: 30px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
                    <h2 style="color: #2d3748; font-size: 1.2rem; margin-bottom: 20px; border-bottom: 2px solid #edf2f7; padding-bottom: 10px; text-transform: uppercase; font-size: 0.85rem; letter-spacing: 1px; color: #718096;">
                         Próximas Entregas
                    </h2>
                    <ul style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 15px;">
                        <li style="display: flex; justify-content: space-between; font-size: 0.95rem; border-bottom: 1px dashed #edf2f7; padding-bottom: 10px;">
                            <span> <strong>Grammar Unit 3</strong></span>
                            <span style="color: #e53e3e; font-weight: bold;">Retrasado</span>
                        </li>
                        <li style="display: flex; justify-content: space-between; font-size: 0.95rem; border-bottom: 1px dashed #edf2f7; padding-bottom: 10px;">
                            <span> <strong>Vocabulary Quiz</strong></span>
                            <span style="color: #dd6b20;">Pendiente (Mañana)</span>
                        </li>
                        <li style="display: flex; justify-content: space-between; font-size: 0.95rem;">
                            <span> <strong>Writing Essay 1</strong></span>
                            <span style="color: #38a169; font-weight: bold;">Calificado (8.5)</span>
                        </li>
                    </ul>
                </div>

            </div>

        </main>
    </div>

</body>
</html>