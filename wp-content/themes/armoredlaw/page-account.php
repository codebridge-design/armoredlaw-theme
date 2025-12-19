<?php
/**
 * Template Name: Account
 */
defined('ABSPATH') || exit;

get_header();

if (!is_user_logged_in()) {
  $login_page_id = (int) get_option('mepr_login_page_id');
  $login_url = $login_page_id ? get_permalink($login_page_id) : home_url('/login/');
  wp_safe_redirect($login_url);
  exit;
}
?>

<main class="container al-auth al-auth--account">
  <div class="al-auth__container">

    <header class="al-auth__header">
      <h1 class="al-auth__title"><?php echo esc_html__('My Account', 'armoredlaw'); ?></h1>
    </header>

    <div class="al-account">
      <?php
      echo do_shortcode('[mepr_account_form]');
      ?>
    </div>

  </div>
</main>

<?php get_footer(); ?>
