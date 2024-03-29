<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;
add_filter( 'use_widgets_block_editor', '__return_false' );
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


//create shortcode for login or profile if is logged
function menu_login() {
    $mostrar_formulario = ' <div class="logedinuser"><a href="/cuenta/" class="btn-ingresar">INGRESAR</a></div>';
    ob_start();
    if (is_user_logged_in()) {
        $user_id = get_current_user_id();
        $avatar = get_avatar($user_id) ? get_avatar($user_id) : 'https://placehold.co/300x300.png';
        $logout_link = wp_logout_url(home_url('/'));
        $mostrar_formulario = ' <div class="logedinuser">
        <a href="/cuenta" data-bs-toggle="tooltip" data-bs-title="Revisa tus puntos" class="d-none d-md-flex"><p><span class="text">Tus puntos:</span><span class="puntos">'.do_shortcode('[gamipress_points type="puntos" user_id="'.$user_id.'" inline ="yes" label="no" thumbnail="no" align="none"align="none"]').'</span></p></a>
            <a href="/cuenta" data-bs-toggle="tooltip" data-bs-title="Revisa tu cuenta">
                '.$avatar.'
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

    // Luego, carga Bootstrap
    wp_enqueue_style('bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css', array(), '5.3.1', 'all');
    wp_enqueue_script('bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js', array('jquery'), '5.3.1', true);

    // Luego, carga DataTables
    wp_enqueue_style('datatables', 'https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css', array(), '1.11.5', 'all');
    wp_enqueue_script('datatables', 'https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js', array('jquery', 'bootstrap'), '1.11.5', true);

    // Luego, carga jQuery Validate
    wp_enqueue_script( 'validate', '//cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js', array('jquery'));
    wp_enqueue_script( 'extravalidate', '//cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/additional-methods.min.js', array('jquery','validate'));

    // Luego, carga Inputmask
    wp_enqueue_script('inputmask', '//cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/3.3.4/jquery.inputmask.bundle.min.js', array('jquery'), '5.0.8', true);

    // Finalmente, carga tu script personalizado
    wp_enqueue_script('child-template', get_stylesheet_directory_uri(). '/main.js', array('jquery', 'validate', 'inputmask', 'datatables'), '1.1.0', true);
}
add_action('wp_enqueue_scripts', 'add_scripts');

//get mayorista
function obtener_mayorista($title) {
    $mayorista = get_page_by_title($title, OBJECT, 'mayorista');
    if (!$mayorista) {
        return false;
    }
    $id = $mayorista->ID;
    $title = $mayorista->post_title;
    $thumbnail = get_the_post_thumbnail_url($id);
    $permalink = get_the_permalink($id);
    return array(
        'id' => $id,
        'title' => $title,
        'logo' => $thumbnail,
        'link' => $permalink
    );
}

function obtener_posts_json() {

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
            $mayoristas_producto[] = get_field('stock_farma1', get_the_ID()) ? obtener_mayorista('CENTRAL ABAST FARMA 7 S A') : false;
            $mayoristas_producto[] = get_field('stock_farma2', get_the_ID()) ? obtener_mayorista('CLINICAL MARKET S A') : false;
            $mayoristas_producto[] = get_field('stock_farma3', get_the_ID()) ? obtener_mayorista('DROGUERIA GLOBAL PHARMA SPA') : false;
            $mayoristas_producto[] = get_field('stock_farma4', get_the_ID()) ? obtener_mayorista('ETHON PHARMACEUTICALS SPA') : false;
            $mayoristas_producto[] = get_field('stock_farma5', get_the_ID()) ? obtener_mayorista('MEDIVEN SPA') : false;
            $mayoristas_producto[] = get_field('stock_farma6', get_the_ID()) ? obtener_mayorista('RAMIREZ Y SANCHEZ LTDA') : false;
            $mayoristas_producto[] = get_field('stock_farma7', get_the_ID()) ? obtener_mayorista('TOLEDO SOCIEDAD ANONIMA') : false;

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
    require_once('vendor/php-excel-reader/excel_reader2.php');
    require_once('vendor/SpreadsheetReader.php');
    ?>
    <div class="wrap">
        <h2>Cargar excel</h2>
        <form method="post" enctype="multipart/form-data">
            <input type="file" name="file" id="file"/>
            <input type="submit" name="import" value="Cargar" />
        </form>
        <?php
        if (isset($_POST["import"])){
            $allowedFileType = ['application/vnd.ms-excel','text/xls','text/xlsx','application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];
              if(in_array($_FILES["file"]["type"],$allowedFileType)){
                    $targetPath = wp_upload_dir()['path'] . '/' . $_FILES['file']['name'];
                    move_uploaded_file($_FILES['file']['tmp_name'], $targetPath);
                    $Reader = new SpreadsheetReader($targetPath);
                    $sheetCount = count($Reader->sheets());
                    if($sheetCount > 1){
                        $type = "error";
                        $message = "El archivo contiene más de una hoja. Por favor vuelva a intentarlo";
                    }
                    //expected number of columns
                    $expectedColumns = 4;
                    $fileColumns = count($Reader->current());
                    if($fileColumns != $expectedColumns){
                        $type = "error";
                        $message = "El archivo contiene un número de columnas incorrecto. Por favor vuelva a intentarlo";
                    }
                    $data  = array();
                    for($i=0;$i<$sheetCount;$i++){
                        $Reader->ChangeSheet($i);
                        $firstRow = true;
                        $headers = array();
                        foreach ( $Reader as $Row ) {
                            //skip first
                            if($firstRow){
                                $firstRow = false;
                                $headers = $Row;
                                continue;
                            }
                            $data[] = array(
                                'sku' => $Row[0],
                                'name' => $Row[1],
                                'farma1' => $Row[2] == "SI" ?  true : false,
                                'farma2' => $Row[3] == "SI" ?  true : false,
                                'farma3' => $Row[4] == "SI" ?  true : false,
                                'farma4' => $Row[5] == "SI" ?  true : false,
                                'farma5' => $Row[6] == "SI" ?  true : false,
                                'farma6' => $Row[7] == "SI" ?  true : false,
                                'farma7' => $Row[8] == "SI" ?  true : false,
                            );
                        };
                     }
                     echo "Se han encontrado ". count($data). " productos<br>";
                     echo "Actualizando...<br>";
                     update_stock($data);

              } else {
                    $type = "error";
                    $message = "El archivo enviado es invalido. Por favor vuelva a intentarlo";
              }
            }
}

//update stock in mayorista posttype meta acf 
function update_stock($data) {
    foreach ($data as $farma){
        foreach ($farma as $key => $value){
            if($key == 'sku'){
                $value = str_replace(' ', '', $value);
                $args = array(
                    'post_type' => 'producto',
                    'posts_per_page' => 1,
                    'meta_key' => 'sku_lab',
                    'meta_value' => $value,
                    'meta_compare' => '='
                );
                $query = new WP_Query($args);
                if ($query->have_posts()) {
                    $query->the_post();
                    $post_id = get_the_ID();
                    echo get_the_title($post_id). " actualizado<br>";
                    update_field('stock_farma1', $farma['farma1'], $post_id);
                    update_field('stock_farma2', $farma['farma2'], $post_id);
                    update_field('stock_farma3', $farma['farma3'], $post_id);
                    update_field('stock_farma4', $farma['farma4'], $post_id);
                    update_field('stock_farma5', $farma['farma5'], $post_id);
                    update_field('stock_farma6', $farma['farma6'], $post_id);
                    update_field('stock_farma7', $farma['farma7'], $post_id);
                }
            }
        }
    }

}

function custom_breadcrumbs() {
    $delimiter = ' ';
    $home = 'Inicio'; // Texto para el enlace 'Inicio'
    $showCurrent = 1; // 1 - Mostrar el título actual de la página, 0 - No mostrar
    $before = '<li class="breadcrumb-item">'; // Etiqueta antes de la miga de pan actual
    $after = '</li>'; // Etiqueta después de la miga de pan actual

    //skip if home page
    if (is_front_page()) {
        return;
    }

    global $post;
    $homeLink = get_bloginfo('url');

    echo '<nav aria-label="breadcrumb">';
    echo '<ol class="breadcrumb">';
    echo '<li class="breadcrumb-item"><a href="' . $homeLink . '">' . $home . '</a></li>' . $delimiter . ' ';

    if (is_category() || is_single()) {
        $category = get_the_category();

        if ($category) {
            $category_id = $category[0]->cat_ID;
            $category_parents = get_category_parents($category_id, true, ' ' . $delimiter . ' ');

            if ($category_parents) {
                echo $category_parents;
            }
        }
    }

    if (is_single()) {
       
        //if is type question
        if ($post->post_type == 'question') {
            echo $before . '<a href="/foro">Foro</a>' . $after;
        }
        if ($post->post_type == 'educacion') {
            echo $before . '<a href="/educacion">Educación</a>' . $after;
        }
        echo $before . get_the_title() . $after;
        
    } elseif (is_page() && !$post->post_parent) {
        echo $before . get_the_title() . $after;
    } elseif (is_page() && $post->post_parent) {
        $parent_id = $post->post_parent;
        $breadcrumbs = array();

        while ($parent_id) {
            $page = get_page($parent_id);
            $breadcrumbs[] = '<a href="' . get_permalink($page->ID) . '">' . get_the_title($page->ID) . '</a>';
            $parent_id = $page->post_parent;
        }

        $breadcrumbs = array_reverse($breadcrumbs);

        foreach ($breadcrumbs as $crumb) {
            echo $crumb . ' ' . $delimiter . ' ';
        }
    }

    if (is_home()) {
        if ($showCurrent == 1) echo $before . 'Blog' . $after;
    }
    echo '</ol>';
    echo '</nav>';
}
//add shortcode [custom_breadcrumbs]
add_shortcode('custom_breadcrumbs', 'custom_breadcrumbs');
// bootstrap 5 wp_nav_menu walker
class bootstrap_5_wp_nav_menu_walker extends Walker_Nav_menu
{
  private $current_item;
  private $dropdown_menu_alignment_values = [
    'dropdown-menu-start',
    'dropdown-menu-end',
    'dropdown-menu-sm-start',
    'dropdown-menu-sm-end',
    'dropdown-menu-md-start',
    'dropdown-menu-md-end',
    'dropdown-menu-lg-start',
    'dropdown-menu-lg-end',
    'dropdown-menu-xl-start',
    'dropdown-menu-xl-end',
    'dropdown-menu-xxl-start',
    'dropdown-menu-xxl-end'
  ];

  function start_lvl(&$output, $depth = 0, $args = null)
  {
    $dropdown_menu_class[] = '';
    foreach($this->current_item->classes as $class) {
      if(in_array($class, $this->dropdown_menu_alignment_values)) {
        $dropdown_menu_class[] = $class;
      }
    }
    $indent = str_repeat("\t", $depth);
    $submenu = ($depth > 0) ? ' sub-menu' : '';
    $output .= "\n$indent<ul class=\"dropdown-menu$submenu " . esc_attr(implode(" ",$dropdown_menu_class)) . " depth_$depth\">\n";
  }

  function start_el(&$output, $item, $depth = 0, $args = null, $id = 0)
  {
    $this->current_item = $item;

    $indent = ($depth) ? str_repeat("\t", $depth) : '';

    $li_attributes = '';
    $class_names = $value = '';

    $classes = empty($item->classes) ? array() : (array) $item->classes;

    $classes[] = ($args->walker->has_children) ? 'dropdown' : '';
    $classes[] = 'nav-item';
    $classes[] = 'nav-item-' . $item->ID;
    if ($depth && $args->walker->has_children) {
      $classes[] = 'dropdown-menu dropdown-menu-end';
    }

    $class_names =  join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args));
    $class_names = ' class="' . esc_attr($class_names) . '"';

    $id = apply_filters('nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args);
    $id = strlen($id) ? ' id="' . esc_attr($id) . '"' : '';

    $output .= $indent . '<li ' . $id . $value . $class_names . $li_attributes . '>';

    $attributes = !empty($item->attr_title) ? ' title="' . esc_attr($item->attr_title) . '"' : '';
    $attributes .= !empty($item->target) ? ' target="' . esc_attr($item->target) . '"' : '';
    $attributes .= !empty($item->xfn) ? ' rel="' . esc_attr($item->xfn) . '"' : '';
    $attributes .= !empty($item->url) ? ' href="' . esc_attr($item->url) . '"' : '';

    $active_class = ($item->current || $item->current_item_ancestor || in_array("current_page_parent", $item->classes, true) || in_array("current-post-ancestor", $item->classes, true)) ? 'active' : '';
    $nav_link_class = ( $depth > 0 ) ? 'dropdown-item ' : 'nav-link ';
    $attributes .= ( $args->walker->has_children ) ? ' class="'. $nav_link_class . $active_class . ' dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"' : ' class="'. $nav_link_class . $active_class . '"';

    $item_output = $args->before;
    $item_output .= '<a' . $attributes . '>';
    $item_output .= $args->link_before . apply_filters('the_title', $item->title, $item->ID) . $args->link_after;
    $item_output .= '</a>';
    $item_output .= $args->after;

    $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
  }
}
// register a new menu
register_nav_menu('main-menu', 'Main menu');
//add custom header site logo, menu and search function
function custom_header_setup() { ?>
    <div class="container">
        <div class="row">
            <div class="col-md-12 g-0">
                <div class="navbar navbar-default">
                    <div class="navbar-header">
                        <a href="<?php echo esc_url(home_url('/')); ?>" class="navbar-brand">
                            <img src="<?php echo get_stylesheet_directory_uri(); ?>/logo.png" width="230" alt="<?php echo esc_attr(get_bloginfo('name', 'display')); ?>">
                        </a>
                    </div>
                    <div class="menu-principal d-none d-md-block">
                        <?php
                        wp_nav_menu(array(
                            'theme_location' => 'main-menu',
                            'container' => false,
                            'menu_class' => '',
                            'fallback_cb' => '__return_false',
                            'items_wrap' => '<ul id="%1$s" class="navbar-nav d-flex flex-row justify-content-between mb-md-0 %2$s">%3$s</ul>',
                            'depth' => 2,
                            'walker' => new bootstrap_5_wp_nav_menu_walker()
                        ));?>
                    </div>
                    <?php echo do_shortcode('[menu_login]');?>
                </div>
            </div>
            <div class="col-12">
                <?php echo custom_breadcrumbs();?>
            </div>
    </div>
<?php }
add_action('bootstrap_header', 'custom_header_setup');
// add shortcode custom_header_setup
add_shortcode('custom_header_setup', 'custom_header_setup');

//get post type quimico if rut acf exist
function get_quimico(){
    global $wpdb;
    $rut = $_POST['rut'];
    if (empty($rut)) {
        echo 'Error: No se envió el rut';
        return;
    }
    $rut = str_replace(array('.', '-'), '', $rut);
    $rut = substr($rut, 0, -1);
    $sql = "SELECT * FROM wp_qfs_teva WHERE rut = '$rut'";
    $results = $wpdb->get_row($sql);
    if (empty($results)) {
        echo '<p class="mb-3">No se encontró el RUT en nuestra base de datos. Si quieres ser parte de Independientes Conectados ';
        echo '<a href="mailto:admin@independientesconectados.cl" target="_blank" class="link">puedes contactarte con nuestro equipo.</a></p>';
        exit();
    }
    else {
        $user = get_user_by('login', $results->rut);
        if (!$user) {
            //add new user to wp
            $userdata = array(
                'user_login' => $results->rut,
                'user_pass' => $results->rut,
                'user_email' => $results->rut. '@independientesconectados.cl',
                'first_name' => $results->nombre,
                'last_name' => $results->apellido1. ' '. $results->apellido2,
            );
            $user_id = wp_insert_user($userdata);
            if (is_wp_error($user_id)) {
                $response = array(
                    'success' => false,
                    'message' => 'Error al crear el usuario'
                );
                header('Content-Type: application/json');
                echo json_encode($response);
                return;
            }else{
                //login user
                wp_set_current_user($user_id);
                wp_set_auth_cookie($user_id);
                $data['success'] = true;
                header('Content-Type: application/json');
                echo json_encode($data);
                exit;
            }
        }else{
            //login user
            $user_id = $user->ID;
            wp_set_current_user($user_id);
            wp_set_auth_cookie($user_id);
            $data['success'] = true;
            header('Content-Type: application/json');
            echo json_encode($data);
            exit;
        }
    }
}

add_action('wp_ajax_get_quimico', 'get_quimico');
add_action('wp_ajax_nopriv_get_quimico', 'get_quimico');

//add footer modal if user has no name acf
function add_footer_modal() {
    if (is_user_logged_in()) {
        $user_id = get_current_user_id();
        $user_info = get_userdata($user_id);
        $nombre = $user_info->first_name;
        $apellido = $user_info->last_name;
        $direccion = get_field('direccion', 'user_'. $user_id);
        $correo = get_field('correo', 'user_'. $user_id);
        $rut = get_field('rut', 'user_'. $user_id);
        $fecha = get_field('fecha', 'user_'. $user_id);
        $rutfarmacia = get_field('rutfarmacia', 'user_'. $user_id);
        if (!$direccion) {
            $page_id = get_queried_object_id();
            $page = get_post($page_id);
            $page_slug = $page->post_name;
            if ($page_slug != 'cuenta') {
                echo '<script>
                document.addEventListener("DOMContentLoaded", function () {
                    window.location.href = "/cuenta";
                });
                </script>';
                exit;
            }
            echo '<div class="modal" tabindex="-1" id="modal-footer" data-bs-keyboard="false">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body">
                        <h3 class="text-center titleblue mt-4">Debes completar tu perfīl</h3>
                        <p class="text-center mb-5 mb-md-3"> Para continuar tu registro en Independientes Conectados debes completar la siguiente información.</p>';?>
                        <form class="px-md-3" id="updateForm" method="get" action="">
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
                        <script>
                              jQuery("#updateForm").validate({
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
        <?php echo          '</div>
                        </div>
                    </div>
                </div>';
                echo '<script>
                document.addEventListener("DOMContentLoaded", function () {
                    const modal = new bootstrap.Modal(document.getElementById("modal-footer"), {
                        backdrop: "static"
                    });
                    modal.show();
                });
                </script>';

        }
        else{
            return;
        }
    }
    else{
        footer_bar();
    }
}
add_action('wp_footer', 'add_footer_modal');

//register footer bar
add_action('wp_footer', 'footer_bar');
function footer_bar() {
    if (!is_page('Cuenta') && !is_front_page() && !is_user_logged_in()) {
        echo '<div class="footer-bar">
            <div class="container h-100">
                <div class="row h-100 align-items-center">
                    <div class="col-md-4">
                        <img src="'.get_stylesheet_directory_uri().'/logo.png" alt="Logo Footer" class="img-fluid px-5">
                        <p class="text-center">Regístrate para ver los beneficios y descuentos exclusivos.</p>
                    </div>
                    <div class="col-md-8 d-flex justify-content-center">
                        <div class="row gy-4">
                            <div class="col-md-4">
                                <div class="card card-blue">
                                    <h4>LÍNEA DE SOPORTE</h4>
                                    <p>Contacta a nuestra línea de soporte
                                    y obten asistencia personalizada</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card card-blue">
                                    <h4>LÍNEA DE SOPORTE</h4>
                                    <p>Contacta a nuestra línea de soporte
                                    y obten asistencia personalizada</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card card-blue">
                                    <h4>LÍNEA DE SOPORTE</h4>
                                    <p>Contacta a nuestra línea de soporte
                                    y obten asistencia personalizada</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card card-blue">
                                    <h4>LÍNEA DE SOPORTE</h4>
                                    <p>Contacta a nuestra línea de soporte
                                    y obten asistencia personalizada</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card card-blue">
                                    <h4>LÍNEA DE SOPORTE</h4>
                                    <p>Contacta a nuestra línea de soporte
                                    y obten asistencia personalizada</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>';
        echo '<script>
        document.addEventListener("DOMContentLoaded", function () {
            var footerBar = document.querySelector(".footer-bar");
            setTimeout(function () {
                footerBar.classList.add("show");
                window.scrollTo({
                    top: 0,
                    behavior: "smooth"
                });
                document.documentElement.style.overflow = "hidden";
                document.body.style.overflow = "hidden";
            }, 1500);
        });
        </script>';
    }
}

//create custom html code shortcode
add_shortcode('custom_html', 'custom_html_shortcode');
function custom_html_shortcode() {
    $html = '</footer>
    <script>
        jQuery(document).ready(function($) {
            jQuery(".open-modal").on("click", function () {
                console.log("click");
                var modal = new bootstrap.Modal(document.getElementById("modal-soporte"), {
                    backdrop: "static"
                });
                modal.show();
            });
        });
    </script>
    <div class="modal fade" id="modal-soporte" tabindex="-1" aria-labelledby="modal-soporteLabel" aria-hidden="true">
        <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
            <h1 class="modal-title fs-5" id="modal-soporteLabel">Soporte en línea</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <a href="mailto:contacto@teva.cl" class="link d-flex">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M3 3H21C21.5523 3 22 3.44772 22 4V20C22 20.5523 21.5523 21 21 21H3C2.44772 21 2 20.5523 2 20V4C2 3.44772 2.44772 3 3 3ZM12.0606 11.6829L5.64722 6.2377L4.35278 7.7623L12.0731 14.3171L19.6544 7.75616L18.3456 6.24384L12.0606 11.6829Z"></path></svg> contacto@teva.cl
                </a>
                <a href="tel:+56985644090" class="link d-flex">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M21 16.42V19.9561C21 20.4811 20.5941 20.9167 20.0705 20.9537C19.6331 20.9846 19.2763 21 19 21C10.1634 21 3 13.8366 3 5C3 4.72371 3.01545 4.36687 3.04635 3.9295C3.08337 3.40588 3.51894 3 4.04386 3H7.5801C7.83678 3 8.05176 3.19442 8.07753 3.4498C8.10067 3.67907 8.12218 3.86314 8.14207 4.00202C8.34435 5.41472 8.75753 6.75936 9.3487 8.00303C9.44359 8.20265 9.38171 8.44159 9.20185 8.57006L7.04355 10.1118C8.35752 13.1811 10.8189 15.6425 13.8882 16.9565L15.4271 14.8019C15.5572 14.6199 15.799 14.5573 16.001 14.6532C17.2446 15.2439 18.5891 15.6566 20.0016 15.8584C20.1396 15.8782 20.3225 15.8995 20.5502 15.9225C20.8056 15.9483 21 16.1633 21 16.42Z"></path></svg> +56 9 85644090
                </a>
                <a href="https://api.whatsapp.com/send?phone=56985644090" class="link d-flex">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M12.001 2C17.5238 2 22.001 6.47715 22.001 12C22.001 17.5228 17.5238 22 12.001 22C10.1671 22 8.44851 21.5064 6.97086 20.6447L2.00516 22L3.35712 17.0315C2.49494 15.5536 2.00098 13.8345 2.00098 12C2.00098 6.47715 6.47813 2 12.001 2ZM8.59339 7.30019L8.39232 7.30833C8.26293 7.31742 8.13607 7.34902 8.02057 7.40811C7.93392 7.45244 7.85348 7.51651 7.72709 7.63586C7.60774 7.74855 7.53857 7.84697 7.46569 7.94186C7.09599 8.4232 6.89729 9.01405 6.90098 9.62098C6.90299 10.1116 7.03043 10.5884 7.23169 11.0336C7.63982 11.9364 8.31288 12.8908 9.20194 13.7759C9.4155 13.9885 9.62473 14.2034 9.85034 14.402C10.9538 15.3736 12.2688 16.0742 13.6907 16.4482C13.6907 16.4482 14.2507 16.5342 14.2589 16.5347C14.4444 16.5447 14.6296 16.5313 14.8153 16.5218C15.1066 16.5068 15.391 16.428 15.6484 16.2909C15.8139 16.2028 15.8922 16.159 16.0311 16.0714C16.0311 16.0714 16.0737 16.0426 16.1559 15.9814C16.2909 15.8808 16.3743 15.81 16.4866 15.6934C16.5694 15.6074 16.6406 15.5058 16.6956 15.3913C16.7738 15.2281 16.8525 14.9166 16.8838 14.6579C16.9077 14.4603 16.9005 14.3523 16.8979 14.2854C16.8936 14.1778 16.8047 14.0671 16.7073 14.0201L16.1258 13.7587C16.1258 13.7587 15.2563 13.3803 14.7245 13.1377C14.6691 13.1124 14.6085 13.1007 14.5476 13.097C14.4142 13.0888 14.2647 13.1236 14.1696 13.2238C14.1646 13.2218 14.0984 13.279 13.3749 14.1555C13.335 14.2032 13.2415 14.3069 13.0798 14.2972C13.0554 14.2955 13.0311 14.292 13.0074 14.2858C12.9419 14.2685 12.8781 14.2457 12.8157 14.2193C12.692 14.1668 12.6486 14.1469 12.5641 14.1105C11.9868 13.8583 11.457 13.5209 10.9887 13.108C10.8631 12.9974 10.7463 12.8783 10.6259 12.7616C10.2057 12.3543 9.86169 11.9211 9.60577 11.4938C9.5918 11.4705 9.57027 11.4368 9.54708 11.3991C9.50521 11.331 9.45903 11.25 9.44455 11.1944C9.40738 11.0473 9.50599 10.9291 9.50599 10.9291C9.50599 10.9291 9.74939 10.663 9.86248 10.5183C9.97128 10.379 10.0652 10.2428 10.125 10.1457C10.2428 9.95633 10.2801 9.76062 10.2182 9.60963C9.93764 8.92565 9.64818 8.24536 9.34986 7.56894C9.29098 7.43545 9.11585 7.33846 8.95659 7.32007C8.90265 7.31384 8.84875 7.30758 8.79459 7.30402C8.66053 7.29748 8.5262 7.29892 8.39232 7.30833L8.59339 7.30019Z"></path></svg>  +56 9 85644090
                </a>
            </div>
        </div>
        </div>
    </div>
    <a id="whatsappsupport" href="https://api.whatsapp.com/send?phone=56985644090" target="_blank">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="18" height="18" fill="rgba(255,255,255,1)"><path d="M7.25361 18.4944L7.97834 18.917C9.18909 19.623 10.5651 20 12.001 20C16.4193 20 20.001 16.4183 20.001 12C20.001 7.58172 16.4193 4 12.001 4C7.5827 4 4.00098 7.58172 4.00098 12C4.00098 13.4363 4.37821 14.8128 5.08466 16.0238L5.50704 16.7478L4.85355 19.1494L7.25361 18.4944ZM2.00516 22L3.35712 17.0315C2.49494 15.5536 2.00098 13.8345 2.00098 12C2.00098 6.47715 6.47813 2 12.001 2C17.5238 2 22.001 6.47715 22.001 12C22.001 17.5228 17.5238 22 12.001 22C10.1671 22 8.44851 21.5064 6.97086 20.6447L2.00516 22ZM8.39232 7.30833C8.5262 7.29892 8.66053 7.29748 8.79459 7.30402C8.84875 7.30758 8.90265 7.31384 8.95659 7.32007C9.11585 7.33846 9.29098 7.43545 9.34986 7.56894C9.64818 8.24536 9.93764 8.92565 10.2182 9.60963C10.2801 9.76062 10.2428 9.95633 10.125 10.1457C10.0652 10.2428 9.97128 10.379 9.86248 10.5183C9.74939 10.663 9.50599 10.9291 9.50599 10.9291C9.50599 10.9291 9.40738 11.0473 9.44455 11.1944C9.45903 11.25 9.50521 11.331 9.54708 11.3991C9.57027 11.4368 9.5918 11.4705 9.60577 11.4938C9.86169 11.9211 10.2057 12.3543 10.6259 12.7616C10.7463 12.8783 10.8631 12.9974 10.9887 13.108C11.457 13.5209 11.9868 13.8583 12.559 14.1082L12.5641 14.1105C12.6486 14.1469 12.692 14.1668 12.8157 14.2193C12.8781 14.2457 12.9419 14.2685 13.0074 14.2858C13.0311 14.292 13.0554 14.2955 13.0798 14.2972C13.2415 14.3069 13.335 14.2032 13.3749 14.1555C14.0984 13.279 14.1646 13.2218 14.1696 13.2222V13.2238C14.2647 13.1236 14.4142 13.0888 14.5476 13.097C14.6085 13.1007 14.6691 13.1124 14.7245 13.1377C15.2563 13.3803 16.1258 13.7587 16.1258 13.7587L16.7073 14.0201C16.8047 14.0671 16.8936 14.1778 16.8979 14.2854C16.9005 14.3523 16.9077 14.4603 16.8838 14.6579C16.8525 14.9166 16.7738 15.2281 16.6956 15.3913C16.6406 15.5058 16.5694 15.6074 16.4866 15.6934C16.3743 15.81 16.2909 15.8808 16.1559 15.9814C16.0737 16.0426 16.0311 16.0714 16.0311 16.0714C15.8922 16.159 15.8139 16.2028 15.6484 16.2909C15.391 16.428 15.1066 16.5068 14.8153 16.5218C14.6296 16.5313 14.4444 16.5447 14.2589 16.5347C14.2507 16.5342 13.6907 16.4482 13.6907 16.4482C12.2688 16.0742 10.9538 15.3736 9.85034 14.402C9.62473 14.2034 9.4155 13.9885 9.20194 13.7759C8.31288 12.8908 7.63982 11.9364 7.23169 11.0336C7.03043 10.5884 6.90299 10.1116 6.90098 9.62098C6.89729 9.01405 7.09599 8.4232 7.46569 7.94186C7.53857 7.84697 7.60774 7.74855 7.72709 7.63586C7.85348 7.51651 7.93392 7.45244 8.02057 7.40811C8.13607 7.34902 8.26293 7.31742 8.39232 7.30833Z"></path></svg></span></a>';
    if (get_post_type() == 'question') {
        $html = $html.'<script>
        jQuery(document).ready(function($) {
            //last navlink
            var last_navlink = $("li:last-child .nav-link");
            last_navlink.addClass("active");
        });
        </script>
        <script src="https://cdn.jsdelivr.net/npm/party-js@latest/bundle/party.min.js"></script>
        ';
    }
    return $html;
}

//tiempoo de lectura
function get_estimated_reading_time( $content = '', $wpm = 250 ) {
    $content    = strip_shortcodes( $content );
    $content    = strip_tags( $content );
    $word_count = str_word_count( $content );
    $time       = ceil( $word_count / $wpm );
    return $time ? $time > 1 ? $time . ' minutos' : '1 minuto' : '';
  }

  //edirect to home if user is not admin
    add_action('admin_init', 'redirect_non_admin_users');
    function redirect_non_admin_users() {
        if (!current_user_can('manage_options') && $_SERVER['PHP_SELF'] != '/wp-admin/admin-ajax.php') {
            wp_redirect(site_url().'/cuenta');
            exit;
        }
    }


    add_action('after_setup_theme', 'remove_admin_bar');
function remove_admin_bar() {
  if (!current_user_can('administrator') && !is_admin()) {
    show_admin_bar(false);
  }
}

//update_quimico ajax function
function update_quimico(){
    $current_user = wp_get_current_user();
    $user_id = $current_user->ID;
    $rut = $_POST['rut'];
    $nombre = $_POST['nombre'];
    $apellido1 = $_POST['apellido1'];
    $apellido2 = $_POST['apellido2'];
    $fono = $_POST['fono'];
    $correo = $_POST['correo'];
    $fecha = $_POST['fecha'];
    $rutfarmacia = $_POST['rutfarmacia'];
    $direccion = $_POST['direccion'];
    //if user exist update fields
    if ($user_id) {
        update_field('rut', $rut, 'user_'. $user_id);
        update_field('nombre', $nombre, 'user_'. $user_id);
        update_field('apellido1', $apellido1, 'user_'. $user_id);
        update_field('apellido2', $apellido2, 'user_'. $user_id);
        update_field('fono', $fono, 'user_'. $user_id);
        update_field('correo', $correo, 'user_'. $user_id);
        update_field('fecha', $fecha, 'user_'. $user_id);
        update_field('rutfarmacia', $rutfarmacia, 'user_'. $user_id);
        update_field('direccion', $direccion, 'user_'. $user_id);
        $data['success'] = true;
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }else{
        echo 'Error: No se encontró el usuario';
        die();
    }
}
add_action('wp_ajax_update_quimico', 'update_quimico');
add_action('wp_ajax_nopriv_update_quimico', 'update_quimico');