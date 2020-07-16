<?php
/**
 * Displays the Flights Results
 *
 * @package WordPress
 * @subpackage Twenty_Twenty
 * @since Twenty Twenty 1.0
 */
?>

<section class="section-inner">
  <h1>resultados</h1>

  <p>origin <?php echo get_query_var( 'origin' )  ?></p>
  <p>destination <?php echo get_query_var( 'destination' )  ?></p>
  <p>outboundDate <?php echo get_query_var( 'outboundDate' )  ?></p>
  <p>inboundDate <?php echo get_query_var( 'inboundDate' )  ?></p>
</section>
