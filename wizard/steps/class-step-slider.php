<?php
include_once 'class-wizard-step.php';

class Step_Slider extends Wizard_Step {

    public static $name = "slider";

    public function handle() {
        global $ep_the_wizard;

        $fcpt_arr = array();

        if (isset($_POST['fcpt'])) {
            $fcpt_arr = $_POST['fcpt'];
        }

        foreach ($fcpt_arr as $key => $fcpt) {
            $page_attr = array(
                'post_title' => sanitize_text_field($fcpt["title"]),
                'post_status' => 'publish',
                'post_type' => 'featured_cpt',
                'post_content' => sanitize_text_field($fcpt["content"])
            );

            $post_id = wp_insert_post($page_attr);
            update_post_meta($post_id, "redirect", esc_url($fcpt["link"]));
            set_post_thumbnail($post_id, $ep_the_wizard->get_placeholder_attachment_id());
        }

        /** change slider option to fcpt * */
        $slider_options = get_option('genesis_responsive_slider_settings');
        if ($slider_options) {
            $slider_options["post_type"] = "featured_cpt";
            update_option('genesis_responsive_slider_settings', $slider_options);
        }
        
    }

    public function view() {

        $query = new WP_Query(array('post_type' => ['page', 'post', 'services'], 'post_status' => 'publish', 'posts_per_page' => '-1'));
        ?>
        <h2> choose what to add to featured cpt: </h2>

        <div>
        <?php if ($query->have_posts()): ?>
                <table>
                    <thead>
                        <tr>
                            <th> include post </th>
                            <th> post name </th>
                            <th> post type </th>
                            <th> featured cpt title </th>
                            <th> featured cpt content </th>
                        </tr>
                    </thead>
                    <tbody>
            <?php while ($query->have_posts()): $query->the_post() ?>
                            <tr>
                                <td><input type="checkbox" name="fcpt[<?php the_ID() ?>][checkbox]" class="check_fcpt"></td>
                                <td><?php the_title() ?></td>
                                <td><?php echo $query->post->post_type ?></td>
                                <td>
                                    <input type="text" name="fcpt[<?php the_ID() ?>][title]" disabled="" class="fcpt_title">
                                    <input type="hidden" name="fcpt[<?php the_ID() ?>][link]" value="<?php the_permalink() ?>" disabled="" class="fcpt_link">
                                </td>
                                <td><textarea name="fcpt[<?php the_ID() ?>][content]" disabled="" class="fcpt_content"></textarea></td>
                            </tr>
            <?php endwhile ?>
                    </tbody>
                </table>

                <script>
                    jQuery(document).ready(function ($) {
                        $(".check_fcpt").click(function () {
                            if ($(this).is(":checked")) {
                                $(this).closest("tr").find(".fcpt_title").removeAttr("disabled");
                                $(this).closest("tr").find(".fcpt_link").removeAttr("disabled");
                                $(this).closest("tr").find(".fcpt_content").removeAttr("disabled");
                            }
                            else {
                                $(this).closest("tr").find(".fcpt_title").attr("disabled", "true").val("");
                                $(this).closest("tr").find(".fcpt_content").attr("disabled", "true").val("");
                                $(this).closest("tr").find(".fcpt_link").attr("disabled", "true");
                            }
                        });
                    });
                </script>
        <?php endif; ?>
        </div>
            <?php
        }

    }
    