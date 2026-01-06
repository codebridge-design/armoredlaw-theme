<?php
/**
 * Template Name: State Laws
 */
get_header();

$page_id = get_queried_object_id();

// ACF (with fallbacks)
$eyebrow  = get_field('sl_eyebrow', $page_id) ?: 'CHECK YOUR AREA';
$title    = get_field('sl_title', $page_id) ?: 'Reciprocity Map & Gun Laws by State';
$desc = get_field('sl_description', $page_id);

$topics_title = get_field('sl_topics_title', $page_id) ?: 'CLICK A TOPIC BELOW TO OPEN A SUMMARY BY STATE';
?>

<main class="state-laws-page slp">

  <?php get_template_part('template-parts/hero'); ?>

  <section class="slp__section">
    <div class="container">

      <div class="slp__head">
        <p class="slp__eyebrow"><?php echo esc_html($eyebrow); ?></p>
        <h2 class="slp__title"><?php echo esc_html($title); ?></h2>
        <div class="slp__desc wysiwyg">
          <?php
          if ($desc) {
            echo apply_filters('the_content', $desc);
          } else {
            echo '<p>Check your concealed carry permit(s) reciprocity and learn about every state’s concealed carry and gun laws.</p>';
          }
          ?>
        </div>
      </div>

      <div class="slp__ui">
        <div class="slp__select-wrap">
          <select
            id="alStateSelect"
            class="slp__select"
            data-placeholder="<?php echo esc_attr('Select State'); ?>"
          ></select>
        </div>

        <button type="button" class="slp__next btn btn--primary" data-al-next>
          <span><?php echo esc_html('Next'); ?></span>
        </button>
      </div>

      <div class="slp__map">
        <div class="slp__map-wrap" id="alMapWrap">
          <?php
            echo file_get_contents(
              get_template_directory() . '/assets/img/map.svg'
            );
          ?>
        </div>
      </div>

    </div>
  </section>

  <?php
    // Topics block stays reusable, but we pass title from ACF
    get_template_part('template-parts/state-laws-topics', null, [
      'title' => $topics_title,
    ]);
  ?>

</main>

<?php get_footer(); ?>
