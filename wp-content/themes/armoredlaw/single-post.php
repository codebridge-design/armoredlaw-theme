<?php
/**
 * Single Post template
 */
get_header();

if ( have_posts() ) :
  while ( have_posts() ) : the_post();

    // HERO (global, options)
    get_template_part('template-parts/hero', null, [
      'acf_context' => 'option',
    ]);

    $post_id   = get_the_ID();
    $title     = get_the_title();
    $date      = get_the_date();
    $author_id  = (int) get_the_author_meta('ID');
    $author_url = get_author_posts_url($author_id);
    $author_name = get_the_author();
    $thumb_id  = get_post_thumbnail_id($post_id);
    $thumb_url = $thumb_id ? wp_get_attachment_image_url($thumb_id, 'large') : '';

    $categories  = get_the_category($post_id);
    $primary_cat = !empty($categories) ? $categories[0] : null;

    $related_posts = [];
    if ( $primary_cat ) {
      $related_posts = get_posts([
        'post_type'           => 'post',
        'posts_per_page'      => 3,
        'post_status'         => 'publish',
        'post__not_in'        => [$post_id],
        'ignore_sticky_posts' => true,
        'category__in'        => [$primary_cat->term_id],
      ]);
    }
?>

<?php
	$posts_page_id = (int) get_option('page_for_posts');
	$blog_url  = $posts_page_id ? get_permalink($posts_page_id) : home_url('/blog/');
	$home_label = 'Home';
?>

<div class="breadcrumbs container" aria-label="Back to blog">
	<ul class="breadcrumbs__list">
    <li class="breadcrumbs__item ">
      <a class="breadcrumbs__link" href="<?php echo esc_url($blog_url); ?>">
        <?php echo esc_html($home_label); ?>
      </a>
    </li>
    <li class="breadcrumbs__item">
      <span class="breadcrumbs__link">
				<?php echo esc_html(get_the_title()); ?>
			</span>
    </li>
  </ul>
</div>

<main class="blog-single">

  <!-- HERO -->
  <section class="blog-single__hero">
    <div class="container blog-single__hero-inner">

      <div class="blog-single__hero-top">
        <?php if ( $primary_cat ) : ?>
          <a class="badge badge--category" href="<?php echo esc_url(get_category_link($primary_cat->term_id)); ?>">
            <?php echo esc_html($primary_cat->name); ?>
          </a>
        <?php endif; ?>

        <h1 class="blog-single__title"><?php echo esc_html($title); ?></h1>

        <div class="blog-single__meta">
          <p class="blog-single__meta-item">
            <span>By:</span>
            <a class="blog-single__author-link" href="<?php echo esc_url($author_url); ?>">
              <?php echo esc_html($author_name); ?>
            </a>
          </p>
          <p class="blog-single__meta-sep">|</p>
          <time class="blog-single__meta-item" datetime="<?php echo esc_attr(get_the_date('c')); ?>">
            <?php echo esc_html($date); ?>
          </time>
        </div>
      </div>
    </div>
  </section>

  <!-- BODY -->
  <section class="blog-single__body">
    <div class="container blog-single__grid">

      <!-- CONTENT -->
      <article class="blog-single__content" id="post-<?php the_ID(); ?>">
        <div class="wysiwyg">
          <?php the_content(); ?>
        </div>
      </article>

      <!-- SIDEBAR -->
      <aside class="blog-single__sidebar sidebar">

        <!-- Search -->
        <div class="sidebar__box sidebar__box--search">
          <form class="sidebar__search" action="<?php echo esc_url(home_url('/')); ?>" method="get" role="search">
            <input type="search"
                   name="s"
                   value="<?php echo esc_attr(get_search_query()); ?>"
                   placeholder="Search Blog"
                   class="sidebar__search-input" />
            <input type="hidden" name="post_type" value="post" />
            <button type="submit" class="sidebar__search-btn" aria-label="Search"></button>
          </form>

          <div class="sidebar__select-wrap">
            <select
              class="sidebar__select"
              name="category"
              name="category"
              onchange="if (this.value) window.location.href = this.value;"
            >
              <option value="">
                Select Topic
              </option>

              <?php
                $cats = get_categories([
                  'taxonomy'   => 'category',
                  'hide_empty' => true,
                  'orderby'    => 'name',
                  'order'      => 'ASC',
                ]);

                foreach ( $cats as $cat ) :
                  $cat_link = get_category_link($cat->term_id);
              ?>
                <option value="<?php echo esc_url($cat_link); ?>">
                  <?php echo esc_html($cat->name); ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>

        <!-- Quick Links -->
        <div class="sidebar__box sidebar__box--quicklinks">
          <h3 class="sidebar__title">Quick Links</h3>
          <?php
            wp_nav_menu([
              'theme_location' => 'blog_quick_links',
              'container'      => false,
              'menu_class'     => 'sidebar__list',
              'fallback_cb'    => '__return_empty_string',
              'depth'          => 1,
            ]);
          ?>
        </div>

      </aside>

    </div>
  </section>

	<?php if ( !empty($related_posts) ) : ?>
		<section class="container blog-single__related">
	    <div class="related-posts">
	      <h2 class="related-posts__title">Related Posts</h2>

	      <div class="related-posts__grid">
	        <?php foreach ( $related_posts as $rp ) :
	          $rp_id = $rp->ID;
	          $rp_cat = get_the_category($rp_id);
	          $rp_primary = !empty($rp_cat) ? $rp_cat[0] : null;
	          $rp_thumb_id = get_post_thumbnail_id($rp_id);
	        ?>
	          <a class="related-posts__card" href="<?php echo esc_url(get_permalink($rp_id)); ?>">
	            <div class="related-posts__thumb">
	              <?php
	                if ( $rp_thumb_id ) {
	                  echo wp_get_attachment_image($rp_thumb_id, 'medium', false, ['loading' => 'lazy']);
	                }
	              ?>
	            </div>

	            <div class="related-posts__body">
	              <?php if ( $rp_primary ) : ?>
	                <div class="related-posts__badge">
	                  <span class="badge badge--category"><?php echo esc_html($rp_primary->name); ?></span>
	                </div>
	              <?php endif; ?>
	              <div class="related-posts__bottom">
									<div class="related-posts__text">
										<h3 class="related-posts__card-title"><?php echo esc_html(get_the_title($rp_id)); ?></h3>
                    <div class="related-posts__excerpt">
                      <?php echo esc_html(wp_trim_words(get_the_excerpt($rp_id), 18)); ?>
                    </div>
									</div>
									<div class="related-posts__link">Continue Reading →</div>
								</div>
							</div>
	          </a>
	        <?php endforeach; ?>
	      </div>
	    </div>
		</section>
  <?php endif; ?>

</main>

<?php
  endwhile;
endif;

get_footer();
