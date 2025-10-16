<?php if (!empty($instance['tabs']) && is_array($instance['tabs'])): ?>
<?php
  $tabs = array_values($instance['tabs']);
  $default_active = '';
  $uid = uniqid('msb-sow-tabs-');

  $slug_counts = array();
  $tab_slugs = array();
  foreach ($tabs as $i => $tab) {
    $base = '';
    if (!empty($tab['id'])) {
      $base = sanitize_key($tab['id']);
    } else if (!empty($tab['title'])) {
      $base = sanitize_title($tab['title']);
    } else {
      $base = 'tab' . $i;
    }
    if (!isset($slug_counts[$base])) { $slug_counts[$base] = 0; }
    $slug_counts[$base]++;
    $slug = $base;
    if ($slug_counts[$base] > 1) { $slug = $base . '-' . $slug_counts[$base]; }
    $tab_slugs[$i] = $slug;
  }
?>

<div class="msb-sow-tabs" id="<?php echo esc_attr($uid); ?>" role="tablist">
  <div class="msb-tabs-nav">
    <?php foreach ($tabs as $i => $tab):
      $tid = $tab_slugs[$i];
      $label = !empty($tab['title']) ? $tab['title'] : ('Tab '.($i+1));
      $is_active = ($i === 0); // Always make first tab active by default
    ?>
      <button class="msb-tab-btn<?php echo $is_active ? ' is-active' : ''; ?>" role="tab" aria-selected="<?php echo $is_active ? 'true' : 'false'; ?>" data-tab="<?php echo esc_attr($tid); ?>"><?php echo esc_html($label); ?></button>
    <?php endforeach; ?>
  </div>

  <div class="msb-tabs-panels">
    <?php foreach ($tabs as $i => $tab):
      $tid = $tab_slugs[$i];
      $is_active = ($i === 0); // Always make first panel active by default
      $hidden = $is_active ? '' : ' hidden';
    ?>
      <div class="msb-tab-panel<?php echo $is_active ? ' is-active' : ''; ?>" id="panel-<?php echo esc_attr($tid); ?>" role="tabpanel"<?php echo $hidden; ?>>
        <?php if (!empty($tab['content'])): ?>
          <?php echo siteorigin_panels_render('msb_tab_' . $uid . '_' . $tid, true, $tab['content']); ?>
        <?php else: ?>
          <div class="msb-tab-empty"><?php _e('Chưa có nội dung cho tab này.', 'msb-app-theme'); ?></div>
        <?php endif; ?>
      </div>
    <?php endforeach; ?>
  </div>
</div>
<?php endif; ?>



