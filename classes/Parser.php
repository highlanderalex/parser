<?php
	
	include_once('simple_html_dom.php');
	
	/**
	* Class for parse a html page.
	*
	*/
	class Parser 
	{
		protected $html;
		
		/**
		* Create a new instance of class simple_html_dom.
		*
		*
		* @return void
		*/
		public function __construct()
		{
			$this->html = new simple_html_dom();
		}
		
		/**
		* Load dom from url address.
		*
		*
		* @param string $url
		* @return object
		*/
		public function loadFile($url)
		{
			$this->html->load_file($url);
			
			return $this->html;
			
		}
		
		/**
		* Clear object.
		*
		*
		* @return object
		*/
		public function clear()
		{
			$this->html->clear();
			
			return $this->html;
		}
		
		/**
		* Get array of attributes from selector.
		*
		*
		* @param string $sel
		* @param string $attr
		* @return array
		*/
		public function getArrayFromSelector($sel, $attr)
		{
			$result = array();
			foreach($this->html->find($sel) as $item) 
			{ 
				$result[] = $item->$attr; 
			}
			
			return $result;
		}
		
		/**
		* Get string from selector by attribute.
		*
		*
		* @param string $sel
		* @param string $attr
		* @param integer $ind
		* @return string
		*/
		public function getTextFromSelector($sel, $attr, $ind = 0)
		{
			$block = $this->html->find($sel, $ind);
			if(!$block)
				return false;
			
			$result = $block->$attr;
			
			return $result;
		}
		
		/**
		* Get array of attributes from selector with method.
		*
		*
		* @param string $sel
		* @param string $method
		* @param string $attr
		* @param string $tag
		* @param integer $ind
		* @return array
		*/
		public function getArrayWithMethod($sel, $method, $attr, $tag, $ind = 0)
		{
			$block = $this->html->find($sel, $ind);
			if(!$block)
				return false;
			
			$result	= array();		
			foreach($block->$method()->find($tag) as $item)
			{
				$result[] = $item->$attr;
			}
			return $result;
		}
	}
	
	