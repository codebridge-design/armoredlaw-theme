<?php
$eyebrow   = get_field( 'steps_eyebrow' );
$title     = get_field( 'steps_title' );
$subtitle  = get_field( 'steps_subtitle' );
$cta_text  = get_field( 'steps_cta_text' );
$cta_url   = get_field( 'steps_cta_url' );
?>

<section class="steps">
	<div class="container">
		<?php if ( $eyebrow ) : ?>
			<p class="steps__eyebrow">
				<?php echo esc_html( $eyebrow ); ?>
			</p>
		<?php endif; ?>

		<?php if ( $title ) : ?>
			<h2 class="steps__title">
				<?php echo esc_html( $title ); ?>
			</h2>
		<?php endif; ?>

		<?php if ( $subtitle ) : ?>
			<p class="steps__subtitle">
				<?php echo esc_html( $subtitle ); ?>
			</p>
		<?php endif; ?>

		<?php if ( have_rows( 'steps_items' ) ) : ?>
			<div class="steps__grid">
				<?php while ( have_rows( 'steps_items' ) ) : the_row(); ?>
					<?php
					$icon        = get_sub_field( 'icon' );
					$kicker      = get_sub_field( 'kicker' );
					$item_title  = get_sub_field( 'title' );
					$desc        = get_sub_field( 'description' );
					$number      = get_sub_field( 'number' );
					?>
					<article class="steps-card">
						<?php if ( $icon ) : ?>
							<div class="steps-card__icon">
								<img src="<?php echo esc_url( $icon ); ?>" alt="">
							</div>
						<?php endif; ?>

						<div class="steps-card__body">
							<?php if ( $kicker ) : ?>
								<p class="steps-card__kicker">
									<?php echo esc_html( $kicker ); ?>
								</p>
							<?php endif; ?>

							<?php if ( $item_title ) : ?>
								<h3 class="steps-card__title">
									<?php echo esc_html( $item_title ); ?>
								</h3>
							<?php endif; ?>

							<?php if ( $desc ) : ?>
								<p class="steps-card__text">
									<?php echo esc_html( $desc ); ?>
								</p>
							<?php endif; ?>
						</div>

						<?php if ( $number ) : ?>
							<div class="steps-card__number">
								<div class="steps-card__number--inner">
									<span class="steps-card__number-value">
										<?php echo esc_html( $number ); ?>
									</span>
									<span class="steps-card__number-label">STEP</span>
								</div>
							</div>
						<?php endif; ?>
					</article>
				<?php endwhile; ?>
			</div>
		<?php endif; ?>

		<?php if ( $cta_text ) : ?>
			<div class="steps__cta">
				<a href="<?php echo esc_url( $cta_url ); ?>" class="btn btn--primary">
					<span><?php echo esc_html( $cta_text ); ?></span>
				</a>
			</div>
		<?php endif; ?>
	</div>
</section>
