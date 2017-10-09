<?php
	
	set_time_limit(0);
	ini_set('MAX_EXECUTION_TIME', 3600);
	error_reporting(E_ALL);
	
	require_once('config/config.php');
	require_once('lib/PHPMailer/class.phpmailer.php');
	require_once('lib/helpers/func.php');
	
	$data['start'] = date('d/m/Y H:i:s');
	$urls = array();
	
	$parser = new Parser();
	
	//Get all urls for products
	for($i = 1; $i <= PAGES; $i++)
	{
		$parser->loadFile(BASE_SITE . '/' . MAIN_PAGE . '?page=' . $i);
		$urls[] = $parser->getArrayFromSelector('a.lst_a', 'href');
		$parser->clear();
	}
	$urlOut = merge($urls);
	
	//Get info about products
	for( $i = 0; $i < count($urlOut); $i++)
	{
		if ( !check_url(BASE_SITE . $urlOut[$i]) )
		{
			$data['error'][] = BASE_SITE . $urlOut[$i] . ': ' . FAIL_URL;
			loggit(BASE_SITE . $urlOut[$i], FAIL_URL);
			continue;
		}
		
		$parser->loadFile(BASE_SITE . $urlOut[$i]);
		$product = array();
		$id = $parser->getTextFromSelector('b[itemprop=sku]', 'plaintext');
		
		if( !$id ) 
		{
			$data['error'][] = BASE_SITE . $urlOut[$i] . ': ' . FAIL_DATA;
			loggit(BASE_SITE . $urlOut[$i], FAIL_DATA);
			continue;
		}
		$product[] = $id;
		//name
		$product[] = $parser->getTextFromSelector('h1.prod_name', 'plaintext');
		//price
		$product[] = $parser->getTextFromSelector('span.js-product-price-hide', 'plaintext');
		//images
		$product[] = array_to_str($parser->getArrayFromSelector('div.prod-thumbs a[style]', 'href'));
		//video
		$product[] = array_to_str($parser->getArrayFromSelector('div.prod-thumbs a[!style][href$=mp4]', 'href'));
		//pdf
		$product[] = array_to_str($parser->getArrayFromSelector('div.prod-thumbs a[!style][href$=pdf]', 'href'));
		//features
		$product[] = array_to_str($parser->getArrayWithMethod('h3#features', 'next_sibling', 'plaintext', 'li'), ':os:');
		//reviews
		$product[] = array_to_str($parser->getArrayFromSelector('span[itemprop=datePublished]', 'content'), ',', true);
		
		$parser->clear();
		$newProducts[] = $product;
	}
	//dump($newProducts);
	
	
	//create csv files
	$columns = 'Product Identifier,Product Name,Product Price,Product Images';
	$columns .= 'Product Video,Product PDF,Product Features,Dates of Reviews';

	$csv = new CSV();
	
	if(!file_exists(PATH . 'products.csv'))
	{
		$productsCSV = $csv->setCsv(PATH . 'products.csv', $columns, $newProducts, true);
		$columns = 'Product Identifier';
		$newProductsCSV = $csv->setCsv(PATH . 'new_products.csv', $columns);
		$delProductsCSV = $csv->setCsv(PATH . 'disapperaed_products.csv', $columns);
		$reviewedProductsCSV = $csv->setCsv(PATH . 'recently_reviewed_products.csv', $columns);
		
	}
	else
	{
		$oldProducts = $csv->getCsv(PATH . 'products.csv');
		$idOldProducts = create_array_by_index($oldProducts);
		$idNewProducts = create_array_by_index($newProducts);
		$productsCSV = $csv->setCsv(PATH . 'products.csv', $columns, $newProducts, true);
		
		$columns = 'Product Identifier';
		// id's new product
		$newProd = array_diff($idNewProducts, $idOldProducts);
		$newProductsCSV = $csv->setCsv(PATH . 'new_products.csv', $columns, $newProd);
		
		// id's del product
		$delProd = array_diff($idOldProducts, $idNewProducts);
		$delProductsCSV = $csv->setCsv(PATH . 'disapperaed_products.csv', $columns, $delProd);
		
		//id's reviewed prod
		$arrId = array_uintersect($idOldProducts, $idNewProducts, 'strcasecmp');
		$reviewedProd = get_reviewed($oldProducts, $newProducts, $arrId);
		$reviewedProductsCSV = $csv->setCsv(PATH . 'recently_reviewed_products.csv', $columns, $reviewedProd);
		
	}
	
	$data['end'] = date('d/m/Y H:i:s');
	
	//send mail
    $mail = new PHPMailer;
	$mail->CharSet = 'UTF-8';
	$mail->setFrom(FROM_EMAIL, 'Alex');
	$mail->addAddress(EMAIL, 'PHP Parser test');
	$mail->Subject = 'CARiD suspension systems | ' . date('Y-m-d H:i:s');
	$mail->Body = 'Start script : ' . $data['start'] . PHP_EOL;
	$mail->Body .= 'End script : ' . $data['end'] . PHP_EOL;
	$mail->addAttachment(PATH . 'new_products.csv', 'new_products.csv', 'base64', 'text/csv');
	$mail->addAttachment(PATH . 'products.csv', 'products.csv', 'base64', 'text/csv');
	$mail->addAttachment(PATH . 'disapperaed_products.csv', 'disapperaed_products.csv', 'base64', 'text/csv');
	$mail->addAttachment(PATH . 'recently_reviewed_products.csv', 'recently_reviewed_products.csv', 'base64', 'text/csv');
	try
	{	
		$mail->send();
	}
	catch(Exception $e)
	{
		$data['error'][] = 'Send Mail Error: ' . $e->getMessage();
	} 
	finally
	{
		require_once('resource/page.php');
	}
?>