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

function get_foo_bar(WP_REST_Request $request) {
  $data = [ 'foo' => 'bar'];

  $response = new WP_REST_Response($data, 200);
  $response->set_headers([ 'Cache-Control' => 'must-revalidate, no-cache, no-store, private' ]);
  
  return $response;
}

function get_ip(WP_REST_Request $request) {
  $ip  = $request->get_header('x-forwarded-for'); // heroku passes origin IP in this header
  if ($ip) {
    $ip = explode(',', $ip)[0];
  } else {
    $ip = $_ENV["MOCK_IP_ADDRESS"];
  }

  $data = [ 'ip' => $ip ];

  $response = new WP_REST_Response($data, 200);
  
  $response->set_headers([ 'Cache-Control' => 'must-revalidate, no-cache, no-store, private' ]);
  
  return $response;
}

function callAPI($method, $url, $data){
  $curl = curl_init();
  switch ($method){
     case "POST":
        curl_setopt($curl, CURLOPT_POST, 1);
        if ($data)
           curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        break;
     case "PUT":
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
        if ($data)
          curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        break;
     default:
        if ($data)
           $url = sprintf("%s?%s", $url, http_build_query($data));
  }
  // OPTIONS:
  curl_setopt($curl, CURLOPT_URL, $url);
  curl_setopt($curl, CURLOPT_HTTPHEADER, array(
    //  'APIKEY: 111111111111111111111',
     'Content-Type: application/json',
  ));
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
  // EXECUTE:
  $result = curl_exec($curl);
  if(!$result){die("Connection Failure");}
  curl_close($curl);
  return $result;
}


function get_userinfo(WP_REST_Request $request) {
  $ip  = $request->get_header('x-forwarded-for'); // heroku passes origin IP in this header
  if ($ip) {
    $ip = explode(',', $ip)[0];
  } else {
    $ip = $_ENV["MOCK_IP_ADDRESS"];
  }

  $SKYSCANNER_URL = $_ENV["SKYSCANNER_URL"];
  $SKYSCANNER_API_KEY = $_ENV["SKYSCANNER_API_KEY"];
  // $url = "$SKYSCANNER_URL/autosuggest/v1.0/$country/$currency/$locale?query=$query&apiKey=$skyscanner_api_key";
  $url = "$SKYSCANNER_URL/autosuggest/v1.0/US/USD/en?id=$ip-ip&apiKey=$SKYSCANNER_API_KEY";
  $get_data = callAPI('GET', $url, false);
  $response = json_decode($get_data, true);

  $Places = $response['Places'];
  $Place = $Places[0];
  $market = explode('-', $Place["CountryId"])[0];
  $CountryName = $Place["CountryName"];

  $locale = 'es-MX';
  $currency = 'USD';

  $data = [
    'locale' => $locale,
    'market' => $market,
    'currency' => $currency,
    'country' => $CountryName
  ];

  $response = new WP_REST_Response($data, 200);
  
  $response->set_headers([ 'Cache-Control' => 'must-revalidate, no-cache, no-store, private' ]);
  
  return $response;
}

add_action( 'rest_api_init', function () {
  register_rest_route('xvuelos/v1', '/hello/', [
    'methods'  => WP_REST_Server::READABLE,
    'callback' => 'get_foo_bar',
  ]);

  register_rest_route('xvuelos/v1', '/ip/', [
    'methods'  => WP_REST_Server::READABLE,
    'callback' => 'get_ip',
  ]);

  register_rest_route('xvuelos/v1', '/userinfo/', [
    'methods'  => WP_REST_Server::READABLE,
    'callback' => 'get_userinfo',
  ]);
});

flush_rewrite_rules();
