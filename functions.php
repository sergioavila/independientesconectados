<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

// BEGIN ENQUEUE PARENT ACTION
// AUTO GENERATED - Do not modify or remove comment markers above or below:

if ( !function_exists( 'chld_thm_cfg_locale_css' ) ):
    function chld_thm_cfg_locale_css( $uri ){
        if ( empty( $uri ) && is_rtl() && file_exists( get_template_directory() . '/rtl.css' ) )
            $uri = get_template_directory_uri() . '/rtl.css';
        return $uri;
    }
endif;
add_filter( 'locale_stylesheet_uri', 'chld_thm_cfg_locale_css' );
         
if ( !function_exists( 'child_theme_configurator_css' ) ):
    function child_theme_configurator_css() {
        wp_enqueue_style( 'chld_thm_cfg_ext1', 'https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,400&display=swap' );
    }
endif;
add_action( 'wp_enqueue_scripts', 'child_theme_configurator_css', 99999998 );

// END ENQUEUE PARENT ACTION
// 

function buscar_rut($rut) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'register_propt'; 
    $result = $wpdb->get_row($wpdb->prepare("SELECT NOMBRE, APELLIDOS FROM $table_name WHERE RUT = %s", $rut));
    return $result;
}

function mostrar_formulario_registro() {
    $nombre = '';
    $apellidos = '';
    $mostrar_formulario = false; // Inicialmente, el formulario de registro está oculto

    if (isset($_POST['submit_registro'])) {
        // Validar y procesar el formulario de registro
        $rut = sanitize_text_field($_POST['rut']);
        $nombre = sanitize_text_field($_POST['nombre']);
        $apellidos = sanitize_text_field($_POST['apellidos']);
        $email = sanitize_email($_POST['email']);
        $fecha_nacimiento = sanitize_text_field($_POST['fecha_nacimiento']);
        $rut_farmacia = sanitize_text_field($_POST['rut_farmacia']);
        $sucursal = sanitize_text_field($_POST['sucursal']);
        $password = sanitize_text_field($_POST['password']);

        // Validar la contraseña
        if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/', $password)) {
            echo 'La contraseña debe tener al menos 8 caracteres, incluyendo al menos una letra mayúscula, una letra minúscula y un número.';
            return;
        }

        // Crear un nuevo usuario en WordPress
        $user_id = wp_insert_user(array(
            'user_login' => $email,
            'user_email' => $email,
            'user_pass' => $password,
            'first_name' => $nombre,
            'last_name' => $apellidos,
            'role' => 'subscriber'
        ));

        if (is_wp_error($user_id)) {
            echo 'Error al registrar el usuario: ' . $user_id->get_error_message();
        } else {
            // Registro exitoso
            echo 'Usuario registrado exitosamente';
        }
    } elseif (isset($_POST['rut'])) {
        // Procesar el formulario de búsqueda
        $rut = sanitize_text_field($_POST['rut']);
        $registro = buscar_rut($rut);

        if ($registro) {
            $nombre = $registro->NOMBRE;
            $apellidos = $registro->APELLIDOS;
            $mostrar_formulario = true; // Mostrar el formulario de registro si se encuentra un RUT válido
        } else {
            // El RUT no es válido, redirigir a una nueva página
            $url_pagina_contacto = home_url('/contacto-registro'); // Reemplaza '/pagina-de-contacto' con la URL de tu página de contacto
            wp_redirect($url_pagina_contacto);
            exit;
        }
    }

    ob_start();

function mostrar_formulario_contacto() {
    ob_start();
    ?>
    <div id="formulario-contacto">
        <form action="" method="post">
            <!-- Agrega los campos y el diseño que necesitas para el formulario de contacto -->
            <input type="text" name="nombre_contacto" class="inputForm" placeholder="Nombre" required>
            <input type="email" name="email_contacto" class="inputForm" placeholder="Email" required>
            <textarea name="mensaje_contacto" class="inputForm" placeholder="Mensaje" required></textarea>
            <input type="submit" name="submit_contacto" class="btnReg" value="Enviar Mensaje">
        </form>
    </div>
    <?php
    return ob_get_clean();
}


    ?>
    <div id="formulario-busqueda" <?php echo $mostrar_formulario ? 'style="display: none;"' : ''; ?>>
        <form action="" method="post">
            <input type="text" name="rut" class="inputForm" placeholder="RUT" required>
            <input type="submit" class="btnReg" value="Buscar">
        </form>
    </div>

    <div id="formulario-registro" <?php echo $mostrar_formulario ? '' : 'style="display: none;"'; ?>>
        <form action="" method="post">
            <input type="text" name="rut" class="inputForm" placeholder="RUT" value="<?php echo esc_attr($rut); ?>" required>
            <input type="text" name="nombre" class="inputForm" placeholder="Nombre" value="<?php echo esc_attr($nombre); ?>" required>
            <input type="text" name="apellidos" class="inputForm" placeholder="Apellidos" value="<?php echo esc_attr($apellidos); ?>" required>
            <input type="email" name="email" class="inputForm" placeholder="Email" required>
            <input type="password" name="password" class="inputForm" placeholder="Contraseña" required>
            <input type="date" name="fecha_nacimiento" class="inputForm" placeholder="Fecha de nacimiento" required>
            <input type="text" name="rut_farmacia" class="inputForm" placeholder="RUT Farmacia" required>
            <input type="text" name="sucursal" class="inputForm" placeholder="Sucursal" required>
            <input type="submit" name="submit_registro" class="btnReg" value="Registrarse">
        </form>
    </div>

    


    <script>
    // JavaScript para mostrar u ocultar los formularios según la variable $mostrar_formulario
    document.addEventListener('DOMContentLoaded', function() {
        var formularioBusqueda = document.getElementById('formulario-busqueda');
        var formularioRegistro = document.getElementById('formulario-registro');
        
        if (<?php echo $mostrar_formulario ? 'true' : 'false'; ?>) {
            formularioBusqueda.style.display = 'none';
            formularioRegistro.style.display = 'block';
        } else {
            formularioBusqueda.style.display = 'block';
            formularioRegistro.style.display = 'none';
        }
    });
    </script>
    <?php
    return ob_get_clean();
}
add_shortcode('formulario_registro', 'mostrar_formulario_registro');

//create shortcode for login or profile if is logged
function menu_login() {
    $mostrar_formulario = ' <div class="logedinuser"><a href="/ingresar/">INGRESAR</a></div>';
    ob_start();
    if (is_user_logged_in()) {
        $mostrar_formulario = ' <div class="logedinuser">
            <p><span class="text">Tus puntos:</span><span class="puntos">12.600</span></p>
            <a href="#">
                <img src="https://placehold.co/300x300.png" />
            </a>
        </div>';
    }
    ob_start();
        echo $mostrar_formulario;
    return ob_get_clean();
}

add_shortcode('menu_login','menu_login');

//add queue scripts to all pages
function add_scripts() {
    wp_enqueue_script('jquery');
    wp_enqueue_script('child-template', get_stylesheet_directory_uri(). '/main.js', array('jquery'), '1.0.0', true);
    wp_enqueue_style('bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css', array(), '5.3.1', 'all');
    wp_enqueue_script('bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js', array(), '5.3.1', true);
}
add_action('wp_enqueue_scripts', 'add_scripts');

function obtener_posts_json() {
    //get all mayorista post type and meta producto
    $args = array(
        'post_type' => 'mayorista',
        'posts_per_page' => -1,
        'order' => 'ASC',
        'orderby' => 'title',
    );
    $query = new WP_Query($args);
    $mayoristas = array();

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $post_id = get_the_ID();
            $post_title = get_the_title();
            $post_thumbnail = get_the_post_thumbnail_url();
            $productos = get_field('producto', get_the_ID());
            $array_productos = array();
            foreach($productos as $producto){
                $array_productos[] = array(
                    'title' => $producto['descripcion'],
                    'sku' => $producto['sku']
                );
            }
            $mayoristas[] = array(
                'title' => $post_title,
                'thumbnail' => $post_thumbnail,
                'productos' => $array_productos,
            );
        }
        wp_reset_postdata();
    }

    $args = array(
        'post_type' => 'producto',
        'posts_per_page' => -1,
        'order' => 'ASC',
        'orderby' => 'title',
    );

    $query = new WP_Query($args);

    $posts = array();

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $mayoristas_producto = array();
            $query->the_post();
            $post_id = get_the_ID();
            $post_title = get_the_title();
            $sliders_producto = get_field('slider_producto', get_the_ID());
            $slider = '';
            foreach($sliders_producto as $slider){
                if($slider['tipo'] == 'img'){
                    $slider = $slider['imagen']['sizes']['medium_large'];
                    break;
                }
            }
            foreach($mayoristas as $mayorista){
                foreach($mayorista['productos'] as $producto){
                    if($producto['sku'] == get_field('sku_lab', get_the_ID())){
                        $mayoristas_producto[] = array(
                            'title' => $mayorista['title'],
                            'thumbnail' => $mayorista['thumbnail'],
                        );
                    }
                }
            }

            $posts[] = array(
                'id' => $post_id,
                'title' => $post_title,
                'sku' => get_field('sku_lab', get_the_ID()),
                'thumbnail' => $slider,
                'category' => '',
                'mayoristas' => $mayoristas_producto,
                'permalink' => get_the_permalink(),
            );
        }
    }

    wp_reset_postdata();

    header('Content-Type: application/json');
    echo json_encode($posts);
    exit;
}

add_action('wp_ajax_obtener_posts_json', 'obtener_posts_json');
add_action('wp_ajax_nopriv_obtener_posts_json', 'obtener_posts_json');

//add custom admin subpage to  wp-admin/edit.php?post_type=mayorista
function add_custom_subpage() {
    add_submenu_page(
        'edit.php?post_type=mayorista',
        'Actualizar stock',
        'Actualizar stock',
       'manage_options',
        'add-stock',
        'add_stock_page'
    );
}
add_action('admin_menu', 'add_custom_subpage');

//add add_stock_page
function add_stock_page() {
    global $wpdb;
    $table_name = $wpdb->prefix. 'posts';
    // require 'vendor/autoload.php'; // Carga la biblioteca PhpSpreadsheet
    // use PhpOffice\PhpSpreadsheet\IOFactory;
    ?>
    <div class="wrap">
        <h2>Cargar excel</h2>
        <form method="post" enctype="multipart/form-data">
            <input type="file" name="excel" />
            <input type="submit" name="submit" value="Cargar" />
        </form>
        <?php
        //read excel file
        // if (isset($_POST['submit'])) {
        //     if (isset($_FILES['excel']) && $_FILES['excel']['error'] == 0) {
        //         $fileTmpPath = $_FILES['excel']['tmp_name'];
        //         $fileName = $_FILES['excel']['name'];
        
        //         $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
        //         $allowedExtensions = ['xls', 'xlsx'];
        
        //         if (in_array($fileExtension, $allowedExtensions)) {
        //             $upload_dir = wp_upload_dir(); // Obtiene el directorio de carga de WordPress
        //             $uploadPath = $upload_dir['path'] . '/' . 'uploaded_excel.' . $fileExtension;
        
        //             if (move_uploaded_file($fileTmpPath, $uploadPath)) {
        //                 $spreadsheet = IOFactory::load($uploadPath);
        
        //                 // Obtén la primera hoja del libro de trabajo
        //                 $sheet = $spreadsheet->getActiveSheet();
        
        //                 // Obtén todos los datos de la hoja
        //                 $data = $sheet->toArray();
        
        //                 // Crea una tabla HTML
        //                 echo '<table border="1">';
        //                 foreach ($data as $row) {
        //                     echo '<tr>';
        //                     foreach ($row as $cell) {
        //                         echo '<td>' . $cell . '</td>';
        //                     }
        //                     echo '</tr>';
        //                 }
        //                 echo '</table>';
        
        //                 echo "Archivo cargado y leído correctamente.";
        //             } else {
        //                 echo "Error al mover el archivo a la ubicación deseada.";
        //             }
        //         } else {
        //             echo "Extensión de archivo no permitida. Solo se permiten archivos Excel.";
        //         }
        //     } else {
        //         echo "Error al cargar el archivo.";
        //     }
        // }
}