<?php
/**
 * Template Name: Page with Hero + Elementor
 */

get_header();

// Hero з ACF
get_template_part( 'template-parts/hero' );
?>

<main id="primary" class="site-main">
    <?php
    while ( have_posts() ) :
        the_post();
        the_content();
    endwhile;
    ?>
</main>

<?php
get_footer();
