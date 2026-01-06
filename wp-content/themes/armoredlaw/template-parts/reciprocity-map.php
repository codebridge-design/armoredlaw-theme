<?php
if ( ! function_exists('get_field') ) {
  return;
}

$eyebrow   = get_field('rms_eyebrow', 'option');
$title     = get_field('rms_title', 'option');
$sub_title = get_field('rms_subtitle', 'option');
$btn_text  = get_field('rms_button_title', 'option');
$btn_url   = get_field('rms_button_url', 'option');
?>

<div class="al-map-block__wrapper">
	<div class="container">
		<div class="al-map-block">
			<div class="al-map-block__content">
				<div class="al-map-block__text">
					<?php if ( $eyebrow ) : ?>
						<p class="al-map-block__eyebrow"><?php echo esc_html( $eyebrow ); ?></p>
					<?php endif; ?>

					<?php if ( $title ) : ?>
						<h2 class="al-map-block__title"><?php echo esc_html( $title ); ?></h2>
					<?php endif; ?>

					<?php if ( $sub_title ) : ?>
						<p class="al-map-block__subtitle"><?php echo esc_html( $sub_title ); ?></p>
					<?php endif; ?>
				</div>
			  <div class="al-map-ui">
					<div class="al-select-wrap">
			      <select id="alStateSelect" class="al-select"></select>
					</div>

			    <div class="al-buttons">
						<?php if ( $btn_text ) : ?>
							<a href="<?php echo esc_url($btn_url); ?>"
	               class="btn btn--primary">
	              <span><?php echo esc_html( $btn_text ); ?></span>
	            </a>
						<?php endif; ?>
			    </div>
			  </div>
			</div>

		  <div class="al-map-wrap" id="alMapWrap">
		    <?php
            echo file_get_contents(
              get_template_directory() . '/assets/img/map.svg'
            );
          ?>
		  </div>
		</div>
	</div>
</div>
