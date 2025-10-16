<?php
function msb_saving_rates_tabs_widget($args, $instance, $instance_number){
    $title = isset($instance['title']) ? $instance['title'] : '';
    
    // Load rates data
    $json_path = get_template_directory() . '/widgets/saving-rates-tabs/data/rates.json';
    $data = array();
    if (file_exists($json_path)) {
        $data = json_decode(file_get_contents($json_path), true);
    }
    
    if (empty($data)) {
        echo '<p>' . __('Không thể tải dữ liệu lãi suất.', 'msb-app-theme') . '</p>';
        return;
    }

    echo $args['before_widget'];
    if ($title) echo $args['before_title'] . esc_html($title) . $args['after_title'];

    $widget_id = 'msb-saving-rates-tabs-' . esc_attr($instance_number);
    ?>
    <div class="msb-saving-rates-tabs" id="<?php echo $widget_id; ?>" role="tablist">
        <!-- Tab Navigation -->
        <div class="msb-srt-nav">
            <button class="msb-srt-tab-btn is-active" role="tab" aria-selected="true" data-tab="counter">
                <?php echo esc_html($data['counter']['title']); ?>
            </button>
            <button class="msb-srt-tab-btn" role="tab" aria-selected="false" data-tab="online">
                <?php echo esc_html($data['online']['title']); ?>
            </button>
            <button class="msb-srt-tab-btn" role="tab" aria-selected="false" data-tab="foreign">
                <?php echo esc_html($data['foreign']['title']); ?>
            </button>
        </div>

        <!-- Tab Panels -->
        <div class="msb-srt-panels">
            <!-- Counter Tab (Default Active) -->
            <div class="msb-srt-panel is-active" id="panel-counter" role="tabpanel">
                <?php msb_srt_render_table($data['counter']); ?>
            </div>

            <!-- Online Tab -->
            <div class="msb-srt-panel" id="panel-online" role="tabpanel" hidden>
                <?php msb_srt_render_table($data['online']); ?>
            </div>

            <!-- Foreign Currency Tab -->
            <div class="msb-srt-panel" id="panel-foreign" role="tabpanel" hidden>
                <?php msb_srt_render_table($data['foreign']); ?>
            </div>
        </div>
    </div>
    <?php
    echo $args['after_widget'];
}

function msb_srt_render_table($tab_data) {
    if (empty($tab_data['headers']) || empty($tab_data['rows'])) return;
    
    echo '<div class="msb-srt-table-wrap">';
    echo '<table class="msb-srt-table">';
    
    // Header
    echo '<thead>';
    echo '<tr>';
    foreach ($tab_data['headers'] as $header) {
        echo '<th>' . esc_html($header) . '</th>';
    }
    echo '</tr>';
    
    // Subheader (if exists)
    if (!empty($tab_data['subheaders'])) {
        echo '<tr class="msb-srt-subheader">';
        foreach ($tab_data['subheaders'] as $subheader) {
            echo '<td>' . esc_html($subheader) . '</td>';
        }
        echo '</tr>';
    }
    echo '</thead>';
    
    // Body
    echo '<tbody>';
    foreach ($tab_data['rows'] as $row) {
        echo '<tr>';
        echo '<td>' . esc_html($row['term']) . '</td>';
        
        // Dynamic columns based on tab type
        if (isset($row['highest'])) {
            echo '<td>' . esc_html($row['highest']) . '</td>';
            echo '<td>' . esc_html($row['partial']) . '</td>';
            echo '<td>' . esc_html($row['periodic']) . '</td>';
            echo '<td>' . esc_html($row['pay_now']) . '</td>';
            echo '<td>' . esc_html($row['bee']) . '</td>';
            if (isset($row['sprout'])) echo '<td>' . esc_html($row['sprout']) . '</td>';
            if (isset($row['contract'])) echo '<td>' . esc_html($row['contract']) . '</td>';
        } else {
            // Foreign currency columns
            echo '<td>' . esc_html($row['aud']) . '</td>';
            echo '<td>' . esc_html($row['eur']) . '</td>';
            echo '<td>' . esc_html($row['cad']) . '</td>';
            echo '<td>' . esc_html($row['jpy']) . '</td>';
            echo '<td>' . esc_html($row['gbp']) . '</td>';
            echo '<td>' . esc_html($row['sgd']) . '</td>';
            echo '<td>' . esc_html($row['usd']) . '</td>';
        }
        echo '</tr>';
    }
    echo '</tbody>';
    echo '</table>';
    echo '</div>';
}
?>