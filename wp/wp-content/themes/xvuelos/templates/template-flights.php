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

<?php get_header(); ?>

<div class="container">

<?php get_template_part( 'template-parts/flights-search' ); ?>

<?
  $loading = get_query_var('loading');
  if ($loading) {
    get_template_part( 'template-parts/flights-loading' ); 
  } else {
    get_template_part( 'template-parts/flights-results' ); 
  }
?>

</div>

<?php get_template_part( 'template-parts/footer-menus-widgets' ); ?>

<?php get_footer(); ?>
