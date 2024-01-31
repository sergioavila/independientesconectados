<form method="post" action="">
    <label for="rut">Ingrese el RUT a buscar:</label>
    <input type="text" name="rut" id="rut" required>
    <input type="submit" value="Buscar">
</form>

<?php
// Verificar si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener el RUT ingresado por el usuario
    $rut = $_POST["rut"];

    // Realizar una consulta a la tabla "register_propt" para buscar el RUT
    global $wpdb;
    $table_name = $wpdb->prefix . 'register_propt';
    $result = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE rut = %s", $rut));

    // Si se encontró el RUT en la tabla, mostrar el formulario de Forminator
    if ($result) {
        // Obtener los datos de nombre y apellido de la consulta
        $nombre = $result->name;
        $apellido = $result->apellido;

        // Mostrar el formulario de Forminator con los datos precargados
        echo do_shortcode('[forminator_form id="1994" data="nombre=' . $nombre . '&apellido=' . $apellido . '"]');
    } else {
        // Si no se encontró el RUT, mostrar un mensaje de error
        echo "El RUT no existe en la tabla.";
    }
}
?>
