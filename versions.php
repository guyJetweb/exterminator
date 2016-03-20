<?php
add_action('init', 'add_ver_style_selctor');

function add_ver_style_selctor() {

    if (current_theme_supports('ver_style_selctor')) {

        add_filter('genesis_theme_settings_defaults', 'ver_style_defaults');
        add_action('genesis_settings_sanitizer_init', 'ver_style_sanitization_filters');
        add_action('genesis_theme_settings_metaboxes', 'register_ver_style_settings_box');
    }
}

function ver_style_defaults($defaults) {

    $defaults['ver-style'] = 'Default';
    return $defaults;
}

function ver_style_sanitization_filters() {
    genesis_add_option_filter(
            'no_html', GENESIS_SETTINGS_FIELD, array(
        'ver-style',
            )
    );
}

function register_ver_style_settings_box($_genesis_theme_settings_pagehook) {
    add_meta_box('style-settings', 'Style', 'ver_style_box', $_genesis_theme_settings_pagehook, 'main', 'high');
}

function ver_style_box() {

    $ver_styles = get_theme_support('ver_style_selctor');
    $current_style = genesis_get_option('ver-style');
    ?>
    <br>
    <p>
        Style Version:
        <select  name="<?php echo GENESIS_SETTINGS_FIELD; ?>[ver-style]" value="">
    <?php
    if (!empty($ver_styles)) {
        $ver_styles = array_shift($ver_styles);
        foreach ((array) $ver_styles as $style => $title) {
            ?><option value="<?php echo esc_attr($style); ?>"<?php selected($current_style, $style) ?>><?php echo esc_html($title); ?></option><?php
                }
            }
            ?>
        </select>
    </p>

    <p><span class="description"><?php _e('Please select the style from the drop down list and save your settings.', 'genesis'); ?></span></p>
    <?php
}
add_filter('body_class', 'add_ver_to_body_class');

function add_ver_to_body_class($classes) {
        $ver_class=genesis_get_option('ver-style');
        $classes[] = $ver_class;
        return $classes;
}