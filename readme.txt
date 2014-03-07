=== url2function ===
Contributors: Antonio Navarro
Tags: ajax, permalinks, url, function
Tested up to: 3.8.1
License: GNU-GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Plugin for WordPress that allow to execute custom PHP function from a URL.

== Installation ==
Deploy and install in plugins directory.
Create a new Page with name 'Do function' and slug 'dofunc' and permalink 'dofunc'.
Use a Template Page for this new page only with this function: process_query_request();
In Settings-->Permalinks, use 'Post Name'.

This is my first plugin and I did it because I need to include in a wordpress site a method to do queries to a database through ajax
to display tables and other data.

With this plugin you have the minimun that you need to map a URL from a page of your WP to a PHP function.
It's a simply entry point for extend with your needs.

== Changelog ==
v0.0.1 - First version