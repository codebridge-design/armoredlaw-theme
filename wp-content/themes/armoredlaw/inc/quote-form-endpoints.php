<?php
// inc/quote-form-endpoints.php

add_action('wp_ajax_nopriv_al_quote_submit', 'al_quote_submit');
add_action('wp_ajax_al_quote_submit', 'al_quote_submit');

function al_quote_submit() {
  // Security
  $nonce = $_POST['nonce'] ?? '';
  if (!wp_verify_nonce($nonce, 'al_quote_submit')) {
    wp_send_json_error(['message' => 'Invalid nonce'], 403);
  }

  $raw = $_POST['data'] ?? '';
  $data = json_decode(stripslashes($raw), true);

  if (!is_array($data)) {
    wp_send_json_error(['message' => 'Invalid payload'], 400);
  }

  // Minimal server-side validation
  $required = ['protection_type','has_ccw','state','plan','full_name','email','phone'];
  foreach ($required as $k) {
    if (empty($data[$k])) {
      wp_send_json_error(['message' => "Missing field: {$k}"], 422);
    }
  }

  // TODO: sanitize
  $payload = [
    'protection_type' => sanitize_text_field($data['protection_type']),
    'has_ccw'         => sanitize_text_field($data['has_ccw']),
    'state'           => sanitize_text_field($data['state']),
    'plan'            => sanitize_text_field($data['plan']),
    'full_name'       => sanitize_text_field($data['full_name']),
    'email'           => sanitize_email($data['email']),
    'phone'           => sanitize_text_field($data['phone']),
    'utm'             => $data['utm'] ?? [],
    'page_url'        => esc_url_raw($data['page_url'] ?? ''),
  ];

  // === РЕЖИМ 1: Заглушка (поки HubSpot нема) ===
  error_log('[QUOTE SUBMIT] ' . print_r($payload, true));
  wp_send_json_success(['message' => 'Saved (stub)']);
  return;

  // === РЕЖИМ 2: HubSpot Forms API ===
  // portalId/formId краще тягнути з ACF options або з цієї сторінки
	// $portal_id = get_field('hs_portal_id', get_the_ID());
	// $form_id   = get_field('hs_form_id', get_the_ID());

	//   if (!$portal_id || !$form_id) {
	//     wp_send_json_error(['message' => 'HubSpot form is not configured'], 500);
	//   }

  //$hs = al_quote_build_hubspot_payload($payload);

  //$endpoint = "https://api.hsforms.com/submissions/v3/integration/submit/{$portal_id}/{$form_id}";

	//   $res = wp_remote_post($endpoint, [
	//     'timeout' => 15,
	//     'headers' => [
	//       'Content-Type' => 'application/json'
	//     ],
	//     'body' => wp_json_encode($hs),
	//   ]);

  if (is_wp_error($res)) {
    wp_send_json_error(['message' => $res->get_error_message()], 500);
  }

  $code = wp_remote_retrieve_response_code($res);
  $body = wp_remote_retrieve_body($res);

  if ($code >= 200 && $code < 300) {
    wp_send_json_success(['message' => 'Submitted']);
  }

  wp_send_json_error([
    'message' => 'HubSpot error',
    'status'  => $code,
    'body'    => $body,
  ], 502);
}

function al_quote_build_hubspot_payload(array $p): array {
  // HubSpot очікує fields[] з name/value
  // ВАЖЛИВО: name має збігатися з internal name полів у HubSpot Form
  $fields = [
    ['name' => 'firstname', 'value' => al_quote_firstname($p['full_name'])],
    ['name' => 'lastname',  'value' => al_quote_lastname($p['full_name'])],
    ['name' => 'email',     'value' => $p['email']],
    ['name' => 'phone',     'value' => $p['phone']],

    // кастомні поля (мають існувати в HubSpot як properties + додані у form)
    ['name' => 'protection_type', 'value' => $p['protection_type']],
    ['name' => 'has_ccw',         'value' => $p['has_ccw']],
    ['name' => 'state',           'value' => $p['state']],
    ['name' => 'plan',            'value' => $p['plan']],
  ];

  // UTM як окремі hidden fields, якщо вони є у формі:
  if (!empty($p['utm']['utm_source']))   $fields[] = ['name'=>'utm_source',   'value'=>$p['utm']['utm_source']];
  if (!empty($p['utm']['utm_medium']))   $fields[] = ['name'=>'utm_medium',   'value'=>$p['utm']['utm_medium']];
  if (!empty($p['utm']['utm_campaign'])) $fields[] = ['name'=>'utm_campaign', 'value'=>$p['utm']['utm_campaign']];
  if (!empty($p['utm']['utm_term']))     $fields[] = ['name'=>'utm_term',     'value'=>$p['utm']['utm_term']];
  if (!empty($p['utm']['utm_content']))  $fields[] = ['name'=>'utm_content',  'value'=>$p['utm']['utm_content']];

  return [
    'fields'  => $fields,
    'context' => [
      'pageUri'  => $p['page_url'] ?: home_url('/get-a-free-quote/'),
      'pageName' => 'Get a Free Quote',
      // 'hutk' => $_COOKIE['hubspotutk'] ?? null, // якщо треба, але cookie може бути відсутня
      // 'ipAddress' => $_SERVER['REMOTE_ADDR'] ?? null, // обережно з privacy
    ],
    // Якщо HubSpot вимагає legal consent:
    // 'legalConsentOptions' => [...]
  ];
}

function al_quote_firstname(string $full): string {
  $parts = preg_split('/\s+/', trim($full));
  return $parts[0] ?? $full;
}
function al_quote_lastname(string $full): string {
  $parts = preg_split('/\s+/', trim($full));
  array_shift($parts);
  return $parts ? implode(' ', $parts) : '';
}
