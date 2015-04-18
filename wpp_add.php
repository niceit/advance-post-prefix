<h2><?php if (isset ($mode) && $mode == 'Edit') echo "Edit"; else echo "Add new"; ?> prefix</h2>
<hr />
<?php
	if (isset ($result)){
		if (!$result) echo '<div id="message" class="error"><p>' . $respond_msg . '</p></div>';
		else{
			if ($mode == "Edit") echo '<div id="message" class="updated"><p>Prefix edited! Click <a href="' . get_bloginfo ('wpurl') . '/wp-admin/admin.php?page=advance-post-prefix" title="Advance post prefix">here</a> to return management page.</p></div>';
			else  echo '<div id="message" class="updated"><p>Prefix added! Click <a href="' . get_bloginfo ('wpurl') . '/wp-admin/admin.php?page=advance-post-prefix" title="Advance post prefix">here</a> to return management page.</p></div>';
		}
	}
?>
<form name="frm_add_prefix" action="" method="post" onsubmit="return validateAddForm();">
<table class="form-table">
	<tbody>
		<tr valign="top">
			<th scope="row"><label for="prefix_name">Prefix name</label></th>
			<td>
				<input type="text" name="prefix_name" id="prefix-name" class="regular-text" <?php if (isset ($mode) && $mode == "Edit") echo 'value="' . $prefix['prefix'] . '"'; ?>  />
				<p class="description">Enter your post prefix name here. Ex: Wordpress, Audio,...
			</td>
		</tr>
		<tr valign="top">
			<th scope="row"><label for="prefix_description">Description</label></th>
			<td>
				<textarea style="width: 25em; height: 120px; resize: none;" name="prefix_description" id="prefix-description"><?php if (isset ($mode) && $mode == "Edit") echo $prefix['description']; ?></textarea>
				<p class="description">Enter some description for this prefix. (Ignore if you dont want)</p>
			</td>
		</tr>
	</tbody>
</table>
<p class="submit"><input type="submit" class="button-primary" name="submit" value="<?php if (isset ($mode) && $mode == "Edit") echo "Save changes"; else echo "Add and save"; ?>" /></p>
</form>
<script type="text/javascript">
	function validateAddForm(){
		if (jQuery ("#prefix-name").val() == ""){
			alert ("Please input the prefix name!");
			jQuery ("#prefix-name").focus();
			return false;
		}
		return true;
	}
</script>