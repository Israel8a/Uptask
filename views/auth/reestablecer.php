<div class="contenedor reestablecer">
    <?php include_once __DIR__ . "/../templates/nombre-sitio.php" ?>
    
    <div class="contenedor-sm">
        <p class="descripcion-pagina">Coloca tu nuevo password</p>
        <?php include_once __DIR__ . "/../templates/alertas.php" ?>
        <?php if($mostrar){?>
            <form method="post" class="formulario">
                <div class="campo">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" placeholder="Tu Password" >
                </div>
                <input class="boton" type="submit" value="Guardar Password">
            </form>
        <?php } ?>    
        <div class="acciones">
            <a href="/">¿Ya tienes cuenta?Inicia Sesión</a>
            <a href="/olvide">¿Olvidaste tu Password?</a>
        </div>
    </div>
</div>