<?php
/**
 * Blog Listing (Posts page)
 */
get_header();

// Global hero (options)
get_template_part('template-parts/hero', null, [
  'acf_context' => 'option',
]);

// 1) Recent posts (top 3)
$recent_posts = get_posts([
  'post_type'           => 'post',
  'post_status'         => 'publish',
  'posts_per_page'      => 3,
  'ignore_sticky_posts' => true,
  'orderby'             => 'date',
  'order'               => 'DESC',
]);

$exclude_ids = array_map(static fn($p) => (int) $p->ID, $recent_posts);

// 2) Filters from URL
$paged       = max(1, (int) get_query_var('paged'));
$active_type = isset($_GET['content_type']) ? sanitize_text_field($_GET['content_type']) : '';
$search      = isset($_GET['s']) ? sanitize_text_field($_GET['s']) : '';
$date_raw    = isset($_GET['date']) ? sanitize_text_field($_GET['date']) : ''; // YYYY-MM or YYYY-MM-DD

$tax_query = [];
if ($active_type) {
  $tax_query[] = [
    'taxonomy' => 'content_type',
    'field'    => 'slug',
    'terms'    => [$active_type],
  ];
}

$date_query = [];
if ($date_raw) {
  if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $date_raw)) {
    [$y, $m, $d] = array_map('intval', explode('-', $date_raw));
    $date_query[] = ['year' => $y, 'monthnum' => $m, 'day' => $d];
  } elseif (preg_match('/^\d{4}-\d{2}$/', $date_raw)) {
    [$y, $m] = array_map('intval', explode('-', $date_raw));
    $date_query[] = ['year' => $y, 'monthnum' => $m];
  }
}

// 3) Main grid query (exclude recent + apply filters)
$args = [
  'post_type'           => 'post',
  'post_status'         => 'publish',
  'posts_per_page'      => 9,
  'paged'               => $paged,
  'post__not_in'        => $exclude_ids,
  'ignore_sticky_posts' => true,
  'orderby'             => 'date',
  'order'               => 'DESC',
  's'                   => $search,
];

if (!empty($tax_query))  $args['tax_query']  = $tax_query;
if (!empty($date_query)) $args['date_query'] = $date_query;

$main_query = new WP_Query($args);

$max_pages = (int) $main_query->max_num_pages;
?>

<?php
$filters = [
  ''            => 'All',
  'case-studies'=> 'Case Studies',
  'news'        => 'News',
  'podcasts'    => 'Podcasts',
  'insights'    => 'Insights',
  'tips-guides' => 'Tips & Guides',
];

function al_build_url($overrides = []) {
  $base = get_permalink(get_option('page_for_posts')) ?: home_url('/blog/');
  $q = $_GET;
  foreach ($overrides as $k => $v) {
    if ($v === '' || $v === null) unset($q[$k]);
    else $q[$k] = $v;
  }
  return add_query_arg($q, $base);
}
?>

<div class="blog-filters">
  <div class="container blog-filters__inner">

    <div class="blog-filters__tabs">
      <?php foreach ($filters as $slug => $label): ?>
        <?php
          $url = al_build_url(['content_type' => $slug, 'paged' => null]);
          $is_active = ($slug === $active_type) || ($slug === '' && $active_type === '');
        ?>
        <a class="blog-filters__tab <?php echo $is_active ? 'is-active' : ''; ?>"
           href="<?php echo esc_url($url); ?>">
          <?php echo esc_html($label); ?>
        </a>
      <?php endforeach; ?>
    </div>

    <div class="blog-filters__controls">
      <!-- Date -->
      <form class="blog-filters__date" method="get" action="<?php echo esc_url(al_build_url()); ?>">
        <?php if ($active_type): ?>
          <input type="hidden" name="content_type" value="<?php echo esc_attr($active_type); ?>">
        <?php endif; ?>
        <?php if ($search): ?>
          <input type="hidden" name="s" value="<?php echo esc_attr($search); ?>">
        <?php endif; ?>

        <input
          type="month"
          name="date"
          value="<?php echo esc_attr($date_raw); ?>"
          class="blog-filters__date-input"
          onchange="this.form.submit()"
        >
      </form>

      <!-- Search -->
      <form class="blog-filters__search" method="get" action="<?php echo esc_url(al_build_url()); ?>">
        <?php if ($active_type): ?>
          <input type="hidden" name="content_type" value="<?php echo esc_attr($active_type); ?>">
        <?php endif; ?>
        <?php if ($date_raw): ?>
          <input type="hidden" name="date" value="<?php echo esc_attr($date_raw); ?>">
        <?php endif; ?>

        <input
          type="search"
          name="s"
          value="<?php echo esc_attr($search); ?>"
          placeholder="Search Blog"
          class="blog-filters__search-input"
        >
        <button type="submit" class="blog-filters__search-btn" aria-label="Search"></button>
      </form>
    </div>

  </div>
</div>

<main class="blog-listing">
  <?php if (!empty($recent_posts)) : ?>
    <section class="blog-listing__recent">
      <div class="container">

        <h2 class="blog-listing__section-title">Recent Blog Posts</h2>

        <div class="blog-recent">
          <?php
            // Big card = first post
            $big = $recent_posts[0];
            $big_id = (int) $big->ID;
            setup_postdata($big);
          ?>
          <a class="blog-recent__big blog-card" href="<?php echo esc_url(get_permalink($big_id)); ?>">
            <div class="blog-recent__big-thumb blog-card__thumb">
              <?php
                if (has_post_thumbnail($big_id)) {
                  echo get_the_post_thumbnail($big_id, 'large', ['loading' => 'eager']);
                }
              ?>
            </div>

            <div class="blog-recent__big-body blog-card__body">
              <?php
                $cats = get_the_category($big_id);
                $primary = !empty($cats) ? $cats[0] : null;
              ?>
              <?php if ($primary) : ?>
                <div class="blog-card__badge">
                  <span class="badge badge--category"><?php echo esc_html($primary->name); ?></span>
                </div>
              <?php endif; ?>

              <h3 class="blog-recent__big-title blog-card__title">
                <?php echo esc_html(get_the_title($big_id)); ?>
              </h3>

              <div class="blog-recent__meta">
                <span class="blog-recent__author">
                  By: <span class="author"><?php echo esc_html(get_the_author_meta('display_name', $big->post_author)); ?></span>
                </span>
                <span class="blog-recent__sep">|</span>
                <time datetime="<?php echo esc_attr(get_the_date('c', $big_id)); ?>">
                  <?php echo esc_html(get_the_date('', $big_id)); ?>
                </time>
              </div>

              <p class="blog-recent__excerpt blog-card__excerpt">
                <?php echo esc_html(wp_trim_words(get_the_excerpt($big_id), 22)); ?>
              </p>

              <span class="blog-recent__more blog-card__more">Continue Reading →</span>
            </div>
          </a>

          <?php wp_reset_postdata(); ?>

          <div class="blog-recent__side">
            <?php for ($i = 1; $i <= 2; $i++) :
              if (empty($recent_posts[$i])) continue;
              $p = $recent_posts[$i];
              $pid = (int) $p->ID;
            ?>
              <a class="blog-recent__small blog-card" href="<?php echo esc_url(get_permalink($pid)); ?>">
                <div class="blog-recent__small-thumb blog-card__thumb">
                  <?php
                    if (has_post_thumbnail($pid)) {
                      echo get_the_post_thumbnail($pid, 'medium', ['loading' => 'lazy']);
                    }
                  ?>
                </div>

                <div class="blog-recent__small-body blog-card__body">
                  <?php
                    $cats = get_the_category($pid);
                    $primary = !empty($cats) ? $cats[0] : null;
                  ?>
                  <?php if ($primary) : ?>
	                  <div class="blog-card__badge">
	                    <span class="badge badge--category"><?php echo esc_html($primary->name); ?></span>
                    </div>
                  <?php endif; ?>

                  <h3 class="blog-recent__small-title blog-card__title">
                    <?php echo esc_html(get_the_title($pid)); ?>
                  </h3>

                  <div class="blog-recent__meta">
                    <span class="blog-recent__author">
                      By: <span class="author"><?php echo esc_html(get_the_author_meta('display_name', $big->post_author)); ?></span>
                    </span>
                    <span class="blog-recent__sep">|</span>
                    <time datetime="<?php echo esc_attr(get_the_date('c', $big_id)); ?>">
                      <?php echo esc_html(get_the_date('', $big_id)); ?>
                    </time>
                  </div>

                  <p class="blog-recent__excerpt blog-card__excerpt">
                    <?php echo esc_html(wp_trim_words(get_the_excerpt($pid), 14)); ?>
                  </p>

                  <span class="blog-recent__more blog-card__more">Continue Reading →</span>
                </div>
              </a>
            <?php endfor; ?>
          </div>
        </div>

      </div>
    </section>
  <?php endif; ?>

  <section class="blog-listing__grid">
    <div class="container">

      <?php if ($main_query->have_posts()) : ?>

        <div
          class="blog-cards"
          data-exclude="<?php echo esc_attr(implode(',', $exclude_ids)); ?>"
          data-content-type="<?php echo esc_attr($active_type); ?>"
          data-date="<?php echo esc_attr($date_raw); ?>"
          data-search="<?php echo esc_attr($search); ?>"
        >
          <?php while ($main_query->have_posts()) : $main_query->the_post(); ?>
            <?php get_template_part('template-parts/blog-card'); ?>
          <?php endwhile; ?>
        </div>

        <?php if ($max_pages > 1) : ?>
          <div class="blog-listing__loadmore">
            <button
              type="button"
              class="btn btn--secondary blog-loadmore"
              data-page="1"
              data-max="<?php echo esc_attr($max_pages); ?>"
            >
              <span>Load More</span>
            </button>
          </div>
        <?php endif; ?>

      <?php else : ?>
        <p>No posts found.</p>
      <?php endif; ?>

      <?php wp_reset_postdata(); ?>

    </div>
  </section>

</main>
<?php
	if ( did_action('elementor/loaded') ) {
	  echo \Elementor\Plugin::instance()->frontend->get_builder_content_for_display( 396 );
	}
	?>
<?php get_footer(); ?>
