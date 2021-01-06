<?
  $browseroutes = xvuelos_get_browseroutes();
  $Routes = $browseroutes["Routes"];
  $currency = $browseroutes["Currencies"][0]["Code"];

  $Itineraries = $browseroutes["Itineraries"];
  $Carriers = $browseroutes["Carriers"];

  $quotes = array_column($browseroutes["Quotes"], NULL, 'QuoteId');
  $places = array_column($browseroutes["Places"], NULL, 'PlaceId');
  $carriers = array_column($browseroutes["Carriers"], NULL, 'Id');

  $originId = $Routes[0]["OriginId"];
  $originPlaceName = $places[$originId]["Name"];
  $RoutesWithPrice = array_filter($Routes, function($route) {
    return $route["Price"] && $route["QuoteIds"];
  });

  shuffle($RoutesWithPrice);

  $country_images_by_id = array_column(xvuelos_country_images_by_id(), NULL, 'Id');

  // TODO: this should be solved with setlocale(LC_ALL,"es_ES"); and strftime("%B", strtotime($outboundDate))
  // However, heroku only has English locales
  // Thus, this hack
  $MONTH_NAMES = [
    '01' => 'Enero',
    '02' => 'Febrero',
    '03' => 'Marzo',
    '04' => 'Abril',
    '05' => 'Mayo',
    '06' => 'Junio',
    '07' => 'Julio',
    '08' => 'Agosto',
    '09' => 'Septiembre',
    '10' => 'Octubre',
    '11' => 'Noviembre',
    '12' => 'Diciembre',
  ];

  function getDaysCount($departure, $arrival) {
    return intval(date_diff(
      date_create(substr($departure, 0, 10)),
      date_create(substr($arrival, 0, 10))
    )->format('%a'));
  }
?>

<div>
  <h5>Ofertas saliendo de <? echo $originPlaceName; ?></h5>
  <div class="masonry">
  <?
    foreach($RoutesWithPrice as $route) {
      $destination = $places[$route["DestinationId"]];
      $destinationName = $destination["Name"];
      $destinationCode = $destination["SkyscannerCode"];
      $price = $route["Price"];
      $quoteId = $route["QuoteIds"][0];
      $quote = $quotes[$quoteId];

      $outboundLeg = $quote["OutboundLeg"];
      
      $origin = $places[$outboundLeg["OriginId"]]["SkyscannerCode"];
      $destination = $places[$outboundLeg["DestinationId"]]["SkyscannerCode"];

      $outboundDate = substr($outboundLeg["DepartureDate"], 0, 10);
      $inboundDate = substr($quote["InboundLeg"]["DepartureDate"], 0, 10);

      $nightsCount = getDaysCount($outboundDate, $inboundDate);

      $images = $country_images_by_id[$destinationCode];
      if (!$images) {
        $images = $country_images_by_id['default'];
      }
      if(sizeof($images["photos"]) == 0) {
        $images = $country_images_by_id['default'];
      }
      $imageUrl = "";
      
      $imageIndex = rand(0 , sizeof($images["photos"]) - 1);
      $image = $images["photos"][$imageIndex];
      $imageUrl = $image["photo_image_url"];
      $aspectRatio = floatval($image["photo_aspect_ratio"]);
      if ($aspectRatio == 0) {
        $aspectRatio = 1;
      }
      $imageStyle = "min-height:" . intval(200 / $aspectRatio) . "px";

      $url = "/vuelos/$origin/$destination/$outboundDate/$inboundDate";

      if ($nightsCount >0 && $nightsCount < 30) {
  ?>
    <a class="masonry-item" href="<? echo $url; ?>" target="_blank">
      <div class="card hover-shadow">
        <img
          class="card-img-top"
          src="<? echo $imageUrl . '?w=200'; ?>"
          alt="<? echo $destinationName; ?>"
          <? echo "style" . "=" . "'" . $imageStyle . "'"; ?>
        />
        
        <div class="card-body">
          <div class="card-text">
            <h6 class="card-subtitle my-3 text-muted">
              <? echo $destinationName; ?>
            </h6>

            <small class="text-dark">
              
              <? echo $MONTH_NAMES[date_create($outboundDate)->format("m")]; ?> | <? echo $nightsCount; ?> noches
            </small>

            <div class="text-right font-weight-bold color-accent text-nowrap">
              <small><? echo $currency; ?> </small><? echo number_format($price); ?>
            </div>

          </div>
        </div>
      </div>
    </a>
  <? } } ?>
  </div>
</div>