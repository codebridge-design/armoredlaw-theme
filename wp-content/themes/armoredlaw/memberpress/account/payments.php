<?php
if (!defined('ABSPATH')) {
    die('You are not allowed to call this page directly.');
}

if (!empty($payments)) {
    ?>
  <div class="mp_wrapper">
		<h3 class="al-account__title"><?php esc_html_e('Payments', 'memberpress'); ?></h3>
    <table id="mepr-account-payments-table" class="mepr-account-table">
      <caption class="screen-reader-text"><?php echo esc_html_x('Payments', 'ui', 'memberpress'); ?></caption>
      <thead>
        <tr>
          <th scope="col"><?php echo esc_html_x('Date', 'ui', 'memberpress'); ?></th>
          <th scope="col"><?php echo esc_html_x('Total', 'ui', 'memberpress'); ?></th>
          <th scope="col"><?php echo esc_html_x('Membership', 'ui', 'memberpress'); ?></th>
          <th scope="col"><?php echo esc_html_x('Method', 'ui', 'memberpress'); ?></th>
          <th scope="col"><?php echo esc_html_x('Status', 'ui', 'memberpress'); ?></th>
          <th scope="col"><?php echo esc_html_x('Invoice', 'ui', 'memberpress'); ?></th>
          <?php MeprHooks::do_action('mepr_account_payments_table_header'); ?>
        </tr>
      </thead>
      <tbody>
        <?php
        foreach ($payments as $payment) :
            $alt = (isset($alt) && !$alt);
            $txn = new MeprTransaction($payment->id);
            $pm  = $txn->payment_method();
            $prd = $txn->product();

						$status_raw = $payment->status;
            $status_hr  = MeprAppHelper::human_readable_status($status_raw);

            switch ($status_raw) {
              case MeprTransaction::$complete_str:
                $status_label = 'Paid';
                $status_class = 'is-paid';
                break;

              case MeprTransaction::$pending_str:
                $status_label = 'Pending';
                $status_class = 'is-pending';
                break;

              case MeprTransaction::$failed_str:
                $status_label = 'Failed';
                $status_class = 'is-failed';
                break;

              case MeprTransaction::$refunded_str:
                $status_label = 'Refunded';
                $status_class = 'is-refunded';
                break;

              default:
                $status_label = $status_hr;
                $status_class = 'is-unknown';
            }
            ?>
            <tr class="mepr-payment-row <?php echo ($alt) ? 'mepr-alt-row' : ''; ?>">
              <td data-label="<?php echo esc_attr_x('Date', 'ui', 'memberpress'); ?>"><?php echo esc_html(MeprAppHelper::format_date($payment->created_at)); ?></td>
              <td data-label="<?php echo esc_attr_x('Total', 'ui', 'memberpress'); ?>"><?php echo esc_html(MeprAppHelper::format_currency($payment->total <= 0.00 ? $payment->amount : $payment->total)); ?></td>

              <!-- MEMBERSHIP ACCESS URL -->
            <?php if (isset($prd->access_url) && !empty($prd->access_url)) : ?>
                <td data-label="<?php echo esc_attr_x('Membership', 'ui', 'memberpress'); ?>"><a href="<?php echo esc_url(stripslashes($prd->access_url)); ?>"><?php echo esc_html(MeprHooks::apply_filters('mepr_account_payment_product_name', $prd->post_title, $txn)); ?></a></td>
            <?php else : ?>
                <td data-label="<?php echo esc_attr_x('Membership', 'ui', 'memberpress'); ?>"><?php echo esc_html(MeprHooks::apply_filters('mepr_account_payment_product_name', $prd->post_title, $txn)); ?></td>
            <?php endif; ?>

              <td data-label="<?php echo esc_attr_x('Method', 'ui', 'memberpress'); ?>"><?php echo (is_object($pm) ? esc_html($pm->label) : esc_html_x('Unknown', 'ui', 'memberpress')); ?></td>
              <td data-label="<?php echo esc_attr_x('Status', 'ui', 'memberpress'); ?>">
                <span class="mepr-status <?php echo esc_attr($status_class); ?>">
                  <?php echo esc_html($status_label); ?>
                </span>
              </td>
              <td data-label="<?php echo esc_attr_x('Invoice', 'ui', 'memberpress'); ?>"><?php echo esc_html($payment->trans_num); ?></td>
            <?php MeprHooks::do_action('mepr_account_payments_table_row', $payment); ?>
            </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <div id="mepr-payments-paging">
      <?php if ($prev_page) : ?>
        <a href="<?php echo esc_url($account_url . $delim . 'currpage=' . $prev_page); ?>">&lt;&lt; <?php echo esc_html_x('Previous Page', 'ui', 'memberpress'); ?></a>
      <?php endif; ?>
      <?php if ($next_page) : ?>
        <a href="<?php echo esc_url($account_url . $delim . 'currpage=' . $next_page); ?>" style="float:right;"><?php echo esc_html_x('Next Page', 'ui', 'memberpress'); ?> &gt;&gt;</a>
      <?php endif; ?>
    </div>
		<p class="al-account__desc al-account__desc-bottom">
      <?php echo esc_html__('Your billing information is encrypted and never stored on our servers.', 'armoredlaw'); ?>
    </p>
    <div style="clear:both"></div>
  </div>
    <?php
} else {
    ?><div class="mp-wrapper mp-no-subs"><?php
    echo esc_html_x('You have no completed payments to display.', 'ui', 'memberpress');
?></div><?php
}

MeprHooks::do_action('mepr_account_payments', $mepr_current_user);
