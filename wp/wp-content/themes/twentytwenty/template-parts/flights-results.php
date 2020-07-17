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
?>

<?php
  if ($formComplete) {
?>

  <div>
    <h1>resultados</h1>

    <p>origin <?php echo $origin; ?></p>
    <p>destination <?php echo $destination; ?></p>
    <p>outboundDate <?php echo $outboundDate; ?></p>
    <p>inboundDate <?php echo $inboundDate; ?></p>
  </div>

<?php
  }
?>