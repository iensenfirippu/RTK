<?php
if (defined('RTK') or exit(1))
{
	/**
	 * Contains the definition of a text in HTML
	 **/
	class RTK_CommentView extends RTK_Box
	{
		private $_display = null;
		private $_commentbox = null;
		private $_nocomments = null;
		
		private $_comments = null;
		
		/**
		 * A widget that lists all comments for a given article
		 * @param string $articleid The id of the article
		 **/
		public function __construct($articleid)
		{
			parent::__construct('CommentView');
			$this->AddJavascript(RTK_DIRECTORY.'script/rtk-commentview.js');
			if ($articleid != null) {
				$this->AddChild(new RTK_Header('Comments'));
				$this->_display = new HtmlElement();
				$this->_commentbox = new RTK_Box('Comments');
				
				$this->_comments = Comment::LoadComments($articleid);
				if (sizeof($this->_comments) > 0) {
					$this->TraverseComment($this->_commentbox, $this->_comments);
				}
				
				if (Login::IsLoggedIn()) { $message = 'No comments yet, be the first to comment on this recipe!'; }
				else { $message = 'No comments yet, log in and be the first to comment on this recipe!'; }
				$this->_nocomments = new RTK_Textview($message, false, null, 'commentnone');
				
				if (Site::HasHttps() && Login::IsLoggedIn()) {
					$form = new RTK_Form('CommentForm', EMPTYSTRING, 'POST', true, array('autocomplete' => 'off'));
					$form->AddChild($this->_commentbox);
					$inputbox = new RTK_Box('NewComment');
					$inputbox->AddChild(new HtmlElement('a', array('href' => '#', 'onclick' => 'SelectComment(\'\')'), 'New comment'));
					$inputbox->AddChild(new HtmlElement('input', array('name' => 'CommentSelect', 'id' => 'CommentSelect','type' => 'hidden')));
					$inputbox->AddChild(new HtmlElement('input', array('name' => 'CommentInput', 'id' => 'CommentInput','type' => 'text', 'autocomplete' => 'off')));
					$inputbox->AddChild(new RTK_Button('submit', 'Send'));
					$form->AddChild($inputbox);
					
					$this->_commentbox = $form;
				}
				
				$this->AddChild($this->_display);
			}
		}
		
		/**
		 * Converts the element into an HTML string
		 * @param boolean $newline Specifies whether or not to start with a newline
		 * @return string A string containing the entire HTML structure of the element and it's children
		 **/
		public function ToString(&$newline)
		{
			if (sizeof($this->_comments) > 0) {
				$this->_display = $this->_commentbox;
			} else {
				$this->_display = $this->_nocomments;
			}
			return parent::ToString($newline);
		}
		
		/**
		 * Recursively traverses the tree of comments and builds the markup accordingly
		 * @param HtmlElement $box the box to put the resulting markup into
		 * @param Comment[] $comments the comments for the current recursion
		 **/
		private function TraverseComment(&$box, $comments)
		{
			if (sizeof($comments) > 0) {
				foreach ($comments as $comment) {
					if (is_a($comment, 'Comment')) {
						$args = null;
						if (Login::IsLoggedIn()) { $args = array('onclick' => 'SelectComment('.$comment->GetId().')'); }
						$childbox = new RTK_Box($comment->GetId(), 'comment');
						$infobox = new RTK_Box($comment->GetId(), 'commentinfo', $args);
						$infobox->AddChild(new RTK_Textview($comment->GetUser()->GetUserName().':', true, null, 'commentposter'));
						$infobox->AddChild(new RTK_Textview($comment->GetContents(), true, null, 'commentmessage'));
						$infobox->AddChild(new RTK_Textview('Posted '. $comment->GetTime(), true, null, 'commenttime'));
						$childbox->AddChild($infobox);
						if (!empty($comment->GetComments())) { $this->TraverseComment($childbox, $comment->GetComments()); }
						$box->AddChild($childbox);
					}
				}
			}
		}
		
		/**
		 * Adds a comment directly to the view (only use for demonstration purposes)
		 * @param string $comment the comment to add to the view
		 **/
		public function AddComment($comment)
		{
			if (is_string($comment)) {
				$args = null;
				if (Login::IsLoggedIn()) { $args = array('onclick' => 'SelectComment('.$comment->GetId().')'); }
				$childbox = new RTK_Box($comment->GetId(), 'comment');
				$infobox = new RTK_Box($comment->GetId(), 'commentinfo', $args);
				$infobox->AddChild(new RTK_Textview($comment->GetUser()->GetUserName().':', true, null, 'commentposter'));
				$infobox->AddChild(new RTK_Textview($comment->GetContents(), true, null, 'commentmessage'));
				$infobox->AddChild(new RTK_Textview('Posted '. $comment->GetTime(), true, null, 'commenttime'));
				$childbox->AddChild($infobox);
				if (!empty($comment->GetComments())) { $this->TraverseComment($childbox, $comment->GetComments()); }
				$this->_commentbox::GetLastChild()->AddChild($childbox);
			}
		}
	}
}
?>