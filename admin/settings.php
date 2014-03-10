<?php
add_action('admin_menu', 'u2f_plugin_menu');

//adding rewrite rules
add_filter('rewrite_rules_array','u2f_rewrite_rules');
add_filter('query_vars','u2f_query_vars');

// flush_rules() if our rules are not yet included
function u2f_flush_rules() {
    $rules = get_option('rewrite_rules');
    $url_endpoint = get_option('u2f_endpoint');

    if ( ! isset( $rules['('.$url_endpoint.')/(.+)$'])) {
	global $wp_rewrite;
   	$wp_rewrite->flush_rules();
    }
}

// New rule to catch all parms from url and put it in parmsu2f
function u2f_rewrite_rules( $rules ) {
    $url_endpoint = get_option('u2f_endpoint');

    $newrules = array();
    $newrules['('.$url_endpoint.')/(.+)$'] = 'index.php?pagename=$matches[1]&parmsu2f=$matches[2]';

    return $newrules + $rules;
}

function u2f_query_vars( $vars ) {

    array_push($vars, 'parmsu2f');
    return $vars;
}

function u2f_plugin_menu() {
	add_options_page( 'URL2Function Options', 'URL2Function Options', 'manage_options', 'url2function-uid', 'u2f_plugin_options' );
}

function u2f_plugin_options() {
    // variables for the field and option names
    $opt_name = 'u2f_endpoint';
    $hidden_field_name = 'u2f_submit_hidden';
    $nonce_field_name = 'u2fnonce';
    $data_field_name = 'u2f_endpoint';
    $show_updated = false;

    // Read in existing option value from database
    $opt_val = get_option($opt_name);

    //must check that the user has the required capability
    if (!current_user_can('manage_options'))
    {
      wp_die(__('You do not have sufficient permissions to access this page.','url2function'));
    }

    // See if the user has posted us some information
    // If they did, this hidden field will be set to 'Y'
    if( isset($_POST[$hidden_field_name]) && $_POST[$hidden_field_name] == 'Y') {

        //Verify nonce to prevent CSRF attack
	$nonce = $_POST[$nonce_field_name];

        if (!wp_verify_nonce($nonce, 'u2f-nonce')) {
	    wp_die(__('Security check','url2function'));
	}
        // Read their posted value
        $opt_val = $_POST[$data_field_name];

        // Save the posted value in the database
        update_option($opt_name, $opt_val);
        u2f_flush_rules();

	$show_updated = true;
    }
    ?>

    <div class="u2f-settings">

        <h2><?php _e('URL2Function Plugin Settings','url2function'); ?></h2>

	<div style="float:right; width:32%;margin-right: 1%;">
	    <div class="postbox open">
		<h3 style="margin-left:10px; margin-bottom: 0;"><?php _e('About the Author','url2function'); ?></h3>
		<div class="inside" style="display: inline-block; width: 90%;">
		    <h4 style="margin-top:0;"><?php// _e('This is URL2Function Plugin','url2function'); ?></h4>
		    <div div style="float:left; width:25%">
			<img src="http://www.gravatar.com/avatar/1f42aa7aac0ded557e15dcead9261545?s=80" />
		    </div>
		    <div div style="float:left;">
			<h5 style="margin-bottom: 0;"><?php _e('Developed by','url2function'); ?> Antonio Navarro</h5>
		    </div>
		</div>
		<h3  style="margin-left:10px;"><?php _e('Thank you for using this plugin on your site','url2function'); ?></h3>
		<div class="inside">
		    <p><?php _e('You can contribute to support and improve this plugin clicking this button:','url2function'); ?></p>
		    <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=4UKTXMMWHUMGN"
		       target="_blank">
		    <img src="https://www.paypal.com/en_US/i/btn/x-click-but21.gif" alt="" /></a>

		    <p><?php _e('You can contact with me in ','url2function'); ?>
			<a href="https://www.google.com/+AntonioNavarro"
			   target="_blank">Google Plus</a>
		    </p>
		</div>
	    </div>
	</div> <!-- /float:right -->

	<div style="float:left; width:63%;">
	    <form name="form1" method="post" action="">
		<div class="postbox open">
		    <input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">
		    <input type="hidden" name="<?php echo $nonce_field_name; ?>" value="<?php echo wp_create_nonce('u2f-nonce'); ?>">

		    <p style="margin-left:10px;"><strong><?php _e('URL EndPoint:','url2function'); ?></strong>
			<input type="text" name="<?php echo $data_field_name; ?>" value="<?php echo $opt_val; ?>" size="20">
		    </p>
		</div>
		<div class="postbox open">
		    <p style="margin-left:10px;"><strong style="border-bottom: 1px solid;"><?php _e('Important!','url2function'); ?></strong></p>
		    <p style="margin-left:10px;"><?php _e('You have to:','url2function'); ?></p>
		    <ul style="margin-left:30px; list-style-type: disc;">
			<li><?php
			    _e("Create a static page with 'slug' and 'permalink' equal to:",'url2function');
			    echo ' <strong>'.$opt_val.'</strong> ';
			    _e("(save to update if you change the Endpoint).",'url2function');
			?></li>
			<li><?php _e("Activate in Options --> Permalinks, URL with 'postname' (the 5th option).",'url2function'); ?></li>
		    </ul>
		    <p style="margin-left:10px;"><?php _e("This plugin don't work until you satisfy this points.",'url2function'); ?></p>

		    <p style="margin-left:10px;">
		    <?php
			_e("You can then try to access",'url2function');
			$test_url = get_bloginfo('wpurl').'/'.$opt_val.'/test/4000/';
			echo ' <a href="'.$test_url.'" target="_blank">'.$test_url.'</a> ';
			_e("to test the plugin.","url2function");
		    ?></p>
		    <p style="margin-left:10px;">
			<?php _e("You have to obtain this JSON-object:","url2function"); ?>
		    </p>
		    <p style="margin-left:30px;">
			{"error":0,"result":"01:06:40"}
		    </p>
		    <p style="margin-left:10px;">
			<?php _e("When this is working, you have to edit 'backend.php' file ","url2function"); ?>
			<?php _e("and add your custom mappings and functions.","url2function"); ?>
		    </p>
		</div>
		<?php
		    if ($show_updated) {
			// Put an settings updated message on the screen
			echo '<div class="updated" style="margin:0 0 0 0;"><p><strong>';
			_e('settings saved.','url2function');
			echo '</strong></p></div>';
		    }
		?>
		    <p class="submit" style="/*margin-left:10px;*/">
			<input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e('Save Changes') ?>" />
		    </p>
	    </form>
	</div>
    </div>
    <?php

}
