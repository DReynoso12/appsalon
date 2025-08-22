<h1 class="nombre-pagina">Actualizar Servicio</h1>
<p class="descripcion-pagina">Modifica los datos de servicios</p>

<div class="barra">
    <p>Hola <?php echo $nombre ?? ''; ?></p>

    <a class="boton" href="/logout">Cerrar sesión</a>
</div>
<?php if (isset($_SESSION['admin'])) { ?>
    <div class="barra-servicios">
        <a href="/admin" class="boton">Ver citas</a>
        <a href="/servicios" class="boton">ver servicios</a>
        <a href="/servicios/crear" class="boton">Nuevo servicio</a>
    </div>

<?php
}
?>
<?php include_once __DIR__ . '/../templates/alertas.php'; ?>
<form class="formulario" method="POST">
    <?php include_once __DIR__ . '/formulario.php'; ?>

    <input type="submit" class="boton" value="Actualizar servicio">
</form>