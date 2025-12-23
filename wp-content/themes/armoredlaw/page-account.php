<?php
/**
 * Template Name: Account
 */
defined('ABSPATH') || exit;

$action = isset($_GET['action']) ? sanitize_key(wp_unslash($_GET['action'])) : 'home';

$tab_copy = [
  'home' => [
    'title' => __('My Account', 'armoredlaw'),
    'subtitle' => sprintf(
      __('Welcome back, %s.', 'armoredlaw'),
      do_shortcode('[mepr_account_info field="first_name"]')
    ),
  ],
  'subscriptions' => [
    'title' => __('Subscriptions', 'armoredlaw'),
    'subtitle' => __('Manage your membership plans and renewals.', 'armoredlaw'),
  ],
  'payments' => [
    'title' => __('Payments', 'armoredlaw'),
    'subtitle' => __('View and manage your billing history and payment methods.', 'armoredlaw'),
  ],
  'newpassword' => [
    'title' => __('Change password', 'armoredlaw'),
    'subtitle' => __('Secure your account by updating your password.', 'armoredlaw'),
  ],
];

$copy = $tab_copy[$action] ?? $tab_copy['home'];

get_header();

if (!is_user_logged_in()) {
  $login_page_id = (int) get_option('mepr_login_page_id');
  $login_url = $login_page_id ? get_permalink($login_page_id) : home_url('/login/');
  wp_safe_redirect($login_url);
  exit;
}
?>

<main class="container al-auth al-auth--account">
  <div class="al-auth--account__container">

		<header class="al-auth__header al-auth--account__header">
		  <h1 class="al-auth__title al-auth--account__title">
		    <?php echo esc_html($copy['title']); ?>
		  </h1>

		  <p class="al-auth--account__subtitle">
		    <?php echo wp_kses_post($copy['subtitle']); ?>
		  </p>
		</header>

    <div class="al-account">
      <?php
        $html = do_shortcode('[mepr_account_form]');
        $html = str_replace('id="mepr-address-one"', 'id="mepr-address-one" placeholder="Address Line 1*"', $html);
        $html = str_replace('id="mepr-address-two"', 'id="mepr-address-two" placeholder="Address Line 2"', $html);
        $html = str_replace('id="mepr-address-city"', 'id="mepr-address-city" placeholder="City*"', $html);
				$html = str_replace('id="mepr-address-state"', 'id="mepr-address-state" placeholder="State/Province*"', $html);
				$html = str_replace('id="mepr-address-zip"', 'id="mepr-address-zip" placeholder="Zip/Postal Code*"', $html);
        echo $html;
      ?>
    </div>

  </div>
</main>

<?php get_footer(); ?>
