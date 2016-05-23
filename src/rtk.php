<?php
define("RTK", true);

// Neatness constants, for often repeated values
define("RTK_EMPTYSTRING", "");
define("RTK_SINGLESPACE", " ");
define("RTK_SINGLECOMMA", ",");
define("RTK_SINGLEDOT",   ".");
define("RTK_SINGLESLASH", "/");
define("RTK_NEWLINE",     "\n");
define("RTK_INDENT",      "\t");
define("RTK_COMMASPACE",  RTK_SINGLECOMMA.RTK_SINGLESPACE);

if (defined('RTK') or exit(1))
{
	/**
	 * Contains RTK specific functionality
	 */
	class RTK
	{
		/**
		 * Returns true if the client is connecting via HTTPS, otherwise it returns false.
		 */
		public static function HasHttps()
		{
			$secure_connection = false;
			if (isset($_SERVER['HTTPS'])) {
				if ($_SERVER['HTTPS'] == "on") {
					$secure_connection = true;
				}
			}
			return $secure_connection;
		}
		
		/**
		 * Returns true if the client is connecting via HTTPS, otherwise it returns false.
		 * @param boolean $forcehttps Specify if the link has to have https
		 */
		public static function GetBaseURL($forcehttps=false)
		{
			if (RTK::HasHttps() || $forcehttps) { return 'https://'.RTK_BASEURL; }
			else { return 'http://'.RTK_BASEURL; }
		}
		
		/**
		 * Creates a Security token to secure a form against XSRF.
		 */
		public static function CreateSecurityToken($formname)
		{
			$formname .= '_securitytoken';
			$token = md5(utf8_encode(uniqid().uniqid()));
			return $_SESSION[$formname] = $token;
		}
		
		/**
		 * Checks a Security token against the token stored in the users session.
		 */
		public static function CheckSecurityToken($formname)
		{
			$result = false;
			$formname .= '_securitytoken';
			if (RTK\Value::SetAndNotNull($_POST, $formname)) {
				if (RTK\Value::SetAndNotNull($_SESSION, $formname)) {
					$result = RTK\Value::SetAndEqualTo($_POST[$formname], $_SESSION[$formname]);
				}
			}
			return $result;
		}
		
/* Boolean functions */
		
		/**
		 * Display a boolean as "TRUE" or "FALSE", instead of "1" and "0".
		 * @param boolean, the boolean to display.
		 **/
		public static function DisplayBool($boolean)
		{
			if ($boolean == true || $boolean == 1 || $boolean == '1') { $value = 'true'; }
			elseif ($boolean == false || $boolean == 0 || $boolean == '0') { $value = 'false'; }
			else { $value = $boolean; }
			return $value;
		}
		
		/**
		 * "Flip" a boolean value to the opposite value
		 * @param boolean, the value to "flip".
		 **/
		public static function FlipBool(&$boolean)
		{
			if (is_bool($boolean)) { $boolean = !$boolean; }
		}
		
/* String functions */
		
		/**
		 * Removes any Windows linebreaks (\r\n) and replaces them with proper UNIX linebreaks (\n).
		 * @param param, description.
		 **/
		public static function EnforceProperLineEndings(& $string)
		{
			$improper_lineending = "\r\n";
			if (strstr($string, $improper_lineending)) { $string = str_replace($improper_lineending, NEWLINE, $string); }
			return $string;
		}
		
		/**
		 * Removes a text from the beginning of another
		 * @param string, the string to look in.
		 * @param prefix, the prefix to remove.
		 **/
		public static function RemovePrefix($string, $prefix)
		{
			if(strpos($string, $prefix) === 0) { $string = substr($string, strlen($prefix)).RTK_EMPTYSTRING; }
			return $string;
		}
		public static function strrempre($a,$b) { return _string::string_remove_prefix($a,$b); }
		
/* Array functions */

		/**
		 * Determines whether a variable is and array and is longer than x items
		 * @param array, the variable to check.
		 * @param size, the smallest acceptable size of the array.
		 **/
		public static function ArrayIsLongerThan($array, $size)
		{
			return is_array($array) && sizeof($array) > $size;
		}
		
		/**
		 * Remove an item from an array
		 * @param array, the variable to remove from.
		 * @param index, the index of the item to remove.
		 **/
		public static function RemoveFromArray(&$array, $index)
		{
			$result = false;
			if (!is_integer($index)) { $index = array_search($index, $array); }
			if (is_array($array) && sizeof($array) > $index)
			{
				unset($array[$index]);
				$array = array_values($array);
				$result = true;
			}
			return $result;
		}
		
/* Value checks */
		
		/**
		 * Determines if a variable: isset(v) && v != NULL
		 * @param variable, the variable to check.
		 * @param key, (optionally) the key in variable to check (for arrays).
		 **/
		public static function SetAndNotNull($variable, $key=null)
		{
			if ($key != null && is_array($variable))
			{
				if (array_key_exists($key, $variable)) { $variable = $variable[$key]; }
				else { $variable = null; }
			}
			return (isset($variable) && $variable != null);
		}
	}
}
define("RTK_ABSOLUTE", dirname(__FILE__).DIRECTORY_SEPARATOR);
define("RTK_DIRECTORY", RTK::RemovePrefix(RTK_ABSOLUTE, $_SERVER['HOME'].DIRECTORY_SEPARATOR));
$userconfig = RTK_DIRECTORY."config".DIRECTORY_SEPARATOR."rtk-userconfig.php";
$defaultconfig = RTK_DIRECTORY."config".DIRECTORY_SEPARATOR."rtk-defaults.php";

if (file_exists($userconfig)) { include_once($userconfig); } // else { echo "Couldn't include: ".$userconfig; die(1); }
if (file_exists($defaultconfig)) { include_once($defaultconfig); } // else { echo "Couldn't include: ".$defaultconfig; die(1); }

// Include general classes
//include_once(RTK_DIRECTORY."rtk.php");
//include_once(RTK_DIRECTORY."rtk-boolean.php");
//include_once(RTK_DIRECTORY."rtk-string.php");
//include_once(RTK_DIRECTORY."rtk-value.php");
include_once(RTK_DIRECTORY."htmldocument.php");
include_once(RTK_DIRECTORY."htmlelement.php");
//include_once(RTK_DIRECTORY."htmlattribute.php");
//include_once(RTK_DIRECTORY."htmlattributes.php");

// Include widget classes
include_once(RTK_DIRECTORY."widget".DIRECTORY_SEPARATOR."box.php");
include_once(RTK_DIRECTORY."widget".DIRECTORY_SEPARATOR."button.php");
include_once(RTK_DIRECTORY."widget".DIRECTORY_SEPARATOR."commentview.php");
include_once(RTK_DIRECTORY."widget".DIRECTORY_SEPARATOR."dropdown.php");
include_once(RTK_DIRECTORY."widget".DIRECTORY_SEPARATOR."form.php");
include_once(RTK_DIRECTORY."widget".DIRECTORY_SEPARATOR."formline.php");
include_once(RTK_DIRECTORY."widget".DIRECTORY_SEPARATOR."header.php");
include_once(RTK_DIRECTORY."widget".DIRECTORY_SEPARATOR."image.php");
include_once(RTK_DIRECTORY."widget".DIRECTORY_SEPARATOR."link.php");
include_once(RTK_DIRECTORY."widget".DIRECTORY_SEPARATOR."bulletlist.php");
include_once(RTK_DIRECTORY."widget".DIRECTORY_SEPARATOR."listview.php");
include_once(RTK_DIRECTORY."widget".DIRECTORY_SEPARATOR."menu.php");
include_once(RTK_DIRECTORY."widget".DIRECTORY_SEPARATOR."pagination.php");
include_once(RTK_DIRECTORY."widget".DIRECTORY_SEPARATOR."textview.php");
?>