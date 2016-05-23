<?php
if (defined('RTK') or exit(1))
{
	if (!defined("RTK_DEBUG"))                define("RTK_DEBUG",                false);
	if (!defined("RTK_ONELINEOUTPUT"))        define("RTK_ONELINEOUTPUT",        false);
	if (!defined("RTK_BASEURL"))              define("RTK_BASEURL",              'localhost/');
	
	// Visual configuration:
	if (!defined("RTK_STYLE"))                define("RTK_STYLE",                'default');    // options include: default, flat, oldschool
	
	if (!defined("RTK_PAGINATION_LINKS"))     define("RTK_PAGINATION_LINKS",     3);            // amount of page-links in either direction
	if (!defined("RTK_PAGINATION_FIRST"))     define("RTK_PAGINATION_FIRST",     '&Lt;');       // text on button for: first page
	if (!defined("RTK_PAGINATION_LAST"))      define("RTK_PAGINATION_LAST",      '&Gt;');       // text on button for: last page
	if (!defined("RTK_PAGINATION_PREV"))      define("RTK_PAGINATION_PREV",      '&lt;');       // text on button for: previous page
	if (!defined("RTK_PAGINATION_NEXT"))      define("RTK_PAGINATION_NEXT",      '&gt;');       // text on button for: next page
	if (!defined("RTK_PAGINATION_SHOWEMPTY")) define("RTK_PAGINATION_SHOWEMPTY", false);        // determines weither or not to display an empty pagination
}
?>
