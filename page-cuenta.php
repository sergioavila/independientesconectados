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
                    $user_info = get_userdata($user_id);
                    $nombre = $user_info->first_name;
                    $apellido = $user_info->last_name;
                    $rut = get_field('rut', 'user_'. $user_id);
                    $telefono = get_field('telefono', 'user_'. $user_id);
                    $correo = get_field('correo', 'user_'. $user_id);
                    $fecha = get_field('fecha', 'user_'. $user_id);
                    $rutfarmacia = get_field('rutfarmacia', 'user_'. $user_id);
                    $notificaciones = get_field('notificaciones', 'user_'. $user_id);
                    $direccion = get_field('direccion', 'user_'. $user_id);
                    ?>
                        <div class="row">
                            <div class="col ">
                                <div class="d-flex align-items-center justify-content-center">
                                    <div height="120" width="120" class="">
                                        <?php echo $avatar; ?>
                                    </div>
                                    <div class="">
                                        <h3 class="card-title color-blue">Hola <?php echo wp_get_current_user()->first_name; ?> <?php echo wp_get_current_user()->last_name; ?> </h3>
                                        <h4 class="color-blue">RUT farmacia:  <?php echo get_field('rutfarmacia', 'user_'.$user_id);?></h4>
                                        <h5>Rut: <?php echo get_field('rut', 'user_'.$user_id);?></h5>
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
                                    <form class="px-md-3" id="update" method="get" action="">
                                        <fieldset>
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label for="name" class="form-label">Nombre completo</label>
                                                        <input type="text" name="name" class="form-control" id="name" value="<?php echo $nombre; ?>" required>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label for="lastname" class="form-label">Apellidos</label>
                                                        <input type="text" name="lastname" class="form-control" id="lastname" value="<?php echo $apellido; ?>" required>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label for="rut" class="form-label">RUT</label>
                                                        <input type="text" class="form-control" name="rut" id="rut" value="<?php echo $rut; ?>" required>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label for="fecha" class="form-label">Fecha de nacimiento</label>
                                                        <input type="date" class="form-control" id="fecha" name="fecha" value="<?php echo $fecha; ?>" required>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label for="correo" class="form-label">Correo electrónico</label>
                                                        <input type="email" class="form-control" id="correo" name="correo" value="<?php echo $correo; ?>" required>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="h-100 justify-content-center d-flex align-items-center">
                                                        <a href="#" class="link">Recibir notificaciones</a>
                                                    </div>
                                                </div>
                                                <hr>
                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label for="rutfarmacia" class="form-label">RUT Farmacia</label>
                                                        <input type="text" class="form-control" id="rutfarmacia" name="rutfarmacia" value="<?php echo $rutfarmacia; ?>" required>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label for="direccion" class="form-label">Dirección Farmacia</label>
                                                        <input type="text" class="form-control" id="direccion" name="direccion" value="<?php echo $direccion; ?>" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12 text-center">
                                                <input type="submit" class="btn btn-primary btn-blue mt-5 mb-3" value="Actualizar datos">
                                            </div>
                                        </fieldset>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <script>
                          jQuery("#update").validate({
                            rules: {
                            rut: { validateRut: true },
                            name: { validaDosPalabras: true },
                            lastname: { validaDosPalabras: true },
                            correo: { required: true },
                            fecha: { required: true },
                            direccion: { required: true },
                            rutfarmacia: { validateRut: true },
                            },
                            messages: {
                            rut: "Ingresa un RUT válido.",
                            name: "Ingresa tu nombre completo.",
                            lastname: "Ingresa tus apellidos.",
                            fecha: "Ingresa una fecha válida.",
                            correo: "Ingresa un correo electrónico válido.",
                            direccion: "Ingresa la dirección de tu farmacia",
                            rutfarmacia: "Ingresa el RUT de tu farmacia.",
                            },
                            submitHandler: function (form) {
                            let formData = new FormData(form);
                            formData.append("action", "update_quimico");
                            jQuery.ajax({
                                url: ajaxurl,
                                type: "POST",
                                data: formData,
                                contentType: false,
                                processData: false,
                                success: function (response) {
                                if (response.success) {
                                    window.location.href = "/cuenta";
                                } else {
                                    window.location.href = "/cuenta";
                                }
                                },
                                error: function (response) {
                                console.log("error", response);
                                jQuery("#update-error").show();
                                },
                            });
                            },
                        });
                    </script>
                <?php
                //if post forrm is submitted
                if (isset($_POST['submit'])) {
                    echo '<div class="alert alert-success">Información actualizada</div>';
                }
                ?>
            <?php } else {?>
                <div class="pb-5 text-center profile-login">
                    <form id="login-form">
                        <input type="text" id="user_login" name="user_login" class="form-control input" placeholder="RUT" required>
                        <div id="login-error"></div>
                        <button id="submit">Enviar</button>
                    </form>
                </div>
           <?php  } ?>
        </div>
    </div>
</div>
<?php

get_footer();
