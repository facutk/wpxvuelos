<?php
/**
 * Template Name: Flights Template
 * Template Post Type: post, page
 *
 * @package WordPress
 * @subpackage Twenty_Twenty
 * @since Twenty Twenty 1.0
 */
?>

<?
  $pagename = get_query_var('pagename');
  $sid = get_query_var('sid');

  $loading = false;
  if ($pagename && !$sid) {
    $sid = xvuelos_create_sessionid();
    // wp_redirect('?sid=' . $sid);
    // exit;
  }
?>

<?php get_header(); ?>

<div class="container">

<?php get_template_part( 'template-parts/flights-search' ); ?>

<?  
  if ($loading) {
    get_template_part( 'template-parts/flights-loading' ); 
  } else {
    get_template_part( 'template-parts/flights-results' ); 
  }
?>

</div>

<?php get_template_part( 'template-parts/footer-menus-widgets' ); ?>

<?php get_footer(); ?>
