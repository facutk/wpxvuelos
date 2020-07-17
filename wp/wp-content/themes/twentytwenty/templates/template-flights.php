<?php
/**
 * Template Name: Flights Template
 * Template Post Type: post, page
 *
 * @package WordPress
 * @subpackage Twenty_Twenty
 * @since Twenty Twenty 1.0
 */

get_header();

?>

<main role="main">
	<section class="section-inner">
		<?php
			get_template_part( 'template-parts/flights-search' );
		?>

		<?php
			get_template_part( 'template-parts/flights-results' );
		?>
	</section>
</main><!-- #site-content -->

<?php get_template_part( 'template-parts/footer-menus-widgets' ); ?>

<?php get_footer(); ?>
