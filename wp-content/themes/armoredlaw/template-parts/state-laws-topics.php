<?php
$title = $args['title'] ?? 'CLICK A TOPIC BELOW TO OPEN A SUMMARY BY STATE';
$state_laws_page_id = function_exists('al_get_state_laws_page_id') ? al_get_state_laws_page_id() : 0;
$topics_rows = $state_laws_page_id ? get_field('al_topics', $state_laws_page_id) : [];
?>

<section class="al-topics-block" id="alTopicsBlock">
  <div class="container">

    <?php if ($title) : ?>
      <p class="al-topics-block__title"><?php echo esc_html($title); ?></p>
    <?php endif; ?>

    <div class="al-topics" id="alTopics" data-state="">
      <?php if (is_array($topics_rows) && !empty($topics_rows)) : ?>
        <?php foreach ($topics_rows as $row) :
          $key   = isset($row['key']) ? sanitize_key($row['key']) : '';
          $label = $row['label'] ?? '';
          if (!$key || !$label) continue;
        ?>
          <div class="al-topic-item">
            <button class="al-topic-btn" type="button" data-topic="<?php echo esc_attr($key); ?>" aria-expanded="false">
              <span><?php echo esc_html($label); ?></span>
            </button>

            <div class="al-topic-panel" data-panel="<?php echo esc_attr($key); ?>" hidden>
              <div class="al-topic-panel__inner">
                <p class="al-topic-empty">Select a state to view this summary.</p>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      <?php else : ?>
        <p class="al-topic-empty">Topics are not configured yet (al_topics).</p>
      <?php endif; ?>
    </div>

  </div>
</section>
