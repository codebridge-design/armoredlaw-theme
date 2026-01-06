<?php
/**
 * State Laws module:
 * - Helper to find State Laws page by template
 * - REST API endpoint to get state topics content
 */

if (!defined('ABSPATH')) {
  exit;
}

/**
 * Find the page ID that uses the State Laws template.
 */
function al_get_state_laws_page_id(): int {
  static $cached_id = null;
  if ($cached_id !== null) {
    return (int) $cached_id;
  }

  $q = new WP_Query([
    'post_type'      => 'page',
    'post_status'    => 'publish',
    'posts_per_page' => 1,
    'fields'         => 'ids',
    'meta_key'       => '_wp_page_template',
    'meta_value'     => 'page-templates/page-state-laws.php',
  ]);

  $cached_id = (!empty($q->posts)) ? (int) $q->posts[0] : 0;
  return (int) $cached_id;
}

/**
 * Register REST route
 */
add_action('rest_api_init', function () {
  register_rest_route('armoredlaw/v1', '/state-laws/(?P<state>[A-Z]{2})', [
    'methods'  => 'GET',
    'callback' => 'al_get_state_laws',
    'permission_callback' => '__return_true',
  ]);
});

/**
 * REST callback: get topics HTML for a given state code (e.g., WA)
 */
function al_get_state_laws($request) {
  $state = strtoupper((string) $request['state']);

  $q = new WP_Query([
    'post_type'      => 'state_laws',
    'posts_per_page' => 1,
    'post_status'    => 'publish',
    'meta_query'     => [[
      'key'   => 'state_code',
      'value' => $state,
    ]],
  ]);

  if (!$q->have_posts()) {
    return new WP_REST_Response(['state' => $state, 'topics' => []], 200);
  }

  $post_id = (int) $q->posts[0]->ID;

  // If ACF is off, return empty topics (avoid fatal)
  if (!function_exists('get_field')) {
    return new WP_REST_Response(['state' => $state, 'topics' => []], 200);
  }

  $rows = get_field('topics', $post_id);
  $out = [];

  if (is_array($rows)) {
    foreach ($rows as $r) {
      $key = isset($r['topic_key']) ? sanitize_key($r['topic_key']) : '';
      if (!$key) continue;

      $html = $r['topic_content'] ?? '';
      $out[$key] = wp_kses_post($html);
    }
  }

  return new WP_REST_Response([
    'state'  => $state,
    'topics' => $out,
  ], 200);
}
