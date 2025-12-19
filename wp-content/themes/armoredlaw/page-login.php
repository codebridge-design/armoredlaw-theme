<?php
/**
 * Template Name: Login
 */
defined('ABSPATH') || exit;

get_header();

if (is_user_logged_in()) {
  wp_safe_redirect(home_url('/account/'));
  exit;
}
?>
<main class="container al-auth al-auth--login">
  <div class="al-auth__container">
    <header class="al-auth__header">
      <h1 class="al-auth__title"><?php echo esc_html__('Login to your account', 'armoredlaw'); ?></h1>
    </header>

    <div class="al-auth__form">
      <?php
	      $html = do_shortcode('[mepr-login-form]');
	      $html = str_replace('id="user_login"', 'id="user_login" placeholder="Email"', $html);
	      $html = str_replace('id="user_pass"', 'id="user_pass" placeholder="Password"', $html);
	      echo $html;
      ?>
    </div>

    <div class="al-auth__alt">
      <p class="al-auth__alt-text">
        <?php echo esc_html__('New here?', 'armoredlaw'); ?>
        <a class="al-auth__alt-link" href="<?php echo esc_url( home_url('/pricing/') ); ?>">
          <?php echo esc_html__('View plans & subscribe', 'armoredlaw'); ?>
        </a>
      </p>
    </div>
  </div>
</main>

<?php get_footer(); ?>