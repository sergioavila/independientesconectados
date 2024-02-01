<?php

get_header();

$show_default_title = get_post_meta( get_the_ID(), '_et_pb_show_title', true );

?>

<div id="main">
    <div class="container">
        <div id="content-area" class="">
            <h2>Busca la información de tu medicamento</h2>
            <h4>Disponibilidad mayorista</h4>
            <table id="postsTable">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Disponibilidad</th>
                    </tr>
                </thead>
            </table>
            <link rel="stylesheet" href="//cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
            <script src="//cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
            <script>
            jQuery(document).ready(function($) {
                $.ajax({
                    url: '<?php echo admin_url('admin-ajax.php'); ?>',
                    type: 'POST',
                    data: {
                        action: 'obtener_posts_json'
                    },
                    success: function(response) {
                        if (response && response.length > 0) {
                            $('#postsTable').DataTable({
                                data: response,
                                columns: [
                                    { data: 'title' },
                                   // { data: 'category' },
                                    { data: 'thumbnail' }
                                ],
                                ordering: false,
                                "bLengthChange" : false, //thought this line could hide the LengthMenu
                                "bInfo": false, //thought this line could hide the "Showing X of Y entries"
                            });
                        } else {
                            console.error('La respuesta no es válida:', response);
                        }
                    },
                    error: function(error) {
                        console.error('Error en la llamada Ajax:', error);
                    }
                });
            });

            </script>

        </div>
    </div>
</div>
<?php

get_footer();
