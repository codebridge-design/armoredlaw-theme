<?php
$show_hero         = get_field( 'hero_enable' );
$hero_eyebrow      = get_field( 'hero_eyebrow' );
$hero_title        = get_field( 'hero_title' );
$hero_subtitle     = get_field( 'hero_subtitle' );
$hero_bg_image     = get_field( 'hero_bg_image' );
$hero_primary_text = get_field( 'hero_primary_text' );
$hero_primary_url  = get_field( 'hero_primary_url' );
$hero_secondary_text = get_field( 'hero_secondary_text' );
$hero_secondary_url  = get_field( 'hero_secondary_url' );

if ( ! $show_hero ) {
	return;
}

$hero_bg_image_url = '';

if ( is_array( $hero_bg_image ) && isset( $hero_bg_image['url'] ) ) {
	$hero_bg_image_url = $hero_bg_image['url'];
} elseif ( is_string( $hero_bg_image ) ) {
	$hero_bg_image_url = $hero_bg_image;
}
?>


<section class="hero"<?php if ( $hero_bg_image_url ) : ?>
	style="background-image: url('<?php echo esc_url( $hero_bg_image_url ); ?>');"
<?php endif; ?>>
	<div class="hero__overlay"></div>

	<div class="container hero__inner">
		<div class="hero__content">
			<?php if ( $hero_eyebrow ) : ?>
				<p class="hero__eyebrow">
					<?php echo esc_html( $hero_eyebrow ); ?>
				</p>
			<?php endif; ?>

			<?php if ( $hero_title ) : ?>
				<h1 class="hero__title">
					<?php
					// textarea → <br> між рядками
					echo nl2br( esc_html( $hero_title ) );
					?>
				</h1>
			<?php endif; ?>

			<?php if ( $hero_subtitle ) : ?>
				<p class="hero__subtitle">
					<?php echo esc_html( $hero_subtitle ); ?>
				</p>
			<?php endif; ?>

			<?php if ( $hero_primary_text || $hero_secondary_text ) : ?>
				<div class="hero__actions">
					<?php if ( $hero_primary_text ) : ?>
						<a href="<?php echo esc_url( $hero_primary_url ); ?>"
						   class="btn btn--secondary hero__btn hero__btn--secondary">
							<span><?php echo esc_html( $hero_primary_text ); ?></span>
						</a>
					<?php endif; ?>

					<?php if ( $hero_secondary_text ) : ?>
						<a href="<?php echo esc_url( $hero_secondary_url ); ?>"
						   class="btn btn--primary hero__btn hero__btn--primary">
							<span><?php echo esc_html( $hero_secondary_text ); ?></span>
						</a>
					<?php endif; ?>
				</div>
			<?php endif; ?>
		</div>
	</div>

	<?php if ( have_rows( 'hero_features' ) ) : ?>
		<div class="hero-features">
			<div class="container hero-features__inner">
				<?php while ( have_rows( 'hero_features' ) ) : the_row(); ?>
					<?php
					$icon  = get_sub_field( 'icon' );
					$label = get_sub_field( 'label' );
					?>
					<div class="hero-features__item">
						<?php if ( $icon ) : ?>
							<div class="hero-features__icon">
								<img src="<?php echo esc_url( $icon ); ?>" alt="">
							</div>
						<?php endif; ?>

						<?php if ( $label ) : ?>
							<div class="hero-features__label">
								<?php echo esc_html( $label ); ?>
							</div>
						<?php endif; ?>
					</div>
				<?php endwhile; ?>
			</div>
		</div>
	<?php endif; ?>

</section>
