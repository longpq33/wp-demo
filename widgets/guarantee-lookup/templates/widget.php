<?php
/**
 * Guarantee Lookup Widget Display
 */

function msb_guarantee_lookup_widget($args, $instance) {
    $form_title = isset($instance['form_title']) ? trim($instance['form_title']) : '';
    $ref_label = isset($instance['ref_label']) ? trim($instance['ref_label']) : '';
    $ref_placeholder = isset($instance['ref_placeholder']) ? trim($instance['ref_placeholder']) : '';
    $amount_label = isset($instance['amount_label']) ? trim($instance['amount_label']) : '';
    $amount_placeholder = isset($instance['amount_placeholder']) ? trim($instance['amount_placeholder']) : '';
    $notes_title = isset($instance['notes_title']) ? trim($instance['notes_title']) : '';
    $notes_content = isset($instance['notes_content']) ? trim($instance['notes_content']) : '';
    $button_label = isset($instance['button_label']) ? trim($instance['button_label']) : '';
    $button_url = isset($instance['button_url']) ? trim($instance['button_url']) : '';
    $button_color = isset($instance['button_color']) ? trim($instance['button_color']) : '';

    echo $args['before_widget'];

    echo '<div class="msb-guarantee-lookup">';
    
    if ($form_title !== '') {
        echo '<h3 class="msb-form-title">' . esc_html($form_title) . '</h3>';
    }

    echo '<form class="msb-lookup-form" action="' . esc_url($button_url ?: '#') . '">';
    
    echo '<div class="msb-field-group">';
    if ($ref_label !== '') {
        echo '<label>' . esc_html($ref_label) . '</label>';
    }
    echo '<input type="text" name="ref_number" placeholder="' . esc_attr($ref_placeholder) . '" required>';
    echo '</div>';

    echo '<div class="msb-field-group">';
    if ($amount_label !== '') {
        echo '<label>' . esc_html($amount_label) . '</label>';
    }
    echo '<input type="text" name="amount" placeholder="' . esc_attr($amount_placeholder) . '" required>';
    echo '</div>';

    if ($notes_title !== '' || $notes_content !== '') {
        echo '<div class="msb-notes">';
        if ($notes_title !== '') {
            echo '<h4>' . esc_html($notes_title) . '</h4>';
        }
        if ($notes_content !== '') {
            $notes_lines = array_filter(array_map('trim', explode("\n", $notes_content)));
            if (!empty($notes_lines)) {
                echo '<ul>';
                foreach ($notes_lines as $note) {
                    echo '<li>' . esc_html($note) . '</li>';
                }
                echo '</ul>';
            }
        }
        echo '</div>';
    }

    if ($button_label !== '') {
        $style = $button_color !== '' ? ' style="background-color:' . esc_attr($button_color) . '"' : '';
        echo '<div class="block-button"><button type="submit" id = "msb-lookup-btn" class="msb-lookup-btn"' . $style . '>' . esc_html($button_label) . '</button></div>';
    }

    echo '<div id="msb-result" style="margin-top: 20px;"></div>';
    echo '</form>';
    echo '</div>';

    echo $args['after_widget'];
}
