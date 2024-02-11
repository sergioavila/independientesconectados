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
        <a href="/cuenta" data-bs-toggle="tooltip" data-bs-title="Revisa tus puntos"><p><span class="text">Tus puntos:</span><span class="puntos">'.do_shortcode('[gamipress_points type="puntos" user_id="'.$user_id.'" inline ="yes" label="no" thumbnail="no" align="none"align="none"]').'</span></p></a>
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
    //load datatable js and css
    wp_enqueue_script('datatables', 'https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js', array('jquery'), '1.11.5', true);
    wp_enqueue_style('datatables', 'https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css', array(), '1.11.5', 'all');
    wp_enqueue_script('child-template', get_stylesheet_directory_uri(). '/main.js', array('jquery'), '1.0.0', true);
    wp_enqueue_style('bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css', array(), '5.3.1', 'all');
    wp_enqueue_script('bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js', array(), '5.3.1', true);
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


//add html to header
function add_html_header() {
    echo '<script src="https://cdn.onesignal.com/sdks/web/v16/OneSignalSDK.page.js" defer></script>
    <script>
      window.OneSignalDeferred = window.OneSignalDeferred || [];
      OneSignalDeferred.push(function(OneSignal) {
        OneSignal.init({
          appId: "d715473c-d954-4b70-94e8-d3564de38060",
          safari_web_id: "web.onesignal.auto.68a9d4a9-72e3-41ba-a788-4f8badeb71ae",
          notifyButton: {
            enable: true,
          },
        });
      });
    </script>';
    //echo '<script>const ajaxurl = "'. admin_url('admin-ajax.php'). '";</script>';
}
//add html to header site
add_action('wp_head', 'add_html_header');

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
                    <div class="menu-principal">
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
    $sql = "SELECT * FROM wp_qfs_teva WHERE rut = '$rut'";
    $results = $wpdb->get_row($sql);
    if (empty($results)) {
        echo 'Error: No se encontró el químico';
        return;
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
                //'role' => 'quimico'
            );
            $user_id = wp_insert_user($userdata);
            if (is_wp_error($user_id)) {
                echo 'Error: Ha ocurrido un error al iniciar sesión';
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
        acf_form_head();
        $user_id = get_current_user_id();
        $user_info = get_userdata($user_id);
        $nombre = $user_info->first_name;
        //check if loged iser is admin
        if (is_super_admin($user_id)) {
            return;
        }
        if (empty($nombre)) {
            echo '<div class="modal" tabindex="-1" id="modal-footer" >
            <div class="modal-dialog modal-dialog-centered modal-xl">
                <div class="modal-content">
                    <div class="modal-body">
                        <h3 class="mb-4 text-center">Para poder continuar debe completar su información personal</h3>
                        '.update_user_info_form().'
                    </div>
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

function update_user_info_form() {
    if (is_user_logged_in() && !is_admin()) {
        acf_form_head();
        $user_id = get_current_user_id();
        $user_info = get_userdata($user_id);
        $nombre = $user_info->first_name;
        $apellido = $user_info->last_name;
        $rut = get_field('rut', 'user_'. $user_id);
        $telefono = get_field('telefono', 'user_'. $user_id);
        $email = $user_info->user_email;
        $direccion = get_field('direccion', 'user_'. $user_id);
        if (empty($nombre)) {
            return '<form action="/cuenta" method="post" class="needs-validation" novalidate>
                <div class="row">
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre completo</label>
                            <input type="text" name="nombre" value="'. $nombre. '" class="form-control" required>
                            <div class="invalid-feedback">Por favor, ingresa tu nombre.</div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label for="apellido" class="form-label">Apellidos</label>
                            <input type="text" name="apellido" value="'. $apellido. '" class="form-control" required>
                            <div class="invalid-feedback">Por favor, ingresa tu apellido.</div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label for="rut" class="form-label">RUT</label>
                            <input type="text" name="rut" value="'. $rut. '" class="form-control" required>
                            <div class="invalid-feedback">Por favor, ingresa tu RUT.</div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label for="telefono" class="form-label">Teléfono</label>
                            <input type="text" name="telefono" value="'. $telefono. '" class="form-control" required>
                            <div class="invalid-feedback">Por favor, ingresa tu número de teléfono.</div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label for="email" class="form-label">Correo Electrónico</label>
                            <input type="text" name="email" value="'. $email. '" class="form-control" required>
                            <div class="invalid-feedback">Por favor, ingresa tu correo electrónico.</div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label for="direccion" class="form-label">Dirección</label>
                            <input type="text" name="direccion" value="'. $direccion. '" class="form-control" required>
                            <div class="invalid-feedback">Por favor, ingresa tu dirección.</div>
                        </div>
                    </div>
                    <div class="col-12">
                        <input type="hidden" name="action" value="update_user_info">
                        <button type="submit" class="btn btn-primary">Actualizar mi información</button>
                    </div>
                </div>
            </div>
        </form>';
        }
        else{
            return;
        }
    }
    else{
        return;
    }
}

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
    $html = '</footer><div id="whatsappsupport"></div>';
    if (get_post_type() == 'question') {
        $html = $html.'<script>
        jQuery(document).ready(function($) {
            //last navlink
            var last_navlink = $("li:last-child .nav-link");
            last_navlink.addClass("active");
        });
        </script>
        <script src="https://cdn.jsdelivr.net/npm/party-js@latest/bundle/party.min.js"></script>';
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
