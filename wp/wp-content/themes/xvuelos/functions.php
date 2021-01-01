<?php
add_action('init', function(){
  add_rewrite_rule( 
    '^vuelos/([^/]*)/([^/]*)/([^/]*)/([^/]*)/?', 
    //!IMPORTANT! THIS MUST BE IN SINGLE QUOTES!:
    'index.php?pagename=vuelos&origin=$matches[1]&destination=$matches[2]&outboundDate=$matches[3]&inboundDate=$matches[4]', 
    'top'
    );
});

add_filter('query_vars', function( $vars ){
  $vars[] = 'pagename';
  $vars[] = 'origin';
  $vars[] = 'destination';
  $vars[] = 'outboundDate';
  $vars[] = 'inboundDate';
  $vars[] = "loading";
  return $vars;
});


function add_styles() {
  wp_enqueue_style('jqueryui_css', get_theme_file_uri( '/assets/jqueryui/jquery-ui.min.css'));
  wp_enqueue_style('bootstrap_css', get_theme_file_uri( '/assets/bootstrap/css/bootstrap.min.css'));
  wp_enqueue_style('skeleton_screen_css', get_theme_file_uri( '/assets/skeleton-screen-css/index.min.css')); 
}
add_action( 'wp_enqueue_scripts', 'add_styles' );

function xvuelos_get_flights() {
  $mockSession = json_decode(file_get_contents('wp-content/themes/xvuelos/mockSession.json'), true);

  return $mockSession;
}

/* Adds scripts */
add_action( 'after_setup_theme', 'xvuelos_theme_setup' );

if ( ! function_exists( 'xvuelos_theme_setup' ) ) {
  function xvuelos_theme_setup() {
    add_action( 'wp_enqueue_scripts', 'xvuelos_frontend_scripts' );
  }
}

if ( ! function_exists( 'xvuelos_frontend_scripts' ) ) {
  function xvuelos_frontend_scripts() {
    wp_enqueue_script('main', get_theme_file_uri('/assets/main.js'), array('jquery-ui-autocomplete', 'jquery'), '1.0.0', true );
  }
}

function hello_method(WP_REST_Request $request) {
  $data = [ 'foo' => 'bar'];

  $response = new WP_REST_Response($data, 200);
  $response->set_headers([ 'Cache-Control' => 'must-revalidate, no-cache, no-store, private' ]);
  
  return $response;
}

function ip_method(WP_REST_Request $request) {
  $ip  = $request->get_header('x-forwarded-for'); // heroku passes origin IP in this header
  if ($ip) {
    $ip = explode(',', $ip)[0];
  } else {
    $ip = "186.139.211.36"; // @TODO move this to an env var
  }

  $data = [ 'ip' => $ip ];

  $response = new WP_REST_Response($data, 200);
  
  $response->set_headers([ 'Cache-Control' => 'must-revalidate, no-cache, no-store, private' ]);
  
  return $response;
}

add_action( 'rest_api_init', function () {
  register_rest_route('xvuelos/v1', '/hello/', [
    'methods'  => WP_REST_Server::READABLE,
    'callback' => 'hello_method',
  ]);

  register_rest_route('xvuelos/v1', '/ip/', [
    'methods'  => WP_REST_Server::READABLE,
    'callback' => 'ip_method',
  ]);
});


flush_rewrite_rules();
