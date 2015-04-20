<?php
    /*
    Plugin Name: Advance Post Prefix
    Version: 1.1.1
    Author: Tran Tuan
    Plugin URI: http://blog-it.info
    Author URI: http://blog-it.info
    Description: Add custom post prefix to the post title, easily to use and manage. Display post prefix on add new and edit post page, supported quick post also on admin area.
    */
    function wpp_register_menu () {
        add_menu_page ('Advance Post Prefix', 'Post Prefix', 'manage_options', 'advance-post-prefix', 'wpp_dashboard', plugins_url ('/post-prefix-ico.png', __FILE__));
        add_submenu_page ('advance-post-prefix', 'Add new', 'Add new', 'manage_options', 'add-prefix', 'wpp_addnew_prefix');
        add_submenu_page ('advance-post-prefix', 'Settings', 'Settings', 'manage_options', 'wpp-settings', 'wpp_settings');
    }

    //Hook before post will be saved
    function wpp_before_save ($post_id) {
        if (isset ($_POST['post_prefix_input']) && $_POST['post_prefix_input'] != 0) {
            $post_meta = get_post_meta ($post_id, 'prefix', true);
            if (empty ($post_meta))
                add_post_meta ($post_id, 'prefix', $_POST['post_prefix_input']);
            else {
                update_post_meta ($post_id, 'prefix', $_POST['post_prefix_input']);
            }
        }
    }

    function are_we_in ($f) {
        $aray = debug_backtrace (DEBUG_BACKTRACE_IGNORE_ARGS);
        foreach ($aray as $a) {
            $aa[] = $a["function"];
        }
        if (in_array ($f, $aa)) {
            return true;
        }

        return false;
    }

    //Filter before display the post
    function wpp_rewrite_title ($title) {
        global $wpdb;
        global $post;
        if (!are_we_in ("the_title")) {
            return $title;
        }

        if(!isset($_GET['prefix_id']) &&  get_option('wp_action_prefix')==0 )
        {
            if (get_option ('wp_post_id_prefix') && get_option ('wp_post_id_prefix') != '') {
                $prefix_meta = get_post_meta ($post->ID, 'prefix', true);
                if (!empty ($prefix_meta)) {
                    $sql = "SELECT * FROM {$wpdb->base_prefix}post_prefix WHERE id = {$prefix_meta}";
                    $prefix = $wpdb->get_row ($sql, ARRAY_A);
                    if (!empty ($prefix)){
                        $prefix_title = get_option('wp_prefix_title_before').$prefix['prefix'].get_option('wp_prefix_title_after').get_option('wp_prefix_title_center');

                        $title = " <span class='prefix' >[" . $prefix_title . "]</span>" . $title . '<script type="text/javascript">
                                jQuery("span.prefix").each(function(){
                                    var jParent = jQuery(this).parent("a");
                                    var link = jQuery(this).attr("onclick");
                                    jQuery(this).remove();
                                    jParent.before(\'<a href="' . esc_url (home_url ('/')) . '?page_id=' . get_option ('wp_post_id_prefix') . '&prefix_id=' . $prefix_meta . '">' . $prefix_title . '</a>\');
                                });
                        </script>';
                    }
                }
            }
        }

        return $title;
    }

    //Management post prefix page
    function wpp_dashboard () {
        global $wpdb;
        //Edit action
        if (isset ($_GET['action']) && $_GET['action'] == 'edit') {
            wpp_edit_prefix ();
        }
        //Dashboard
        else {
            if (isset ($_POST['submit_form'])) {
                $result = false;
                if (!empty ($_POST['prefix'])) {
                    foreach ($_POST['prefix'] as $prefix) {
                        //Delete from post meta
                        $sql = "DELETE FROM {$wpdb->base_prefix}postmeta WHERE meta_key = 'prefix' AND meta_value = {$prefix}";
                        $result = $wpdb->query ($sql);
                        //Delete on prefix table
                        $sql = "DELETE FROM {$wpdb->base_prefix}post_prefix WHERE id = " . $prefix;
                        $result = $wpdb->query ($sql);
                        if (!$result) {
                            $respond_msg = "Delete in DB failed!";
                            break;
                        }
                    }
                }
                else $respond_msg = "No prefix item selected";
            }
            $sql = "SELECT * FROM {$wpdb->base_prefix}post_prefix WHERE 1 ORDER BY id DESC";
            $prefix = $wpdb->get_results ($sql, ARRAY_A);

            // Delete prefix recol
            if (isset ($_GET['prefix_id'])) {
                //Delete from post meta
                $sql = "DELETE FROM {$wpdb->base_prefix}postmeta WHERE meta_key = 'prefix' AND meta_value = {$_GET['prefix_id']}";
                $result = $wpdb->query ($sql);
                //Delete on prefix table
                $sql = "DELETE FROM {$wpdb->base_prefix}post_prefix WHERE id = " . $_GET['prefix_id'];
                $result = $wpdb->query ($sql);
                if (!$result) {
                    $respond_msg = "Delete in DB failed!";
                }
                ?>
                <script>
                    window.location.href = '/wp-admin/admin.php?page=advance-post-prefix';
                </script>
                <?php
            }
            include (dirname (__FILE__) . '/wpp_dashboard.php');
        }
    }

    //Setting page
    function wpp_settings(){
        if(isset($_POST['submit'])){
            update_option ('wp_prefix_title_before',    $_POST['wp_prefix_title_before']);
            update_option ('wp_prefix_title_after',     $_POST['wp_prefix_title_after']);
            update_option ('wp_prefix_title_center',    $_POST['wp_prefix_title_center']);
            update_option ('wp_action_prefix',          $_POST['wp_action_prefix']);
        }
        include (dirname (__FILE__) . '/wpp_settings.php');

    }

    /*
     * Add column prefix at wordpress list post table
     * */
    function wpp_prefix_column_head ($defaults) {
        $column_meta = array ('post_prefix' => 'Post Prefix');
        $defaults = array_slice ($defaults, 0, 1, true) + $column_meta + array_slice ($defaults, 1, null, true);

        return $defaults;
    }

    function wpp_prefix_column_value ($column_name, $post_ID) {
        global $wpdb;
        if ($column_name == 'post_prefix') {
            $prefix_meta = get_post_meta ($post_ID, 'prefix', true);
            if (!empty($prefix_meta)) {
                $sql = "SELECT * FROM {$wpdb->base_prefix}post_prefix WHERE id = {$prefix_meta}";
                $prefix = $wpdb->get_row ($sql, ARRAY_A);
                echo '<a href="#" class="editinline" title="Click to quick edit"><b style="color: #21759B;">' . $prefix['prefix'] . '</b></a>';
            }
        }
    }

    add_filter ('manage_posts_columns', 'wpp_prefix_column_head', 10, 2);
    add_action ('manage_posts_custom_column', 'wpp_prefix_column_value', 10, 2);

    //Add prefix selection before the post title
    function wpp_add_prefix () {
        $post_id = $_GET['post'];
        $script = '<script type="text/javascript">
					jQuery (function (){
						jQuery ("input[name=post_title]").after(\'<div class="post-prefix" style="margin-left: 5px; height: 30px; padding-top: 5px;"><b>Choose prefix for title</b><span style="margin-left: 10px;"><img src="' . get_bloginfo ('wpurl') . '/wp-admin/images/wpspin_light.gif" /></span></div>\');
						jQuery (".post-prefix span").load("' . get_bloginfo ('wpurl') . '/wp-admin/admin-ajax.php?action=fetchprefix&post_id=' . $post_id . '");
					});
				</script>
				';
        echo $script;
    }

    //Fetch the prefixs
    function wpp_fetch_data () {
        global $wpdb;
        $post_id = $_GET['post_id'];
        $prefix_meta = get_post_meta ($post_id, 'prefix', true);
        $sql = "SELECT * FROM `{$wpdb->base_prefix}post_prefix` WHERE 1 ORDER BY `id` DESC";
        $prefix = $wpdb->get_results ($sql, ARRAY_A);
        include (dirname (__FILE__) . '/prefix_choose.php');
        die();
    }

    //Add new post prefix
    function wpp_addnew_prefix () {
        if (isset ($_POST['prefix_name'])) {
            $result = false;
            if ($_POST['prefix_name'] != "") {
                global $wpdb;
                $prefix = $wpdb->get_results ("SELECT * FROM {$wpdb->base_prefix}post_prefix WHERE prefix = '" . $_POST['prefix_name'] . "'", ARRAY_A);
                if (empty ($prefix)) {
                    $desciption = (!empty ($_POST['prefix_description'])) ? $_POST['prefix_description'] : '';
                    $result = $wpdb->insert ($wpdb->base_prefix . 'post_prefix', array ('prefix' => $_POST['prefix_name'], 'description' => $desciption, 'date' => current_time ('mysql')));
                    if (!$result)
                        $respond_msg = "Unable to insert new record to database";
                }
                else $respond_msg = "Prefix name exist! Please try another!";
            }
            else $respond_msg = "Please input prefix name";
        }
        include (dirname (__FILE__) . '/wpp_add.php');
    }

    //Edit post prefix
    function wpp_edit_prefix () {
        $prefix_id = $_GET['id'];
        if (!empty ($prefix_id) || is_numeric ($prefix_id)) {
            global $wpdb;
            if (isset ($_POST['prefix_name']) && !empty ($_POST['prefix_name'])) {
                if (empty ($_POST['prefix_description']))
                    $sql = "UPDATE {$wpdb->base_prefix}post_prefix SET prefix = '" . $_POST['prefix_name'] . "' WHERE id = {$prefix_id}";
                else $sql = "UPDATE {$wpdb->base_prefix}post_prefix SET prefix = '" . $_POST['prefix_name'] . "', description = '" . $_POST['prefix_description'] . "' WHERE id = {$prefix_id}";
                $result = $wpdb->query ($sql);
                if (!$result)
                    $respond_msg = "Update prefix failed!";
            }
            $prefix = $wpdb->get_row ("SELECT * FROM {$wpdb->base_prefix}post_prefix WHERE id = {$prefix_id}", ARRAY_A);
            $mode = "Edit";
            include (dirname (__FILE__) . '/wpp_add.php');
        }
        else header ('location: ' . $_SERVER['HTTP_REFERER']);
    }

    //Init data when activation
    function wpp_init_data () {
        global $wpdb;
        $sql = "CREATE TABLE IF NOT EXISTS `{$wpdb->base_prefix}post_prefix` (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`prefix` varchar(128) NOT NULL,
				`description` varchar(255) NOT NULL,
				`date` DATETIME NOT NULL,
				PRIMARY KEY (`id`)
			)ENGINE=MyISAM  DEFAULT CHARSET=utf8;";
        $wpdb->query ($sql);
        $post = array ('post_title' => 'Page Post prefix', 'post_content' => '[prefix_content]', 'post_status' => 'publish', 'post_author' => 1, 'post_type' => 'page', 'post_excerpt' => 'post_excerpt');
        // Insert the post into the database
        $post_id = wp_insert_post ($post);
        update_option ('wp_post_id_prefix', $post_id);
        update_option ('wp_prefix_title_before',    '[');
        update_option ('wp_prefix_title_after',     ']');
        update_option ('wp_prefix_title_center',    '-');
        update_option ('wp_action_prefix',            0);
    }

    //Remove data when uninstall plugin
    function wpp_remove_data () {
        global $wpdb;
        $sql = "DROP TABLE `{$wpdb->base_prefix}post_prefix`";
        $wpdb->query ($sql);
        wp_delete_post (get_option ('wp_post_id_prefix'), true);
        delete_option ('wp_post_id_prefix');
        delete_option ('wp_prefix_title_before');
        delete_option ('wp_prefix_title_after');
        delete_option ('wp_prefix_title_center');
        delete_option ('wp_action_prefix');
    }

    function load_post_prefix () {
        $arr_prefix = array ();
        if (isset($_GET['page_id']) && $_GET['page_id'] == get_option ('wp_post_id_prefix')) {
            if (isset($_GET['prefix_id'])) {
                global $wpdb;
                $sql = "select * from `{$wpdb->base_prefix}postmeta`  where meta_key = 'prefix' and meta_value = " . $_GET['prefix_id'];
                $prefix_meta = $wpdb->get_results ($sql);
                foreach ($prefix_meta as $val) {
                    $arr_prefix[] = $val->post_id;
                }
            }
            else {
            }
        }
        else {
            echo '<p>There are no posts in this prefix.</p>';
        }
        $args = array ('post_type' => 'post', 'post__in' => $arr_prefix, 'paged' => (get_query_var ('paged') ? get_query_var ('paged') : 1), 'posts_per_page' => get_option ('posts_per_page'));
        $wp_query = new WP_Query($args);
        while ($wp_query->have_posts ()) : $wp_query->the_post ();
            get_template_part ('content', '');
        endwhile;
        if ($wp_query->max_num_pages > 1) { // check if the max number of pages is greater than 1  ?>
            <nav class="prev-next-posts">
            <div class="prev-posts-link">
                <?php echo get_next_posts_link ('Next page', $wp_query->max_num_pages); // display older posts link ?>
            </div>
            <div class="next-posts-link">
                <?php echo get_previous_posts_link ('Previous page'); // display newer posts link ?>
            </div>
        </nav>
        <?php }
    }
    /* Init components */
    add_action ('admin_menu', 'wpp_register_menu');
    add_action ('save_post', 'wpp_before_save');
    add_filter ('the_title', 'wpp_rewrite_title');
    register_activation_hook (__FILE__, 'wpp_init_data');
    register_uninstall_hook (__FILE__, 'wpp_remove_data');
    add_action ('admin_head', 'wpp_add_prefix');

    /* Ajax Callback */
    add_action ('wp_ajax_fetchprefix', 'wpp_fetch_data');
    wp_enqueue_script("wpp_frontend", plugin_dir_url(_FILE_) . 'advance-post-prefix/wpp_main.js', false, '1.1.1');
    add_shortcode ('prefix_content', 'load_post_prefix');
?>
