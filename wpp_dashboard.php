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
			<th scope="col" class="manage-column column-prefix-date">Date added</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($prefix as $key => $value) : ?>
		<tr>
			<th scope="col" class="check-column"><input type="checkbox" name="prefix[]" value="<?php echo $value['id']; ?>" /></th>
			<td class="column-prefix-name"><a href="<?php echo bloginfo ('wpurl'); ?>/wp-admin/admin.php?page=advance-post-prefix&action=edit&id=<?php echo $value['id']; ?>" title="Edit prefix"><?php echo $value['prefix']; ?></a></td>
			<td class="column-prefix-description"><?php echo $value['description']; ?></td>
			<td class="column-prefix-date">
				<?php
					$date = new DateTime($value['date']);
					echo $date->format("d/m/Y");
				?>
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
		alert ("12345");
		var confirmBox = confirm("Are you sure delete selected item(s)?");
		if (confirmBox) return true;
		else return false;
	}
</script>