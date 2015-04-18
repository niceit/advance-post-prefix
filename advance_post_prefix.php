<?php
/*
Plugin Name: Advance Post Prefix
Version: 1.1.1
Author: Tran Tuan
Plugin URI: http://blog-it.info
Author URI: http://blog-it.info
Description: Add custom post prefix to the post title, easily to use and manage. Display post prefix on add new and edit post page, supported quick post also on admin area.
*/
function wpp_register_menu(){
	add_menu_page ('Advance Post Prefix', 'Post Prefix',
					'manage_options', 'advance-post-prefix',
					'wpp_dashboard', plugins_url('/post-prefix-ico.png', __FILE__));
	add_submenu_page('advance-post-prefix', 'Add new', 'Add new', 'manage_options', 'add-prefix', 'wpp_addnew_prefix');
}

//Hook before post will be saved
function wpp_before_save($post_id){
	if (isset ($_POST['post_prefix_input']) && $_POST['post_prefix_input'] != 0){
		$post_meta = get_post_meta ($post_id, 'prefix', true);
		if (empty ($post_meta))
			add_post_meta ($post_id, 'prefix', $_POST['post_prefix_input']);
		else{
			update_post_meta ($post_id, 'prefix', $_POST['post_prefix_input']);
		}
	}
}

function are_we_in($f) {
	$aray = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
	foreach($aray as $a) {	$aa[] = $a["function"];	}
	if(in_array($f,$aa)) { return true; }
	return false;
}

//Filter before display the post
function wpp_rewrite_title($title){
	global $wpdb;
	global $post;
	if(!are_we_in("the_title")) { return $title; }
	$prefix_meta = get_post_meta ($post->ID, 'prefix', true);
	if (!empty ($prefix_meta)){
		$sql = "SELECT * FROM {$wpdb->base_prefix}post_prefix WHERE id = {$prefix_meta}";
		$prefix = $wpdb->get_row ($sql, ARRAY_A);
		if (!empty ($prefix))
			$title = "[" . $prefix['prefix'] . "] " . $title;
	}
	return $title;
}


//Management post prefix page
function wpp_dashboard(){
	global $wpdb;

	//Edit action
	if (isset ($_GET['action']) && $_GET['action'] == 'edit'){ wpp_edit_prefix(); }

	//Dashboard
	else {
		if (isset ($_POST['submit_form'])){
			$result = false;
			if (!empty ($_POST['prefix'])){
				foreach ($_POST['prefix'] as $prefix){
					//Delete from post meta
					$sql = "DELETE FROM {$wpdb->base_prefix}postmeta WHERE meta_key = 'prefix' AND meta_value = {$prefix}";
					$result = $wpdb->query ($sql);

					//Delete on prefix table
					$sql = "DELETE FROM {$wpdb->base_prefix}post_prefix WHERE id = " . $prefix;
					$result = $wpdb->query ($sql);
					if (!$result){
						$respond_msg = "Delete in DB failed!";
						break;
					}
				}
			}
			else $respond_msg = "No prefix item selected";
		}
		$sql = "SELECT * FROM {$wpdb->base_prefix}post_prefix WHERE 1 ORDER BY id DESC";
		$prefix = $wpdb->get_results ($sql, ARRAY_A);
		include (dirname (__FILE__) . '/wpp_dashboard.php');
	}
}

//Add prefix selection before the post title
function wpp_add_prefix(){
	$post_id = $_GET['post'];
	$script = '<script type="text/javascript">
					jQuery (function (){
						jQuery ("input[name=post_title]").after(\'<div class="post-prefix" style="margin-left: 5px; height: 30px; padding-top: 5px;"><b>Choose prefix for title</b><span style="margin-left: 10px;"><img src="' . get_bloginfo('wpurl') . '/wp-admin/images/wpspin_light.gif" /></span></div>\');
						jQuery (".post-prefix span").load("' . get_bloginfo('wpurl') . '/wp-admin/admin-ajax.php?action=fetchprefix&post_id=' . $post_id . '");
					});
				</script>
				';
	echo $script;
}

//Fetch the prefixs
function wpp_fetch_data(){
	global $wpdb;
	$post_id = $_GET['post_id'];
	$prefix_meta = get_post_meta ($post_id, 'prefix', true);
	$sql = "SELECT * FROM `{$wpdb->base_prefix}post_prefix` WHERE 1 ORDER BY `id` DESC";
	$prefix = $wpdb->get_results ($sql, ARRAY_A);
	include (dirname (__FILE__) . '/prefix_choose.php'); die();
}

//Add new post prefix
function wpp_addnew_prefix(){
	if (isset ($_POST['prefix_name'])){
		$result = false;
		if ($_POST['prefix_name'] != ""){
			global $wpdb;
			$prefix = $wpdb->get_results ("SELECT * FROM {$wpdb->base_prefix}post_prefix WHERE prefix = '" . $_POST['prefix_name'] . "'", ARRAY_A);
			if (empty ($prefix)){
				$desciption = (!empty ($_POST['prefix_description'])) ? $_POST['prefix_description'] : '';
				$result = $wpdb->insert ($wpdb->base_prefix . 'post_prefix',
											array('prefix' => $_POST['prefix_name'],
													'description' => $desciption,
													'date' => current_time('mysql'))
										);
				if (!$result) $respond_msg = "Unable to insert new record to database";
			}
			else $respond_msg = "Prefix name exist! Please try another!";
		}
		else $respond_msg = "Please input prefix name";
	}
	include (dirname (__FILE__) . '/wpp_add.php');
}

//Edit post prefix
function wpp_edit_prefix(){
	$prefix_id = $_GET['id'];
	if (!empty ($prefix_id) || is_numeric($prefix_id)){
		global $wpdb;
		if (isset ($_POST['prefix_name']) && !empty ($_POST['prefix_name'])){
			if (empty ($_POST['prefix_description']))
				$sql = "UPDATE {$wpdb->base_prefix}post_prefix SET prefix = '" . $_POST['prefix_name'] . "' WHERE id = {$prefix_id}";
			else $sql = "UPDATE {$wpdb->base_prefix}post_prefix SET prefix = '" . $_POST['prefix_name'] . "', description = '" . $_POST['prefix_description'] . "' WHERE id = {$prefix_id}";
			$result = $wpdb->query ($sql);
			if (!$result) $respond_msg = "Update prefix failed!";
		}
		$prefix = $wpdb->get_row ("SELECT * FROM {$wpdb->base_prefix}post_prefix WHERE id = {$prefix_id}", ARRAY_A);
		$mode = "Edit";
		include (dirname (__FILE__) . '/wpp_add.php');
	}
	else header ('location: ' . $_SERVER['HTTP_REFERER']);
}

//Init data when activation
function wpp_init_data(){
	global $wpdb;
	$sql = "CREATE TABLE IF NOT EXISTS `{$wpdb->base_prefix}post_prefix` (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`prefix` varchar(128) NOT NULL,
				`description` varchar(255) NOT NULL,
				`date` DATETIME NOT NULL,
				PRIMARY KEY (`id`)
			)ENGINE=MyISAM  DEFAULT CHARSET=utf8;";
	$wpdb->query ($sql);
}

//Remove data when uninstall plugin
function wpp_remove_data(){
	global $wpdb;
	$sql = "DROP TABLE `{$wpdb->base_prefix}post_prefix`";
	$wpdb->query ($sql);
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
?>