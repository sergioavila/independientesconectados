<?php
    get_header();
    $show_default_title = get_post_meta( get_the_ID(), '_et_pb_show_title', true );
    $composicion_repeater = get_field('composicion_repeater', get_the_ID());
    $advertencia_de_lectura = get_field('advertencia_de_lectura', get_the_ID());
    $contenidos = get_field('contenidos', get_the_ID());
    $sliders_producto = get_field('slider_producto', get_the_ID());
    $descargable = get_field('descargable', get_the_ID());
    $descargable_profesional = get_field('descargable_profesional', get_the_ID());
?>

<div id="main">
    <div class="container p-lg-5 single-product">
        <div class="my-3">
            <h2 class="text-uppercase">Información del medicamento</h2>
        </div>
        <div id="content-area" class="card border-0 p-3 p-lg-5">
            <?php if ( is_user_logged_in() ) {?>
               <div class="row">
                <div class="col-lg-5">
                    <?php if(!empty($sliders_producto)): ?>
                    <div id="productCarousel" class="carousel slide">
                        <div class="carousel-inner border rounded p-5">
                            <?php
                                    $j = 0;
                                        foreach($sliders_producto as $slider){
                                            if($slider['tipo'] == 'img'){ ?>
                                            <div <?php echo $j==0 ? 'class="carousel-item active"': 'class="carousel-item"'; ?>>
                                                <img src="<?php echo $slider['imagen']['sizes']['medium_large'];?>" alt="" class="img-fluid w-100" />
                                            </div>
                                        <?php
                                            $j++;
                                        }
                                    }
                                ?>
                            </div>
                            <div class="carousel-indicators mt-3">
                            <?php
                            $i = 0;
                            foreach($sliders_producto as $slider){
                                if($slider['tipo'] == 'img'){ ?>
                                <button type="button" data-bs-target="#productCarousel" data-bs-slide-to="<?php echo $i;?>" <?php echo $i==0 ? 'class="active p-1 border rounded"': 'class="p-1 border rounded"'; ?> aria-current="true" aria-label="Slide <?php echo $i;?>">
                                    <img src="<?php echo $slider['imagen']['sizes']['thumbnail'];?>" alt="" class="img-fluid" />
                                </button>
                            <?php
                                $i++;
                                }
                            }
                            ?>
                        </div>
                    </div>
                    <?php endif;?>
                    <?php if(!empty($descargable) OR !empty($descargable_profesional)): ?>
                    <div class="descargas mt-5">
                        <?php if(!empty($descargable)): ?>
                        <a class="btn btn-primary mb-3" href="<?php echo $descargable['url'];?>" download>Descargar folleto paciente</a>
                        <?php endif;?>
                        <?php if(!empty($descargable_profesional)): ?>
                        <a class="btn btn-primary"  href="<?php echo $descargable_profesional['url'];?>" download>Descargar folleto profesional</a>
                        <?php endif;?>
                    </div>
                    <?php endif;?>
                </div>
                <div class="col-lg-7 ps-lg-5">
                    <h1>
                        <?php the_title();?>
                    </h1>
                    <ul>
                        <?php
                            if(!empty($composicion_repeater)){
                                foreach($composicion_repeater as $composicion){
                                    echo '<li class="product__list__item">'.$composicion['composicion'].'</li>';
                                }
                            }
                        ?>
                    </ul>
                    <div class="disponible mt-4">
                        <h3>Disponible en:</h3>
                    </div>
                    <div class="mt-5">
                        <?php if(!empty($advertencia_de_lectura)){
                            echo $advertencia_de_lectura;
                        }?>
                    </div>
                    <div>
                        <?php
                            if(!empty($contenidos)){
                                foreach($contenidos as $contenido){
                                    echo $contenido['contenido'];
                                }
                            }
                        ?>
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
