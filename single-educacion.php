<?php
    get_header();
    $show_default_title = get_post_meta( get_the_ID(), '_et_pb_show_title', true );
    $quiz = get_field('quiz');
?>

<div id="main">
    <div class="educacion">
        <img src="<?php echo the_post_thumbnail_url(); ?>" alt="" class="img-fluid w-100 educacion-img mb-lg-5 rounded-4">
        <div id="content-area" class="card border-0 p-3 p-lg-5">
            <?php if ( is_user_logged_in() ) {?>
               <div class="row">
                <div class="col-12">
                    <div class="my-3">
                        <h1 class=""><?php the_title();?></h1>
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="py4 d-flex">
                        <p><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16" fill="rgba(10,14,119,1)"><path d="M12 22C6.47715 22 2 17.5228 2 12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12C22 17.5228 17.5228 22 12 22ZM12 20C16.4183 20 20 16.4183 20 12C20 7.58172 16.4183 4 12 4C7.58172 4 4 7.58172 4 12C4 16.4183 7.58172 20 12 20ZM13 12H17V14H11V7H13V12Z"></path></svg> Lectura: <?php echo get_estimated_reading_time(get_the_content()); ?></p>
                        <p><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16" fill="rgba(10,14,119,1)"><path d="M3 3.9934C3 3.44476 3.44495 3 3.9934 3H20.0066C20.5552 3 21 3.44495 21 3.9934V20.0066C21 20.5552 20.5551 21 20.0066 21H3.9934C3.44476 21 3 20.5551 3 20.0066V3.9934ZM5 5V19H19V5H5ZM10.6219 8.41459L15.5008 11.6672C15.6846 11.7897 15.7343 12.0381 15.6117 12.2219C15.5824 12.2658 15.5447 12.3035 15.5008 12.3328L10.6219 15.5854C10.4381 15.708 10.1897 15.6583 10.0672 15.4745C10.0234 15.4088 10 15.3316 10 15.2526V8.74741C10 8.52649 10.1791 8.34741 10.4 8.34741C10.479 8.34741 10.5562 8.37078 10.6219 8.41459Z"></path></svg> Video: <?php echo get_field('tiempo');?></p>
                    </div>
                    <?php the_content(); ?>
                    <div class="embed-responsive w-100 my-4">
                        <?php echo get_field('video');?>
                    </div>
                    <p>Este contenido podría presentar información comercial y promocional de productos farmacéuticos.  La información presentada aplica sólo para Chile.</p>
                </div>
                <div class="col-lg-4">
                    <div class="card mb-5">
                        <div class="card-body">
                            <p><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16" fill="rgba(10,14,119,1)"><path d="M4 22C4 17.5817 7.58172 14 12 14C16.4183 14 20 17.5817 20 22H4ZM13 16.083V20H17.6586C16.9423 17.9735 15.1684 16.4467 13 16.083ZM11 20V16.083C8.83165 16.4467 7.05766 17.9735 6.34141 20H11ZM12 13C8.685 13 6 10.315 6 7C6 3.685 8.685 1 12 1C15.315 1 18 3.685 18 7C18 10.315 15.315 13 12 13ZM12 11C14.2104 11 16 9.21043 16 7C16 4.78957 14.2104 3 12 3C9.78957 3 8 4.78957 8 7C8 9.21043 9.78957 11 12 11Z"></path></svg> Autor: <?php echo get_field('autor');?></p>
                            <p><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16" fill="rgba(10,14,119,1)"><path d="M9 1V3H15V1H17V3H21C21.5523 3 22 3.44772 22 4V20C22 20.5523 21.5523 21 21 21H3C2.44772 21 2 20.5523 2 20V4C2 3.44772 2.44772 3 3 3H7V1H9ZM20 10H4V19H20V10ZM15.0355 11.136L16.4497 12.5503L11.5 17.5L7.96447 13.9645L9.37868 12.5503L11.5 14.6716L15.0355 11.136ZM7 5H4V8H20V5H17V6H15V5H9V6H7V5Z"></path></svg> Publicado: <?php echo get_field('publicacion');?></p>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body text-center">
                            <p>Realiza este pequeño quiz y gana</p>
                            <p class="mb-4"><?php echo get_field('puntos');?> puntos conectados</p>
                            <button type="button" class="btn btn-primary" id="showQuiz">
                                Comenzar
                            </button>
                        </div>
                    </div>
                </div>
               </div>
                <div class="modal fade" id="modalQuiz" tabindex="-1" role="dialog" aria-labelledby="modalQuizLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-body">
                            <?php echo do_shortcode('[tqb_quiz id="'.$quiz.'"]'); ?>
                            </div>
                        </div>
                    </div>
                </div>
                <script>
                    //enable bootstrap modal
                    jQuery(document).ready(function(){
                        //on click button show
                        jQuery('#showQuiz').click(function(){
                            jQuery('#modalQuiz').modal('show');
                        });
                    });
                </script>

            <?php } else { ?>
                <p class="text-center">
                    Debes iniciar sesión para ver el contenido.
                </p>
            <? } ?>
        </div>
    </div>
</div>
<?php

get_footer();
