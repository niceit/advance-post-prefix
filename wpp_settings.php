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
                            <label title="Activate"><input <?php if(get_option('wp_action_prefix') == 0) echo 'checked="checked"'; ?> type="radio" name="wp_action_prefix" value="0" > <span>Activate</span></label><br>
                            <label title="Deactivate"><input <?php if(get_option('wp_action_prefix') == 1) echo 'checked="checked"'; ?> type="radio" name="wp_action_prefix" value="1"> <span>Deactivate</span></label>
                            <p class="description">Active or disable this prefix for a while at frontend</p>
                        </fieldset>
                    </td>
                </tr>
                    <tr valign="top">
                        <th scope="row"><label for="prefix_title_format">Title Prefix format</label></th>
                        <td class="prefix_title">
                            Before prefix title: <input value="<?php echo get_option('wp_prefix_title_before'); ?>" name="wp_prefix_title_before" type="text" id="wp_prefix_title_before" >
                            After prefix title:  <input value="<?php echo get_option('wp_prefix_title_after'); ?>" name="wp_prefix_title_after" type="text" id="wp_prefix_title_after" >
                            Between title prefix and title post:  <input  value="<?php echo get_option('wp_prefix_title_center'); ?>" name="wp_prefix_title_center" type="text" id="wp_prefix_title_center" >
                            <p class="description">This setting will set your post prefix like : [Prefix] - Post Title</p>
                        </td>
                    </tr>
                </tbody>
            </table>
            <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes"></p></form>
        </form>
        <div>
            Love this plugin? Donate your small amount to help it alive and update recently to support better features
            <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top" style="display: inline-block;">
                <input type="hidden" name="cmd" value="_s-xclick">
                <input type="hidden" name="hosted_button_id" value="KK9R92SYFKSV6">
                <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_SM.gif" style="  width: 60px; height: 22px; vertical-align: middle;" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
                <img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
            </form>
        </div>
        <p class="description">Found a bug or want to suggest a feature? contact me at <a href="mailto:tranit1209@gmail.com">tranit1209@gmail.com</a> </p>
    </div>
</div>
