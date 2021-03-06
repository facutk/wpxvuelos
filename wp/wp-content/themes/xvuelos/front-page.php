<?php get_header(); ?>

<div class="container">

<?php get_template_part( 'template-parts/flights-search' ); ?>

	<?php
  if ( have_posts() ) {
    while ( have_posts() ) {
      the_post();
      the_content();
    }
  }
	?>

<? dynamic_sidebar( 'front-page-middle' ); ?>

<?php get_template_part( 'template-parts/flights-routes-browser' ); ?>
</div>

<?php get_template_part( 'template-parts/footer-menus-widgets' ); ?>

<?php get_footer(); ?>
