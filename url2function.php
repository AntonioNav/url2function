<?php
/**
 * Plugin Name: URL to Function
 * Plugin URI: https://github.com/AntonioNav/url2function
 * Description: Plugin for WordPress that allow to execute custom PHP function from a URL
 * Version: 0.0.2
 * Author: Antonio Navarro
 * Author URI: https://www.google.com/+AntonioNavarro
 * License: GPLv2
 * Text Domain: url2function
 */

/*  Copyright 2014  Antonio Navarro  (email : antonio@hunos.net)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

load_plugin_textdomain('url2function', false, basename(dirname(__FILE__)).'/languages/' );

require_once(dirname(__FILE__).'/backend/backend.php');
require_once(dirname(__FILE__).'/admin/settings.php');

//set default value
add_option('u2f_endpoint', 'dofunc' );

//Main function to parse parms a do real work :-)
function process_query_request() {

    $parms = explode ('/',get_query_var('parmsu2f'));

    if ($parms[0]=='') {
        exit;
    } else {
        echo json_encode(processRequest($parms));
    }

}