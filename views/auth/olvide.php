<div class="contenedor olvide">
<?php include_once __DIR__ . "/../templates/nombre-sitio.php" ?>
    <div class="contenedor-sm">
        <p class="descripcion-pagina">Recupera tu Acceso Uptask</p>
        <?php include_once __DIR__ . "/../templates/alertas.php" ?>
        <form action="/olvide" method="post" class="formulario" novalidate>
            <div class="campo">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" placeholder="Tu Email" >
            </div>
            <input class="boton" type="submit" value="Enviar instrcciones">
        </form>

        <div class="acciones">
            <a href="/">¿Ya tienes cuenta?Inicia Sesión</a>
            <a href="/crear">¿Aún no tienes una cuenta? obtener una</a>
        </div>
    </div>
</div>