<?php
add_action( 'admin_menu', 'u2f_plugin_menu' );

//adding rewrite rules
add_action( 'wp_loaded','u2f_flush_rules' );
add_filter( 'rewrite_rules_array','u2f_rewrite_rules' );
add_filter( 'query_vars','u2f_query_vars' );

// flush_rules() if our rules are not yet included
function u2f_flush_rules() {
    $rules = get_option( 'rewrite_rules' );
    $url_endpoint=get_option('u2f_endpoint');

    if ( ! isset( $rules['('.$url_endpoint.')/(.+)$'] ) ) {
	global $wp_rewrite;
   	$wp_rewrite->flush_rules();
    }
}

// New rule to catch all parms from url and put it in parmsu2f
function u2f_rewrite_rules( $rules ) {
    $url_endpoint=get_option('u2f_endpoint');

    $newrules = array();
    $newrules['('.$url_endpoint.')/(.+)$'] = 'index.php?pagename=$matches[1]&parmsu2f=$matches[2]';

    return $newrules + $rules;
}

function u2f_query_vars( $vars ) {

    array_push($vars, 'parmsu2f');
    return $vars;
}

function u2f_plugin_menu() {
	add_options_page( 'URL2Function Options', 'URL2Function', 'manage_options', 'url2function-uid', 'u2f_plugin_options' );
}

function u2f_plugin_options() {

    //must check that the user has the required capability
    if (!current_user_can('manage_options'))
    {
      wp_die('You do not have sufficient permissions to access this page.');
    }

    // variables for the field and option names
    $opt_name = 'u2f_endpoint';
    $hidden_field_name = 'u2f_submit_hidden';
    $data_field_name = 'u2f_endpoint';

    // Read in existing option value from database
    $opt_val = get_option( $opt_name );

    // See if the user has posted us some information
    // If they did, this hidden field will be set to 'Y'
    if( isset($_POST[ $hidden_field_name ]) && $_POST[ $hidden_field_name ] == 'Y' ) {
        // Read their posted value
        $opt_val = $_POST[ $data_field_name ];

        // Save the posted value in the database
        update_option( $opt_name, $opt_val );
        u2f_flush_rules();

        // Put an settings updated message on the screen

        ?>
        <div class="updated"><p><strong>settings saved.</strong></p></div>
        <?php

    }

    ?>

    <div class="wrap">

        <h2>URL2Function Plugin Settings</h2>

        <form name="form1" method="post" action="">
            <input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">

            <p>URL EndPoint:
                <input type="text" name="<?php echo $data_field_name; ?>" value="<?php echo $opt_val; ?>" size="20">
            </p><hr />

            <p class="submit">
                <input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e('Save Changes') ?>" />
            </p>
        </form>
    </div>
    <?php

}

