<?php
/**
 * Template Name: Page: Hero + Elementor + Testimonials
 */

get_header();

// HERO з ACF
get_template_part( 'template-parts/hero' );
?>

<main id="primary" class="site-main">
	<?php
	while ( have_posts() ) :
		the_post();

		the_content(); // тут працює Elementor
	endwhile;
	?>
</main>

<?php
// TESTIMONIALS з ACF
get_template_part( 'template-parts/testimonials' );

get_footer();
