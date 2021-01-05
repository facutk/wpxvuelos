<?
  $browseroutes = xvuelos_get_browseroutes();
  $Routes = $browseroutes["Routes"];
  $currency = $browseroutes["Currencies"][0]["Code"];

  $Itineraries = $browseroutes["Itineraries"];
  $Carriers = $browseroutes["Carriers"];
  // $legs = array_column($browseroutes["Legs"], NULL, 'Id');
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
?>

<div>
  Ofertas saliendo de <? echo $originPlaceName; ?>
  <div class="masonry">
  <?
    foreach($RoutesWithPrice as $route) {
      $destination = $places[$route["DestinationId"]];
      $destinationName = $destination["Name"];
      $destinationCode = $destination["SkyscannerCode"];
      $price = $route["Price"];
      // $legs = [$quote["OutboundLeg"], $quote["InboundLeg"]];
      // $minPrice = $quote["MinPrice"];
      // $direct = $quote["Direct"];

      $images = $country_images_by_id[$destinationCode];
      if (!$images) {
        $images = $country_images_by_id['default'];
      }
      if(sizeof($images["photos"]) == 0) {
        $images = $country_images_by_id['default'];
      }
      $imageUrl = "";
      if ($images) {
        $imageIndex = rand(0 , sizeof($images["photos"]) - 1);
        $image = $images["photos"][$imageIndex];
        $imageUrl = $image["photo_image_url"];
        $aspectRatio = floatval($image["photo_aspect_ratio"]);
        if ($aspectRatio == 0) {
          $aspectRatio = 1;
        }
        $imageStyle = "min-height:" . intval(200 / $aspectRatio) . "px";
      }
  ?>
    <div class="masonry-item">
      <div class="card">
        <? if ($imageUrl) { ?>
          <img
            class="card-img-top img-fluid"
            src="<? echo $imageUrl . '?w=200'; ?>"
            alt="<? echo $destinationName; ?>"
            <? echo "style" . "=" . "'" . $imageStyle . "'"; ?>
          />
        <? } ?>
        
        <div class="card-body">
          <h5 class="card-title"><? echo $destinationName; ?></h5>
          <p class="card-text">
            <small><? echo $currency; ?></small> <? echo number_format($price); ?>
          </p>
        </div>
      </div>
    </div>
  <? } ?>
  </div>
</div>