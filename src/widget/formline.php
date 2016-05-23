<?php
if (defined('RTK') or exit(1))
{
	/**
	 * Contains the definition of a user form in HTML
	 **/
	class RTK_Formline extends HtmlElement
	{
		/**
		 * A widget containing a line of user inputs for a form element
		 * @param string $name The HTML name (and #id) of the input element(s) and label
		 * @param string $title The text written next to the input element(s)
		 * @param string $inputs The input element(s) for the form line
		 **/
		public function __construct($name, $title, $inputs)
		{
			parent::__construct('div', array('class' => 'formline'));
			
			// Add the label
			$this->AddContainer(new HtmlElement('label', array('for' => $name), $title), 'LABEL');
			
			// Create the input group
			$group = new HtmlElement('div', array('class' => 'formgroup'));
			if (is_a($inputs, 'HtmlElement')) {
				$group->AddChild($inputs);
			} elseif (RTK::ArrayIsLongerThan($array, 0)) {
				foreach ($inputs as $input) {
					if (is_a($input, 'HtmlElement')) {
						$group->AddChild($input);
					}
				}
			}
			$this->AddContainer($group, 'GROUP');
			
			// Add the error section
			$this->AddContainer(new HtmlElement(), 'ERROR');
		}
	}
}
?>