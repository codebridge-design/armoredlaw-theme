<?php
// Template Part: Membership Pricing Table (ACF Options)

// Safety: ACF required
if ( ! function_exists('get_field') ) {
  return;
}

$eyebrow = get_field('pricing_eyebrow', 'option');
$title   = get_field('pricing_title', 'option');

$default = get_field('billing_default', 'option') ?: 'monthly'; // monthly|yearly

$plans = get_field('pricing_plans', 'option');
$rows  = get_field('pricing_rows', 'option');

if ( empty($plans) || empty($rows) ) {
  return;
}

// helper: get plan by key
$plans_by_key = [];
foreach ($plans as $p) {
  if (!empty($p['plan_key'])) {
    $plans_by_key[$p['plan_key']] = $p;
  }
}

function al_mp_link($membership_post){
  if (!$membership_post) return '#';
  $id = is_object($membership_post) ? (int)$membership_post->ID : (int)$membership_post;
  return $id ? get_permalink($id) : '#';
}

/**
 * Render single pricing cell (per plan, per row).
 * $cell is ACF group: ['type' => text|icon|empty, 'text' => '', 'icon' => check|cross|na]
 */
function al_render_pricing_cell($cell) {
  $type = $cell['type'] ?? 'empty';

  if ($type === 'text') {
    $t = trim((string)($cell['text'] ?? ''));
    return $t !== ''
      ? '<div class="pricing__value">'.esc_html($t).'</div>'
      : '<div class="pricing__value">&nbsp;</div>';
  }

  if ($type === 'icon') {
    $icon = $cell['icon'] ?? 'na'; // check|cross|na
    return '<span class="pricing__icon pricing__icon--'.esc_attr($icon).'" aria-hidden="true"></span>';
  }

  return '<div class="pricing__value">&nbsp;</div>';
}

$monthly_label = get_field('billing_monthly_label','option') ?: 'Billed monthly';
$yearly_label  = get_field('billing_yearly_label','option') ?: 'Annually';
?>

<section class="pricing" data-billing="<?php echo esc_attr($default); ?>">
	<div class="container">
	  <div class="pricing__head">
	    <?php if($eyebrow): ?><div class="pricing__eyebrow"><?php echo esc_html($eyebrow); ?></div><?php endif; ?>
	    <?php if($title): ?><h2 class="pricing__title"><?php echo esc_html($title); ?></h2><?php endif; ?>
	  </div>

		<div class="pricing__hint">Swipe to see all plans →</div>

		<div class="pricing__scroll">
		  <div class="pricing__wrap">
		    <!-- header row -->
		    <div class="pricing__row pricing__row--header">
		      <div class="pricing__cell pricing__cell--label">
		        <button
		          type="button"
		          class="pricing__toggle"
		          aria-pressed="<?php echo $default === 'yearly' ? 'true' : 'false'; ?>"
		        >
		          <span class="pricing__toggle-text pricing__toggle-text--monthly">
		            <?php echo esc_html($monthly_label); ?>
		          </span>

		          <span class="pricing__toggle-ui" aria-hidden="true">
		            <span class="pricing__toggle-track"></span>
		            <span class="pricing__toggle-thumb"></span>
		          </span>

		          <span class="pricing__toggle-text pricing__toggle-text--yearly">
		            <?php echo esc_html($yearly_label); ?>
		          </span>
		        </button>
		      </div>

		      <?php foreach (['essential','advanced','elite'] as $k):
		        $p = $plans_by_key[$k] ?? null;
		        if (!$p) continue;

		        $m_link = al_mp_link($p['plan_mp_membership_monthly'] ?? null);
		        $y_link = al_mp_link($p['plan_mp_membership_yearly'] ?? null);
		      ?>
		        <div class="pricing__cell pricing__cell--plan <?php echo !empty($p['plan_is_featured']) ? 'is-featured' : ''; ?>">

		          <?php if (!empty($p['plan_badge_enabled'])): ?>
			          <div class="pricing__badge-wrapper">
			            <div class="pricing__badge">
			              <span class="pricing__badge-text">Most Popular</span>
			            </div>
								</div>
		          <?php endif; ?>

		          <div class="pricing__plan-title"><?php echo esc_html($p['plan_title'] ?? ucfirst($k)); ?></div>

		          <div class="pricing__price">
		            <span class="pricing__price-monthly" data-price="monthly">
		              <?php echo esc_html(($p['plan_currency'] ?? '$') . ($p['plan_price_monthly'] ?? '')); ?>
		              <span class="pricing__price-suffix"><?php echo esc_html($p['plan_suffix_monthly'] ?? '/Mo'); ?></span>
		            </span>
		            <span class="pricing__price-sep">or</span>
		            <span class="pricing__price-yearly" data-price="yearly">
		              <?php echo esc_html(($p['plan_currency'] ?? '$') . ($p['plan_price_yearly'] ?? '')); ?>
		              <span class="pricing__price-suffix"><?php echo esc_html($p['plan_suffix_yearly'] ?? '/Yr'); ?></span>
		            </span>
		          </div>

		          <div class="pricing__cta">
		            <a class="btn pricing__btn" data-billing-link="monthly" href="<?php echo esc_url($m_link); ?>">
		              <?php echo esc_html('Get Started'); ?>
		            </a>
		            <a class="btn pricing__btn" data-billing-link="yearly" href="<?php echo esc_url($y_link); ?>">
		              <?php echo esc_html('Get Started'); ?>
		            </a>
		          </div>
		        </div>
		      <?php endforeach; ?>
		    </div>

		    <!-- rows -->
		    <?php foreach ($rows as $r): ?>
		      <?php if (($r['acf_fc_layout'] ?? '') === 'section_heading'): ?>

		        <div class="pricing__row pricing__row--section">
		          <div class="pricing__cell pricing__cell--label">
		            <div class="pricing__section-title"><?php echo esc_html($r['row_heading'] ?? ''); ?></div>
		          </div>
		          <div class="pricing__cell pricing__cell--plan"></div>
		          <div class="pricing__cell pricing__cell--plan"></div>
		          <div class="pricing__cell pricing__cell--plan"></div>
		        </div>

		      <?php elseif (($r['acf_fc_layout'] ?? '') === 'feature_row'): ?>

		        <div class="pricing__row">
		          <div class="pricing__cell pricing__cell--label">
		            <div class="pricing__label"><?php echo esc_html($r['row_label'] ?? ''); ?></div>
		            <?php if(!empty($r['row_note'])): ?>
		              <div class="pricing__note"><?php echo esc_html($r['row_note']); ?></div>
		            <?php endif; ?>
		          </div>

		          <div class="pricing__cell pricing__cell--plan">
		            <?php echo al_render_pricing_cell($r['essential_cell'] ?? []); ?>
		          </div>

		          <div class="pricing__cell pricing__cell--plan">
		            <?php echo al_render_pricing_cell($r['advanced_cell'] ?? []); ?>
		          </div>

		          <div class="pricing__cell pricing__cell--plan">
		            <?php echo al_render_pricing_cell($r['elite_cell'] ?? []); ?>
		          </div>
		        </div>

		      <?php endif; ?>
		    <?php endforeach; ?>

		    <?php if (get_field('pricing_show_bottom_cta','option')): ?>
		      <div class="pricing__row pricing__row--bottom">
		        <div class="pricing__cell pricing__cell--label"></div>

		        <?php foreach (['essential','advanced','elite'] as $k):
		          $p = $plans_by_key[$k] ?? null;
		          if(!$p) continue;
		          $m_link = al_mp_link($p['plan_mp_membership_monthly'] ?? null);
		          $y_link = al_mp_link($p['plan_mp_membership_yearly'] ?? null);
		        ?>
		          <div class="pricing__cell pricing__cell--plan <?php echo !empty($p['plan_is_featured']) ? 'is-featured' : ''; ?>">
								<div class="pricing__plan-title"><?php echo esc_html($p['plan_title'] ?? ucfirst($k)); ?></div>

		            <div class="pricing__price">
	                <span class="pricing__price-monthly" data-price="monthly">
	                  <?php echo esc_html(($p['plan_currency'] ?? '$') . ($p['plan_price_monthly'] ?? '')); ?>
	                  <span class="pricing__price-suffix"><?php echo esc_html($p['plan_suffix_monthly'] ?? '/Mo'); ?></span>
	                </span>
	                <span class="pricing__price-sep">or</span>
	                <span class="pricing__price-yearly" data-price="yearly">
	                  <?php echo esc_html(($p['plan_currency'] ?? '$') . ($p['plan_price_yearly'] ?? '')); ?>
	                  <span class="pricing__price-suffix"><?php echo esc_html($p['plan_suffix_yearly'] ?? '/Yr'); ?></span>
	                </span>
	              </div>

		            <div class="pricing__cta">
		              <a class="btn pricing__btn" data-billing-link="monthly" href="<?php echo esc_url($m_link); ?>"><?php echo esc_html('Get Started'); ?></a>
		              <a class="btn pricing__btn" data-billing-link="yearly" href="<?php echo esc_url($y_link); ?>"><?php echo esc_html('Get Started'); ?></a>
		            </div>
		          </div>
		        <?php endforeach; ?>

		      </div>
		    <?php endif; ?>

		  </div>
		</div>
	</div>
</section>

<script>
document.addEventListener('click', (e) => {
  const btn = e.target.closest('.pricing__toggle');
  if (!btn) return;

  const wrap = btn.closest('.pricing');
  if (!wrap) return;

  const cur = wrap.getAttribute('data-billing') || 'monthly';
  const next = cur === 'monthly' ? 'yearly' : 'monthly';

  wrap.setAttribute('data-billing', next);
  btn.setAttribute('aria-pressed', next === 'yearly' ? 'true' : 'false');
});

</script>