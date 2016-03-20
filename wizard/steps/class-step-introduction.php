<?php
include_once 'class-wizard-step.php';

class Step_Introduction extends Wizard_Step {

    public static $name = "Introduction";
    private $plugins = array(
        "required" => array(
            array('name' => 'Advanced Custom Fields', 'path' => 'advanced-custom-fields/acf.php'),
            array('name' => 'Genesis Featured Widget Amplified', 'path' => 'genesis-featured-widget-amplified/plugin.php'),
            array('name' => 'Genesis Responsive Slider', 'path' => 'genesis-responsive-slider/genesis-responsive-slider.php'),
            array('name' => 'Gravity Forms', 'path' => 'gravityforms-1.9.15/gravityforms.php'),
            array('name' => 'Genesis Simple Edits', 'path' => 'genesis-simple-edits/plugin.php')
        ),
        "recommended" => array(
            array('name' => 'WP Smush', 'path' => 'wp-smushit/wp-smush.php'),
            array('name' => 'Google XML Sitemaps', 'path' => 'google-sitemap-generator/sitemap.php'),
            array('name' => 'Imsanity', 'path' => 'imsanity/imsanity.php')
    ));

    public function handle() {
        
    }

    public function view() {

        $required_plugins_missings = $this->test_for_plugins("required");

        $recommended_plugins_missings = $this->test_for_plugins("recommended");

        $message = "";
        
        //disable wizard if required plugins not activated
        if ($required_plugins_missings) {
            add_filter('ep_wizard_disable_continue', '__return_false');
            $message .= "please activate missing plugins before continue with the wizard. ";
        }
        
        if ($recommended_plugins_missings) {
            $message .= "please consider activating the missing recommended plugins";
        }
        ?>
        <h1><?php echo 'Welcome to jetweb site build wizard ' ?></h1>

        <div
        <?php if (!current_theme_supports("exterminator")): ?>
            <?php add_filter('ep_wizard_disable_continue', '__return_false') ?>
                <h2> WARNING: your current theme does not supports this plugin</h2>
                <?php endif; ?>
        </div>

        <?php if ($message):  ?>
            <h2><?php echo $message ?></h2>
        <?php endif; ?>
            
        <div>
            <h3>Required Plugins:</h3>
            <ul class="plugins">
                <?php foreach ($this->plugins["required"] as $plugin): ?>
                    <li><i class="fa <?php echo in_array($plugin["path"], $required_plugins_missings) ? "fa-times" : "fa-check" ?>"></i> <?php echo $plugin["name"] ?></li>
                <?php endforeach; ?>
            </ul>
        </div>

        <div>
            <h3>recommend Plugins:</h3>
            <ul class="plugins">
                <?php foreach ($this->plugins["recommended"] as $plugin): ?>
                    <li><i class="fa <?php echo in_array($plugin["path"], $recommended_plugins_missings) ? "fa-times" : "fa-check" ?>"></i> <?php echo $plugin["name"] ?></li>
                    <?php endforeach; ?>
            </ul>
        </div>
            
        <?php if ($recommended_plugins_missings || $required_plugins_missings): ?>
            <div><a class="button" href="<?php echo admin_url('/plugins.php') ?>">To plugins page</a></div>
        <?php endif; ?>
        
        <?php
    }

    private function test_for_plugins($type) {
        $activated_plugins = get_option('active_plugins');
        $plugins = array_column($this->plugins[$type], 'path');
        return array_diff($plugins,$activated_plugins);
    }

}
