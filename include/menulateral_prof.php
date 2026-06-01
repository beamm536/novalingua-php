<?php
$pagina_actual = basename($_SERVER['PHP_SELF']);
?>

<aside class="sidebar">
    <div class="logo">
        NOVALINGUA <br>
        <span>Panel Profesor</span>
    </div>

    <nav class="sidebar-menu">
        <a href="dashboard_prof.php" class="menu-item <?php echo ($pagina_actual == 'dashboard_prof.php') ? 'active' : ''; ?>">
             Mis Clases
        </a>
        
        <a href="gestionar_material.php" class="menu-item <?php echo ($pagina_actual == 'gestionar_material.php') ? 'active' : ''; ?>">
             Subir Material
        </a>
        
        <a href="../logout.php" class="menu-item logout-btn">
             Cerrar Sesión
        </a>
    </nav>
</aside>