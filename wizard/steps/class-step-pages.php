<?php
include_once 'class-wizard-step.php';

class Step_Pages extends Wizard_Step {

    public static $name = "pages";
    private $pages = array(
        "contact" => array('name' => 'contact', 'title' => 'Contact Us', 'page-template' => 'template-contact-us.php'),
        "about" => array('name' => 'about', 'title' => 'About Us', 'page-template' => 'template-about-us.php'),
        "service_area" => array('name' => 'service_area', 'title' => 'Service Area', 'page-template' => 'template-service-area.php'),
    );

    public function handle() {
        global $ep_the_wizard;
        $pages_to_add = $_POST["pages"];

        foreach ($pages_to_add as $key => $page_to_add) {

            if (array_key_exists($key, $this->pages)) {
                $page_attr = array(
                    'post_title' => $this->pages[$key]["title"],
                    'post_status' => 'publish',
                    'post_type' => 'page',
                    'post_content' => EXTERMINATOR_PLUGIN_LOREM_IPSUM
                );

                $post_id = wp_insert_post($page_attr);
                update_post_meta($post_id, '_wp_page_template', $this->pages[$key]["page-template"]);
                set_post_thumbnail($post_id, $ep_the_wizard->get_placeholder_attachment_id());
                
            }
        }
        
    }

    public function view() {
        ?>
        <h2> check what pages to include in the site: </h2>
        <div>
                <?php foreach ($this->pages as $page): ?>
                <label for="page-<?php echo $page["name"] ?>">
                    <input type="checkbox" name="pages[<?php echo $page["name"] ?>]" id="page-<?php echo $page["name"] ?>" value="1">
                <?php echo $page["name"] ?>
                </label><br/>
        <?php endforeach; ?>
        </div>
        <?php
    }

}
