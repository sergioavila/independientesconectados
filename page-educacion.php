<?php

get_header();

$show_default_title = get_post_meta( get_the_ID(), '_et_pb_show_title', true );

?>

<div id="main">
    <div class="">
        <div id="content-area" class="pt-4 educacion">
            <h1 class="my-4" >Contenido educativo disponible</h1>
            <p class="mb-5">Mientras más interactúas, más puntos conectados obtienes</p>
            <?php 
            //gett all posts educacion
            $args = array(
                'post_type' => 'educacion',
                'posts_per_page' => -1,
                'orderby' => 'date',
                'order' => 'DESC'
            );
            $query = new WP_Query($args);
            if($query->have_posts()) : ?>
                <div class="row gy-4">
                    <?php while($query->have_posts()) : $query->the_post(); ?>
                        <div class="col-12 col-md-6 col-lg-4">
                            <div class="card card-search border-0 h-100">
                                <div class="card-body">
                                    <a href="<?php the_permalink(); ?>" class="">
                                        <img src="<?php echo the_post_thumbnail_url(); ?>" class="card-img-top" alt="<?php the_title(); ?>">
                                    </a>
                                    <h4 class="card-title mt-4"><?php the_title(); ?></h4>
                                    <div class="d-flex justify-content-between align-items-center pt-4">
                                        <a href="<?php the_permalink(); ?>" class="btn btn-primary btn-blue">Abrir</a>
                                        <p class="blue"><?php echo get_field('puntos');?> Puntos</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php

get_footer();
