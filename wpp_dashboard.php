<h2>Advance Post Prefix Management Page</h2>
<?php
	if (isset ($result)){
		if ($result) echo '<div id="message" class="updated"><p>Prefix(s) deleted successfully!</p></div>';
		else echo '<div id="message" class="error"><p>' . $respond_msg . '</p></div>';
	}
?>
<form name="frm_prefix_manage" method="post" action="" onsubmit="return validateForm();">
<table class="wp-list-table widefat fixed wpp-prefix" cellspacing="0">
	<thead>
		<tr>
			<th scope="col" class="manage-column column-cb check-column"><input type="checkbox" /></th>
			<th scope="col" class="manage-column column-prefix-name">Prefix Name</th>
			<th scope="col" class="manage-column column-prefix-description">Description</th>
            <th scope="col" class="manage-column column-prefix-number-post">Number of articles</th>
			<th scope="col" class="manage-column column-prefix-date">Date added</th>
            <th scope="col" class="manage-column column-prefix-delete">Delete</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($prefix as $key => $value) : ?>
		<tr>
			<th scope="col" class="check-column"><input type="checkbox" name="prefix[]" value="<?php echo $value['id']; ?>" /></th>
			<td class="column-prefix-name"><a href="<?php echo bloginfo ('wpurl'); ?>/wp-admin/admin.php?page=advance-post-prefix&action=edit&id=<?php echo $value['id']; ?>" title="Edit prefix"><?php echo $value['prefix']; ?></a></td>
			<td class="column-prefix-description"><?php echo $value['description']; ?></td>
            <td class="column-prefix-number-post">
                <a href="/wp-admin/edit.php"><?php
                    $query =  $wpdb->get_results ("SELECT COUNT(*) FROM {$wpdb->base_prefix}postmeta WHERE meta_key = 'prefix' AND meta_value = " . $value['id'], ARRAY_A);
                    $count_post = $query[0]['COUNT(*)'];
                    echo $count_post;
                ?></a>
            </td>
			<td class="column-prefix-date">
				<?php
					$date = new DateTime($value['date']);
					echo $date->format("d/m/Y");
				?>
			</td>
            <td>
                <a href="/wp-admin/admin.php?page=advance-post-prefix&prefix_id=<?php echo $value['id']; ?>"
                    <?php  if($count_post>0) echo 'onclick="return confirm(\''.$value['prefix'].' has post, are you sure? \');"' ?>  >[Delete]</a>
            </td>
		</tr>
		<?php endforeach; ?>
	</tbody>
</table>
<p class="description">Please carefull! If you choose delete prefix, it will be deleted without any confirmation under this version! Make sure you trusted the action!</p>
<p><input type="submit" name="submit_form" value="Delete" class="button-primary" /> <a href="<?php echo get_bloginfo('wpurl') . '/wp-admin/admin.php?page=add-prefix'; ?>" class="button-primary">Add new</a></p>
</form>
<script type="text/javascript">
	function validateForm(){
		var confirmBox = confirm("Are you sure delete selected item(s)?");
		if (confirmBox) return true;
		else return false;
	}
</script>