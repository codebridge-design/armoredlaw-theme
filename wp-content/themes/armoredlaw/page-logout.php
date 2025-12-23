<?php
/**
 * Template Name: Logout Confirm
 */
defined('ABSPATH') || exit;

if (!is_user_logged_in()) {
  wp_safe_redirect(home_url('/login/'));
  exit;
}

get_header();

$continue = home_url('/account/');
$logout   = wp_logout_url($continue);

?>
<main class="container al-logout">
  <div class="al-logout__box">
    <h1><?php echo esc_html__('Do you want to log out?', 'armoredlaw'); ?></h1>
    <p><?php echo esc_html__('Do you want to log out?', 'armoredlaw'); ?></p>

    <div class="al-logout__actions">
      <a class="btn btn--back" href="<?php echo esc_url($continue); ?>"><?php echo esc_html__('Continue ', 'armoredlaw'); ?></a>
      <a class="btn btn--primary" href="<?php echo esc_url($logout); ?>"><?php echo esc_html__('Log out ', 'armoredlaw'); ?></a>
    </div>

		<div class="al-logout__bottom">
			<h2><?php echo esc_html__('Need help with your account?', 'armoredlaw'); ?></h1>
			<p><?php echo esc_html__('Contact our support team if you need any assistance during your logout process.', 'armoredlaw'); ?></p>
		</div>
  </div>
</main>
<?php get_footer(); ?>
