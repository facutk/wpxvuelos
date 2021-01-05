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
  })
?>

<div>
  Ofertas saliendo de <? echo $originPlaceName; ?>
  <?
    foreach($RoutesWithPrice as $route) {
      $destination = $places[$route["DestinationId"]];
      $destinationName = $destination["Name"];
      $price = $route["Price"];
      // $legs = [$quote["OutboundLeg"], $quote["InboundLeg"]];
      // $minPrice = $quote["MinPrice"];
      // $direct = $quote["Direct"];

  ?>
    <div class="card my-3">
      <div class="card-body">
        <div>
          <? echo $destinationName; ?> por <small><? echo $currency; ?></small> <? echo number_format($price); ?>
        </div>
        
      </div>
    </div>
  <? } ?>
</div>