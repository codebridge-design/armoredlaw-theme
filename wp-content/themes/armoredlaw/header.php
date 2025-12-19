<?php
/**
 * Header template for Armored Law theme
 *
 * @package ArmoredLaw
 */

$header_cta_text = get_field( 'header_cta_text', 'option' );
$header_cta_url  = get_field( 'header_cta_url', 'option' );
?>

<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div id="page" class="site">

	<header class="site-header">
		<div class="site-header__top-bar">
			<div class="container site-header__inner">

				<button class="cw-header__burger" type="button" aria-controls="primary-menu" aria-expanded="false">
					<span></span>
					<span></span>
					<span></span>
				</button>

				<div class="site-header__logo">
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="site-header__logo-link">
						<?php
						if ( function_exists( 'the_custom_logo' ) && has_custom_logo() ) {
							the_custom_logo();
						} else {
							?>
							<span class="site-header__logo-text">
								<?php bloginfo( 'name' ); ?>
							</span>
							<?php
						}
						?>
					</a>
				</div>
				<div class="cw-header__panel">
					<nav class="site-header__nav" aria-label="<?php esc_attr_e( 'Primary menu', 'armoredlaw' ); ?>">
						<?php
						wp_nav_menu(
							array(
								'theme_location' => 'primary',
								'container'      => false,
								'menu_class'     => 'primary-menu',
								'fallback_cb'    => false,
								'depth'          => 1,
							)
						);
						?>
					</nav>

					<div class="site-header__actions">
						<?php
            $login_page_id   = (int) get_option('mepr_login_page_id');
            $account_page_id = (int) get_option('mepr_account_page_id');

            $login_url   = $login_page_id   ? get_permalink($login_page_id)   : home_url('/login/');
            $account_url = $account_page_id ? get_permalink($account_page_id) : home_url('/account/');
            ?>

            <?php if ( ! is_user_logged_in() ) : ?>
              <a href="<?php echo esc_url($login_url); ?>" class="btn btn--outline site-header__login">
                <span>Login</span>
              </a>
            <?php else : ?>
              <a href="<?php echo esc_url($account_url); ?>" class="btn btn--outline site-header__login">
                <span>My Account</span>
              </a>
            <?php endif; ?>





						<a href="<?php echo esc_url( $header_cta_url ?: '#quote' ); ?>"
	             class="btn btn--primary button--icon site-header__cta">
	             <span><?php echo esc_html( $header_cta_text ?: 'Get a Free Quote' ); ?></span>
	          </a>
					</div>
				</div>

			</div>
		</div>
	</header>
