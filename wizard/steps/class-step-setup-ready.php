<?php
include_once 'class-wizard-step.php';

class Step_Setup_Ready extends Wizard_Step {

    public static $name = 'setup_ready';

    
    public function handle() {
        
    }

    public function view() {
        ?>
        <h1><?php echo 'Your Site Is Ready!' ?></h1>
        <?php
    }

}
