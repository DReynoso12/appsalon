<h1 class="nombre-pagina">Servicios</h1>
<p class="descripcion-pagina">Administracion de servicios</p>

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
<ul class="servicios">
    <?php foreach ($servicios as $servicio) { ?>
        <li>
            <p>Nombre: <span><?php echo $servicio->nombre; ?></span></p>
            <p>Precio: <span> $ <?php echo $servicio->precio; ?></span></p>

            <div class="acciones">
                <a class="boton" href="/servicios/actualizar?id=<?php echo $servicio->id; ?>">Actualizar</a>
                <form action="/servicios/eliminar" method="post">
                    <input type="hidden" name="id" value="<?php echo $servicio->id; ?>">
                    <input type="submit" value="Borrar" class="boton-eliminar">
                </form>
            </div>
        </li>
    <?php } ?>
</ul>