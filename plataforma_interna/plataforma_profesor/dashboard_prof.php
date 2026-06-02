<?php
session_start();


if (!isset($_SESSION['id_profesor']) || $_SESSION['rol'] !== 'profesor') {
    header("Location: ../../login.php");
    exit();
}

require_once "../../config/conexion.php";

$mensaje = "";
$clase_mensaje = "";


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['accion_crear_curso'])) {
    $descripcion = trim($_POST['descripcion']);
    $nivel = $_POST['nivel'];
    $modalidad = $_POST['modalidad'];
    $dias_semana = $_POST['dias_semana'];
    $franja_horaria = trim($_POST['franja_horaria']);
    
    
    $id_profesor = isset($_SESSION['id_profesor']) ? intval($_SESSION['id_profesor']) : 1; 
    $id_sede = 1; 

    if (!empty($descripcion) && !empty($nivel) && !empty($modalidad) && !empty($dias_semana) && !empty($franja_horaria)) {
        try {
            $sql_insert = "INSERT INTO curso (descripcion, nivel, modalidad, dias_semana, franja_horaria, id_profesor, id_sede) 
                           VALUES (:descripcion, :nivel, :modalidad, :dias_semana, :franja_horaria, :id_profesor, :id_sede)";
            
            $stmt_insert = $pdo->prepare($sql_insert);
            $stmt_insert->execute([
                'descripcion' => $descripcion,
                'nivel' => $nivel,
                'modalidad' => $modalidad,
                'dias_semana' => $dias_semana,
                'franja_horaria' => $franja_horaria,
                'id_profesor' => $id_profesor,
                'id_sede' => $id_sede
            ]);
            
            $mensaje = "🟢 ¡Nuevo grupo de idioma creado correctamente!";
            $clase_mensaje = "success";
        } catch (PDOException $e) {
            $mensaje = "❌ Error al crear el grupo: " . $e->getMessage();
            $clase_mensaje = "error";
        }
    } else {
        $mensaje = "❌ Todos los campos son obligatorios.";
        $clase_mensaje = "error";
    }
}


$id_curso_detalle = isset($_GET['id_curso']) ? intval($_GET['id_curso']) : 0;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Control de Grupos — Novalingua</title>
    <link rel="stylesheet" href="../../scss/scss_interno/main.css">
</head>
<body>

<div class="dashboard-container">
    
    <?php include "../../include/menulateral_prof.php"; ?>

    <main class="main-content">
        
        <?php if (!empty($mensaje)): ?>
            <div class="alerta-box <?php echo $clase_mensaje; ?>" style="padding: 15px; border-radius: 6px; margin-bottom: 20px; font-weight: 600; <?php echo $clase_mensaje == 'success' ? 'background: #c6f6d5; color: #22543d; border-left: 5px solid #38a169;' : 'background: #fed7d7; color: #742a2a; border-left: 5px solid #e53e3e;'; ?>">
                <?php echo $mensaje; ?>
            </div>
        <?php endif; ?>

        <?php if ($id_curso_detalle === 0): ?>
            <header class="dashboard-header">
                <h2>📚 Control de Grupos Académicos</h2>
                <p>Gestiona las asignaturas de idiomas e infórmate del estado de cada clase.</p>
            </header>

            <div class="dashboard-card" style="margin-bottom: 30px;">
                <h3>➕ Crear un nuevo grupo de idioma</h3>
                <form action="dashboard_prof.php" method="POST" style="display: flex; flex-direction: column; gap: 15px; margin-top: 15px;">
                    
                    <div style="display: flex; flex-direction: column; gap: 6px; width: 100%;">
                        <label style="font-size: 0.9rem; font-weight: 600; color: #2d3748;">Nombre / Descripción del Curso</label>
                        <input type="text" name="descripcion" placeholder="Ej. Curso Intensivo de Alemán" required style="padding: 11px; border: 1px solid #e2e8f0; border-radius: 6px; background: #f7fafc; font-size: 0.95rem; width: 100%; box-sizing: border-box;">
                    </div>

                    <div style="display: flex; gap: 15px; flex-wrap: wrap;">
                        <div style="display: flex; flex-direction: column; gap: 6px; flex: 1; min-width: 120px;">
                            <label style="font-size: 0.9rem; font-weight: 600; color: #2d3748;">Nivel</label>
                            <select name="nivel" required style="padding: 11px; border: 1px solid #e2e8f0; border-radius: 6px; background: #f7fafc; font-size: 0.95rem; height: 44px; color: #2d3748;">
                                <option value="">-- Elige --</option>
                                <option value="A1">A1</option>
                                <option value="A2">A2</option>
                                <option value="B1">B1</option>
                                <option value="B2">B2</option>
                                <option value="C1">C1</option>
                                <option value="C2">C2</option>
                            </select>
                        </div>

                        <div style="display: flex; flex-direction: column; gap: 6px; flex: 1; min-width: 140px;">
                            <label style="font-size: 0.9rem; font-weight: 600; color: #2d3748;">Modalidad</label>
                            <select name="modalidad" required style="padding: 11px; border: 1px solid #e2e8f0; border-radius: 6px; background: #f7fafc; font-size: 0.95rem; height: 44px; color: #2d3748;">
                                <option value="">-- Elige --</option>
                                <option value="Presencial"> Presencial</option>
                                <option value="Online"> Online</option>
                                <option value="Híbrido">Híbrido</option>
                            </select>
                        </div>

                        <div style="display: flex; flex-direction: column; gap: 6px; flex: 1; min-width: 160px;">
                            <label style="font-size: 0.9rem; font-weight: 600; color: #2d3748;">Días de Clase</label>
                            <select name="dias_semana" required style="padding: 11px; border: 1px solid #e2e8f0; border-radius: 6px; background: #f7fafc; font-size: 0.95rem; height: 44px; color: #2d3748;">
                                <option value="">-- Elige --</option>
                                <option value="Lunes-Miércoles"> Lunes - Miércoles</option>
                                <option value="Martes-Jueves">Martes - Jueves</option>
                                <option value="Viernes"> Viernes</option>
                                <option value="Sábado"> Sábado</option>
                            </select>
                        </div>

                        <div style="display: flex; flex-direction: column; gap: 6px; flex: 1; min-width: 180px;">
                            <label style="font-size: 0.9rem; font-weight: 600; color: #2d3748;">Horario / Franja</label>
                            <input type="text" name="franja_horaria" placeholder="Ej. 16:00 - 18:00 o Tarde" required style="padding: 11px; border: 1px solid #e2e8f0; border-radius: 6px; background: #f7fafc; font-size: 0.95rem; height: 44px; width: 100%; box-sizing: border-box;">
                        </div>
                    </div>

                    <div style="text-align: right; margin-top: 5px;">
                        <button type="submit" name="accion_crear_curso" style="background: #1a365d; color: #fff; padding: 12px 30px; border: none; border-radius: 6px; font-weight: 700; cursor: pointer; transition: background 0.2s;">
                            Crear e Inicializar Grupo
                        </button>
                    </div>
                </form>
            </div>

            <h3>Grupos Activos en la Base de Datos</h3>
            <div class="grupos-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 20px; margin-top: 15px;">
                <?php
                
                $sql_cursos = "SELECT id_curso, descripcion, modalidad,
                               (SELECT COUNT(*) FROM alumno_curso WHERE id_curso = curso.id_curso) AS total_alumnos,
                               (SELECT COUNT(*) FROM material WHERE id_curso = curso.id_curso) AS total_materiales
                               FROM curso ORDER BY descripcion ASC";
                $cursos_stmt = $pdo->query($sql_cursos);
                $cursos = $cursos_stmt->fetchAll(PDO::FETCH_ASSOC);

                foreach ($cursos as $cur):
                ?>
                    <div class="dashboard-card" style="display: flex; flex-direction: column; justify-content: space-between; border-top: 4px solid #1a365d; transition: transform 0.2s; cursor: pointer;" onclick="window.location.href='dashboard_prof.php?id_curso=<?php echo $cur['id_curso']; ?>'">
                        <div>
                            <h4 style="margin: 0 0 10px 0; color: #1a365d; font-size: 1.15rem;"><?php echo $cur['descripcion']; ?></h4>
                            <p style="margin: 4px 0; font-size: 0.9rem; color: #718096;"> Alumnos inscritos: <strong><?php echo $cur['total_alumnos']; ?></strong></p>
                            <p style="margin: 4px 0; font-size: 0.9rem; color: #718096;"> Recursos subidos: <strong><?php echo $cur['total_materiales']; ?></strong></p>
                        </div>
                        <div style="margin-top: 15px; text-align: right;">
                            <a href="dashboard_prof.php?id_curso=<?php echo $cur['id_curso']; ?>" style="color: #2b6cb0; text-decoration: none; font-weight: bold; font-size: 0.9rem;">Ver resumen →</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

        <?php else: ?>
            <?php
            
            $stmt_c = $pdo->prepare("SELECT * FROM curso WHERE id_curso = :id");
            $stmt_c->execute(['id' => $id_curso_detalle]);
            $curso_actual = $stmt_c->fetch(PDO::FETCH_ASSOC);

            if (!$curso_actual) {
                echo "<p>El curso seleccionado no existe.</p><a href='dashboard_prof.php'>Volver al listado</a>";
                exit();
            }

            
            $stmt_al = $pdo->prepare("SELECT ai.id_alumno, ai.nombre, ai.email 
                                      FROM alumno_info ai
                                      INNER JOIN alumno_curso ac ON ai.id_alumno = ac.id_alumno 
                                      WHERE ac.id_curso = :id 
                                      ORDER BY ai.nombre ASC");
            $stmt_al->execute(['id' => $id_curso_detalle]);
            $alumnos_grupo = $stmt_al->fetchAll(PDO::FETCH_ASSOC);

            
            $stmt_mat = $pdo->prepare("SELECT nombre, tipo, url_descarga FROM material WHERE id_curso = :id ORDER BY id_material DESC");
            $stmt_mat->execute(['id' => $id_curso_detalle]);
            $materiales_grupo = $stmt_mat->fetchAll(PDO::FETCH_ASSOC);
            ?>

            <header class="dashboard-header" style="margin-bottom: 20px;">
                <a href="dashboard_prof.php" style="text-decoration: none; color: #2b6cb0; font-weight: bold; font-size: 0.95rem;">⬅️ Volver al listado de grupos</a>
                <h2 style="margin-top: 10px; color: #1a365d;">📊 Resumen: <?php echo $curso_actual['descripcion']; ?></h2>
                
                <p style="margin: 4px 0; color:#718096; font-size:0.95rem;">
                    🎯 <strong>Nivel:</strong> <?php echo $curso_actual['nivel']; ?> | 
                    📍 <strong>Modalidad:</strong> <?php echo $curso_actual['modalidad']; ?> | 
                    🗓️ <strong>Días:</strong> <?php echo $curso_actual['dias_semana']; ?> | 
                    ⏰ <strong>Horario:</strong> <?php echo $curso_actual['franja_horaria']; ?>
                </p>
            </header>

            <div style="display: flex; gap: 20px; margin-bottom: 30px; flex-wrap: wrap;">
                <div class="dashboard-card" style="flex: 1; min-width: 200px; text-align: center; border-left: 5px solid #2b6cb0;">
                    <span style="font-size: 2rem; font-weight: 800; color: #2b6cb0;"><?php echo count($alumnos_grupo); ?></span>
                    <p style="margin: 5px 0 0 0; color: #4a5568; font-weight: 600;">Estudiantes Totales</p>
                </div>
                <div class="dashboard-card" style="flex: 1; min-width: 200px; text-align: center; border-left: 5px solid #319795;">
                    <span style="font-size: 2rem; font-weight: 800; color: #319795;"><?php echo count($materiales_grupo); ?></span>
                    <p style="margin: 5px 0 0 0; color: #4a5568; font-weight: 600;">Materiales Didácticos</p>
                </div>
            </div>

            <div class="dashboard-card" style="margin-bottom: 30px;">
                <h3 style="color: #2d3748; margin-bottom: 15px;">👥 Alumnos Matriculados</h3>
                <table class="tabla-horario" style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background-color: #f7fafc;">
                            <th style="padding: 12px; border-bottom: 1px solid #e2e8f0; text-align: left;">ID Alumno</th>
                            <th style="padding: 12px; border-bottom: 1px solid #e2e8f0; text-align: left;">Nombre Completo</th>
                            <th style="padding: 12px; border-bottom: 1px solid #e2e8f0; text-align: left;">Email de Contacto</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($alumnos_grupo)): ?>
                            <tr><td colspan="3" style="text-align: center; padding: 15px; color: #a0aec0;">No hay alumnos apuntados en este grupo todavía.</td></tr>
                        <?php else: ?>
                            <?php foreach ($alumnos_grupo as $al): ?>
                                <tr>
                                    <td style="padding: 12px; border-bottom: 1px solid #e2e8f0; color: #718096;">#<?php echo $al['id_alumno']; ?></td>
                                    <td style="padding: 12px; border-bottom: 1px solid #e2e8f0; font-weight: 600; color: #2d3748;"><?php echo $al['nombre']; ?></td>
                                    <td style="padding: 12px; border-bottom: 1px solid #e2e8f0; color: #4a5568;"><?php echo $al['email']; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div class="dashboard-card">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                    <h3 style="color: #2d3748; margin: 0;">📑 Materiales de esta Asignatura</h3>
                    <a href="gestionar_material.php" style="background: #319795; color: #fff; padding: 8px 14px; border-radius: 4px; text-decoration: none; font-size: 0.85rem; font-weight: bold;">+ Añadir Recurso</a>
                </div>
                <table class="tabla-horario" style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background-color: #f7fafc;">
                            <th style="padding: 12px; border-bottom: 1px solid #e2e8f0; text-align: left;">Nombre del Recurso</th>
                            <th style="padding: 12px; border-bottom: 1px solid #e2e8f0; text-align: left;">Tipo</th>
                            <th style="padding: 12px; border-bottom: 1px solid #e2e8f0; text-align: center;">Enlace</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($materiales_grupo)): ?>
                            <tr><td colspan="3" style="text-align: center; padding: 15px; color: #a0aec0;">No se ha subido ningún material didáctico para este grupo.</td></tr>
                        <?php else: ?>
                            <?php foreach ($materiales_grupo as $mat): ?>
                                <tr>
                                    <td style="padding: 12px; border-bottom: 1px solid #e2e8f0; font-weight: 600; color: #2d3748;"><?php echo $mat['nombre']; ?></td>
                                    <td style="padding: 12px; border-bottom: 1px solid #e2e8f0;">
                                        <span class="badge badge-<?php echo $mat['tipo']; ?>" style="font-size: 0.8rem; padding: 2px 8px; border-radius: 4px; font-weight: bold; <?php echo $mat['tipo']=='pdf' ? 'background:#ffe3e3; color:#c53030;' : ($mat['tipo']=='audio' ? 'background:#eef2ff; color:#4c51bf;' : 'background:#fef3c7; color:#b45309;'); ?>">
                                            <?php echo strtoupper($mat['tipo']); ?>
                                        </span>
                                    </td>
                                    <td style="padding: 12px; border-bottom: 1px solid #e2e8f0; text-align: center;">
                                        <a href="../../<?php echo $mat['url_descarga']; ?>" target="_blank" style="color: #2b6cb0; text-decoration: none; font-weight: bold; font-size: 0.9rem;">⬇️ Abrir</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

        <?php endif; ?>

    </main>
</div>

</body>
</html>