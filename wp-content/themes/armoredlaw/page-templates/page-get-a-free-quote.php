<?php
/**
 * Template Name: Get a Free Quote
 */
get_header();
get_template_part('template-parts/hero');

$quote_title     = get_field('quote_title');
$quote_subtitle  = get_field('quote_subtitle');

$left_image      = get_field('quote_left_image');

$step1_title     = get_field('quote_step1_title');
$step1_opts      = get_field('quote_step1_options');

$ccw_title       = get_field('quote_ccw_title');
$state_title     = get_field('quote_state_title');

$step2_title     = get_field('quote_step2_title');
$plans           = get_field('quote_plans');

$submit_text     = get_field('quote_submit_text') ?: 'GET MY FREE QUOTE';

$terms_text      = get_field('quote_terms_text');
$terms_url       = get_field('quote_terms_url');
$privacy_url     = get_field('quote_privacy_url');

$hs_portal_id    = get_field('hs_portal_id');
$hs_form_id      = get_field('hs_form_id');

$step3_title = get_field('quote_step3_title');
$step3_text  = get_field('quote_step3_text');
$step3_btn1_text = get_field('quote_step3_btn1_text');
$step3_btn1_url  = get_field('quote_step3_btn1_page');
$step3_btn2_text = get_field('quote_step3_btn2_text');
$step3_btn2_url  = get_field('quote_step3_btn2_page');


?>

<section class="quote" id="quoteForm">
	<div class="container quote__head">
		<h2 class="quote__title"><?php echo esc_html($quote_title); ?></h2>
	  <p class="quote__subtitle"><?php echo esc_html($quote_subtitle); ?></p>
  </div>

  <div class="container quote__grid">
    <div class="quote__left">
      <?php if ($left_image): ?>
        <div class="quote__left-bg" style="background-image:url('<?php echo esc_url($left_image['url']); ?>')"></div>
      <?php endif; ?>
    </div>

    <div class="quote__right">
      <div class="quote__progress">
        <span data-step-indicator="1" class="is-active">STEP 1</span>
        <span data-step-indicator="2">STEP 2</span>
      </div>

      <form id="quoteMultiForm" novalidate>
        <!-- STEP 1 -->
        <div class="quote__step" data-step="1">
          <h3><?php echo esc_html($step1_title); ?></h3>

          <div class="quote__options" data-field="protection_type">
            <?php if ($step1_opts): foreach ($step1_opts as $opt): ?>
              <button type="button"
                class="opt-btn"
                data-value="<?php echo esc_attr($opt['value']); ?>">
                <?php echo esc_html($opt['label']); ?>
              </button>
            <?php endforeach; endif; ?>
          </div>

          <h3><?php echo esc_html($ccw_title); ?></h3>
          <div class="quote__options" data-field="has_ccw">
            <button type="button" class="opt-btn" data-value="yes">YES</button>
            <button type="button" class="opt-btn" data-value="no">NO</button>
          </div>

          <h3><?php echo esc_html($state_title); ?></h3>
          <div class="quote__options">
	          <select name="state" id="quoteState">
              <option value="">Select State</option>
              <?php
                foreach (armoredlaw_get_us_states() as $code => $name) {
                  echo '<option value="' . esc_attr($code) . '">' . esc_html($name) . '</option>';
                }
              ?>
            </select>
					</div>
          <div class="quote__actions">
            <button type="button" class="btn btn-next btn--white" data-action="next"><span>NEXT STEP</span></button>
          </div>
					<div class="quote__message quote__message--step1" aria-live="polite"></div>
        </div>

        <!-- STEP 2 -->
        <div class="quote__step" data-step="2" hidden>
          <h3><?php echo esc_html($step2_title); ?></h3>

          <div class="quote__plans" data-field="plan">
            <?php if ($plans): foreach ($plans as $p): ?>
              <button type="button" class="plan-btn" data-value="<?php echo esc_attr($p['value']); ?>">
                <?php if (!empty($p['badge'])): ?><span class="badge"><?php echo esc_html($p['badge']); ?></span><?php endif; ?>
                <?php echo esc_html($p['label']); ?>
              </button>
            <?php endforeach; endif; ?>
          </div>

          <h3>CONTACT</h3>
          <div class="quote__plans">
	          <div class="quote__fields">
	            <input type="text" name="full_name" placeholder="Full Name" autocomplete="name">
	            <input type="email" name="email" placeholder="Email" autocomplete="email">
	            <input type="tel" name="phone" placeholder="Phone number" autocomplete="tel">
	          </div>
					</div>

          <label class="quote__checkbox">
            <input type="checkbox" name="terms" value="1">
            <span>
              <?php echo esc_html($terms_text ?: 'I agree to the'); ?>
              <?php if ($terms_url): ?>
                <a href="<?php echo esc_url($terms_url['url']); ?>" target="<?php echo esc_attr($terms_url['target'] ?: '_self'); ?>">Terms</a>
              <?php endif; ?>
              <?php if ($privacy_url): ?>
                &amp; <a href="<?php echo esc_url($privacy_url['url']); ?>" target="<?php echo esc_attr($privacy_url['target'] ?: '_self'); ?>">Privacy Policy</a>
              <?php endif; ?>
            </span>
          </label>

          <div class="quote__actions">
            <button type="button" class="btn btn--white btn-back" data-action="back"><span>BACK</span></button>
            <button type="submit" class="btn btn--primary" id="quoteSubmitBtn"><span><?php echo esc_html($submit_text); ?></span></button>
          </div>

          <div class="quote__message" id="quoteMessage" aria-live="polite"></div>
        </div>

        <!-- STEP 3 -->
        <div class="quote__step" data-step="3" hidden>
          <h2><?php echo nl2br(esc_html($step3_title)); ?></h2>

          <p><?php echo nl2br(esc_html($step3_text)); ?></p>

          <div class="quote__actions">
            <?php if ($step3_btn1_url): ?>
              <a class="btn btn--white btn-next" href="<?php echo esc_url($step3_btn1_url); ?>">
								<span><?php echo esc_html($step3_btn1_text); ?></span>
              </a>
            <?php endif; ?>

            <?php if ($step3_btn2_url): ?>
              <a class="btn btn--primary" href="<?php echo esc_url($step3_btn2_url); ?>">
								<span><?php echo esc_html($step3_btn2_text); ?></span>
              </a>
            <?php endif; ?>
          </div>
        </div>

        <?php wp_nonce_field('al_quote_submit', 'al_quote_nonce'); ?>
      </form>
    </div>
  </div>
</section>

<script>
  window.AL_QUOTE = {
    ajaxUrl: "<?php echo esc_js(admin_url('admin-ajax.php')); ?>",
    nonce: "<?php echo esc_js(wp_create_nonce('al_quote_submit')); ?>",
    hubspot: {
      portalId: "<?php echo esc_js((string)$hs_portal_id); ?>",
      formId: "<?php echo esc_js((string)$hs_form_id); ?>"
    }
  };
</script>

<?php

get_footer();
