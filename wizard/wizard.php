<?php

if (!class_exists('wizard')) :

    final class wizard {

        protected static $_instance = null;

        public static function instance() {
            if (is_null(self::$_instance)) {
                self::$_instance = new self();
            }
            return self::$_instance;
        }

        /**
         * Cloning is forbidden.
         * @since 1.0.0
         */
        public function __clone() {
            _doing_it_wrong(__FUNCTION__, 'cant create another object', '1.0.0');
        }

        /**
         * Unserializing instances of this class is forbidden.
         * @since 1.0.0
         */
        public function __wakeup() {
            _doing_it_wrong(__FUNCTION__, 'must serialzing wizard', '1.0.0');
        }

        static function install() {

            //wp_redirect( admin_url().'?page=setup',301 ); exit;
        }

        /**
         * wizard Constructor.
         */
        public function __construct() {

            if (current_theme_supports('ezbz')) {
                return;
            }

            $this->includes();
            register_activation_hook(__FILE__, array('wizard', 'install'));

            //do_action( 'wizard_loaded' );
        }

        public function includes() {
            include_once( 'the_wizard.php' );
          
        }

        /**
         * Get the plugin url.
         * @return string
         */
        public function plugin_url() {
            return untrailingslashit(plugins_url('/', __FILE__));
        }

        /**
         * Get the plugin path.
         * @return string
         */
        public function plugin_path() {
            return untrailingslashit(plugin_dir_path(__FILE__));
        }

    }

    endif;

function get_wizard() {
    return wizard::instance();
}

// Global for backwards compatibility.
get_wizard();


