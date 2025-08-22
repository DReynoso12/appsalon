<h1 class="nombre-pagina">Recuperar password</h1>
<p class="descripcion-pagina">Reestablece tu password con tu email</p>

<?php
include_once __DIR__ . "/../templates/alertas.php";
?>

<form action="/olvide" class="formulario" method="post">
    <div class="campo">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" placeholder="Tu Email">
    </div>
    <input type="submit" class="boton" value="Enviar instrucciones">
</form>

<div class="acciones">
    <a href="/crear-cuenta">¿Aún no tienes una cuenta? Crear una</a>
    <a href="/">¿Ya tienes una cuenta? Inicia sesión</a>
</div>