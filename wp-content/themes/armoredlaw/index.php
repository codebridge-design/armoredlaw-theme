<?php
/**
 * Main template file
 */

get_header();
?>

<main>
    <h1><?php bloginfo( 'name' ); ?></h1>

    <?php if ( have_posts() ) : ?>
        <?php while ( have_posts() ) : the_post(); ?>
            <article id="post-<?php the_ID(); ?>">
                <h2><?php the_title(); ?></h2>
                <div>
                    <?php the_content(); ?>
                </div>
            </article>
        <?php endwhile; ?>
    <?php else : ?>
        <p>Nothing found.</p>
    <?php endif; ?>
</main>

<?php
get_footer();
