<?php

function get_version_number() {
	static $assetsVersion;
	if (empty($assetsVersion)) {
		$assetsVersion = file_get_contents(get_template_directory() . '/assets.version');
	}
	return $assetsVersion;
}

define( 'CSSPATH', get_template_directory_uri() . '/assets/css/' );
define( 'JSPATH', get_template_directory_uri() . '/assets/scripts/' );

function agregar_css_js(){
    //wp_enqueue_style('style', get_stylesheet_uri());
    wp_enqueue_style( 'styles', CSSPATH . 'app.css', array(), get_version_number() );
    wp_deregister_script('jquery');
    wp_enqueue_script('jquery', JSPATH . 'app.js', array(), get_version_number(), true );

    //wp_enqueue_script('count', 'https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js', array(), '1.0', true);

    //wp_enqueue_script( 'script', get_template_directory_uri() . '/js/functions.js', array ( 'count' ), 1.1, true);
    //wp_enqueue_script( 'script1', get_template_directory_uri() . '/js/form.js', array ( 'script' ), 1.1, true);

    //wp_enqueue_script('popper', 'https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js', array('script1'), '1.0', true);
    //wp_enqueue_script('count', 'https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js', array('popper'), '1.0', true);


}
add_action('wp_enqueue_scripts', 'agregar_css_js');

//API Call
function callAPI($param)
{
    //extract data from the post
    extract($_POST);
    $fields_string = '';

    //set POST variables
    $url = get_bloginfo( "url" ).'/api/';
    //$param = substr($param, 0, -1);
    //$param.= ',"app":"inc_backend","apikey":"2895b89346258eea8cd184e80876518823d46ac8","platform":"web"}';
    $fields = array
    (
        'param'=>urlencode($param)
    );

    //url-ify the data for the POST
    foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&amp;'; }
    rtrim($fields_string,'&amp;');

    //open connection
    $ch = curl_init();

    //set the url, number of POST vars, POST data
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_POST,count($fields));
    curl_setopt($ch,CURLOPT_POSTFIELDS,$fields_string);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);

    //execute post
    $result = curl_exec($ch);
    $status = curl_getinfo($ch);

    //close connection
    curl_close($ch);

    return $result;
}
//Indent JSON
function indent($json)
{
    $result      = '';
    $pos         = 0;
    $strLen      = strlen($json);
    $indentStr   = '  ';
    $newLine     = "\n";
    $prevChar    = '';
    $outOfQuotes = true;

    for ($i=0; $i<=$strLen; $i++) {

        // Grab the next character in the string.
        $char = substr($json, $i, 1);

        // Are we inside a quoted string?
        if ($char == '"' && $prevChar != '\\') {
            $outOfQuotes = !$outOfQuotes;

        // If this character is the end of an element,
        // output a new line and indent the next line.
        } else if(($char == '}' || $char == ']') && $outOfQuotes) {
            $result .= $newLine;
            $pos --;
            for ($j=0; $j<$pos; $j++) {
                $result .= $indentStr;
            }
        }

        // Add the character to the result string.
        $result .= $char;

        // If the last character was the beginning of an element,
        // output a new line and indent the next line.
        if (($char == ',' || $char == '{' || $char == '[') && $outOfQuotes) {
            $result .= $newLine;
            if ($char == '{' || $char == '[') {
                $pos ++;
            }

            for ($j = 0; $j < $pos; $j++) {
                $result .= $indentStr;
            }
        }

        $prevChar = $char;
    }

    return $result;
}

//Print JSON
function printJSON($array)
{
    $json = json_encode($array);
    header('Content-Type: application/json',true);
    echo indent($json);
}

//Change Footer Text
add_filter( 'admin_footer_text', 'my_footer_text' );
add_filter( 'update_footer', 'my_footer_version', 11 );
function my_footer_text() {
    return '<i>Redline</i>';
}
function my_footer_version() {
    return 'Version 1.0';
}

//Post Type Administradores
function register_Administradores_posttype() {
    $labels = array(
        'name'               => _x( 'Administradores', 'administradoreswp' ),
        'singular_name'      => _x( 'Administradores', 'administradoreswp' ),
        'add_new'            => __( 'Añadir nuevo','administradoreswp'),
        'add_new_item'       => __( 'Nuevo','administradoreswp'),
        'edit_item'          => __( 'Editar','administradoreswp' ),
        'new_item'           => __( 'Nuevo ','administradoreswp' ),
        'all_items'          => __( 'Administradores','administradoreswp' ),
        'view_item'          => __( 'Ver','administradoreswp'),
        'search_items'       => __( 'Buscar','administradoreswp'),
        'not_found'          => __( 'No encontrado!','administradoreswp' ),
        'not_found_in_trash' => __( 'No encontrado en la papelera','administradoreswp' ),
        'parent_item_colon'  => '',
        'menu_name'          => __('Administradores','administradoreswp')
    );
    $args = array(
        'labels' => $labels,
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'query_var' => true,
        'rewrite' => true,
        'capability_type' => 'post',
        'has_archive' => true,
        'hierarchical' => false,
        'menu_position' => 12,
        'menu_icon' => 'dashicons-businessperson',
        'supports' => array( 'title', 'thumbnail', 'revisions' )
    );
    register_post_type( 'Administradores', $args );	
}

add_action( 'init', 'register_Administradores_posttype' );

//Post Type Clientes
function register_Clientes_posttype() {
    $labels = array(
        'name'               => _x( 'Clientes', 'clienteswp' ),
        'singular_name'      => _x( 'Clientes', 'clienteswp' ),
        'add_new'            => __( 'Añadir nuevo','clienteswp'),
        'add_new_item'       => __( 'Nuevo','clienteswp'),
        'edit_item'          => __( 'Editar','clienteswp' ),
        'new_item'           => __( 'Nuevo ','clienteswp' ),
        'all_items'          => __( 'Clientes','clienteswp' ),
        'view_item'          => __( 'Ver','clienteswp'),
        'search_items'       => __( 'Buscar','clienteswp'),
        'not_found'          => __( 'No encontrado!','clienteswp' ),
        'not_found_in_trash' => __( 'No encontrado en la papelera','clienteswp' ),
        'parent_item_colon'  => '',
        'menu_name'          => __('Clientes','clienteswp')
    );
    $args = array(
        'labels' => $labels,
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'query_var' => true,
        'rewrite' => true,
        'capability_type' => 'post',
        'has_archive' => true,
        'hierarchical' => false,
        'menu_position' => 12,
        'menu_icon' => 'dashicons-id',
        'supports' => array( 'title', 'thumbnail', 'revisions' )
    );
    register_post_type( 'Clientes', $args );	
}

add_action( 'init', 'register_Clientes_posttype' );

//Post Type Visitas
function register_Visitas_posttype() {
    $labels = array(
        'name'               => _x( 'Visitas', 'visitaswp' ),
        'singular_name'      => _x( 'Visitas', 'visitaswp' ),
        'add_new'            => __( 'Añadir nuevo','visitaswp'),
        'add_new_item'       => __( 'Nuevo','visitaswp'),
        'edit_item'          => __( 'Editar','visitaswp' ),
        'new_item'           => __( 'Nuevo ','visitaswp' ),
        'all_items'          => __( 'Visitas','visitaswp' ),
        'view_item'          => __( 'Ver','visitaswp'),
        'search_items'       => __( 'Buscar','visitaswp'),
        'not_found'          => __( 'No encontrado!','visitaswp' ),
        'not_found_in_trash' => __( 'No encontrado en la papelera','visitaswp' ),
        'parent_item_colon'  => '',
        'menu_name'          => __('Visitas','visitaswp')
    );
    $args = array(
        'labels' => $labels,
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'query_var' => true,
        'rewrite' => true,
        'capability_type' => 'post',
        'has_archive' => true,
        'hierarchical' => false,
        'menu_position' => 12,
        'menu_icon' => 'dashicons-buddicons-buddypress-logo',
        'supports' => array( 'title', 'thumbnail', 'revisions' )
    );
    register_post_type( 'Visitas', $args );	
}

add_action( 'init', 'register_Visitas_posttype' );


//Post Type Membresias
function register_Membresias_posttype() {
    $labels = array(
        'name'               => _x( 'Membresias', 'membresiaswp' ),
        'singular_name'      => _x( 'Membresias', 'membresiaswp' ),
        'add_new'            => __( 'Añadir nuevo','membresiaswp'),
        'add_new_item'       => __( 'Nuevo','membresiaswp'),
        'edit_item'          => __( 'Editar','membresiaswp' ),
        'new_item'           => __( 'Nuevo ','membresiaswp' ),
        'all_items'          => __( 'Membresias','membresiaswp' ),
        'view_item'          => __( 'Ver','membresiaswp'),
        'search_items'       => __( 'Buscar','membresiaswp'),
        'not_found'          => __( 'No encontrado!','membresiaswp' ),
        'not_found_in_trash' => __( 'No encontrado en la papelera','membresiaswp' ),
        'parent_item_colon'  => '',
        'menu_name'          => __('Membresias','membresiaswp')
    );
    $args = array(
        'labels' => $labels,
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'query_var' => true,
        'rewrite' => true,
        'capability_type' => 'post',
        'has_archive' => true,
        'hierarchical' => false,
        'menu_position' => 12,
        'menu_icon' => 'dashicons-welcome-write-blog',
        'supports' => array( 'title', 'thumbnail', 'revisions' )
    );
    register_post_type( 'Membresias', $args );	
}

add_action( 'init', 'register_Membresias_posttype' );

//Post Type Contratos
function register_Contratos_posttype() {
    $labels = array(
        'name'               => _x( 'Contratos', 'contratoswp' ),
        'singular_name'      => _x( 'Contratos', 'contratoswp' ),
        'add_new'            => __( 'Añadir nuevo','contratoswp'),
        'add_new_item'       => __( 'Nuevo','contratoswp'),
        'edit_item'          => __( 'Editar','contratoswp' ),
        'new_item'           => __( 'Nuevo ','contratoswp' ),
        'all_items'          => __( 'Contratos','contratoswp' ),
        'view_item'          => __( 'Ver','contratoswp'),
        'search_items'       => __( 'Buscar','contratoswp'),
        'not_found'          => __( 'No encontrado!','contratoswp' ),
        'not_found_in_trash' => __( 'No encontrado en la papelera','contratoswp' ),
        'parent_item_colon'  => '',
        'menu_name'          => __('Contratos','contratoswp')
    );
    $args = array(
        'labels' => $labels,
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'query_var' => true,
        'rewrite' => true,
        'capability_type' => 'post',
        'has_archive' => true,
        'hierarchical' => false,
        'menu_position' => 12,
        'menu_icon' => 'dashicons-welcome-write-blog',
        'supports' => array( 'title', 'thumbnail', 'revisions' )
    );
    register_post_type( 'Contratos', $args );	
}

add_action( 'init', 'register_Contratos_posttype' );

	