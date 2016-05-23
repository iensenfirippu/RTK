<?php
if (defined('RTK') or exit(1))
{
	/**
	 * Contains the definition of a user form in HTML
	 **/
	class RTK_Form extends HtmlElement
	{
		/**
		 * A widget containing a user input form
		 * @param string $name The HTML #id and name of the element
		 * @param string $action The url that should handle the request (leave blank for current page)
		 * @param string $method POST or GET
		 * @param boolean $usetoken Includes a security token on all forms, pass false to opt out (not recommended)
		 * @param HtmlAttributes $args Allows custom html tag arguments to be specified (not recommended)
		 **/
		public function __construct($name=null, $action=null, $method='POST', $usetoken=true, $args=null)
		{
			parent::__construct('form', array('name' => $name, 'id' => $name, 'action' => $action, 'method' => $method));
			$this->AddAttributes($args);
			if ($usetoken) {
				$this->AddHiddenField('securitytoken', RTK::CreateSecurityToken($name));
			}
			$this->_containers = array();
		}
		
		/**
		 * Add an RTK_TextView to the form
		 * @param string $text The text to display
		 * @param HtmlElement $container (optional) The "container" to add it to
		 **/
		public function AddText($text, $container=null)
		{
			$textview = new RTK_Textview($text, false, null, 'formtext');
			$this->AddToContainer($textview, $container);
		}
		
		/**
		 * Add a hidden field to the form
		 * @param string $name The name of the hidden field
		 * @param string $value The predefined value in the hidden field
		 * @param HtmlElement $container (optional) The "container" to add it to
		 **/
		public function AddHiddenField($name, $value=null, $container=null)
		{
			$field = new HtmlElement('input', array('type' => 'hidden', 'name' => $name, 'id' => $name));
			if (RTK::SetAndNotNull($value)) $field->AddAttribute('value', $value);
			$this->AddToContainer($field, $container);
		}
		
		/**
		 * Add a text input field to the form
		 * @param string $name The HTML name (and #id) of the input field
		 * @param string $title The text written next to the input field
		 * @param string $value The predefined value in the input field
		 * @param integer $size How many rows the input field should span
		 * @param HtmlElement $container (optional) The "container" to add it to
		 **/
		public function AddTextField($name, $title, $value=null, $size=null, $container=null)
		{
			$args = array('name' => $name, 'id' => $name);
			
			if ($size == null || intval($size) <= 0) {
				$args['type'] = 'text';
				if (RTK::SetAndNotNull($value)) { $args['value'] = $value; }
				$input = new HtmlElement('input', $args);
			} else {
				$args['rows'] = $size;
				$input = new HtmlElement('textarea', $args, $value);
			}
			
			$line = new RTK_Formline($name, $title, $input);
			$this->AddToContainer($line, $container);
		}
		
		/**
		 * Add a password input field to the form
		 * @param string $name The HTML name (and #id) of the input field
		 * @param string $title The text written next to the input field
		 * @param string $value The predefined value in the input field (not recommended, use with caution)
		 * @param HtmlElement $container (optional) The "container" to add it to
		 **/
		public function AddPasswordField($name, $title, $value=null, $container=null)
		{
			$args = array('type' => 'password', 'name' => $name, 'id' => $name);
			if (RTK::SetAndNotNull($value)) { $args['value'] = $value; }
			
			$line = new HtmlElement('div', array('class' => 'formline'));
			$line->AddChild(new HtmlElement('label', array('for' => $name), $title));
			$group = new HtmlElement('div', array('class' => 'formgroup'));
			$group->AddChild(new HtmlElement('input', $args));
			$line->AddChild($group);
			
			$this->AddToContainer($line, $container);
		}
		
		/**
		 * Add a file upload field to the form
		 * @param string $name The HTML name (and #id) of the upload field
		 * @param string $title The text written next to the upload field
		 * @param string $value The value sent if there us no file selected
		 * @param HtmlElement $container (optional) The "container" to add it to
		 **/
		public function AddFileUpload($name, $title, $value='false', $container=null)
		{
			$args = array('class' => 'filebox', 'type' => 'file', 'name' => $name, 'id' => $name, 'value' => $value);
			
			$field = new HtmlElement('div', array('class' => 'formline'));
			$field->AddChild(new HtmlElement('label', array('for' => $name), $title));
			$group = new HtmlElement('div', array('class' => 'formgroup'));
			$group->AddChild(new HtmlElement('input', $args));
			$field->AddChild($group);
			
			$this->AddAttribute('enctype', 'multipart/form-data');
			$this->AddToContainer($field, $container);
		}
		
		/**
		 * Add a checkbox to the form
		 * @param string $name The HTML name (and #id) of the input field
		 * @param string $title The text written next to the input field
		 * @param boolean $checked Whether the checkbox is checked
		 * @param string $value The value sent if it is checked
		 * @param string $text (optional) a text to display next to the checkbox
		 * @param HtmlElement $container (optional) The "container" to add it to
		 **/
		public function AddCheckBox($name, $title, $checked=false, $value='true', $text=null, $container=null)
		{
			$args = array('class' => 'checkbox', 'type' => 'checkbox', 'name' => $name, 'id' => $name, 'value' => $value, 'checked' => $checked);
			
			$field = new HtmlElement('div', array('class' => 'formline'));
			$field->AddChild(new HtmlElement('label', array('for' => $name), $title));
			
			$group = new HtmlElement('div', array('class' => 'formgroup'));
			$group->AddChild(new HtmlElement('input', $args));
			if ($text != null) { $group->AddChild(new HtmlElement('span', null, $text)); }
			$field->AddChild($group);
			
			$this->AddToContainer($field, $container);
		}
		
		/**
		 * Add a row of radiobuttons to the form
		 * @param string $name The HTML name (and #id) of the input field
		 * @param string $title The text written next to the input field
		 * @param string[][] $options An array of options, each of which is an array of value and title
		 * @param string $selected The value of the selected radiobutton
		 * @param HtmlElement $container (optional) The "container" to add it to
		 **/
		public function AddRadioButtons($name, $title, $options, $selected=null, $container=null)
		{
			$group = new HtmlElement('div', array('class' => 'formgroup'));
			
			$option_value = RTK_EMPTYSTRING;
			$option_title = RTK_EMPTYSTRING;
			foreach ($options as $option) {
				if (RTK::ArrayIsLongerThan($option, 1)) {
					$option_value = $option[0];
					$option_title = $option[1];
				} else { $option_value = $option_title = $option; }
				
				$args = array('type' => 'radio', 'class' => 'radiobox', 'name' => $name, 'id' => $name, 'value' => $option_value);
				if ($selected == $option_value) { $args['checked'] = true; }
				
				$group->AddChild(new HtmlElement('input', $args));
				$group->AddChild(new HtmlElement('span', null, $option_title));
			}
			
			$field = new HtmlElement('div', array('class' => 'formline'));
			$field->AddChild(new HtmlElement('label', array('for' => $name), $title));
			$field->AddChild($group);
			
			$this->AddToContainer($field, $container);
		}
		
		/**
		 * Add a dropdown selector to the form
		 * @param string $name The HTML name (and #id) of the input field
		 * @param string $title The text written next to the input field
		 * @param string[][] $options An array of options, each of which is an array of value and title
		 * @param string $selected The value of the selected item in the dropdown
		 * @param HtmlElement $container (optional) The "container" to add it to
		 **/
		public function AddDropDown($name, $title, $options, $selected=null, $container=null)
		{
			$dropdown = new RTK_DropDown($name, $options, $selected);
			
			$field = new HtmlElement('div', array('class' => 'formline'));
			$field->AddChild(new HtmlElement('label', array('for' => $name), $title));
			$field->AddChild(new HtmlElement('div', array('class' => 'formgroup'), null, $dropdown));
			
			$this->AddToContainer($field, $container);
		}
		
		/**
		 * Add a button to the form
		 * @param string $name The name/id of the button
		 * @param string $text The text written on the button
		 * @param string $title The text written next to the button
		 * @param HtmlElement $container (optional) The "container" to add it to
		 **/
		public function AddButton($name='submit', $text='Submit', $title=null, $container=null)
		{
			$field = new HtmlElement('div', array('class' => 'formline'));
			$field->AddChild(new HtmlElement('label', array('for' => $name), $title));
			$group = new HtmlElement('div', array('class' => 'formgroup'));
			$group->AddChild(new RTK_Button($name, $text));
			$field->AddChild($group);
			
			$this->AddToContainer($field, $container);
		}
		
		/**
		 * Add a custom HtmlElement into the form (not recommended)
		 * @param string $name The name/id of the element
		 * @param string $title The text written on the element
		 * @param HtmlElement $HtmlElement The element to add
		 * @param HtmlElement $container (optional) The "container" to add it to
		 **/
		public function AddElement($name, $title, $htmlelement, $container=null)
		{
			$field = new HtmlElement('div', array('class' => 'formline'));
			$field->AddChild(new HtmlElement('label', array('for' => $name), $title));
			$group = new HtmlElement('div', array('class' => 'formgroup'));
			$group->AddChild($htmlelement);
			$field->AddChild($group);
			
			$this->AddToContainer($field, $container);
		}
	}
}
?>