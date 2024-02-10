<?php

get_header();

$show_default_title = get_post_meta( get_the_ID(), '_et_pb_show_title', true );

?>

<div id="main">
    <div class="container">
        <div id="content-area" class="pt-4">
            <h2>Busca la información de tu medicamento</h2>

            <div class="preload text-center">
                <div class="spinner-border text-primary" role="status"></div>
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>

            <div class="input-group mt-4">
                <input type="text" id="filtroInput" placeholder="Buscar medicamento">
            </div>

            <div id="products-grid" class="row mb-5 grid gy-4"></div>
            <div id="sin-resultados" class="my-5 d-none">
                <h3 class="text-center pb-5">No se encontraron productos</h3>
            </div>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.isotope/3.0.6/isotope.pkgd.min.js" integrity="sha512-Zq2BOxyhvnRFXu0+WE6ojpZLOU2jdnqbrM1hmVdGzyeCa1DgM3X5Q4A/Is9xA1IkbUeDd7755dNNI/PzSf2Pew==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

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
                                var $grid = $('#products-grid');
                                $grid.isotope({
                                    itemSelector: '.grid-item',
                                    layoutMode: 'fitRows',
                                    percentPosition: true,
                                    resizesContainer: true,
                                    resizable: true,
                                });
                                response.forEach(function(item) {
                                    var $item = $(`<div class="col-12 col-sm-6 col-lg-3 grid-item">
                                        <div class="card card-search border-0">
                                            <div class="card-body">
                                                <img src="${item.thumbnail}" class="card-img-top" alt="${item.title}">
                                                <hr/>
                                                <h4 class="card-title">${item.title}</h4>
                                                <a href="${item.permalink}" class="btn btn-primary btn-product">Ver producto</a>
                                            </div>
                                        </div>
                                        </div>`);
                                    $grid.append($item);
                                    $grid.isotope('appended', $item);
                                });
                                $('#filtroInput').on('input', function() {
                                    var filtroTexto = $(this).val().toLowerCase();

                                    // Filtra los elementos isotope según el texto ingresado
                                    $grid.isotope({ filter: function() {
                                        var textoElemento = $(this).text().toLowerCase();
                                        return textoElemento.includes(filtroTexto);
                                    }});
                                    if ($grid.data('isotope').filteredItems.length === 0) {
                                        $('#sin-resultados').removeClass('d-none');
                                    } else {
                                        $('#sin-resultados').addClass('d-none');
                                    }
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
