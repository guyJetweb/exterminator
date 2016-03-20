<?php

add_action('init', 'ep_add_theme_colors_option');

function ep_add_theme_colors_option() {

    if (current_theme_supports('exterminator_theme_colors')) {

        add_filter('genesis_theme_settings_defaults', 'ep_theme_colors_defaults');
        add_action('genesis_settings_sanitizer_init', 'ep_theme_colors_sanitation_filters');
        add_action('genesis_theme_settings_metaboxes', 'ep_register_theme_colors_settings_box');
    }
}

function ep_theme_colors_defaults($defaults) {

    $defaults['ep-color1'] = '1e6dad';
    $defaults['ep-color2'] = '2483d0';
    return $defaults;
}

function ep_theme_colors_sanitation_filters() {

    genesis_add_option_filter(
        'no_html', GENESIS_SETTINGS_FIELD, array(
            'ep_color1',
            'ep_color2'
        )
    );
}

function ep_register_theme_colors_settings_box($_genesis_theme_settings_pagehook) {
    add_meta_box('ep-colors-settings', 'Colors', 'ep_theme_colors_meta_box_callback', $_genesis_theme_settings_pagehook, 'main', 'high');
}

function ep_theme_colors_meta_box_callback() {
    $theme_colors_support = get_theme_support('exterminator_theme_colors');

    $color1 = genesis_get_option("ep-color1");
    $color2 = genesis_get_option("ep-color2");

    ?>
    <br/>
    <div>
        <label for="ep-color-one">Color 1 :</label><br/>
        <input type="text" name="<?php echo GENESIS_SETTINGS_FIELD ?>[ep-color1]" id="ep-color-one" value="<?php echo $color1 ?>"/>
    </div>
    <div>
        <label for="ep-color-two">Color 2 :</label><br />
        <input type="text" name="<?php echo GENESIS_SETTINGS_FIELD ?>[ep-color2]" id="ep-color-two" value="<?php echo $color2 ?>"/>
    </div>
    <?php
}