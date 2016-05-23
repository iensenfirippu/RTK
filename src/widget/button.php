<?php
if (defined('RTK') or exit(1))
{
	/**
	 * Contains definitions of a button in HTML
	 **/
	class RTK_Button extends HtmlElement
	{
		/**
		 * A button widget
		 * @param string $name The name/id of the button
		 * @param string $title The text written on the button
		 * @param HtmlAttributes $args Allows custom html tag arguments to be specified (not recommended)
		 **/
		public function __construct($name='submit', $title='Submit', $args=null)
		{
			parent::__construct('input', array('type' => 'submit', 'name' => $name, 'class' => 'submit', 'value' => $title));
			if (RTK::SetAndNotNull($args)) { $this->AddAttributes($args); }
		}
	}
}
?>