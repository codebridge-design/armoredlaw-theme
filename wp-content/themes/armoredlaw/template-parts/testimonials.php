<?php
$show_on_page           = get_field( 'testimonials_show' );
$eyebrow                = get_field( 'testimonials_eyebrow', 'option' );
$title                  = get_field( 'testimonials_title', 'option' );
$bg_image               = get_field( 'testimonials_bg_image', 'option' );
$reviews_avatars_image  = get_field( 'reviews_avatars_image', 'option' );
$reviews_stars_image    = get_field( 'reviews_stars_image', 'option' );
$reviews_text           = get_field( 'reviews_text', 'option' );

$bg_style = '';
if ( $bg_image ) {
	$bg_style = sprintf(
		"style='background-image: url(%s);'",
		esc_url( $bg_image )
	);
}
?>

<section class="testimonials" <?php echo $bg_style; // phpcs:ignore ?>>
	<div class="testimonials__overlay"></div>

	<div class="container testimonials__inner">
		<header class="testimonials__header">
			<?php if ( $eyebrow ) : ?>
				<p class="testimonials__eyebrow">
					<?php echo esc_html( $eyebrow ); ?>
				</p>
			<?php endif; ?>

			<?php if ( $title ) : ?>
				<h2 class="testimonials__title">
					<?php echo esc_html( $title ); ?>
				</h2>
			<?php endif; ?>

			<div class="testimonials__reviews">
      	<?php if ( $reviews_avatars_image ) : ?>
      		<div class="testimonials__reviews-avatars">
      			<img src="<?php echo esc_url( $reviews_avatars_image ); ?>" alt="">
      		</div>
      	<?php endif; ?>

      	<div class="testimonials__reviews-main">
      		<div class="testimonials__reviews-google">
      			<img
      				class="testimonials__reviews-google-logo"
      				src="<?php echo esc_url( get_template_directory_uri() . '/assets/img/google-icon.png' ); ?>"
      				alt="Google Reviews"
      			>
      		</div>

					<div class="testimonials__reviews-wrapper">
						<?php if ( $reviews_stars_image ) : ?>
              <div class="testimonials__reviews-stars">
                <img src="<?php echo esc_url( $reviews_stars_image ); ?>" alt="">
              </div>
            <?php endif; ?>

            <?php if ( $reviews_text ) : ?>
              <div class="testimonials__reviews-text">
                <?php echo esc_html( $reviews_text ); ?>
              </div>
            <?php endif; ?>
					</div>
      	</div>
      </div>

		</header>

		<?php if ( have_rows( 'testimonials_items', 'option' ) ) : ?>
        <div class="testimonials__slider js-testimonials-slider">
            <?php while ( have_rows( 'testimonials_items', 'option' ) ) : the_row(); ?>
					<?php
					$author_name     = get_sub_field( 'author_name' );
					$author_location = get_sub_field( 'author_location' );
					$author_avatar   = get_sub_field( 'author_avatar' );
					$rating          = (int) get_sub_field( 'rating' );
					$text            = get_sub_field( 'text' );
					?>
					<article class="testimonial-card">
						<header class="testimonial-card__header">
							<div class="testimonial-card__author">
								<?php if ( $author_avatar ) : ?>
									<div class="testimonial-card__avatar">
										<img src="<?php echo esc_url( $author_avatar ); ?>" alt="<?php echo esc_attr( $author_name ); ?>">
									</div>
								<?php endif; ?>

								<div class="testimonial-card__author-meta">
									<?php if ( $author_name ) : ?>
										<div class="testimonial-card__name">
											<?php echo esc_html( $author_name ); ?>
										</div>
									<?php endif; ?>

									<?php if ( $author_location ) : ?>
										<div class="testimonial-card__location">
											<?php echo esc_html( $author_location ); ?>
										</div>
									<?php endif; ?>
								</div>
							</div>

							<div class="testimonial-card__source">
								<img src="" alt="">
							</div>

						</header>

						<?php if ( $rating ) : ?>
							<div class="testimonial-card__rating">
								<?php for ( $i = 0; $i < $rating; $i++ ) : ?>
									<span class="testimonial-card__star">★</span>
								<?php endfor; ?>
							</div>
						<?php endif; ?>

						<?php if ( $text ) : ?>
							<div class="testimonial-card__text">
								<?php echo esc_html( $text ); ?>
							</div>
						<?php endif; ?>
					</article>
				<?php endwhile; ?>
			</div>

			<div class="testimonials__dots js-testimonials-dots"></div>
		<?php endif; ?>
	</div>
</section>
