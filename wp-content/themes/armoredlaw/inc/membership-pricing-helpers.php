<?php
if ( ! function_exists('al_mp_link') ) {
  function al_mp_link($membership_post){
    if (!$membership_post) return '#';
    $id = is_object($membership_post) ? (int)$membership_post->ID : (int)$membership_post;
    return $id ? get_permalink($id) : '#';
  }
}

/**
 * Render single pricing cell (per plan, per row).
 * $cell is ACF group: ['type' => text|icon|empty, 'text' => '', 'icon' => check|cross|na]
 */
if ( ! function_exists('al_render_pricing_cell') ) {
  function al_render_pricing_cell($cell) {
    $type = $cell['type'] ?? 'empty';

    if ($type === 'text') {
      $t = trim((string)($cell['text'] ?? ''));
      return $t !== ''
        ? '<div class="pricing__value">'.esc_html($t).'</div>'
        : '<div class="pricing__value">&nbsp;</div>';
    }

    if ($type === 'icon') {
      $icon = $cell['icon'] ?? 'na';
      return '<span class="pricing__icon pricing__icon--'.esc_attr($icon).'" aria-hidden="true"></span>';
    }

    return '<div class="pricing__value">&nbsp;</div>';
  }
}
