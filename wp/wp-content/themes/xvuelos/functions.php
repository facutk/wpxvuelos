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
  return $vars;
});

function bootstrap_css() {
  wp_enqueue_style( 'bootstrap_css', 
    'https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css', 
    array(), 
    '4.1.3'
  ); 
}
add_action( 'wp_enqueue_scripts', 'bootstrap_css');

/* Adds scripts */
function add_scripts() {
  wp_enqueue_script('dayjs', get_theme_file_uri( '/assets/js/dayjs.min.js'));
}
add_action( 'wp_enqueue_scripts', 'add_scripts' );

function xvuelos_get_flights() {
  $mockSession = json_decode(file_get_contents('wp-content/themes/xvuelos/mockSession.json'), true);

  return $mockSession;
}

flush_rewrite_rules();
