<?php

/**
 * Plugin Name: Exterminator
 * Version: 1.0.2
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

add_action( 'init', 'github_plugin_updater_init' );
function github_plugin_updater_init() {
	define( 'WP_GITHUB_FORCE_UPDATE', true );
	if ( is_admin() ) { // note the use of is_admin() to double check that this is happening in the admin
		$config = array(
			'slug' => plugin_basename( __FILE__ ),
			'proper_folder_name' => 'exterminator',
			'api_url' => 'https://api.github.com/repos/guyJetweb/exterminator',
			'raw_url' => 'https://raw.github.com/guyJetweb/exterminator/master',
			'github_url' => 'https://github.com/guyJetweb/exterminator',
			'zip_url' => 'https://github.com/guyJetweb/exterminator/archive/master.zip',
			'sslverify' => true,
			'requires' => '3.0',
			'tested' => '3.3',
			'readme' => 'README.md',
			'access_token' => '',
		);
                          
		new WP_GitHub_Updater( $config );
	}
}
