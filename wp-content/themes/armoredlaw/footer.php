<?php
/**
 * Footer template for Armored Law theme
 *
 * @package ArmoredLaw
 */
?>

<?php
$call_label            = get_field( 'footer_call_label', 'option' );
$call_phone            = get_field( 'footer_call_phone', 'option' );
$email_label           = get_field( 'footer_email_label', 'option' );
$email_text            = get_field( 'footer_email_text', 'option' );
$email_address         = get_field( 'footer_email_address', 'option' );
$hours_label           = get_field( 'footer_hours_label', 'option' );
$hours_value           = get_field( 'footer_hours_value', 'option' );
$copyright             = get_field( 'footer_copy', 'option' );
$privacy_text          = get_field( 'footer_privacy_text', 'option' );
$privacy_url           = get_field( 'footer_privacy_url', 'option' );
$terms_text            = get_field( 'footer_terms_text', 'option' );
$terms_url             = get_field( 'footer_terms_url', 'option' );
$year                  = date( 'Y' );
$footer_subscribe_desc = get_field( 'footer_subscribe_desc', 'option' );
?>

<footer class="site-footer">

	<section class="site-footer__top">
		<div class="container site-footer__top-inner">

			<div class="footer-brand">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="footer-brand__logo">
					<?php
					if ( function_exists( 'the_custom_logo' ) && has_custom_logo() ) {
						the_custom_logo();
					} else {
						?>
						<span class="footer-brand__logo-text">
							<?php bloginfo( 'name' ); ?>
						</span>
						<?php
					}
					?>
				</a>
			</div>

			<div class="footer-contact footer-contact--phone">
				<div class="footer-contact__icon" aria-hidden="true">
					<?php echo file_get_contents( get_template_directory() . '/assets/img/call.svg' ); ?>
				</div>
				<div class="footer-contact__text">
          <?php if ( $call_label ) : ?>
            <span class="footer-contact__label"><?php echo esc_html( $call_label ); ?></span>
          <?php endif; ?>
          <?php if ( $call_phone ) : ?>
            <a class="footer-contact__value" href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $call_phone ) ); ?>">
              <?php echo esc_html( $call_phone ); ?>
            </a>
          <?php endif; ?>
        </div>
			</div>

			<div class="footer-contact footer-contact--email">
				<div class="footer-contact__icon" aria-hidden="true">
					<?php echo file_get_contents( get_template_directory() . '/assets/img/sms.svg' ); ?>
				</div>
				<div class="footer-contact__text">
          <?php if ( $email_label ) : ?>
            <span class="footer-contact__label"><?php echo esc_html( $email_label ); ?></span>
          <?php endif; ?>
          <?php if ( $email_text && $email_address ) : ?>
            <a class="footer-contact__value" href="mailto:<?php echo esc_attr( $email_address ); ?>">
              <?php echo esc_html( $email_text ); ?>
            </a>
          <?php endif; ?>
        </div>
			</div>

			<div class="footer-contact footer-contact--hours">
				<div class="footer-contact__icon" aria-hidden="true">
					<?php echo file_get_contents( get_template_directory() . '/assets/img/clock.svg' ); ?>
				</div>
				<div class="footer-contact__text">
          <?php if ( $hours_label ) : ?>
            <span class="footer-contact__label"><?php echo esc_html( $hours_label ); ?></span>
          <?php endif; ?>
          <?php if ( $hours_value ) : ?>
            <div class="footer-contact__value"><?php echo esc_html( $hours_value ); ?></div>
          <?php endif; ?>
        </div>
			</div>

		</div>
	</section>

	<section class="site-footer__middle">
		<div class="container site-footer__middle-inner">

			<div class="footer-column footer-column--menu">
				<h4 class="footer-column__title" role="button" tabindex="0" aria-expanded="false">Membership Plans</h4>
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'footer_membership',
						'container'      => false,
						'menu_class'     => 'footer-menu',
						'fallback_cb'    => false,
						'depth'          => 1,
					)
				);
				?>
			</div>

			<div class="footer-column footer-column--menu">
				<h4 class="footer-column__title" role="button" tabindex="0" aria-expanded="false">Company</h4>
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'footer_company',
						'container'      => false,
						'menu_class'     => 'footer-menu',
						'fallback_cb'    => false,
						'depth'          => 1,
					)
				);
				?>
			</div>

			<div class="footer-column footer-column--menu">
				<h4 class="footer-column__title" role="button" tabindex="0" aria-expanded="false">Train &amp; Learn</h4>
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'footer_train',
						'container'      => false,
						'menu_class'     => 'footer-menu',
						'fallback_cb'    => false,
						'depth'          => 1,
					)
				);
				?>
			</div>

			<div class="footer-column footer-column--subscribe">
				<h4 class="footer-column__title">Subscribe Now</h4>
				<p class="footer-column__text">
					<?php
          	echo esc_html(
          		$footer_subscribe_desc
          		?: 'Lifesaving education and training, the latest laws governing your right to carry, and exclusive offers.'
          	);
          	?>
				</p>

				<form class="footer-subscribe" action="#" method="post">
					<label class="screen-reader-text" for="footer-subscribe-email">
						Email
					</label>
					<div class="footer-subscribe__field">
						<input
							type="email"
							id="footer-subscribe-email"
							name="email"
							placeholder="Enter Your Email"
							required
						/>
						<button type="submit" class="footer-subscribe__submit" aria-label="Subscribe">
							<span class="footer-subscribe__icon" aria-hidden="true"></span>
						</button>
					</div>
				</form>

				<div class="footer-social">
					<a href="#" class="footer-social__link" aria-label="Facebook">
						<?php echo file_get_contents( get_template_directory() . '/assets/img/facebook.svg' ); ?>
					</a>
					<a href="#" class="footer-social__link" aria-label="X">
						<?php echo file_get_contents( get_template_directory() . '/assets/img/x.svg' ); ?>
					</a>
					<a href="#" class="footer-social__link" aria-label="Instagram">
						<?php echo file_get_contents( get_template_directory() . '/assets/img/insta.svg' ); ?>
					</a>
					<a href="#" class="footer-social__link" aria-label="LinkedIn">
						<?php echo file_get_contents( get_template_directory() . '/assets/img/linkedin.svg' ); ?>
					</a>
					<a href="#" class="footer-social__link" aria-label="YouTube">
						<?php echo file_get_contents( get_template_directory() . '/assets/img/youtube.svg' ); ?>
					</a>
					<a href="#" class="footer-social__link" aria-label="Google">
						<?php echo file_get_contents( get_template_directory() . '/assets/img/google.svg' ); ?>
					</a>
				</div>
			</div>

		</div>
	</section>

	<section class="site-footer__bottom">
		<div class="container site-footer__bottom-inner">
			<p class="site-footer__copyright">©
				<?php echo esc_html( $year ); ?>
        <?php echo esc_html( $copyright ?: 'Armored Law' ); ?>
			</p>
			<div class="site-footer__legal-links">
				<?php if ( $privacy_text ) : ?>
				<span class="first" aria-hidden="true">|</span>
          <a href="<?php echo esc_url( $privacy_url ); ?>"><?php echo esc_html( $privacy_text ); ?></a>
        <?php endif; ?>
				<?php if ( $terms_text ) : ?>
          <span aria-hidden="true">|</span>
          <a href="<?php echo esc_url( $terms_url ); ?>"><?php echo esc_html( $terms_text ); ?></a>
        <?php endif; ?>
			</div>
		</div>
	</section>

</footer><!-- .site-footer -->

</div><!-- #page -->

<?php wp_footer(); ?>
</body>
</html>
