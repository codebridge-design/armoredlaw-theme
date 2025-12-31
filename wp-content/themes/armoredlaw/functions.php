<?php
/**
 * Armored Law Theme functions
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Enqueue styles & fonts
 */
function armoredlaw_enqueue_assets() {
  $theme_dir = get_template_directory();
  $theme_uri = get_template_directory_uri();

  // === MAIN CSS ===
  $css_path = $theme_dir . '/assets/dist/css/main.min.css';
  wp_enqueue_style(
    'armoredlaw-main',
    $theme_uri . '/assets/dist/css/main.min.css',
    [],
    file_exists($css_path) ? filemtime($css_path) : null
  );

  // === Slick ===
  $slick_path = $theme_dir . '/assets/js/slick.min.js';
  if (file_exists($slick_path)) {
    wp_enqueue_script(
      'slick',
      $theme_uri . '/assets/js/slick.min.js',
      ['jquery'],
      filemtime($slick_path),
      true
    );
  }

  // === MAIN JS ===
  $main_js_path = $theme_dir . '/assets/js/main.js';
  wp_enqueue_script(
    'armoredlaw-main',
    $theme_uri . '/assets/js/main.js',
    file_exists($slick_path) ? ['jquery', 'slick'] : ['jquery'],
    file_exists($main_js_path) ? filemtime($main_js_path) : null,
    true
  );

  wp_localize_script('armoredlaw-main', 'armoredlawAjax', [
    'url'   => admin_url('admin-ajax.php'),
    'nonce' => wp_create_nonce('armoredlaw_load_more'),
  ]);


  // === GET A FREE QUOTE PAGE ===
  if (is_page_template('page-templates/page-get-a-free-quote.php')) {

    $quote_js_path  = $theme_dir . '/assets/js/quote-form.js';
    $quote_css_path = $theme_dir . '/assets/css/quote-form.css';

    wp_enqueue_script(
      'al-quote-form',
      $theme_uri . '/assets/js/quote-form.js',
      [],
      file_exists($quote_js_path) ? filemtime($quote_js_path) : null,
      true
    );

    wp_enqueue_style(
      'al-quote-form',
      $theme_uri . '/assets/css/quote-form.css',
      [],
      file_exists($quote_css_path) ? filemtime($quote_css_path) : null
    );
  }
}
add_action('wp_enqueue_scripts', 'armoredlaw_enqueue_assets');

// === AJAX ENDPOINT ===
require_once get_template_directory() . '/inc/quote-form-endpoints.php';

/**
 * Theme supports
 */
function armoredlaw_theme_setup() {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support(
		'html5',
		array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption' )
	);

	add_theme_support( 'custom-logo', array(
		'height'      => 60,
		'width'       => 200,
		'flex-width'  => true,
		'flex-height' => true,
	) );

	register_nav_menus(
		array(
			'primary'           => __( 'Primary Menu', 'armoredlaw' ),
			'footer_membership' => __( 'Footer Membership Menu', 'armoredlaw' ),
			'footer_company'    => __( 'Footer Company Menu', 'armoredlaw' ),
			'footer_train'      => __( 'Footer Train & Learn Menu', 'armoredlaw' ),
		)
	);

	register_nav_menus([
    'blog_quick_links' => __('Blog Quick Links', 'armoredlaw'),
  ]);
}
add_action( 'after_setup_theme', 'armoredlaw_theme_setup' );

add_action('init', function () {
  register_taxonomy('content_type', ['post'], [
    'labels' => [
      'name'          => __('Content Types', 'armoredlaw'),
      'singular_name' => __('Content Type', 'armoredlaw'),
    ],
    'public'            => true,
    'show_ui'           => true,
    'show_in_rest'      => true,
    'show_admin_column' => true,
    'hierarchical'      => false,
    'rewrite'           => ['slug' => 'content-type'],
  ]);
});


//(optional) remove the admin bar on the front
add_filter( 'show_admin_bar', '__return_false' );

// Reciprocity map shortcode
add_shortcode('armoredlaw_map', function () {
  ob_start();
  get_template_part('template-parts/reciprocity-map');
  return ob_get_clean();
});

// Testimonials shortcode
add_shortcode('armoredlaw_testimonials', function () {
	ob_start();
	get_template_part('template-parts/testimonials');
	return ob_get_clean();
});

// Membership pricing shortcode
add_shortcode('armoredlaw_membership_pricing', function () {
  if ( ! function_exists('get_field') ) {
    return '';
  }

  ob_start();
  get_template_part('template-parts/membership-pricing');
  return ob_get_clean();
});

// Small Pricing Cards shortcode
add_shortcode('armoredlaw_small_pricing', function () {
  ob_start();
  get_template_part('template-parts/small-pricing-cards');
  return ob_get_clean();
});

require_once get_template_directory() . '/inc/membership-pricing-helpers.php';
require_once get_template_directory() . '/inc/helpers/states.php';

add_filter('body_class', function ($classes) {
  if (is_page('thank-you')) {
    $classes[] = 'page-thank-you';
  }
  return $classes;
});

//Redirect Forgot password page
add_action('template_redirect', function () {
  if (is_admin() || wp_doing_ajax()) return;

  if (is_page('login') && isset($_GET['action']) && $_GET['action'] === 'forgot_password') {
    wp_safe_redirect( home_url('/forgot-password/?action=forgot_password') );
    exit;
  }
});

add_action('wp_ajax_armoredlaw_load_more_posts', 'armoredlaw_load_more_posts');
add_action('wp_ajax_nopriv_armoredlaw_load_more_posts', 'armoredlaw_load_more_posts');

function armoredlaw_load_more_posts() {

  $nonce = $_POST['nonce'] ?? '';
  if (!wp_verify_nonce($nonce, 'armoredlaw_load_more')) {
    wp_send_json_error(['message' => 'Invalid nonce'], 403);
  }

  $page = isset($_POST['page']) ? max(1, (int) $_POST['page']) : 1;
  $exclude_csv = isset($_POST['exclude']) ? (string) $_POST['exclude'] : '';
  $exclude_ids = array_filter(array_map('intval', explode(',', $exclude_csv)));
  $content_type = isset($_POST['content_type']) ? sanitize_text_field($_POST['content_type']) : '';
  $date_raw     = isset($_POST['date']) ? sanitize_text_field($_POST['date']) : '';
  $search       = isset($_POST['s']) ? sanitize_text_field($_POST['s']) : '';

  $args = [
    'post_type'           => 'post',
    'post_status'         => 'publish',
    'posts_per_page'      => 9,
    'paged'               => $page,
    'post__not_in'        => $exclude_ids,
    'ignore_sticky_posts' => true,
    'orderby'             => 'date',
    'order'               => 'DESC',
    's'                   => $search,
  ];

  if ($content_type) {
    $args['tax_query'] = [[
      'taxonomy' => 'content_type',
      'field'    => 'slug',
      'terms'    => [$content_type],
    ]];
  }

  if ($date_raw) {
    $date_query = [];

    if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $date_raw)) {
      [$y, $m, $d] = array_map('intval', explode('-', $date_raw));
      $date_query[] = ['year' => $y, 'monthnum' => $m, 'day' => $d];
    } elseif (preg_match('/^\d{4}-\d{2}$/', $date_raw)) {
      [$y, $m] = array_map('intval', explode('-', $date_raw));
      $date_query[] = ['year' => $y, 'monthnum' => $m];
    }

    if ($date_query) {
      $args['date_query'] = $date_query;
    }
  }

  $q = new WP_Query($args);

  if (!$q->have_posts()) {
    wp_send_json_error(['message' => 'No more posts']);
  }

  ob_start();
  while ($q->have_posts()) : $q->the_post();
    get_template_part('template-parts/blog-card');
  endwhile;
  wp_reset_postdata();

  wp_send_json_success([
    'html' => ob_get_clean(),
    'max'  => (int) $q->max_num_pages,
  ]);
}
