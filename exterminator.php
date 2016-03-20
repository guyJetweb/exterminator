<?php

/**
 * Plugin Name: Exterminator
 * Version: 1.0.0
 * Author: JetWeb
 *
 */
define('EXTERMINATOR_PLUGIN_PATH', dirname(__FILE__));

include_once(dirname(__FILE__) . '/exterminator_acf.php' );
include_once(dirname(__FILE__) . '/class-ep-post-types.php' );
include_once( dirname(__FILE__) . '/organization-details-setting.php' );
include_once(dirname(__FILE__) . '/versions.php' );
include_once(dirname(__FILE__) . '/colors.php' );
include_once(dirname(__FILE__) . '/wizard/wizard.php' );
include_once( dirname(__FILE__) . '/widgets/featured-service-widget.php' );
include_once(dirname(__FILE__) . '/widgets/featured-cpt-widget.php' );

function exterminator_register_widgets() {
	register_widget('Jetweb_Featured_CPT');
        register_widget('Jetweb_Featured_Service');
}

add_action( 'widgets_init', 'exterminator_register_widgets' );
