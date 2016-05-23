<?php
if (defined('RTK') or exit(1))
{
	/**
	 * Contains the definition of a dropdown selector (combobox) in HTML
	 **/
	class RTK_DropDown extends HtmlElement
	{
		/**
		 * A widget containing a dropdown selector
		 * @param string $name The HTML name of the element
		 * @param string[][] $options An array of options, each of which is an array of value and title
		 * @param string $selected The value of the selected item in the dropdown
		 * @param HtmlAttributes $args Allows custom html tag arguments to be specified (not recommended)
		 **/
		public function __construct($name, $options, $selected=null, $args=null)
		{
			parent::__construct('select', array('name' => $name, 'id' => $name));
			$this->AddAttributes($args);
			
			$option_value = RTK_EMPTYSTRING;
			$option_title = RTK_EMPTYSTRING;
			foreach ($options as $option) {
				if (RTK::ArrayIsLongerThan($option, 1)) {
					$option_value = $option[0];
					$option_title = $option[1];
				} else { $option_value = $option_title = $option; }
				
				$optionargs = array('value' => $option_value);
				if ($selected == $option_value) { $optionargs['selected'] = true; }
				
				$this->AddChild(new HtmlElement('option', $optionargs, $option_title));
			}
		}
	}
}
?>