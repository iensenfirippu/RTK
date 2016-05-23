<?php namespace RTK;
if (defined('RTK') or exit(1))
{
	/**
	 * Contains additional general purpose functions for handling strings in PHP
	 * (also contains altered/extended versions of existing PHP functions)
	 */
	class StringVal
	{
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
	}
}
?>