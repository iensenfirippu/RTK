<?php
/*
 * [R]TK [T]ool [K]it
 *  - "make PHP, not HTML..."
 *
 * Toolkit to make manuel HTML creation obsolete.
 * It automatically renders nested PHP objects into 100% valid HTML
 *
 * The main class "RTK" acts like an HTML document,
 * or like a window/canvas from other desktop toolkits
 * (like: winforms, java swing, gtk, etc.)
 */

if (defined('RTK') or exit(1))
{
	class HtmlDocument
	{
		//protected $_doctype = RTK_EMPTYSTRING;
		protected $_title = RTK_EMPTYSTRING;
		protected $_favicon = null;
		protected $_stylesheets = array();
		protected $_javascripts = array();
		protected $_popups = array();
		protected $_elements = array();
		protected $_references = array();
		protected $_pointer = null;
		
		/**
		 * Class containing an abstracted HTML document
		 * @param string $title The title of the document (The <TITLE> to put in <HEAD>)
		 */
		public function __construct($title=null, $favicon=null)
		{
			//$this->_doctype = $doctype;
			$this->_title = $title;
			$this->_favicon = $favicon;
			
			$this->AddElement(new HtmlElement('html'), null, 'HTML');
			$this->AddElement(new HtmlElement('head'), 'HTML', 'HEAD');
			$this->AddElement(new HtmlElement('body'), 'HTML', 'BODY');
			
			$this->_pointer = $this->_references['BODY'];
			
			$stylesheet = RTK_DIRECTORY.'style/rtk-'.RTK_STYLE.'.css';
			if (file_exists($stylesheet)) {
				$this->AddStylesheet($stylesheet);
			}
		}
		
		/**
		 * Adds a stylesheet to the HTML document
		 * @param string $filename The name of the file to add
		 * @param HtmlAttributes $args Allows custom html tag arguments to be specified (not recommended)
		 */
		public function AddStylesheet($filename, $args=null)
		{
			$stylesheet = new HtmlElement('link', array('rel' => 'stylesheet', 'type' => 'text/css', 'href' => $filename));
			$stylesheet->AddAttributes($args);
			
			$this->_stylesheets[$filename] = $stylesheet;
		}
		
		/**
		 * Removes all stylesheets from the HTML document
		 */
		public function ClearStylesheets()
		{
			$this->_stylesheets = array();
		}
		
		/**
		 * Adds a javascript to the HTML document
		 * @param string $filename The name of the file to add
		 * @param HtmlAttributes $args Allows custom html tag arguments to be specified (not recommended)
		 */
		public function AddJavascript($filename, $args=null)
		{
			$script = new HtmlElement('script', array('src' => $filename));
			$script->AddAttributes($args);
			
			$this->_javascripts[$filename] = $script;
		}
		
		/**
		 * Removes all stylesheets from the HTML document
		 */
		public function ClearJavascripts()
		{
			$this->_javascripts = array();
		}
		
		/**
		 * Adds a Popup to the HTML document
		 * @param HtmlElement $element The element to display
		 **/
		public function AddPopup($element)
		{
			$id = 'Popup-'.(sizeof($this->_popups)+1);
			$popup = new RTK_Box($id, 'popup', array('onclick' => 'ClosePopup(\''.$id.'\')'));
			if (is_a($element, 'HtmlElement')) { $popup->AddChild($element); }
			elseif (is_string($element)) { $popup->SetContent($element); }
			else { $popup = null; }
			
			if ($popup != null) {
				$close = new RTK_Image(RTK_DIRECTORY.'image/'.RTK_STYLE.'/close.png', 'X', array('class' => 'close'));
				$popup->AddChild($close, 0);
				$this->_popups[] = $popup;
			}
		}
		
		/**
		 * Sets the favicon of the HTML document
		 * @param string $path The path to the image file to use (null resets)
		 */
		public function SetFavicon($path) { $this->_favicon = $path; }
		
		/**
		 * Sets the "pointer" to a "reference"d name
		 * @param string $name The name of the "reference"
		 */
		public function SetPointer($name) { $this->_pointer = $this->_references[$name]; }
		
		/**
		 * Adds an HtmlElement to the document
		 * @param HtmlElement $HtmlElement The element (or "widget") to add
		 * @param string $inelement (optional) The name of a "reference"d element, in which to add the element to 
		 * @param string $registeras (optional) The name to "reference" the added element as
		 * @param integer $index (optional) A forced index of the new element, to assure a specific placement in the document (doesn't override another element but pushes it instead)
		 */
		public function AddElement($HtmlElement, $inelement=null, $registeras=null, $index=null)
		{
			if ($HtmlElement != null && is_a($HtmlElement, 'HtmlElement')) {
				$HtmlElement->SetParent($this);
				if ($inelement != null) {
					if (isset($this->_references[$inelement]) && is_a($this->_references[$inelement], 'HtmlElement')) {
						$this->_references[$inelement]->AddChild($HtmlElement, $index);
					} else {
						// TODO: Add error reporting at some point
					}
				} elseif ($this->_pointer != null) {
					$this->_pointer->AddChild($HtmlElement);
				} else {
					array_push($this->_elements, $HtmlElement);
				}
				
				if ($registeras != null) {
					if (!isset($this->_references[$registeras])) {
						$this->_pointer = $this->_references[$registeras] = $HtmlElement;
					} else {
						// TODO: Add error reporting at some point
					}
				}
			}
		}
		
		/**
		 * Gets the HtmlElement that was "reference"d as the specified name
		 * @param string $name The name of the "reference" to get
		 * @return var Returns the specified "reference", or false if it doesn't exist
		 */
		public function GetReference($name)
		{
			if (isset($this->_references[$name]) && is_a($this->_references[$name], 'HtmlElement')) {
				return $this->_references[$name];
			} else {
				return false;
			}
		}
		
		public function __tostring()
		{
			//$html = '<!doctype '.$this->_doctype.'>'.RTK_OUTPUTNEWLINE;
			$html = '<!doctype html>'.RTK_OUTPUTNEWLINE;
			
			// To make sure that stylesheets are always loaded in the same order (important for some rules), sort the stylesheet collection
			// TODO: This wont always be the preferable solution so a "weight" or "importance" system might need to be implemented
			sort($this->_stylesheets);
			
			if ($this->_favicon != null) {
				$this->AddElement(new HtmlElement('link', array('rel'=>'icon', 'type'=>'image/png', 'href'=>$this->_favicon)), 'HEAD', 'FAVICON');
			}
			
			$this->AddElement(new HtmlElement('title', null, $this->_title), 'HEAD', 'TITLE');
			foreach ($this->_stylesheets as $stylesheet) { $this->_references['HEAD']->AddChild($stylesheet); }
			foreach ($this->_javascripts as $javascript) { $this->_references['HEAD']->AddChild($javascript); }
			$this->_stylesheets = array();
			$this->_javascripts = array();
			
			if (sizeof($this->_popups) > 0) {
				$popups = new HtmlElement('div', array('id' => 'Popups'));
				$popups->AddChild(new HtmlElement('script', array('language' => 'javascript'), 'function ClosePopup(divid) { var popups = document.getElementById(\'Popups\'); if (popups.children.length > 2) { var popup = document.getElementById(divid); popup.parentNode.removeChild(popup); } else { popups.parentNode.removeChild(popups); } }'));
				foreach ($this->_popups as $popup) { $popups->AddChild($popup); }
				$this->_references['BODY']->AddChild($popups, 0);
			}
			
			$newline = false;
			foreach ($this->_elements as $HtmlElement) {
				if ($newline) { $html .= OUTPUTNEWLINE; } else { $newline = true; }
				$html .= $HtmlElement;
			}
			
			return $html;
		}
	}
}
?>