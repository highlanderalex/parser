<?php
	
	require_once(dirname(__FILE__) . '/../../config/config.php');
	require_once (dirname(__FILE__) . '/../Csv.php');
	
	class TestCsv extends PHPUnit_Framework_TestCase
	{
	
		/**
		* @dataProvider providerSetCsv
		*/
		public function testSetCsv($file, $column, $data, $expected)
		{
			$csv = new Csv;
			$actual = $csv->setCsv($file, $column, $data);
			unlink($file);
			$this->assertEquals($expected, $actual);
		}
		
		public function providerSetCsv()
		{	
			$file = TMP_DIR . 'test1.csv';
			$col = 'Product';
			$data = [1, 2, 3, 4, 5];
			return [
				[$file, $col, $data, true],
			];
		}
		
		/**
		* @dataProvider providerGetCsv
		*/
		public function testGetCsv($file, $col, $data, $expected)
		{
			$handle = fopen($file, 'w');
			fputcsv($handle, explode(',', $col), '|');
			fputcsv($handle, $data, '|');
			fclose($handle);
			
			$csv = new Csv;
			$actual = $csv->getCsv($file);
			unlink($file);
			$this->assertEquals($expected, $actual);
		}
		
		public function providerGetCsv()
		{	
			$file = TMP_DIR . 'test2.csv';
			$col = 'Product';
			$data = [1, 2, 3, 4, 5];
			$exp = [0 => [1, 2, 3, 4, 5]];
			return [
				[$file, $col, $data, $exp],
			];
		}
	}
?>