<?php
include_once 'class-wizard-step.php';

class Step_Menus extends Wizard_Step {

    public static $name = "menus";

    public function handle() {
        $pages = $_POST["pages"];

        $menu_name = 'Main Menu';
        $menu_exists = wp_get_nav_menu_object($menu_name);

        if (!$menu_exists) {
            $menu_id = wp_create_nav_menu($menu_name);

            wp_update_nav_menu_item($menu_id, 0, array(
                'menu-item-title' => __('Home'),
                'menu-item-classes' => 'home',
                'menu-item-url' => home_url('/'),
                'menu-item-status' => 'publish'));

            if (isset($_POST["services"])) {

                $services_menu_item = wp_update_nav_menu_item($menu_id, 0, array(
                    'menu-item-title' => __('services'),
                    'menu-item-url' => home_url('/services'),
                    'menu-item-status' => 'publish'));


                $services = get_posts(array('post_type' => 'services', 'post_per_page' => -1, 'post_status' => 'publish'));

                foreach ($services as $key => $service) {

                    wp_update_nav_menu_item($menu_id, 0, array(
                        'menu-item-title' => __($service->post_title),
                        'menu-item-url' => $service->guid,
                        'menu-item-status' => 'publish',
                        'menu-item-parent-id' => $services_menu_item));
                }
            }

            foreach ($pages as $key => $page_id) {
                $page = get_post($page_id);

                wp_update_nav_menu_item($menu_id, 0, array(
                    'menu-item-title' => __($page->post_title),
                    'menu-item-url' => $page->guid,
                    'menu-item-status' => 'publish'));
            }

            $locations = get_theme_mod('nav_menu_locations');
            $locations["primary"] = $menu_id;
            set_theme_mod("nav_menu_locations", $locations);
        }

        $menu_pages = 'pages_menu';
        $menu_pages_exists = wp_get_nav_menu_object($menu_pages);

        if (!$menu_pages_exists) {
            
            $menu_id = wp_create_nav_menu($menu_pages);

            foreach ($pages as $key => $page_id) {
                $page = get_post($page_id);

                wp_update_nav_menu_item($menu_id, 0, array(
                    'menu-item-title' => __($page->post_title),
                    'menu-item-url' => $page->guid,
                    'menu-item-status' => 'publish'));
            }
        }
        
        $menu_services = 'services_menu';
        $menu_services_exists = wp_get_nav_menu_object($menu_services);

        if (!$menu_services_exists) {
            
            $menu_id = wp_create_nav_menu($menu_services);

            $services = get_posts(array('post_type' => 'services', 'post_per_page' => -1, 'post_status' => 'publish'));
            
            foreach ($services as $key => $service) {

                wp_update_nav_menu_item($menu_id, 0, array(
                    'menu-item-title' => __($service->post_title),
                    'menu-item-url' => $service->guid,
                    'menu-item-status' => 'publish'));
            }
        }
        
        
    }

    public function view() {
        $query = new WP_Query(array('post_type' => 'page', 'post_status' => 'publish'));
        ?>

        <h2> choose what to add to the main menu: </h2>

        <?php if ($query->have_posts()): ?>
            <ul>
                <?php while ($query->have_posts()): $query->the_post() ?>
                    <li>
                        <label for="page-<?php echo $query->post->post_name ?>">
                            <input type="checkbox" name="pages[<?php echo $query->post->post_name ?>]" id="page-<?php echo $query->post->post_name ?>" value="<?php the_ID() ?>">
                            <?php the_title() ?>
                        </label>
                    </li>
                <?php endwhile ?>
                <li>
                    <label for="services">
                        <input type="checkbox" name="services" id="services" value="1">
                        Services
                    </label>
                </li>
            </ul>
            <?php
        endif;
    }

}
