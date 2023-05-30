<aside class="sidebar">
    <div class="contenido-sidebar">
        <h2>UpTask</h2>
        <div class="menu-cerrar">
            <img src="build/img/cerrar.svg" alt="img menu" id="mobile-cerrar">
        </div>
    </div>
    <nav class="sidebar-nav">
        <a class="<?php echo ($titulo =="Proyectos") ? "activo":""; ?>" href="/dashboard">Proyectos</a>
        <a class="<?php echo ($titulo =="Crear Proyecto") ? "activo":""; ?>" href="/crear-proyecto">Crear Proyecto</a>
        <a class="<?php echo ($titulo =="Perfil") ? "activo":""; ?>" href="/perfil">Perfil</a>
    </nav>
    <div class="cerrar-sesion-mobile">
    <a href="/logout" class="cerrar-sesion">Cerrar Sesion</a>
</div>
</aside>