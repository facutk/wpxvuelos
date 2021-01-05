<?php
add_action('init', function(){
  add_rewrite_rule( 
    '^vuelos/([^/]*)/([^/]*)/([^/]*)/([^/]*)/?', 
    //!IMPORTANT! THIS MUST BE IN SINGLE QUOTES!:
    'index.php?pagename=vuelos&origin=$matches[1]&destination=$matches[2]&outboundDate=$matches[3]&inboundDate=$matches[4]', 
    'top'
    );

  init_userinfo();
});

function init_userinfo() {
  $userinfo_changed = false;
  
  $userinfo = $_COOKIE["userinfo"];
  
  if ($userinfo) {
    $userinfo = json_decode(base64_decode($userinfo), true);
    if (!$userinfo) {
      unset($_COOKIE["userinfo"]);
    }
  } else {
    $userinfo = xvuelos_get_userinfo();
    $userinfo_changed = true;  
  }

  if($_POST['market'] || $_POST['currency']) {
    $userinfo['market'] = $_POST['market'];
    $userinfo['currency'] = $_POST['currency'];
    $userinfo_changed = true;
  };

  if ($userinfo_changed) {
    setcookie("userinfo", base64_encode(json_encode($userinfo)), time()+3600);  
  }

  $GLOBALS['userinfo'] = $userinfo;
}

function xvuelos_get_markets() {
  return [
    'US', 'AR', 'BR', 'CL', 'CO', 'EC', 'ES', 'MX', 'PE', 'PA', 'UY', 'AU'
  ];
}

function xvuelos_get_currencies() {
  return [
    'USD', 'ARS', 'BRL', 'CLP', 'COP', 'ECS', 'EUR', 'MXN', 'PEN', 'PYG', 'UYU', 'AUD'
  ];
}

add_filter('query_vars', function( $vars ){
  $vars[] = 'pagename';
  $vars[] = 'origin';
  $vars[] = 'destination';
  $vars[] = 'outboundDate';
  $vars[] = 'inboundDate';
  $vars[] = 'sortby';
  $vars[] = 'stops';
  $vars[] = 'carriers';
  $vars[] = 'offset';
  $vars[] = "loading";
  $vars[] = "sid";
  return $vars;
});


function add_styles() {
  wp_enqueue_style('jqueryui_css', get_theme_file_uri( '/assets/jqueryui/jquery-ui.min.css'));
  wp_enqueue_style('bootstrap_css', get_theme_file_uri( '/assets/bootstrap/css/bootstrap.min.css'));
  wp_enqueue_style('skeleton_screen_css', get_theme_file_uri( '/assets/skeleton-screen-css/index.min.css')); 
}
add_action( 'wp_enqueue_scripts', 'add_styles' );

function xvuelos_get_flights($sid, $offset, $pageSize, $selectedSortby, $selectedStops, $selectedCarriers) {
  if ($sid) {
    $results = xvuelos_poll($sid, $offset, $pageSize, $selectedSortby, $selectedStops, $selectedCarriers);
    return $results;
  }
  
  // $mockSession = json_decode(file_get_contents(get_stylesheet_directory() . '/mockSession.json'), true);

  // return $mockSession;
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
    wp_enqueue_script('flights-search', get_theme_file_uri('/assets/flights-search.js'), array('jquery', 'jquery-ui-autocomplete'), '1.0.0', true );
  }
}

function get_user_ip() {
  $ip = "186.155.103.62"; // mock ip address from bogota, for the lulz
  // unless we are in prod, we should be identified as CO, with currency COP

  $request_headers = getallheaders();
  // heroku passes origin IP in this header
  $forwarded_for = $request_headers['X-Forwarded-For'];

  if ($forwarded_for) {
    $ip = explode(',', $forwarded_for)[0];
  }

  return $ip;
}

function xvuelos_get_userinfo() {
  $ip = get_user_ip();

  $SKYSCANNER_URL = $_ENV["SKYSCANNER_URL"];
  $SKYSCANNER_API_KEY = $_ENV["SKYSCANNER_API_KEY"];
  $url = "$SKYSCANNER_URL/autosuggest/v1.0/US/USD/en?id=$ip-ip&apiKey=$SKYSCANNER_API_KEY";
  $get_data = wp_remote_get($url);
  $body = $get_data['body'];
  $response = json_decode($body, true);

  $Places = $response['Places'];
  $Place = $Places[0];

  // locale
  $locale = 'es-MX';

  // market
  $market = explode('-', $Place["CountryId"])[0];
  $supported_markets = xvuelos_get_markets();
  $is_market_supported = in_array($market, $supported_markets);
  if (!$is_market_supported) {
    $market = $supported_markets[0];
  }
  
  // currency
  $supported_currencies = xvuelos_get_currencies();
  $currency = $supported_currencies[0];

  if ($is_market_supported) {
    $CountryName = $Place["CountryName"];
    $countryCurrencies = json_decode(file_get_contents(get_stylesheet_directory() . '/assets/country-json/country-by-currency-code.json'), true);
    foreach($countryCurrencies as $countryCurrency) {
      if ($countryCurrency["country"] == $CountryName) {
        $currency = $countryCurrency["currency_code"];
        break;
      }
    }
  }

  $userinfo = [
    'locale' => $locale,
    'market' => $market,
    'currency' => $currency
  ];

  return $userinfo;
}

function xvuelos_rest_get_userinfo(WP_REST_Request $request) {
  $userinfo = xvuelos_get_userinfo();

  $response = new WP_REST_Response($userinfo, 200);
  
  $response->set_headers([ 'Cache-Control' => 'must-revalidate, no-cache, no-store, private' ]);
  
  return $response;
}

function xvuelos_get_place_suggestions($query) {
  $SKYSCANNER_URL = $_ENV["SKYSCANNER_URL"];
  $SKYSCANNER_API_KEY = $_ENV["SKYSCANNER_API_KEY"];

  global $userinfo;

  $market = $userinfo['market'];
  $currency = $userinfo['currency'];
  $locale = $userinfo['locale'];

  $url = "$SKYSCANNER_URL/autosuggest/v1.0/$market/$currency/$locale?query=$query&includeCities=false&includeCountries=false&apiKey=$SKYSCANNER_API_KEY";
  $get_data = wp_remote_get($url);

  $body = $get_data['body'];
  $response = json_decode($body, true);
  
  $places = array_map(function($place) {
    $value = explode('-', $place["PlaceId"])[0];
    $label = $place["PlaceName"];
    $trait = $place["CountryName"];

    return [
      'value' => $value,
      'label' => $label,
      'trait' => $trait
    ];
  }, $response['Places']);

  return $places;
}

function xvuelos_rest_get_places(WP_REST_Request $request) {
  global $userinfo;

  $term = $request->get_param('term');
  $station = $request->get_param('station');

  // this is a hack to suggest places near the origin station
  if (strlen($term) == 0 && $station == 'origin') {
    $market = $userinfo['market'];
    $term = $market;
  };

  $places = xvuelos_get_place_suggestions($term);

  $response = new WP_REST_Response($places, 200);
  
  $response->set_headers([ 'Cache-Control' => 'must-revalidate, no-cache, no-store, private' ]);
  
  return $response;
}

function xvuelos_create_sessionid(
  $originplace,
  $destinationplace,
  $outbounddate,
  $inbounddate
) {
  $SKYSCANNER_URL = $_ENV["SKYSCANNER_URL"];
  $SKYSCANNER_API_KEY = $_ENV["SKYSCANNER_API_KEY"];
  
  global $userinfo;

  $market = $userinfo["market"];
  $currency = $userinfo["currency"];
  $locale = $userinfo["locale"];

  $body = [
    "country" => $market,
    "currency" => $currency,
    "locale" => $locale,
    "adults" => 1,
    "children" => 0,
    "infants" => 0,
    "locationSchema" => "iata",
    "originplace" => $originplace,
    "cabinClass" => "economy",
    "destinationplace" => $destinationplace,
    "outbounddate" => $outbounddate,
    "inbounddate" => $inbounddate,
    "groupPricing" => false,
    "apikey" => $SKYSCANNER_API_KEY
  ];
  $url = $SKYSCANNER_URL . '/pricing/v1.0';
  $response = wp_remote_post($url, [
    'body' => $body
  ]);
  $headers = $response["headers"];
  $location = $headers["location"];
  $sid = end(explode('/', $location));

  return $sid;
}

function xvuelos_rest_sessionid(WP_REST_Request $request) {
  $originplace = $request->get_param('originplace');
  $destinationplace = $request->get_param('destinationplace');
  $outbounddate = $request->get_param('outbounddate');
  $inbounddate = $request->get_param('inbounddate');

  $sessionid = xvuelos_create_sessionid(
    $originplace,
    $destinationplace,
    $outbounddate,
    $inbounddate
  );

  $response = new WP_REST_Response($sessionid, 200);
  
  $response->set_headers([ 'Cache-Control' => 'must-revalidate, no-cache, no-store, private' ]);
  
  return $response;
}

function xvuelos_poll($sid, $pageIndex, $pageSize, $sortby, $stops, $includeCarriers) {
  $SKYSCANNER_URL = $_ENV["SKYSCANNER_URL"];
  $SKYSCANNER_API_KEY = $_ENV["SKYSCANNER_API_KEY"];

  $url = "$SKYSCANNER_URL/pricing/uk1/v1.0/$sid?apiKey=$SKYSCANNER_API_KEY&pageIndex=$pageIndex&pageSize=$pageSize";
  if ($sortby) {
    $url = $url . "&sortType=$sortby&sortOrder=asc";
  }
  if ($stops) {
    $url = $url . "&stops=$stops";
  }
  if ($includeCarriers) {
    $url = $url . "&includeCarriers=$includeCarriers";
  }

  $response = wp_remote_get($url, [
    "timeout" => 60
  ]);

  $body = $response["body"];
  $payload = json_decode($body, true);
  $status = $payload["Status"];
  
  return $payload;
}

function xvuelos_rest_poll(WP_REST_Request $request) {
  $sid = $request->get_param('sid');
  $pageIndex = 0; // polling should always be at page 0
  $pageSize = 1; // we don't need a huge payload just to know if updates are complete
  $sortby = ""; // no sorting
  $stops = ""; // no stops defined
  $includeCarriers = ""; // no defined carriers filters
  $results = xvuelos_poll($sid, $pageIndex, $pageSize, $sortby, $stops, $includeCarriers);
  $miniResults = [
    "Status" => $results["Status"]
  ]; // send only whats needed

  $response = new WP_REST_Response($miniResults, 200);
  
  $response->set_headers([ 'Cache-Control' => 'must-revalidate, no-cache, no-store, private' ]);
  
  return $response;
}

add_action( 'rest_api_init', function () {
  register_rest_route('xvuelos/v1', '/userinfo/', [
    'methods'  => WP_REST_Server::READABLE,
    'callback' => 'xvuelos_rest_get_userinfo',
  ]);

  register_rest_route('xvuelos/v1', '/places/', [
    'methods'  => WP_REST_Server::READABLE,
    'callback' => 'xvuelos_rest_get_places',
  ]);

  register_rest_route('xvuelos/v1', '/session/', [
    'methods'  => WP_REST_Server::READABLE,
    'callback' => 'xvuelos_rest_sessionid',
  ]);

  register_rest_route('xvuelos/v1', '/poll/', [
    'methods'  => WP_REST_Server::READABLE,
    'callback' => 'xvuelos_rest_poll',
  ]);
});


add_action("template_redirect", "start_buffer");
add_action("shutdown", "end_buffer", 999);

function filter_buffer($buffer) {
  $buffer = replace_insecure_links($buffer);
  return $buffer;
}
function start_buffer(){
  ob_start("filter_buffer");
}

function end_buffer(){
  if (ob_get_length()) ob_end_flush();
}

function replace_insecure_links($str) {
  $str = str_replace ( array("http://localhost:8080/", "http://wp.xvuelos.com/", "https://wp.xvuelos.com/") , array("/", "/", "/"), $str);

  return apply_filters("rsssl_fixer_output", $str);
}

flush_rewrite_rules();
