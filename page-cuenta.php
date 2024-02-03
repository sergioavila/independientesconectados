<?php

get_header();

$show_default_title = get_post_meta( get_the_ID(), '_et_pb_show_title', true );
acf_form_head();
$avatar = get_field('avatar');
?>
<h2 class="">Mi cuenta</h2>
<div id="main-content">
    <div class="container">
        <div id="content-area" class="profile-user">
            <?php if ( is_user_logged_in() ) {?>
                <div class="row">
                    <div class="col-12">
                        <div class="d-flex align-items-center">
                            <img src="<?php echo $avatar['url']; ?>" alt="" height="100" width="100" class="avatar rounded-circle border object-fit-cover">
                            <div class="">
                                <h3 class="card-title">Hola <?php echo wp_get_current_user()->display_name; ?></h3>
                                <h4>Tu farmacia: XXX</h4>
                                <h5>Rut: xxx</h5>
                                <a href="#">Actualizar información</a>
                                <?php //acf_form(); ?>
                            </div>
                        </div>
                        <div class="puntos-acumulados">
                            <h4>Puntos acumulados</h4>
                            <p><?php echo do_shortcode('[gamipress_points type="gamepress" inline ="yes" label="no" thumbnail="no" align="none"align="none"]');?></p>
                        </div>
                        <p>Canjea tus puntos en tus próximas compras.</p>
                        <a href="#" class="btn btn-primary">Canjear puntos</a>
                    </div>
                    <div class="col-12">
                        <p>Entre más interactués en la plataforma (Independientes Conectados) acumularás más puntos para canjearlos en tu próxima compra de medicamentos.</p>
                    </div>
                    <div class="col-12">
                        <p>Tus puntos acumulados</p>
                        <div class="d-flex">
                            <span>Bienvenida:</span> <span>+10.000 puntos</span>
                        </div>
                        <div class="d-flex">
                            <span>Aprendizaje:</span> <span>+2.500 puntos</span>
                        </div>
                    </div>
                </div>
            <?php } else {
                echo do_shortcode('[wppb-login]');
             } ?>
        </div>
    </div>
</div>
<?php

get_footer();
