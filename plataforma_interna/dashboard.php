<?php
session_start();

if (!isset($_SESSION['id_alumno']) || $_SESSION['rol'] !== 'alumno') {
    header("Location: ../login.php");
    exit();
}

require_once "../config/conexion.php"; 

$id_alumno = $_SESSION['id_alumno'];
$alumno_datos = [];
$cursos_alumno = [];
$materiales_por_curso = [];
$empleados = [];
$error_db = "";

try {
    // 📊 1. Obtener los datos personales del alumno logueado
    $sql_alumno = "SELECT nombre, apellidos, email FROM alumno_info WHERE id_alumno = :id_alumno LIMIT 1";
    $stmt_al = $pdo->prepare($sql_alumno);
    $stmt_al->execute(['id_alumno' => $id_alumno]);
    $alumno_datos = $stmt_al->fetch(PDO::FETCH_ASSOC);

    // 📚 2. Obtener TODOS los cursos activos del alumno
    $sql_cursos = "SELECT c.id_curso, c.descripcion, c.nivel, c.modalidad, c.franja_horaria, ac.progreso 
                   FROM curso c
                   INNER JOIN alumno_curso ac ON c.id_curso = ac.id_curso
                   WHERE ac.id_alumno = :id_alumno AND ac.estado = 'Activo'";
    $stmt_cu = $pdo->prepare($sql_cursos);
    $stmt_cu->execute(['id_alumno' => $id_alumno]);
    $cursos_alumno = $stmt_cu->fetchAll(PDO::FETCH_ASSOC);

    // 📥 3. Si está matriculado en cursos, cargamos sus materiales correspondientes
    if (!empty($cursos_alumno)) {
        $ids_cursos = array_column($cursos_alumno, 'id_curso');
        $placeholders = implode(',', array_fill(0, count($ids_cursos), '?'));
        
        $sql_mat = "SELECT id_material, nombre, tipo, url_descarga, id_curso FROM material WHERE id_curso IN ($placeholders)";
        $stmt_mat = $pdo->prepare($sql_mat);
        $stmt_mat->execute($ids_cursos);
        $todos_los_materiales = $stmt_mat->fetchAll(PDO::FETCH_ASSOC);

        foreach ($todos_los_materiales as $mat) {
            $materiales_por_curso[$mat['id_curso']][] = $mat;
        }
    } else {
        // 👔 4. Si no tiene cursos, traemos los empleados de administración para la tarjeta de contacto
        $sql_emp = "SELECT nombre, apellidos, email, departamento FROM empleado LIMIT 3";
        $stmt_emp = $pdo->query($sql_emp);
        $empleados = $stmt_emp->fetchAll(PDO::FETCH_ASSOC);
    }

} catch (PDOException $e) {
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

    <div class="dashboard-container">
        
        <?php include "../include/menulateral.php"; ?>

        <main class="main-content">
            
            <?php if (!empty($error_db)): ?>
                <div class="alert-error-box">
                    <?php echo htmlspecialchars($error_db); ?>
                </div>
            <?php endif; ?>

            <header class="dashboard-header">
                <h2>Bienvenida, <?php echo htmlspecialchars($alumno_datos['nombre'] ?? 'Estudiante'); ?></h2>
                <p>Plataforma de seguimiento académico y recursos personalizados.</p>
            </header>

            <div class="dashboard-compact-wrapper">
                
                <?php if (!empty($cursos_alumno)): ?>
                    
                    <?php foreach ($cursos_alumno as $curso): ?>
                        
                        <div class="course-row">
                            
                            <section class="dashboard-card">
                                <div class="card-header-inline">
                                    <span class="main-card-tag">Curso Activo</span>
                                    <h3>Estado de Matriculación</h3>
                                </div>
                                
                                <div class="info-curso">
                                    <h4><?php echo htmlspecialchars($curso['descripcion']); ?></h4>
                                    
                                    <ul class="profile-info-list">
                                        <li><strong> Nivel:</strong> <span class="badge-success"><?php echo htmlspecialchars($curso['nivel']); ?></span></li>
                                        <li><strong> Modalidad:</strong> <span><?php echo htmlspecialchars($curso['modalidad']); ?></span></li>
                                        <li><strong> Horario:</strong> <span><?php echo htmlspecialchars($curso['franja_horaria']); ?></span></li>
                                    </ul>

                                    <div class="progress-container">
                                        <div class="progress-bar-wrapper">
                                            <div class="progress-bar" style="width: <?php echo intval($curso['progreso']); ?>%;"></div>
                                        </div>
                                        <span class="progress-text"><?php echo intval($curso['progreso']); ?>%</span>
                                    </div>
                                </div>
                            </section>

                            <section class="dashboard-card">
                                <h3>📚 Recursos y Materiales de Clase</h3>

                                <?php 
                                $id_act = $curso['id_curso'];
                                if (!empty($materiales_por_curso[$id_act])): 
                                ?>
                                    <div class="materials-list">
                                        <?php foreach ($materiales_por_curso[$id_act] as $mat): ?>
                                            <div class="material-item">
                                                <div class="material-meta">
                                                    <div class="icon-wrapper">
                                                        <?php 
                                                            if (strtolower($mat['tipo']) == 'pdf') echo '📄';
                                                            elseif (strtolower($mat['tipo']) == 'audio') echo '🎵';
                                                            elseif (strtolower($mat['tipo']) == 'video') echo '🎥';
                                                            else echo '📁';
                                                        ?>
                                                    </div>
                                                    <div class="material-details">
                                                        <span class="material-title"><?php echo htmlspecialchars($mat['nombre']); ?></span>
                                                        <span class="type-tag"><?php echo htmlspecialchars($mat['tipo']); ?></span>
                                                    </div>
                                                </div>
                                                <a href="../<?php echo htmlspecialchars($mat['url_descarga']); ?>" download class="download-btn">
                                                    Descargar
                                                </a>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php else: ?>
                                    <p class="empty-materials">No hay archivos asignados a esta asignatura todavía.</p>
                                <?php endif; ?>
                            </section>
                            
                        </div>
                        
                    <?php endforeach; ?>

                <?php else: ?>
                    
                    <div class="no-courses-view">
                        <section class="dashboard-card full-width-card">
                            <h3>Estado de Matriculación</h3>
                            <div class="no-enrollment-box">
                                <div class="info-notice">
                                     Actualmente no figuras inscrito en ninguno de nuestros cursos.
                                </div>
                                <p class="section-instruction">Por favor, ponte en contacto con nuestro equipo para gestionar tu matrícula:</p>
                                
                                <div class="contact-list">
                                    <?php if (empty($empleados)): ?>
                                        <div class="contact-item">
                                            <div class="contact-info">
                                                <strong>Sede Central Novalingua</strong>
                                                <span class="department-tag">Atención Alumno</span>
                                            </div>
                                            <a href="mailto:administracion@novalingua.com" class="contact-email-link">administracion@novalingua.com</a>
                                        </div>
                                    <?php else: ?>
                                        <?php foreach ($empleados as $emp): ?>
                                            <div class="contact-item">
                                                <div class="contact-info">
                                                    <strong><?php echo htmlspecialchars($emp['nombre'] . " " . $emp['apellidos']); ?></strong>
                                                    <span class="department-tag"><?php echo htmlspecialchars($emp['departamento']); ?></span>
                                                </div>
                                                <a href="mailto:<?php echo $emp['email']; ?>" class="contact-email-link"><?php echo htmlspecialchars($emp['email']); ?></a>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </section>
                    </div>
                    
                <?php endif; ?>

            </div>
        </main>
    </div>

</body>
</html>