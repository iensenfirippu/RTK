<?php
if (defined('RTK') or exit(1))
{
	/**
	 * Contains the definition of an image in HTML
	 **/
	class RTK_Image extends HtmlElement
	{
		/**
		 * A widget for displaying an image (img)
		 * @param string $imgurl The url of the image
		 * @param string $alttext A text that will be shown if the image could not be loaded
		 * @param boolean $forcehttps Specify if the link has to have https 
		 * @param HtmlAttributes $args Allows custom html tag arguments to be specified (not recommended)
		 **/
		public function __construct($imgurl=EMPTYSTRING, $alttext='[IMG]', $args=null)
		{
			parent::__construct();
			$img = new HtmlElement('img', $args);
			$img->AddAttributes(array('src' => RTK::GetBaseURL().$imgurl, 'alt' => $alttext));
			$this->AddChild($img);
		}
		
		/**
		 * Makes the image a clickable link
		 * @param string $link The url of the link
		 * @param HtmlAttributes $args Allows custom html tag arguments to be specified (not recommended)
		 **/
		public function AddLink($link, $args=null) {
			$child = &$this->_children[0];
			if ($child != false && $child->GetTag() == 'img') {
				$img = $child;
				$child = new RTK_Link($link, RTK_EMPTYSTRING, $args, false);
				$child->AddChild($img);
				$child->SetOneLine(true);
			}
		}
		
		/**
		 * Removes the link and reverts the image back into a regular image
		 **/
		public function RemoveLink() {
			$child = &$this->_children[0];
			if ($child != false && $child->GetTag() == 'a') {
				$grandchild = $child->GetFirstChild();
				if ($child != false && $child->GetTag() == 'img') {
					$child = $grandchild;
					$child->SetOneLine(false);
				}
			}
		}
	}
}
?>