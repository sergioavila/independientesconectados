<?php

get_header();

$show_default_title = get_post_meta( get_the_ID(), '_et_pb_show_title', true );
acf_form_head();

?>
<h2 class="">Mi cuenta</h2>
<div <?php echo ( is_user_logged_in() ) ? 'id="main-content"' : 'class="my-md-5 py-md-5"'; ?> >
    <div class="container">
        <div id="content-area" class="profile-user">
            <?php if ( is_user_logged_in() ) {?>
                <?php
                    $user_id = get_current_user_id();
                    $avatar = get_avatar($user_id) ? get_avatar($user_id) : 'https://placehold.co/300x300.png';
                    ?>
                <div class="row">
                    <div class="col ">
                        <div class="d-flex align-items-center justify-content-center">
                            <div height="120" width="120" class="">
                                <?php echo $avatar; ?>
                            </div>
                            <div class="">
                                <h3 class="card-title color-blue">Hola <?php echo wp_get_current_user()->display_name; ?></h3>
                                <h4 class="color-blue">Tu farmacia: XXX</h4>
                                <h5>Rut: xxx</h5>
                                <a href="#" class="link" data-bs-toggle="modal" data-bs-target="#exampleModal">Actualizar información <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16" fill="rgba(10,14,119,1)"><path d="M10 6V8H5V19H16V14H18V20C18 20.5523 17.5523 21 17 21H4C3.44772 21 3 20.5523 3 20V7C3 6.44772 3.44772 6 4 6H10ZM21 3V12L17.206 8.207L11.2071 14.2071L9.79289 12.7929L15.792 6.793L12 3H21Z"></path></svg></a>
                                <div class="mt-3">
                                <a href="<?php echo wp_logout_url( home_url() ); ?>" class="link">Cerrar sesión</a>
                                </div>
                            </div>
                        </div>
                        <div class="puntos-acumulados">
                            <h4>Puntos acumulados</h4>
                            <span class="puntaje"><?php echo do_shortcode('[gamipress_points type="puntos" user_id="'.$user_id.'" inline ="yes" label="no" thumbnail="no" align="none"align="none"]');?></span>
                        </div>
                    </div>
                    <div class="col-12 text-center">
                        <p>Canjea tus puntos en tus próximas compras. <a href="#" class="">Canjear puntos</a></p>
                        <p class="color-blue mb-5">Entre más interactués en la plataforma (Independientes Conectados) acumularás más puntos para canjearlos en tu próxima compra de medicamentos.</p>
                    </div>
                    <div class="col-12">
                        <div class="p-md-5">
                            <p class="color-blue mb-3">Tus puntos acumulados</p>
                            <?php echo do_shortcode('[gamipress_earnings current_user="yes" force_responsive="yes" limit="10" pagination="yes" order="DESC" points="yes" points_types="all" awards="yes" deducts="no" achievements="no" achievement_types="all" steps="yes" ranks="no" rank_types="all" rank_requirements="no"]'); ?>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel">Actualizar mis datos</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <?php echo update_user_info_form(); ?>
                        </div>
                        </div>
                    </div>
                </div>
            <?php } else {?>
                <div class="pb-5 text-center profile-login">
                    <form id="login-form">
                        <input type="text" id="user_login" class="form-control input" placeholder="RUT">
                        <button id="submit">Enviar</button>
                    </form>
                    <div id="login-error"></div>
                </div>
           <?php  } ?>
        </div>
    </div>
</div>
<?php

get_footer();
