<?php
	
	/**
	* Use for including files with classes.
	*
	*/
	function __autoload($class)
	{
		if (file_exists(dirname(__FILE__) . '/../../classes/'.$class.'.php') ) 
		{
			require_once (dirname(__FILE__) . '/../../classes/'.$class.'.php');
		}
	}
	
	/**
	* Use for dump array or object.
	*
	* @param  array or object $data
	*/
	function dump($data)
	{
		echo '<pre>';
		if (is_array($data))
		{
			print_r($data);
		}
		else
		{
			var_dump($data);
		}
		echo '</pre>';
	}
	
	/**
	* Use for dump array or object.
	*
	* @param  array or object $data
	*/
	function dd($data)
	{
		dump($data);
		die();
	}
	
	/**
	* Checl url address get headers.
	*
	* @param  string $url
	* @return boolean
	*/
	function check_url($url)
	{
		$status = get_headers($url)[7];
		if (strpos($status, '404'))
			return false;
		
		return true;
	}
	
	/**
	* Log errors to file.
	*
	* @param  string $url
	* @param  string $msg
	*/
	function loggit($url, $msg)
	{
		$d = date('d/m/Y H:i:s');
		$str = '[' . $d . '] ERROR - ' . $url . ' - ' . $msg . PHP_EOL;
		file_put_contents(LOG_FILE, $str, FILE_APPEND);
	}
	
	/**
	* Merge multi array to simple array .
	*
	* @param  array $arrInn
	* @return array
	*/
	function merge($arrIn)
	{
		$arrOut = array();
		foreach($arrIn as $subArr)
		{
			$arrOut = array_merge($arrOut, $subArr);
		}
		return $arrOut;
	}
	
	/**
	* Create array by index.
	*
	* @param  array $arrInn
	* @param  integer $ind
	* @return array
	*/
	function create_array_by_index($arrIn, $ind = 0)
	{
		$arrOut = array();
		foreach($arrIn as $subArr)
		{
			$arrOut[] = $subArr[$ind];
		}
		return $arrOut;
	}
	
	/**
    * Transform array to string.
    *
    * @param  array $arr
	* @param  string $delim
	* @param  boolean $data
	* @return string
    */
	function array_to_str($arr, $delim = ',' , $data = false)
	{
		if (is_array($arr) && !empty($arr))
		{
			if ( $data )
			{
				$tmp = array();
				foreach($arr as $val)
				{ 
					$tmp[] = date('m-d-Y', strtotime($val));;
				}
				return implode($delim, $tmp);
			}
			
			return implode($delim, $arr);
		}
		else
		{
			return 'empty';
		}
	}
	
	/**
	* Compare two array by values.
	*
	* @param  array $oldVal
	* @param  array $newVal
	* @param  array $arrKey
	* @return array
	*/
	function get_reviewed($oldVal, $newVal, $arrKey)
	{
		$result = array();
		if ( empty($arrKey) )
			return $result;
		
		foreach($arrKey as $id)
		{
			$old = array_values(array_filter($oldVal, function($innerArray) use($id){
				return ($innerArray[0] == $id);
			}));
			
			$new = array_values(array_filter($newVal, function($innerArray) use($id){
				return ($innerArray[0] == $id);
			}));
			
			if ( $old[0][7] != $new[0][7] )
				$result[] = $id;
		
		}	
		return $result;
	}