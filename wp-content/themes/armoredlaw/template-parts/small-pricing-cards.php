<?php
if ( ! function_exists('get_field') ) {
  return;
}

// Section heading (Pricing small)
$eyebrow = get_field('small_pricing_eyebrow', 'option');
$title   = get_field('small_pricing_title', 'option');

// Data sources:
$plans_big   = get_field('pricing_table_plans', 'option');        // Pricing Table (title, prices, featured, badge, currency, suffix)
$plans_small = get_field('small_pricing_plans', 'option');  // Pricing small (best_for, features, learn_more)

if (empty($plans_big)) {
  return; // without base plans we can't render prices/titles
}

// Build index by plan_key for small plans
$small_by_key = [];
if (!empty($plans_small) && is_array($plans_small)) {
  foreach ($plans_small as $sp) {
    $k = $sp['plan_key'] ?? '';
    if ($k !== '') {
      $small_by_key[$k] = $sp;
    }
  }
}
?>

<section class="small-pricing">
  <div class="container small-pricing__inner">

    <header class="small-pricing__head">
      <?php if (!empty($eyebrow)) : ?>
        <div class="small-pricing__eyebrow"><?php echo esc_html($eyebrow); ?></div>
      <?php endif; ?>

      <?php if (!empty($title)) : ?>
        <h2 class="small-pricing__title"><?php echo esc_html($title); ?></h2>
      <?php endif; ?>
    </header>

    <div class="small-pricing__grid">
      <?php foreach ($plans_big as $p) :

        // We need key to join with small data
        $plan_key = $p['plan_key'] ?? '';
        $sp = ($plan_key !== '' && isset($small_by_key[$plan_key])) ? $small_by_key[$plan_key] : [];

        // Base plan fields (Pricing Table)
        $plan_title  = $p['plan_title'] ?? '';
        $currency    = $p['plan_currency'] ?? '$';
        $price_m     = $p['plan_price_monthly'] ?? '';
        $price_y     = $p['plan_price_yearly'] ?? '';
        $suffix_m    = $p['plan_suffix_monthly'] ?? '/Mo';
        $suffix_y    = $p['plan_suffix_yearly'] ?? '/Yr';

        $is_featured = !empty($p['plan_is_featured']);
        $has_badge   = !empty($p['plan_badge_enabled']);

        // Small/small fields (Pricing small)
        $best_for   = $sp['plan_small_best_for'] ?? '';
        $features   = $sp['plan_small_features'] ?? [];
        $learn_page = $sp['plan_small_learn_more'] ?? null;

        // Normalize Page Link to URL
        $learn_url = '#';
        if (is_string($learn_page) && $learn_page !== '') {
          $learn_url = $learn_page;
        } elseif (is_numeric($learn_page)) {
          $learn_url = get_permalink((int)$learn_page);
        } elseif (is_object($learn_page) && !empty($learn_page->ID)) {
          $learn_url = get_permalink((int)$learn_page->ID);
        }
      ?>
        <article class="small-pricing__card <?php echo $is_featured ? 'is-featured' : ''; ?>">

          <?php if ($has_badge): ?>
            <div class="pricing__badge-wrapper">
	            <div class="small-pricing__badge pricing__badge">
								<span class="pricing__badge-text">Most Popular</span>
							</div>
						</div>
          <?php endif; ?>

					<div class="small-pricing__card-inner">
	          <?php if ($plan_title): ?>
	            <div class="small-pricing__plan"><?php echo esc_html($plan_title); ?></div>
	          <?php endif; ?>

	          <div class="small-pricing__price">
	            <span class="small-pricing__price-main"><?php echo esc_html($currency . $price_m); ?></span>
	            <span class="small-pricing__price-suffix-mo"><?php echo esc_html($suffix_m); ?></span>
	            <span class="small-pricing__price-sep">or</span>
	            <span class="small-pricing__price-alt"><?php echo esc_html($currency . $price_y); ?></span>
	            <span class="small-pricing__price-suffix-yr"><?php echo esc_html($suffix_y); ?></span>
	          </div>

	          <?php if (!empty($best_for)): ?>
	            <div class="small-pricing__bestfor">
								<div class="small-pricing__bestfor-inner">
		              <span class="small-pricing__bestfor-label">Best for:</span>
		              <div class="small-pricing__bestfor-text"><?php echo esc_html($best_for); ?></div>
	              </div>
	            </div>
	          <?php endif; ?>

	          <?php if (!empty($features) && is_array($features)): ?>
	            <ul class="small-pricing__features">
	              <?php foreach ($features as $f):
	                $txt = $f['feature_text'] ?? '';
	                if (!$txt) continue;
	              ?>
	                <li class="small-pricing__feature"><?php echo esc_html($txt); ?></li>
	              <?php endforeach; ?>
	            </ul>
	          <?php endif; ?>

	          <div class="small-pricing__cta">
	            <a class="btn btn--white" href="<?php echo esc_url($learn_url); ?>#membershipPricing">
	              Learn More
	            </a>
	          </div>
					</div>
        </article>
      <?php endforeach; ?>
    </div>

  </div>
</section>
