<?php
if (defined('RTK') or exit(1))
{
	/**
	 * Contains the definition of paginition in HTML
	 **/
	class RTK_Pagination extends HtmlElement
	{
		/**
		 * A widget containing the links to different pages for a common URL
		 * @param string $baseurl The base part of the URL that all links in the paginition shares
		 * @param integer $amount The amount of items to divide into pages
		 * @param integer $perpage The amount of items per page
		 * @param HtmlAttributes $args Allows custom html tag arguments to be specified (not recommended)
		 **/
		public function __construct($baseurl, $amount, $perpage, $page, $args=null)
		{
			if ($amount > $perpage || RTK_PAGINATION_SHOWEMPTY)
			{
				parent::__construct('ul', $args);
				$this->AddAttribute('class', 'pagination');
				$firstpage = 1;
				$lastpage = ceil($amount / $perpage);
				$lowerlimit = ($page - RTK_PAGINATION_LINKS);
				$upperlimit = ($page + RTK_PAGINATION_LINKS);
				$nolink = new HtmlElement('li', RTK_EMPTYSTRING, '&nbsp;');
				
				// First, previous
				if ($page > $firstpage)
				{
					$this->AddChild(
						new HtmlElement('li', null, null,
							new HtmlElement('a', array('href' => $baseurl.$firstpage.RTK_SINGLESLASH), RTK_PAGINATION_FIRST)
						)
					);
					$this->AddChild(
						new HtmlElement('li', null, null,
							new HtmlElement('a', array('href' => $baseurl.($page - 1).RTK_SINGLESLASH), RTK_PAGINATION_PREV)
						)
					);
				}
				else
				{
					$this->AddChild($nolink);
					$this->AddChild($nolink);
				}
				
				// Available page numbers
				for ($i = $lowerlimit; $i <= $upperlimit; $i++)
				{
					
					if ($i == $page) { $this->AddChild(new HtmlElement('li', array('class' => 'current'), $page)); }
					elseif ($i >= $firstpage && $i <= $lastpage)
					{
						$this->AddChild(
							new HtmlElement('li', null, null,
								new HtmlElement('a', array('href' => $baseurl.$i.RTK_SINGLESLASH), $i)
							)
						);
					}
					else { $this->AddChild($nolink); }
				}
				
				// Next Page, Last Page
				if ($page < $lastpage)
				{
					$this->AddChild(
						new HtmlElement('li', null, null,
							new HtmlElement('a', array('href' => $baseurl.($page + 1).RTK_SINGLESLASH), RTK_PAGINATION_NEXT)
						)
					);
					$this->AddChild(
						new HtmlElement('li', null, null,
							new HtmlElement('a', array('href' => $baseurl.$lastpage.RTK_SINGLESLASH), RTK_PAGINATION_LAST)
						)
					);
				}
				else
				{
					$this->AddChild($nolink);
					$this->AddChild($nolink);
				}
			}
			else
			{
				parent::__construct();
			}
		}
		
		public function __tostring()
		{
			return parent::__tostring();
		}
	}
}
?>