<?php
get_header();
?>

<main id="content" class="site-content">
	<?php
	while ( have_posts() ) :
		the_post();

		get_template_part( 'template-parts/hero' );

		the_content();
	endwhile;
	?>
</main>

<?php
get_footer();
