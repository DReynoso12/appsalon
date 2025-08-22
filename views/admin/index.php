<h1 class="nombre-pagina">Panel de Administración</h1>
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

<h2>Buscar citas</h2>
<div class="busqueda">
    <form action="" class="formulario">
        <div class="campo">
            <label for="fecha">Fecha</label>
            <input type="date"
                id="fecha"
                name="fecha"
                value="<?php echo $fecha ?? ''; ?>">
        </div>
    </form>
</div>

<?php
if (count($citas) === 0) {
    echo "<h2>No hay citas en esta fecha</h2>";
}
?>

<div id="citas-admin">
    <ul class="citas">
        <?php
        $idcita = 0;
        foreach ($citas as $key => $cita) {
            if ($idcita !== $cita->id) {
                $total = 0;
        ?>
                <li>
                    <p>ID: <span><?php echo $cita->id ?></span> </p>
                    <p>Hora: <span><?php echo $cita->hora ?></span> </p>
                    <p>Cliente: <span><?php echo $cita->cliente ?></span> </p>
                    <p>Email: <span><?php echo $cita->email ?></span> </p>
                    <p>Teléfono: <span><?php echo $cita->telefono ?></span> </p>
                    <h3>Servicios</h3>
                <?php
                $idcita = $cita->id;
            } //fin de if 

            $total += $cita->precio;
                ?>
                <p class="servicio"><?php echo $cita->servicio . " $ " .  $cita->precio; ?></p>
                <?php
                $actual = $cita->id;
                $proximo = $citas[$key + 1]->id ?? 0;

                if (esultimo($actual, $proximo)) { ?>
                    <p class="total">Total: <span>$ <?php echo $total; ?></span></p>

                    <form action="/api/eliminar" method="post">
                        <input type="hidden" name="id" value="<?php echo $cita->id; ?>">
                        <input type="submit" class="boton-eliminar" value="Eliminar">
                    </form>
            <?php
                }
            } //fin de foreach
            ?>
    </ul>
</div>

<?php
$script = "<script src='build/js/buscador.js'></script>";
?>