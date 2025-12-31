<?php
/**
 * Blog card (used in listing + AJAX)
 */

$post_id = get_the_ID();
$cats = get_the_category($post_id);
$primary = !empty($cats) ? $cats[0] : null;
?>

<a class="blog-card" href="<?php the_permalink(); ?>">
  <div class="blog-card__thumb">
    <?php
      if (has_post_thumbnail($post_id)) {
        echo get_the_post_thumbnail($post_id, 'medium', ['loading' => 'lazy']);
      }
    ?>
  </div>

  <div class="blog-card__body">
    <?php if ($primary) : ?>
      <div class="blog-card__badge">
        <span class="badge badge--category"><?php echo esc_html($primary->name); ?></span>
      </div>
    <?php endif; ?>

    <h3 class="blog-card__title"><?php the_title(); ?></h3>

    <p class="blog-card__excerpt">
      <?php echo esc_html(wp_trim_words(get_the_excerpt($post_id), 18)); ?>
    </p>

    <span class="blog-card__more">Continue Reading →</span>
  </div>
</a>
