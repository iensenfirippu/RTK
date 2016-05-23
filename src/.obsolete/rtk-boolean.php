<?php namespace RTK;
if (defined('RTK') or exit(1))
{
	/**
	 * Contains additional functionality for handling booleans in PHP
	 **/
	class BoolVal
	{
		/**
		 * Display a boolean as "TRUE" or "FALSE", instead of "1" and "0".
		 * @param boolean, the boolean to display.
		 **/
		public static function Display($boolean)
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
		public static function Flip(&$boolean)
		{
			if (is_bool($boolean)) { $boolean = !$boolean; }
		}
	}
}
?>