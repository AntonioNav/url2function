<?php
/**
 * Plugin Name: URL to Function
 * Plugin URI: https://github.com/AntonioNav/url2function
 * Description: Plugin for WordPress that allow to execute custom PHP function from a URL
 * Version: 0.0.1
 * Author: Antonio Navarro
 * Author URI: https://www.google.com/+AntonioNavarro
 * License: GPLv2
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

require_once(dirname(__FILE__).'/backend/backend.php');

//adding rewrite rules
add_action( 'wp_loaded','u2f_flush_rules' );
add_filter( 'rewrite_rules_array','u2f_rewrite_rules' );
add_filter( 'query_vars','u2f_query_vars' );

// flush_rules() if our rules are not yet included
function u2f_flush_rules() {
    $rules = get_option( 'rewrite_rules' );

    if ( ! isset( $rules['(dofunc)/(.+)$'] ) ) {
	global $wp_rewrite;
   	$wp_rewrite->flush_rules();
    }
}

// New rule to catch all parms from url and put it in parmsu2f
function u2f_rewrite_rules( $rules ) {
	$newrules = array();
	$newrules['(dofunc)/(.+)$'] = 'index.php?pagename=$matches[1]&parmsu2f=$matches[2]';

	return $newrules + $rules;
}

function u2f_query_vars( $vars ) {

    array_push($vars, 'parmsu2f');
    return $vars;
}

//Main function to parse parms a do real work :-)
function process_query_request() {

    $parms = explode ('/',get_query_var('parmsu2f'));

    if ($parms[0]=='') {
        exit;
    } else {
        echo json_encode(processRequest($parms));
    }

}