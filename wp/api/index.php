<?
  // app.get('/autosuggest/:country/:currency/:locale', async (req, res) => {
  //   const { country, currency, locale } = req.params;
  //   const { query } = req.query;

  //   const response = await fetch(`${SKYSCANNER_URL}/autosuggest/v1.0/${country}/${currency}/${locale}?query=${query}&apiKey=${SKYSCANNER_API_KEY}`);
  //   const { Places = [] } = await response.json();

  //   return res.status(200).json(Places);
  // });

  include 'Route.php';

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

  Route::add('/', function() {
    echo 'Welcome :-)';
  });

  Route::add('/autosuggest/(.*)/(.*)/(.*)/(.*)', function($country,$currency,$locale,$query) {
    $skyscanner_url = $_ENV["SKYSCANNER_URL"];
    $skyscanner_api_key = $_ENV["SKYSCANNER_API_KEY"];

    $url = "$skyscanner_url/autosuggest/v1.0/$country/$currency/$locale?query=$query&apiKey=$skyscanner_api_key";
    $get_data = callAPI('GET', $url, false);
    echo $get_data;
    // $response = json_decode($get_data, true);
  
// $data = $response['response']['data'][0];
    // echo $url;
  });
  
  Route::run('/api');
?>