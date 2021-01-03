<?php
/**
 * Displays the Flights Search Form
 *
 * @package WordPress
 * @subpackage Twenty_Twenty
 * @since Twenty Twenty 1.0
 */

  $origin = get_query_var('origin');
  $destination = get_query_var('destination');
  $outboundDate = get_query_var('outboundDate');
  $inboundDate = get_query_var('inboundDate');

  $today = date("Y-m-d");
  if (strlen($outboundDate) == 0) {
    $outboundDate = $today;
  }
  if (strlen($inboundDate) == 0) {
    $inboundDate = $today;
  }
?>

<div class="my-5">
  <form autocomplete="off" role="search" class="search-form" id="flights-search-form">
    <label for="flights-search-origin" class="autocomplete">
      <span class="screen-reader-text">Origen</span>
      <input type="search" id="flights-search-origin" class="search-field" placeholder="Origen" value="<?php echo $origin ?>" name="origin" required />
    </label>

    <label for="flights-search-destination" class="autocomplete">
      <span class="screen-reader-text">Destino</span>
      <input type="search" id="flights-search-destination" class="search-field" placeholder="Destino" value="<?php echo $destination ?>" name="destination" required />
    </label>

    <label for="flights-search-departure-date">
      <span class="screen-reader-text">Salida</span>
      <input type="date" id="flights-search-departure-date" class="search-field" placeholder="Salida" value="<?php echo $outboundDate ?>" name="outboundDate" min="<?php echo $today ?>" />
    </label>

    <label for="flights-search-return-date">
      <span class="screen-reader-text">Regreso</span>
      <input type="date" id="flights-search-return-date" class="search-field" placeholder="Regreso" value="<?php echo $inboundDate ?>" name="inboundDate" min="<?php echo $today ?>" />
    </label>

    <input type="submit" class="search-submit" value="Buscar" />
  </form>
  
</div>

<style>
.search-form {
  flex-direction: column;
}
@media (min-width: 1024px) {
  .search-form {
    flex-direction: row;
  } 
}
</style>