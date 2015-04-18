<select name="post_prefix_input" id="post-prefix-input">
	<option value="0"<?php if (empty ($prefix_meta)) echo " selected"; ?>>-No Prefix-</option>
	<?php
		if (!empty ($prefix)){
			foreach ($prefix as $name){
				$html = '<option value="' . $name['id'] . '"';
				if ($prefix_meta == $name['id']) $html .= " selected";
				$html .= '>' . $name['prefix'] . '</option>';
				echo $html;
			}
		}
	?>
</select>