<?php
include_once 'class-wizard-step.php';

class Step_Services extends Wizard_Step {

    public static $name = "services";

    public function handle() {
        global $ep_the_wizard;

        $services_to_add = explode(';', $_POST["services"]);

        foreach ($services_to_add as $key => $service_to_add) {

            $page_attr = array(
                'post_title' => sanitize_text_field($service_to_add),
                'post_status' => 'publish',
                'post_type' => 'services',
                'post_content' => EXTERMINATOR_PLUGIN_LOREM_IPSUM
            );

            $post_id = wp_insert_post($page_attr);
            set_post_thumbnail($post_id, $ep_the_wizard->get_placeholder_attachment_id());
        }
    }

    public function view() {
        ?>
        <h2> write what services to include in the site, separated by ";": </h2>
        <div>
            <textarea type="text" name="services" /></textarea>
        </div>
        <?php
    }

}
