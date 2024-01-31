<?php

get_header();

$show_default_title = get_post_meta( get_the_ID(), '_et_pb_show_title', true );

?>

<div id="main-content">
    <div class="container">
        <div id="content-area" class="">
            <?php if ( is_user_logged_in() ) {?>
                perfil del usuario
            <?php } else {
                echo do_shortcode('[wppb-login]');
             } ?>
        </div>
    </div>
</div>
<?php

get_footer();
