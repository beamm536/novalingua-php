<?php
session_start();

if (!isset($_SESSION['id_profesor'])) {

}

require_once "../../config/conexion.php";

$mensaje = "";
$clase_mensaje = "";


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['accion_subir'])) {
    $nombre = $_POST['nombre'];
    $tipo = $_POST['tipo'];
    $id_curso = $_POST['id_curso'];

    
    $directorio_destino = "../../uploads/";

    
    if (!file_exists($directorio_destino)) {
        mkdir($directorio_destino, 0777, true);
    }

    
    $archivo_nombre = time() . "_" . basename($_FILES["fichero"]["name"]); 
    $ruta_final = $directorio_destino . $archivo_nombre;
    $extension = strtolower(pathinfo($ruta_final, PATHINFO_EXTENSION));

    
    if ($_FILES["fichero"]["error"] == 0) {
        
        if (move_uploaded_file($_FILES["fichero"]["tmp_name"], $ruta_final)) {

            
            $url_descarga = "uploads/" . $archivo_nombre;

            $sql = "INSERT INTO material (nombre, tipo, url_descarga, id_curso) VALUES (:nombre, :tipo, :url, :id_curso)";
            $stmt = $pdo->prepare($sql);
            $resultado = $stmt->execute([
                'nombre' => $nombre,
                'tipo' => $tipo,
                'url' => $url_descarga,
                'id_curso' => $id_curso
            ]);

            if ($resultado) {
                $mensaje = "✅ ¡Material subido y registrado correctamente!";
                $clase_mensaje = "success";
            } else {
                $mensaje = "❌ Error al guardar en la base de datos.";
                $clase_mensaje = "error";
            }
        } else {
            $mensaje = "❌ Error físico al mover el archivo a la carpeta uploads.";
            $clase_mensaje = "error";
        }
    } else {
        $mensaje = "❌ Por favor, selecciona un archivo válido.";
        $clase_mensaje = "error";
    }
}


if (isset($_GET['borrar'])) {
    $id_borrar = $_GET['borrar'];


    $sql_archivo = "SELECT url_descarga FROM material WHERE id_material = :id";
    $stmt_arc = $pdo->prepare($sql_archivo);
    $stmt_arc->execute(['id' => $id_borrar]);
    $material = $stmt_arc->fetch(PDO::FETCH_ASSOC);

    if ($material) {
        $ruta_fisica = "../../" . $material['url_descarga'];
        if (file_exists($ruta_fisica)) {
            unlink($ruta_fisica); 
        }

        
        $sql_delete = "DELETE FROM material WHERE id_material = :id";
        $stmt_del = $pdo->prepare($sql_delete);
        $stmt_del->execute(['id' => $id_borrar]);

        $mensaje = "🗑️ Material eliminado por completo.";
        $clase_mensaje = "success";
    }
}



$cursos_stmt = $pdo->query("SELECT id_curso, descripcion FROM curso");
$lista_cursos = $cursos_stmt->fetchAll(PDO::FETCH_ASSOC);


$materiales_stmt = $pdo->query("SELECT m.*, c.descripcion as nombre_curso FROM material m JOIN curso c ON m.id_curso = c.id_curso ORDER BY m.id_material DESC");
$lista_materiales = $materiales_stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Gestión de Materiales — Profesor</title>
    <link rel="stylesheet" href="../../scss/scss_interno/main.css">
</head>

<body>

    <div class="dashboard-container">

        <?php include "../../include/menulateral_prof.php"; ?>

        <main class="main-content">
            <header class="dashboard-header">
                <h2>📑 Panel de Gestión de Materiales</h2>
                <p>Sube apuntes, audios o vídeos directos a las asignaturas de los alumnos.</p>
            </header>

            <?php if (!empty($mensaje)): ?>
                <div class="alerta-box <?php echo $clase_mensaje; ?>">
                    <?php echo $mensaje; ?>
                </div>
            <?php endif; ?>

            <div class="dashboard-card">
                <h3>Subir nuevo recurso académico</h3>
                <form action="gestionar_material.php" method="POST" enctype="multipart/form-data" class="form-subida">

                    <div class="form-group-inline">
                        <label>Título del Material</label>
                        <input type="text" name="nombre" placeholder="Ej. Vocabulario Unidad 2 - Travel" required>
                    </div>

                    <div class="form-group-inline">
                        <label>Asignar al Curso</label>
                        <select name="id_curso" required>
                            <option value="">-- Selecciona un Curso --</option>
                            <?php foreach ($lista_cursos as $c): ?>
                                <option value="<?php echo $c['id_curso']; ?>"><?php echo $c['descripcion']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group-inline">
                        <label>Tipo de Formato</label>
                        <select name="tipo" required>
                            <option value="pdf">📄 Documento PDF</option>
                            <option value="audio">🎵 Archivo de Audio (MP3)</option>
                            <option value="video">🎥 Enlace / Vídeo</option>
                        </select>
                    </div>

                    <div class="form-group-inline">
                        <label>Seleccionar Archivo</label>
                        <input type="file" name="fichero" required>
                    </div>

                    <button type="submit" name="accion_subir" class="btn-subir">Subir a la plataforma</button>
                </form>
            </div>

            <div class="dashboard-card" style="margin-top: 30px;">
                <h3>Materiales activos en la escuela</h3>
                <table class="tabla-horario">
                    <thead>
                        <tr>
                            <th>Material</th>
                            <th>Tipo</th>
                            <th>Curso Asignado</th>
                            <th>Enlace</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($lista_materiales)): ?>
                            <tr>
                                <td colspan="5" style="text-align:center;">No hay materiales subidos todavía.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($lista_materiales as $mat): ?>
                                <tr>
                                    <td><strong><?php echo $mat['nombre']; ?></strong></td>
                                    <td>
                                        <span class="badge badge-<?php echo $mat['tipo']; ?>">
                                            <?php echo strtoupper($mat['tipo']); ?>
                                        </span>
                                    </td>
                                    <td><?php echo $mat['nombre_curso']; ?></td>
                                    <td><a href="../../<?php echo $mat['url_descarga']; ?>" target="_blank" class="btn-table">⬇️ Ver archivo</a></td>
                                    <td>
                                        <a href="gestionar_material.php?borrar=<?php echo $mat['id_material']; ?>"
                                            onclick="return confirm('¿Seguro que quieres eliminar este archivo?');"
                                            style="color: red; text-decoration: none; font-weight: bold;">
                                            ❌ Eliminar
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>

</body>

</html>