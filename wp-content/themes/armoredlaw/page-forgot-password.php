<?php
/**
 * Template Name: Forgot Password
 */
defined('ABSPATH') || exit;

get_header();

if (is_user_logged_in()) {
  $account_page_id = (int) get_option('mepr_account_page_id');
  $account_url = $account_page_id ? get_permalink($account_page_id) : home_url('/account/');
  wp_safe_redirect($account_url);
  exit;
}
?>
<main class="container al-auth al-auth--forgot">
  <div class="al-auth__container">
    <header class="al-auth__header">
      <h1 class="al-auth__title"><?php echo esc_html__('Reset your password', 'armoredlaw'); ?></h1>
    </header>

    <div class="al-auth__form">
      <?php
        $html = do_shortcode('[mepr-login-form]');
        $html = str_replace('id="mepr_user_or_email"', 'mepr_user_or_email" placeholder="Enter Your Email Address"', $html);
        echo $html;
      ?>

    </div>

    <div class="al-auth__alt">
      <p class="al-auth__alt-text">
        <a class="al-auth__alt-link" href="<?php echo esc_url( home_url('/login/') ); ?>">
          <?php echo esc_html__('Back to login', 'armoredlaw'); ?>
        </a>
      </p>
    </div>
  </div>
</main>
<?php get_footer(); ?>
