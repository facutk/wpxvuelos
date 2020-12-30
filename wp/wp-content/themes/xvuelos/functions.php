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
flush_rewrite_rules();
