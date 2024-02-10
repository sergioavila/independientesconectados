<?php

get_header();

$show_default_title = get_post_meta( get_the_ID(), '_et_pb_show_title', true );

?>

<div id="main">
    <div class="container">
        <div id="content-area" class="">
            <h2>Disponibilidad mayorista</h2>

            <div class="preload text-center">
                <div class="spinner-border text-primary" role="status"></div>
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>

            <table id="postsTable" class="table table-striped table-borderless mt-4 d-none" style="width:100%">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th class="text-end">Disponible en</th>
                    </tr>
                </thead>
            </table>
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
                            $('#postsTable').removeClass('d-none');
                            $('.preload').addClass('d-none');
                            console.log('Respuesta:', response);
                            $('#postsTable').DataTable({
                                data: response,
                                columns: [
                                    { data: 'title' },
                                    {
                                        data: 'mayoristas',
                                        //render mayoristas as a list 
                                        render: function(data, type, row) {
                                            var html = '';
                                            if (data && data.length > 0) {
                                                data.forEach(function(mayorista) {
                                                    //if not false
                                                    if(mayorista == false){
                                                        return
                                                    }
                                                    html += '<a href="' + mayorista.link + '" title="' + mayorista.title + '" target="_blank"><img src="'+mayorista.logo+'" class="icon-mayorista"/></a> ';
                                                });
                                            }
                                            return html;
                                        }
                                    }
                                ],
                                ordering: false,
                                "bLengthChange" : false,
                                "bInfo": false,
                                "language": {
                                    "zeroRecords": "No se han encontrado productos disponibles",
                                    paginate: {
                                       first: "Primera",
                                       last: "Última",
                                       next: "Siguiente",
                                       previous: "Anterior"
                                    }
                                }
                            });
                            jQuery("div.dataTables_filter").addClass("input-group");
                            jQuery("div.dataTables_filter input").addClass("form-control").attr("placeholder", "Busca tu medicamento...");
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
