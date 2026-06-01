<?php
session_start();
// Traemos la conexión sencilla
require_once "config/conexion.php";

// Si el alumno no está logueado, al login
if (!isset($_SESSION['id_alumno'])) {
    header("Location: login.php");
    exit();
}

$id_alumno = $_SESSION['id_alumno'];
$mensaje_exito = "";
$mensaje_error = "";

// ¿Se ha pulsado el botón de enviar reporte?
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tipo = $_POST["input_tipo"];
    $descripcion = $_POST["input_descripcion"];
    
    // Ponemos 'Abierto' exactamente igual que en tu ENUM (con la A mayúscula)
    $estado_inicial = "Abierto"; 

    if (!empty($tipo) && !empty($descripcion)) {
        try {
            // Hacemos el INSERT. Dejamos id_empleado como NULL porque aún no se ha asignado a nadie
            $query = "INSERT INTO incidencia (descripcion, tipo, estado, fecha_creacion, id_alumno, id_empleado) 
                      VALUES (:desc, :tipo, :estado, NOW(), :id_al, NULL)";
            
            $stmt = $pdo->prepare($query);
            $stmt->execute([
                "desc"   => $descripcion,
                "tipo"   => $tipo,
                "estado" => $estado_inicial,
                "id_al"  => $id_alumno
            ]);

            $mensaje_exito = "¡Incidencia reportada con éxito! El equipo de Novalingua la revisará pronto.";
        } catch (PDOException $e) {
            $mensaje_error = "Hubo un error al guardar en la base de datos: " . $e->getMessage();
        }
    } else {
        $mensaje_error = "Por favor, rellena todos los campos del formulario.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reportar Incidencia — Novalingua</title>
    <link rel="stylesheet" href="scss/dashboard.css">
    <style>
        /* Estilos directos y sencillos para el formulario */
        .form-group { margin-bottom: 20px; display: flex; flex-direction: column; gap: 8px; }
        .form-group label { font-weight: 600; color: #1a365d; }
        .form-control { width: 100%; padding: 10px; border: 1px solid #e2e8f0; border-radius: 6px; box-sizing: border-box; font-family: inherit; font-size: 0.95rem; }
        .btn-enviar { background-color: #1a365d; color: white; padding: 12px 20px; border: none; border-radius: 6px; cursor: pointer; font-weight: bold; font-size: 0.95rem; width: 100%; }
        .btn-enviar:hover { background-color: #2b6cb0; }
        .alerta { padding: 12px; border-radius: 6px; margin-bottom: 20px; font-weight: 500; text-align: center; }
        .alerta-exito { background-color: #def7ec; color: #03543f; border: 1px solid #31c48d; }
        .alerta-error { background-color: #fde8e8; color: #9b1c1c; border: 1px solid #f8b4b4; }
    </style>
</head>
<body>

<div class="dashboard-container">
  
  <?php include "include/menulateral.php"; ?>

  <main class="main-content">
    <header class="dashboard-header">
      <h2>⚠️ Centro de Soporte Técnico</h2>
      <p>Crea un reporte si experimentas problemas en la plataforma o con tus servicios escolares.</p>
    </header>

    <div class="grid-layout">
        <div class="dashboard-card">
          <h3>NUEVA INCIDENCIA</h3>
          
          <?php if (!empty($mensaje_exito)): ?>
              <div class="alerta alerta-exito"><?php echo $mensaje_exito; ?></div>
          <?php endif; ?>

          <?php if (!empty($mensaje_error)): ?>
              <div class="alerta alerta-error"><?php echo $mensaje_error; ?></div>
          <?php endif; ?>

          <form action="incidencias.php" method="POST">
              
              <div class="form-group">
                  <label for="input_tipo">Clasificación del problema</label>
                  <select name="input_tipo" id="input_tipo" class="form-control" required>
                      <option value="">-- Selecciona un tipo --</option>
                      <option value="Técnico">Técnico (Aulas virtuales, fallos web)</option>
                      <option value="Académico">Académico (Contenido, profesores, horarios)</option>
                      <option value="Facturación">Facturación (Mensualidades, recibos)</option>
                      <option value="Otros">Otros motivos</option>
                  </select>
              </div>

              <div class="form-group">
                  <label for="input_descripcion">¿Qué sucede? Explícalo detalladamente</label>
                  <textarea name="input_descripcion" id="input_descripcion" rows="5" class="form-control" required placeholder="Escribe aquí los detalles del problema..."></textarea>
              </div>

              <button type="submit" class="btn-enviar">Enviar al equipo de soporte</button>
          </form>
        </div>

        <div class="dashboard-card" style="border-left: 4px solid #1a365d;">
          <h3>📌 Información del sistema</h3>
          <p>Tu reporte quedará registrado inmediatamente con el estado <span style="color: #2b6cb0; font-weight: bold;">Abierto</span>.</p>
          <p>Asociaremos de manera automática tu identificador de alumno de la sesión actual para saber quién eres sin que tengas que escribirlo.</p>
        </div>
    </div>
  </main>
</div>

</body>
</html>