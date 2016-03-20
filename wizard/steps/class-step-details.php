<?php
include_once 'class-wizard-step.php';

class Step_Details extends Wizard_Step {

    public static $name = 'details';

    public function handle() {

        $name = sanitize_text_field($_POST['name']);
        $street = sanitize_text_field($_POST['street']);
        $locality = sanitize_text_field($_POST['locality']);
        $region = sanitize_text_field($_POST['region']);
        $code = sanitize_text_field($_POST['code']);
        $telephone = sanitize_text_field($_POST['telephone']);
        
        $site_name = sanitize_text_field($_POST['site-name']);
        $site_description = sanitize_text_field($_POST['site-description']);
        
        update_option( 'blogname', $site_name );
        update_option( 'blogdescription', $site_description );

        genesis_update_settings(array(
            'name' => $name,
            'street' => $street,
            'locality' => $locality,
            'region' => $region,
            'code' => $code,
            'telephone' => $telephone,
        ));
        
        
        /** change peramlink structure **/
        update_option('permalink_structure','/%postname%/');
        
    }

    public function view() {
        // Defaults
        $checked = genesis_get_option('json-footer-option');
        ?>
        <div>
            <label for="site-name">Site Name: </label> <br>
            <input type="text" name="site-name" id="site-name" value="<?php bloginfo("name") ?>" size="50"/><br>
            <label for="site-description">Site Description: </label> <br>
            <input type="text" name="site-description" id="site-description" value="<?php bloginfo("description") ?>" size="50"/><br>
        </div>
        <p>
            <input type="checkbox" name="<?php echo GENESIS_SETTINGS_FIELD; ?>[json-footer-option]" value="1" <?php checked($checked) ?>/> JSON footer
        </p>
        <p>

            Organization Name:<br> <input type="text"  name="name" value="<?php echo genesis_get_option('name'); ?>" size="50" /><br>
            Street Address:<br> <input type="text" name="street" value="<?php echo genesis_get_option('street') ?>" size="50" /><br>
            Address Locality:<br> <input type="text" name="locality" value="<?php echo genesis_get_option('locality') ?>" size="50" /><br>
            Address Region:<br> <input type="text" name="region" value="<?php echo genesis_get_option('region') ?>" size="50" /><br>
            Postal Code: <br><input type="text" name="code" value="<?php echo genesis_get_option('code') ?>" size="50" /><br>
            Telephone: <br><input type="text" name="telephone" value="<?php echo genesis_get_option('telephone') ?>" size="50" /><br>

        </p>

        <?php
    }

}
