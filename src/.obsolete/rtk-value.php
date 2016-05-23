<?php namespace RTK;
if (defined('RTK') or exit(1))
{
	/**
	 * Contains functions for variable/value checking
	 **/
	class Value
	{
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
		
		/**
		 * Determines if a variable: isset(v) && v == NULL
		 * @param variable, the variable to check.
		 * @param key, (optionally) the key in variable to check (for arrays).
		 **/
		public static function SetAndNull($variable, $key=null)
		{
			if ($key != null && is_array($variable))
			{
				if (array_key_exists($key, $variable)) { $variable = $variable[$key]; }
				else { $variable = -1; }
			}
			return (isset($variable) && $variable == null);
		}
		
		/**
		 * Determines if a variable: isset(v) && !empty(v)
		 * @param variable, the variable to check.
		 * @param key, (optionally) the key in variable to check (for arrays).
		 **/
		public static function SetAndNotEmpty($variable, $key=null)
		{
			if ($key != null && is_array($variable))
			{
				if (array_key_exists($key, $variable)) { $variable = $variable[$key]; }
				else { $variable = null; }
			}
			return (isset($variable) && !empty($variable));
		}
		
		/**
		 * Determines if a variable: isset(v) && empty(v)
		 * @param variable, the variable to check.
		 * @param key, (optionally) the key in variable to check (for arrays).
		 **/
		public static function SetAndEmpty($variable, $key=null)
		{
			if ($key != null && is_array($variable))
			{
				if (array_key_exists($key, $variable)) { $variable = $variable[$key]; }
				else { $variable = null; }
			}
			return (isset($variable) && empty($variable));
		}
		
		/**
		 * Determines if a variable: isset(v) && v ==|=== x
		 * @param value, the value to check for.
		 * @param variable, the variable to check.
		 * @param key, (optionally) the key in variable to check (for arrays).
		 * @param checktype, set true for === instead of == check.
		 **/
		public static function SetAndEqualTo($value, $variable, $key=null, $checktype=false)
		{
			$result = false;
			if ($key != null && is_array($variable))
			{
				if (array_key_exists($key, $variable)) { $variable = $variable[$key]; }
				else { $variable = null; }
			}
			if ($checktype)	{ $result = (isset($variable) && $variable === $value); }
			else			{ $result = (isset($variable) && $variable == $value); }
			return $result;
		}
		
		/**
		 * Determines if a variable: isset(v) && v !=|!== x
		 * @param value, the value to check for.
		 * @param variable, the variable to check.
		 * @param key, (optionally) the key in variable to check (for arrays).
		 * @param checktype, set true for !== instead of != check.
		 **/
		public static function SetAndNotEqualTo($value, $variable, $key=null, $checktype=false)
		{
			$result = false;
			if ($key != null && is_array($variable))
			{
				if (array_key_exists($key, $variable)) { $variable = $variable[$key]; }
				else { $variable = null; }
			}
			if ($checktype)	{ $result = (isset($variable) && $variable !== $value); }
			else			{ $result = (isset($variable) && $variable != $value); }
			return $result;
		}
	}
}
?>