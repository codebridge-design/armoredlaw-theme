<?php
get_header();
the_post();
$plan_id = get_the_ID();

if (
  isset($_GET['action'], $_GET['txn']) &&
  $_GET['action'] === 'checkout'
) : ?>

  <main class="container mp-checkout">
    <div class="mp_wrapper">
      <?php the_content(); ?>
    </div>
  </main>

  <?php
  get_footer();
  return;
endif;

$membership = new MeprProduct(get_the_ID());
$price = $membership->price;

if ($membership->period_type === 'months') {
  $period = 'Month';
} elseif ($membership->period_type === 'years') {
  $period = 'Year';
}
$price_label = sprintf('$%s / %s', esc_html($price), esc_html($period));
?>

<main class="container mp-checkout">
	<h1 class="mp-checkout__title"><?= esc_html__( 'Checkout', 'armoredlaw' ); ?></h1>
	<div class="mp-checkout__wrapper">

	  <section class="mp-checkout__content">
			<div class="mp-checkout__content-inner">
				<div class="mp-checkout__content-head">
					<h2 class="title"><?= esc_html__( 'Selected Plan:', 'armoredlaw' ); ?><?php the_title(); ?></h2>
				</div>
				<div class="mp-checkout__content-card">
					<h3 class="mp-checkout__content-label"><?php the_title(); ?></h3>
					<p class="mp-checkout__content-price"><?= $price_label; ?></p>
					<div class="mp-checkout__content-info">
            <?php
              $raw = get_post_field('post_content', $plan_id);
              $raw = do_shortcode($raw);
              $raw = wpautop($raw);
              $raw = shortcode_unautop($raw);
              echo $raw;
            ?>
          </div>
				</div>
				<div class="mp-checkout__content-bottom">
					<a href="<?= esc_url( home_url('/pricing/') ); ?>#membershipPricing" class="mp-checkout__content-btn">
            <?= esc_html__( 'Change Plan', 'armoredlaw' ); ?>
          </a>
				</div>
			</div>
	  </section>

	  <aside class="mp-checkout__form">
	    <?php echo do_shortcode('[mepr_membership_registration_form id="' . $plan_id . '"]'); ?>
	  </aside>
	</div>

</main>

<?php get_footer(); ?>
