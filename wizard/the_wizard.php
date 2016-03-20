<?php
include_once 'wizard-constants.php';
include_once 'steps/class-step-introduction.php';
include_once 'steps/class-step-style.php';
include_once 'steps/class-step-pages.php';
include_once 'steps/class-step-details.php';
include_once 'steps/class-step-services.php';
include_once 'steps/class-step-slider.php';
include_once 'steps/class-step-setup-ready.php';
include_once 'steps/class-step-menus.php';


class the_wizard {

    /** @var string Currenct Step */
    private $step = '';

    /** @var array Steps for the setup wizard */
    private $steps = array();

    /** @var int Placeholder Attatchment ID * */
    private $placeholder_attachment_id;

    /**
     * Hook in tabs.
     */
    public function __construct() {

        add_role('admin', 'admin', array(
            'administrator' => true
        ));
        add_action('admin_menu', array($this, 'admin_menus'));

        add_action('admin_init', array($this, 'update_placeholder_attatchment_id'));
        
        add_action('admin_init', array($this, 'setup_wizard'));

    }

    /**
     * Add admin menus/screens.
     */
    public function admin_menus() {

        add_dashboard_page('', '', 'read', 'setup', '');

        global $submenu;
        $url = admin_url() . '?page=setup';
        $submenu['genesis'][] = array('Wizard', 'manage_options', $url);
    }

    /**
     * Show the setup wizard.
     */
    public function setup_wizard() {
        if (!isset($_GET['page']) || (isset($_GET['page']) && ('setup' !== $_GET['page']))) {
            return;
        }

        //initialize steps
        $this->steps[Step_Introduction::$name] = new Step_Introduction();
        $this->steps[Step_Details::$name] = new Step_Details();
        $this->steps[Step_Style::$name] = new Step_Style();
        $this->steps[Step_Pages::$name] = new Step_Pages();
        $this->steps[Step_Services::$name] = new Step_Services();
        $this->steps[Step_Slider::$name] = new Step_Slider();
        $this->steps[Step_Menus::$name] = new Step_Menus();
        $this->steps[Step_Setup_Ready::$name] = new Step_Setup_Ready();

        //get the current step
        //the first step is the default
        $this->step = isset($_GET['step']) ? sanitize_key($_GET['step']) : current(array_keys($this->steps));

        //if the current step we got is not in the steps array
        if (!isset($this->steps[$this->step])) {
            $this->step = current(array_keys($this->steps));
        }

        wp_enqueue_style('setup', get_wizard()->plugin_url() . '/setup.css', array('dashicons', 'install'), '1.0.0');

        wp_enqueue_script('ep-wizard-script', get_wizard()->plugin_url() . '/wizard_script.js', array('jquery'), '1.0.0');


        //if we need to handle form
        if (!empty($_POST['save_step'])) {

            //check nonce field
            check_admin_referer('ep-setup');

            //handle the current step
            $this->steps[$this->step]->handle();

            //redirect to next step
            wp_redirect(esc_url_raw($this->get_next_step_link()));
            exit();
        }

        $this->setup_wizard_header();
        $this->setup_wizard_steps();
        $this->setup_wizard_content();
        $this->setup_wizard_footer();
        exit;
    }

    /**
     * returns a link to the next page in the array
     * @return type
     */
    public function get_next_step_link() {
        $keys = array_keys($this->steps);
        return add_query_arg('step', $keys[array_search($this->step, array_keys($this->steps)) + 1]);
    }

    /**
     * Setup Wizard Header.
     */
    public function setup_wizard_header() {
        ?>
        <!DOCTYPE html>
        <html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
            <head>
                <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
                <meta name="viewport" content="width=device-width" />
                <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
                <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
                <title><?php echo 'Jetweb Wizard' ?></title>
                <?php wp_print_scripts('setup'); ?>
                <?php do_action('admin_print_styles'); ?>
                <?php do_action('admin_head'); ?>
            </head>
            <body class="setup wp-core-ui">
                <h1 id="logo"> JetWeb Wizard</h1>
                <?php
            }

            /**
             * Setup Wizard Footer.
             */
            public function setup_wizard_footer() {
                ?>
                <?php if ('next_steps' === $this->step) : ?>
                    <a class="return-to-dashboard" href="<?php echo esc_url(admin_url()); ?>"><?php echo 'Return to the WordPress Dashboard' ?></a>
                    <?php
                endif;
                echo '</body>';
                echo '</html>';
            }

            /**
             * Output the steps.
             */
            public function setup_wizard_steps() {
                $ouput_steps = $this->steps;
                array_shift($ouput_steps);
                ?>
                <ol class="setup-steps">
                    <?php foreach ($ouput_steps as $step_key => $step) : ?>
                        <li class="<?php
                        if ($step_key === $this->step) {
                            echo 'active';
                        } elseif (array_search($this->step, array_keys($this->steps)) > array_search($step_key, array_keys($this->steps))) {
                            echo 'done';
                        }
                        ?>"></li>
                        <?php endforeach; ?>
                </ol>
                <?php
            }

            /**
             * Output the content for the current step.
             */
            public function setup_wizard_content() {
                ?>

                <div class="setup-content">

                    <form method="post">

                        <?php $this->steps[$this->step]->view(); ?>

                        <p class="setup-actions step">    

                            <?php if ($this->is_last_step()): ?>

                                <a class="button button-large" href="<?php echo admin_url(); ?>">done</a>

                            <?php else: ?>

                                <?php if (apply_filters("ep_wizard_disable_continue", true )): ?>
                                <input type="submit" class="button-primary button button-large button-next" value="<?php esc_attr_e('Continue', 'woocommerce'); ?>" name="save_step" />
                                <a href="<?php echo esc_url($this->get_next_step_link()); ?>" class="button button-large button-next"><?php _e('Skip this step', 'woocommerce'); ?></a>

                                <?php wp_nonce_field('ep-setup'); ?>
                                <?php endif; ?>
                                
                            <?php endif; ?>

                        </p>

                    </form>

                </div>
                <?php
            }

            /**
             * is the current page is last
             */
            public function is_last_step() {
                $keys = array_keys($this->steps);
                end($keys);
                return current($keys) == $this->step;
            }

            public function update_placeholder_attatchment_id() {
                
                $attatchment_id = get_option('ep_placeholder_attachment_id');
                if (!$attatchment_id) {
                    $attatchment_id = $this->ep_wizard_upload_placeholder();
                }
                $this->placeholder_attachment_id = $attatchment_id;
                
            }

            private function ep_wizard_upload_placeholder() {

                $upload_dir = wp_upload_dir();
                $image_data = file_get_contents(EXTERMINATOR_PLUGIN_PLACEHOLDER_PATH);
                $filename = basename(EXTERMINATOR_PLUGIN_PLACEHOLDER_PATH);
                if (wp_mkdir_p($upload_dir['path'])) {
                    $file = $upload_dir['path'] . '/' . $filename;
                } else {
                    $file = $upload_dir['basedir'] . '/' . $filename;
                }
                file_put_contents($file, $image_data);

                $wp_filetype = wp_check_filetype($filename, null);
                $attachment = array(
                    'post_mime_type' => $wp_filetype['type'],
                    'post_title' => sanitize_file_name($filename),
                    'post_content' => '',
                    'post_status' => 'inherit'
                );

                $attach_id = wp_insert_attachment($attachment, $file, 0);
                require_once(ABSPATH . 'wp-admin/includes/image.php');
                $attach_data = wp_generate_attachment_metadata($attach_id, $file);
                $res1 = wp_update_attachment_metadata($attach_id, $attach_data);
                update_option('ep_placeholder_attachment_id', $attach_id);
                return $attach_id;
            }

            public function get_placeholder_attachment_id($placeholder_attachment_id) {
                return $this->placeholder_attachment_id;
            }

        }

        global $ep_the_wizard;
        $ep_the_wizard = new the_wizard();

        