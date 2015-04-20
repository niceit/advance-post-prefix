<style type="text/css">
    .prefix_title input{
        width:50px;
    }
</style>
<div class="wrap" xmlns="http://www.w3.org/1999/html">
    <div id="icon-options-general" class="icon32"><br></div><h2>Advance Post Prefix Settings</h2>
        <form method="post" action="">
            <table class="form-table">
                <tbody>
                <tr valign="top">
                    <th scope="row"><label for="action_prefix">Status</label></th>
                    <td>
                        <fieldset>
                            <label title="Activate"><input <?php if(get_option('wp_action_prefix')==0) echo 'checked="checked"'; ?> type="radio" name="wp_action_prefix" value="0" > <span>Activate</span></label><br>
                            <label title="Deactivate"><input <?php if(get_option('wp_action_prefix')==1) echo 'checked="checked"'; ?> type="radio" name="wp_action_prefix" value="1"> <span>Deactivate</span></label>

                        </fieldset>
                    </td>
                </tr>
                    <tr valign="top">
                        <th scope="row"><label for="prefix_title_format">Title Prefix format</label></th>
                        <td class="prefix_title">
                            Before prefix title: <input value="<?php echo get_option('wp_prefix_title_before'); ?>" name="wp_prefix_title_before" type="text" id="wp_prefix_title_before" >
                            After prefix title:  <input value="<?php echo get_option('wp_prefix_title_after'); ?>" name="wp_prefix_title_after" type="text" id="wp_prefix_title_after" >
                            Between title prefix and title post:  <input  value="<?php echo get_option('wp_prefix_title_center'); ?>" name="wp_prefix_title_center" type="text" id="wp_prefix_title_center" >
                        </td>
                    </tr>
                </tbody>
            </table>
            <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes"></p></form>
        </form>
    </div>
</div>
