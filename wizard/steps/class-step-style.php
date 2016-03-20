<?php
include_once 'class-wizard-step.php';

/**
 * Description of class-step-ss
 *
 * @author jetweb1
 */
class Step_Style extends Wizard_Step {

    public static $name = 'style';

    public function handle() {

        $style = sanitize_text_field($_POST['style']);
        $color_style = sanitize_text_field($_POST['color']);


        genesis_update_settings(array(
            'style_selection' => $color_style,
            'ver-style' => $style,
        ));
    }

    public function view() {
        $style = genesis_get_option('ver_style');
        $color_style = genesis_get_option('style_selection');
        ?>
        <h1><?php echo ' choose your style' ?></h1>
            <table class="form-table">
                <tr>
                    <th scope="row"><label><?php echo 'choose your favorit style version for your site' ?></label></th>
                    <td>
                        <select id="weight_unit" name="style" class="wc-enhanced-select">
                            <option value="ver-one" <?php selected($style, 'ver-one'); ?>><?php echo 'version one' ?></option>
                            <option value="ver-two" <?php selected($style, 'ver-two'); ?>><?php echo 'version two' ?></option>
                            <option value="ver-three" <?php selected($style, 'ver-three'); ?>><?php echo 'version three'; ?></option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label><?php echo 'choose your favorit color for your site' ?></label></th>
                    <td>
                        <select id="dimension_unit" name="color" class="wc-enhanced-select">
                            <option value="outreach-pro-blue" <?php selected($color_style, 'outreach-pro-blue'); ?>><?php echo 'blue' ?></option>
                            <option value="outreach-pro-orange" <?php selected($color_style, 'outreach-pro-orange'); ?>><?php echo 'orange' ?></option>
                            <option value="outreach-pro-purple" <?php selected($color_style, 'outreach-pro-purple'); ?>><?php echo 'purple'; ?></option>
                            <option value="outreach-pro-red" <?php selected($color_style, 'outreach-pro-red'); ?>><?php echo 'red'; ?></option>
                        </select>
                    </td>
                </tr>


            </table>
        <?php
    }

}
