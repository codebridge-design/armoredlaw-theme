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
		$css_path = $theme_dir . '/assets/dist/css/main.min.css';

		wp_enqueue_style(
		    'armoredlaw-main',
		    $theme_uri . '/assets/dist/css/main.min.css',
		    [],
		    file_exists( $css_path ) ? filemtime( $css_path ) : null
		);

		// Slick JS
		$slick_path = $theme_dir . '/assets/js/slick.min.js';

		if ( file_exists( $slick_path ) ) {
		    wp_enqueue_script(
		        'slick',
		        $theme_uri . '/assets/js/slick.min.js',
		        [ 'jquery' ],
		        filemtime( $slick_path ),
		        true
		    );
		}
		$main_js_path = $theme_dir . '/assets/js/main.js';

		wp_enqueue_script(
		    'armoredlaw-main',
		    $theme_uri . '/assets/js/main.js',
		    file_exists( $slick_path ) ? [ 'jquery', 'slick' ] : [ 'jquery' ],
		    file_exists( $main_js_path ) ? filemtime( $main_js_path ) : null,
		    true
		);
}
add_action( 'wp_enqueue_scripts', 'armoredlaw_enqueue_assets' );

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
}
add_action( 'after_setup_theme', 'armoredlaw_theme_setup' );

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
