<?php
if (defined('RTK') or exit(1))
{
	// Parameter keys that should not have a value assignment
	//define("BOOLEANPARAMETERS", "|checked|selected|");

	/**
	 * Contains a list of HTML attributes/parameters
	 * like src="/path/to.file" or style="color:blue;"
	 **/
	class HtmlAttributes implements Iterator
	{
		/*
		 * list navigation variables
		 */
		public $_list;
		private $_index = 0;
		public $_nb;
		public $_nbTotal;
		
		/*
		 * list navigation functions
		 */
		public function rewind() { $this->_index = 0;}
		public function current() { $k = array_keys($this->_list); $var = $this->_list[$k[$this->_index]]; return $var; }
		public function key() { $k = array_keys($this->_list); $var = $k[$this->_index]; return $var; }
		public function next() { $k = array_keys($this->_list); if (isset($k[++$this->_index])) { $var = $this->_list[$k[$this->_index]]; return $var; } else { return false; } }
		public function valid() { $k = array_keys($this->_list); $var = isset($k[$this->_index]);return $var; }
				
		/**
		 * A list of HTML attributes
		 **/
		public function __construct($attributes=null)
		{
			$this->_list = array();
			$this->_nb = 0;
			$this->_nbTotal = 0;
			
			if (!is_array($attributes)) { $attributes = array(); } 
			$this->_list = $attributes;
			
			return $this;
		}
		
		public function __tostring()
		{
			$result = RTK_EMPTYSTRING;
			if (RTK::SetAndNotNull($this->_list) && is_array($this->_list)) {
				ksort($this->_list);
				foreach ($this->_list as $key => $val) {
					if (strstr(RTK_BOOLEANPARAMETERS, '|'.$key.'|') && $val == true) {
						$result .= RTK_SINGLESPACE.$key;
					} else {
						$result .= RTK_SINGLESPACE.$key.'="'.$val.'"';
					}
				}
			}
			return $result;
		}
		
		/**
		 * Add an HTML attributes to the list
		 * @param var $key The key in the array
		 * @param var $value The value to put into the array
		 * @param bool $override Allow override if a value already exists at the specified key
		 **/
		public function Add($key, $value, $override=true)
		{
			if ($value == null) {
				if ($override == true && array_key_exists($key, $this->_list)) {
					$this->Remove([$key]);
				}
			} elseif ($override == true || !array_key_exists($key, $this->_list)) {
				$this->_list[$key] = $value;
			}
		}
		
		/**
		 * Remove an HTML attributes from the list
		 * @param var $key The key of the value to remove from the array
		 **/
		public function Remove($key)
		{
			_array::Remove($this->_list, $key);
		}
		
		/**
		 * Checks if a certain key has a certain value
		 * @param var $key The key in the array
		 * @param var $value The value to check for
		 * @return bool Returns true if the specified key has the specified value
		 **/
		public function KeyHasValue($key, $value)
		{
			$result = false;
			if (array_key_exists($key, $this->_list) && $this->_list[$key] == $value) { $result = true; }
			return $result;
		}
		
		/**
		 * Assures that a variable is an HtmlAttributes object
		 * @param var $var The variable to assure
		 **/
		public static function Assure(& $var) {
			if ($var == null || !is_array($var)) { $var = new HtmlAttributes(); }
			elseif (!is_a($var, 'HtmlAttributes')) { $var = new HtmlAttributes($var); }
		}
	}
}
?>