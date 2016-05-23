<?php
if (defined('RTK') or exit(1))
{
	/**
	 * Contains the definition of a text in HTML
	 **/
	class RTK_Textview extends HtmlElement
	{
		/**
		 * A widget containing text (essentially just a div or span with text)
		 * @param string $text The text to display
		 * @param boolean $inline Determines if the widget should be span(true) or div(false)
		 * @param string $id The HTML #id of the element
		 * @param string $class The HTML .class of element
		 * @param HtmlAttributes $args Allows custom html tag arguments to be specified (not recommended)
		 **/
		public function __construct($text=null, $inline=false, $id=null, $class=null, $args=null)
		{
			if ($text == null) { $text = RTK_EMPTYSTRING; }
			
			$attributes = array();
			if (isset($id) && $id != null) { $attributes['id'] = $id; }
			if (isset($class) && $class != null) { $attributes['class'] = $class; }
			
			$tag = $inline ? 'span' : 'div';
			parent::__construct($tag, $attributes, $text);
			$this->AddAttributes($args);
		}
	}
}
?>