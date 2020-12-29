<?php
/**
 * Displays the Flights Results
 *
 * @package WordPress
 * @subpackage Twenty_Twenty
 * @since Twenty Twenty 1.0
 */

$origin = get_query_var('origin');
$destination = get_query_var('destination');
$outboundDate = get_query_var('outboundDate');
$inboundDate = get_query_var('inboundDate');

$formComplete = strlen($origin) > 0 && strlen($destination) > 0 && strlen($outboundDate) > 0 && strlen($inboundDate) > 0;
$mockSession = xvuelos_get_flights();

$Itineraries = $mockSession["Itineraries"];
$legs = array_column($mockSession["Legs"], NULL, 'Id');
$places = array_column($mockSession["Places"], NULL, 'Id');
$carriers = array_column($mockSession["Carriers"], NULL, 'Id');

$arrayFlights = [];
$directionalityLabels = [
  "Outbound" => "Ida",
  "Inbound" => "Vuelta"
];

$directionalityLabels = [
  "Outbound" => "Ida",
  "Inbound" => "Vuelta"
];

function imageUrlToFaviconUrl($imageUrl) {
  $slash = "/";
  $parts = explode($slash, $imageUrl);
  array_splice($parts, sizeof($parts) - 1, 0, "favicon");
  return implode($slash, $parts);
}
?>

<?php
  if ($formComplete) {
?>


<div class="row">
  <div class="col-md-3 mb-4">
    <details id="filter-details">
      <summary></summary>
      <div>
        <pre>
          []
        </pre>
      </div>
    </details>
  </div>

  <!-- Itineraries -->
  <div class="col-md-7">
    <? get_template_part( 'template-parts/flights-sortby' ); ?>

    <?
      foreach ($Itineraries as $Itinerary) {
        $itineraryLegs = [
          $Itinerary["OutboundLegId"],
          $Itinerary["InboundLegId"]
        ];
        $price = $Itinerary["PricingOptions"][0]["Price"];
    ?>

      <div class="card my-2">
        <div class="card-body">
          <div class="row">
            <div class="col-lg-9">
              <?
                foreach ($itineraryLegs as $legId) {
                  $leg = $legs[$legId];

                  $directionality = $leg["Directionality"];
                  $origin = $places[$leg["OriginStation"]];
                  $destination = $places[$leg["DestinationStation"]];
                  $departure = $leg["Departure"];
                  $arrival = $leg["Arrival"];
                  $duration = $leg["Duration"];
                  $stops = sizeof($leg["SegmentIds"]) - 1;
                  $carrier = $carriers[$leg["Carriers"][0]];
                  $carrierFavicon = imageUrlToFaviconUrl($carrier["ImageUrl"]);
                ?>
                  <h6 class="card-subtitle mb-2 text-muted">
                    <? echo $directionalityLabels[$directionality]; ?>
                    <small>
                      / 
                      <script>
                        document.write(dayjs("<? echo $departure; ?>").format("ddd. D MMM. YYYY"));
                      </script>
                    </small>
                  </h6>

                  <p class="card-text">
                    <div class="row">
                      <div class="col-md-3 text-truncate">
                        <div>
                          <img
                            src="<? echo $carrierFavicon; ?>"
                            class="img-fluid"
                            alt="<? echo $carrier["Name"]; ?>"
                            width="24px"
                            height="24px"
                          >
                        </div>
                        <div>
                          <small>
                            <? echo $carrier["Name"]; ?>
                          </small>
                        </div>  
                      </div>
                      
                      <div class="col-md-9">
                        <div class="row">
                          <div class="col text-truncate">
                            <div class="text-monospace">
                              <script>
                                document.write(dayjs("<? echo $departure; ?>").format("HH:mm"));
                              </script>
                            </div>

                            <div>
                              <small>
                                <? echo $origin["Name"]; ?>
                              </small>
                            </div>

                            <div>
                              <span class="text-monospace">
                                <? echo $origin["Code"]; ?>
                              </span>
                            </div>
                          </div>
                          <div class="col">
                            <div class="mb-2 text-nowrap">
                              <small>
                                <script>
                                  document.write(dayjs().minute("<? echo $duration; ?>").format("HH[hs] mm[m]").replace(/^0+/, ''));
                                </script>
                              </small>
                            </div>
                            <div>
                              <small class="d-inline-block">
                                <? echo $stops; ?> escala<? if ($stops !== 1) echo 's'; ?>
                                <div class="mt-2">
                                  <div class="border-bottom border-dark"></div>

                                  <div class="d-flex justify-content-around">
                                    <?
                                      foreach(range(1, $stops) as $n) {
                                    ?>
                                      <span class="stop-indicator"></span>
                                    <?
                                      }
                                    ?>
                                  </div>
                                </div>
                              </small>
                            </div>


                          </div>
                          <div class="col text-truncate">
                            <div class="text-monospace">
                              <script>
                                document.write(dayjs("<? echo $arrival; ?>").format("HH:mm"));
                              </script>
                            </div>

                            <div>
                              <small>
                                <? echo $destination["Name"]; ?>
                              </small>
                            </div>

                            <div>
                              <span class="text-monospace">
                                <? echo $destination["Code"]; ?>
                              </span>
                            </div>
                          </div>
                        </div>
                      </div>
                      
                    </div>
                  </p>
                <?
                }
              ?>
            </div>
            <div class="col-lg-3">
              <div class="row">
                <div class="col">
                  <input type="submit" class="search-submit w-100 py-3" value="ver">
                </div>
                <div class="col text-right align-text-bottom py-2">
                  <span class="font-weight-bold color-accent text-nowrap">
                    <small>$</small><? echo number_format($price); ?>
                  </span>
                </div>
              </div>
            </div>
          </div>
          
        </div>
      </div>

    <?
      }
    ?>
    
    <? get_template_part( 'template-parts/flights-pagination' ); ?>
  </div>

  <div class="col-md-2 mb-4 d-none d-md-block">
    <img src="https://place-hold.it/160x600" class="img-fluid" alt="Responsive image">
  </div>
</div>

<script>
  const mediaQuery = window.matchMedia('(min-width: 768px)')

  function handleTabletChange(e) {
    if (e.matches) {
      document.getElementById("filter-details").setAttribute("open", true);
    }
  }

  mediaQuery.addListener(handleTabletChange)

  handleTabletChange(mediaQuery);
</script>

<?php
  }
?>